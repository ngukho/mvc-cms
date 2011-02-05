<?php
/**
 *
 * @Lite weight Database abstraction layer
 *
 * @copyright Copyright (C) 2009 PHPRO.ORG. All rights reserved.
 *
 * @license new bsd http://www.opensource.org/licenses/bsd-license.php
 * @filesource
 * @package Database
 *
 */

/**
 *
 * @Singleton to create database connection
 *
 * @copyright Copyright (C) 2009 PHPRO.ORG. All rights reserved.
 *
 * @license new bsd http://www.opensource.org/licenses/bsd-license.php
 * @filesource
 * @package Database
 *
 */


class Db{

	/**
	 * Holds an insance of self
	 * @var $instance
	 */
	private static $instance = NULL;

	/**
	*
	* the constructor is set to private so
	* so nobody can create a new instance using new
	*
	*/
	private function __construct()
	{
	}

	/**
	*
	* Return DB instance or create intitial connection
	*
	* @return object (PDO)
	*
	* @access public
	*
	*/
	public static function getInstance()
	{
		if (!self::$instance)
		{
			$config = Config::getInstance();
			$db_type = $config->config_values['database']['db_type'];
			$hostname = $config->config_values['database']['db_hostname'];
			$dbname = $config->config_values['database']['db_name'];
			$db_password = $config->config_values['database']['db_password'];
			$db_username = $config->config_values['database']['db_username'];
			$db_port = $config->config_values['database']['db_port'];

			self::$instance = new PDO("$db_type:host=$hostname;port=$db_port;dbname=$dbname", $db_username, $db_password);
			self::$instance-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		return self::$instance;
	}


	/**
	*
	* Like the constructor, we make __clone private
	* so nobody can clone the instance
	*
	*/
	private function __clone()
	{
	}

} // end of class


abstract class DbAbstraction
{
	
	protected $_table_name;
	protected $_primary_key;

	/*
	 * @the errors array
	 */
	public $errors = array();

	/*
	 * @The sql query
	 */
	private $sql;

	/**
	 * @The name=>value pairs
	 */
	private $values = array();
	
	function __construct()
	{
		
	}
	

	/**
	 *
	 * @add a value to the values array
	 *
	 * @access public
	 *
	 * @param string $key the array key
	 *
	 * @param string $value The value
	 *
	 */
	public function addValue($key, $value)
	{
		$this->values[$key] = $value;
	}


	/**
	 *
	 * @set the values
	 *
	 * @access public
	 *
	 * @param array
	 *
	 */
	public function setValues($array)
	{
		$this->values = $array;
	}

	
	public function get($id)
	{
		$this->select();
		$this->where($this->_primary_key,$id);
		$res = $this->query();
		return $res->fetch();
	}
	
	/**
	 *
	 * @delete a recored from a table
	 *
	 * @access public
	 *
	 * @param string $table The table name
	 *
	 * @param int ID
	 *
	 */
	public function delete($id)
	{
		try
		{
			// get the primary key/
			$pk = $this->_primary_key;

			$table = $this->_table_name;
			// get the primary key name
			$sql = "DELETE FROM $table WHERE {$this->_primary_key}=:$pk";
			
			$db = Db::getInstance();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":$pk", $id);
			$stmt->execute();
		}
		catch(Exception $e)
		{
			$this->errors[] = $e->getMessage();
		}
	}


	/**
	 *
	 * @insert a record into a table
	 *
	 * @access public
	 *
	 * @param string $table The table name
	 *
	 * @param array $values An array of fieldnames and values
	 *
	 * @return int The last insert ID
	 *
	 */
	public function insert($values=null)
	{
		$values = is_null($values) ? $this->values : $values;
		$sql = "INSERT INTO {$this->_table_name} SET ";

		$obj = new CachingIterator(new ArrayIterator($values));

		try
		{
			$db = Db::getInstance();
			foreach( $obj as $field=>$val)
			{
				$sql .= "$field = :$field";
				$sql .=  $obj->hasNext() ? ',' : '';
				$sql .= "\n";
			}
			$stmt = $db->prepare($sql);

			// bind the params
			foreach($values as $k=>$v)
			{
				$stmt->bindParam(':'.$k, $v);
			}
			$stmt->execute($values);
			// return the last insert id
			return $db->lastInsertId();
		}
		catch(Exception $e)
		{
			$this->errors[] = $e->getMessage();
		}
	}


	/**
	 * @update a table
	 *
	 * @access public
	 * 
	 * @param string $table The table name
	 *
	 * @param int $id
	 *
	 */
	public function update($id, $values=null)
	{
		$values = is_null($values) ? $this->values : $values;
		try
		{
			// get the primary key/
			$pk = $this->_primary_key;
	
			// set the primary key in the values array
			$values[$pk] = $id;

			$obj = new CachingIterator(new ArrayIterator($values));

			$db = Db::getInstance();
			$sql = "UPDATE {$this->_table_name} SET \n";
			foreach( $obj as $field=>$val)
			{
				$sql .= "$field = :$field";
				$sql .= $obj->hasNext() ? ',' : '';
				$sql .= "\n";
			}
			$sql .= " WHERE $pk=$id";
			$stmt = $db->prepare($sql);

			// bind the params
			foreach($values as $k=>$v)
			{
				$stmt->bindParam(':'.$k, $v);
			}
			// bind the primary key and the id
			$stmt->bindParam($pk, $id);
			$stmt->execute($values);

			// return the affected rows
			return $stmt->rowCount();
		}
		catch(Exception $e)
		{
			$this->errors[] = $e->getMessage();
		}
	}


	/**
	 *
	 * Fetch all records from table
	 *
	 * @access public
	 *
	 * @param $table The table name
	 *
	 * @return array
	 *
	 */
	public function query()
	{
		$res = Db::getInstance()->query($this->sql);
		return $res;
	}

	/**
	 *
	 * @select statement
	 *
	 * @access public
	 *
	 * @param string $table
	 *
	 */
	public function select($str_select = '*')
	{
		$this->sql = "SELECT {$str_select} FROM {$this->_table_name}";
	}

	/**
	 * @where clause
	 *
	 * @access public
	 *
	 * @param string $field
	 *
	 * @param string $value
	 *
	 */
	public function where($field, $value)
	{
		$this->sql .= " WHERE $field=$value";
	}

	/**
	 *
	 * @set limit
	 *
	 * @access public
	 *
	 * @param int $offset
	 *
	 * @param int $limit
	 *
	 * @return string
	 *
	 */
	public function limit($offset, $limit)
	{
		$this->sql .= " LIMIT $offset, $limit";
	}

	/**
	 *
	 * @add an AND clause
	 *
	 * @access public
	 *
	 * @param string $field
	 *
	 * @param string $value
	 *
	 */
	public function andClause($field, $value)
	{
		$this->sql .= " AND $field=$value";
	}

	/**
	 *
	 * @add an OR clause
	 *
	 * @access public
	 *
	 * @param string $field
	 *
	 * @param string $value
	 *
	 */
	public function orClause($field, $value)
	{
		$this->sql .= " OR $field=$value";
	}	

	/**
	 *
	 * Add and order by
	 *
	 * @param string $fieldname
	 *
	 * @param string $order
	 *
	 */
	public function orderBy($fieldname, $order='ASC')
	{
		$this->sql .= " ORDER BY $fieldname $order";
	}
	
	/**
	 * @get the name of the field that is the primary key
	 *
	 * @access private
	 *
	 * @param string $table The name of the table
	 *
	 * @return string
	 *
	 */
	private function getPrimaryKey($table)
	{
		try
		{
			// get the db name from the config.ini file
			$config = Config::getInstance();
			$db_name = $config->config_values['database']['db_name']; 

			$db = Db::getInstance();
			$sql = "SELECT
				k.column_name
				FROM
				information_schema.table_constraints t
				JOIN
				information_schema.key_column_usage k
				USING(constraint_name,table_schema,table_name)
				WHERE
				t.constraint_type='PRIMARY KEY'
				AND
				t.table_schema='{$db_name}'
				AND
				t.table_name=:table";
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':table', $table, PDO::PARAM_STR);
			$stmt->execute();
			
			return $stmt->fetchColumn(0);
		}
		catch(Exception $e)
		{
			$this->errors[] = $e->getMessage();
		}
	}
	
} // end of class

?>
