<?php
/**
 *
 * @Lite weight Database abstraction layer
 * @Singleton to create database connection
 *
 *
 */

class DbConnection
{

	/**
	 * Holds an array insance of self
	 * @var $instance
	 */
	private static $instances = array();

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
	public static function getInstance($config_name = 'database_master')
	{
		if (!self::$instances[$config_name])
		{
			$config = Config::getInstance();
			$db_type = $config->config_values[$config_name]['db_type'];
			$hostname = $config->config_values[$config_name]['db_hostname'];
			$dbname = $config->config_values[$config_name]['db_name'];
			$db_password = $config->config_values[$config_name]['db_password'];
			$db_username = $config->config_values[$config_name]['db_username'];
			$db_port = $config->config_values[$config_name]['db_port'];

			$pdo = new PDO("$db_type:host=$hostname;port=$db_port;dbname=$dbname", $db_username, $db_password);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			self::$instances[$config_name] = $pdo;
		}
		return self::$instances[$config_name];
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


abstract class Model
{
	
	protected $_table_name;
	protected $_primary_key;
	protected $_conn;
	protected $_fields = array();

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
	
	function __construct($conn = NULL)
	{
		if(is_null($conn))
		{
			$this->_conn = DbConnection::getInstance();
		}
		else 
		{
			$this->_conn = $conn;
		}
		$this->getTableField();
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
			
			$stmt = $this->_conn->prepare($sql);
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
			foreach( $obj as $field=>$val)
			{
				$sql .= "$field = :$field";
				$sql .=  $obj->hasNext() ? ',' : '';
				$sql .= "\n";
			}
			$stmt = $this->_conn->prepare($sql);

			// bind the params
			foreach($values as $k=>$v)
			{
				$stmt->bindParam(':'.$k, $v);
			}
			$stmt->execute($values);
			// return the last insert id
			return $this->_conn->lastInsertId();
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

			$sql = "UPDATE {$this->_table_name} SET \n";
			foreach( $obj as $field=>$val)
			{
				$sql .= "$field = :$field";
				$sql .= $obj->hasNext() ? ',' : '';
				$sql .= "\n";
			}
			$sql .= " WHERE $pk=$id";
			$stmt = $$this->_conn->prepare($sql);

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
		return $this->_conn->query($this->sql);
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
	
/*
   +--------------------------------------------------------------------------
   |   {Function Name : getRow}
   |   ========================================
   |   {Description : get a row}
   |   ========================================
   |   {Parameters : (String) $condition, (Array) $params }
   |   ========================================
   |   {Return Values : }
   |   ========================================   
   |   {Coder : DucBui 12/14/2010 }      
   +--------------------------------------------------------------------------
*/    
	public function getRow($condition,$params = array())
	{
		$result = $this->getRowset($condition,$params);
		if(isset($result[0])) return $result[0];
		return FALSE;
	}

/*
   +--------------------------------------------------------------------------
   |   {Function Name : getRowset}
   |   ========================================
   |   {Description : get a any row}
   |   ========================================
   |   {Parameters : (String) $condition, (Array) $params, (String) $order_by, (Int) $start, (Int) $end }
   |   ========================================
   |   {Return Values : }
   |   ========================================   
   |   {Coder : DucBui 12/14/2010 }      
   +--------------------------------------------------------------------------
*/    	
	public function getRowset($condition = NULL,$params = array(),$order_by = NULL,$start = 0,$end = 0)
	{
		$strFields = implode(",",array_keys($this->_fields));
		$sSql = "SELECT " . $strFields . " FROM " . $this->_table_name;
		if(!is_null($condition))
		{
			$sSql .= " WHERE " . $condition;
		}		
		if(!is_null($order_by))
		{
			$sSql .= " ORDER BY " . $order_by;
		}
		if($end > 0)
		{
			$sSql .= " LIMIT {$start},{$end} ";
		}		
		
		$sth = $this->_conn->prepare($sSql);
		$sth->execute($params);
		
//		return $sth->fetchAll(PDO::FETCH_OBJ);
		return $sth->fetchAll();
	}
	
/*
   +--------------------------------------------------------------------------
   |   {Function Name : getTotalRow}
   |   ========================================
   |   {Description : get total row}
   |   ========================================
   |   {Parameters : (String) $condition, (Array) $params }
   |   ========================================
   |   {Return Values : }
   |   ========================================   
   |   {Coder : DucBui 12/14/2010 }      
   +--------------------------------------------------------------------------
*/
	public function getTotalRow($condition = NULL,$params = array())
	{
		$sSql = "SELECT COUNT(*) AS TotalRow FROM " . $this->_table_name;
		if(!is_null($condition))
		{
			$sSql .= " WHERE " . $condition;
		}
		
		$sth = $this->_conn->prepare($sSql);
		$sth->execute($params);
		
		$result = $sth->fetch(PDO::FETCH_OBJ);
		return $result->TotalRow;
	}

/*
   +--------------------------------------------------------------------------
   |   {Function Name : compileBinds}
   |   ========================================
   |   {Description :}
   |   ========================================
   |   {Parameters : (String) $sql, (Array) $binds }
   |   ========================================
   |   {Return Values : }
   |   ========================================   
   |   {Coder : DucBui 12/14/2010 }      
   +--------------------------------------------------------------------------
*/		
	private function compileBinds($sql, $binds)
	{
		if (strpos($sql, '?') === FALSE)
		{
			return $sql;
		}
		
		if ( ! is_array($binds))
		{
			$binds = array($binds);
		}
		
		// Get the sql segments around the bind markers
		$segments = explode('?', $sql);

		// The count of bind should be 1 less then the count of segments
		// If there are more bind arguments trim it down
		if (count($binds) >= count($segments)) {
			$binds = array_slice($binds, 0, count($segments)-1);
		}

		// Construct the binded query
		$result = $segments[0];
		$i = 0;
		foreach ($binds as $bind)
		{
			$result .= $this->escape($bind);
			$result .= $segments[++$i];
		}

		return $result;
	}	
	
/*
   +--------------------------------------------------------------------------
   |   {Function Name : escape}
   |   ========================================
   |   {Description : Escapes data based on type, sets boolean and null types}
   |   ========================================
   |   {Parameters : (String) $condition, (Array) $params }
   |   ========================================
   |   {Return Values : }
   |   ========================================   
   |   {Coder : DucBui 12/14/2010 }   
   +--------------------------------------------------------------------------
*/	
	private function escape($str)
	{
		if (is_string($str))
		{
			if(function_exists(addslashes))
			{
				$str = "'".addslashes($str)."'";	
			}
			else 
			{
				$str = "'".$str."'";				
			}
		}
		elseif (is_bool($str))
		{
			$str = ($str === FALSE) ? 0 : 1;
		}
		elseif (is_null($str))
		{
			$str = 'NULL';
		}

		return $str;
	}

	//Set Attribute for Class
	private function getTableField()
	{
		$sQuery = " SHOW FIELDS FROM " . $this->_table_name;
		$results = $this->_conn->query($sQuery);
		foreach ($results as $result)
			$this->_fields[] = $result['Field'];
	}
	
	
} // end of class

?>
