<?php
/**
 * Database
 *
 * Provides a database wrapper around the PDO service to help reduce the effort
 * to interact with a data source.
 *
 * @package		MicroMVC
 * @author		David Pennington
 * @copyright	(c) 2010 MicroMVC Framework
 * @license		http://micromvc.com/license
 ********************************** 80 Columns *********************************
 */
class DB
{

public $pdo = NULL;

public $type = NULL;

protected $config = array();

public static $queries = array();

public static $last_query = NULL;

/**
 * Set the database type and save the config for later.
 * 
 * @param array $config
 */
public function __construct(array $config)
{
	$this->type=current(explode(':',$config['dns'],2));$this->config=$config;
}


/**
 * Database lazy-loading to setup connection only when finally needed
 */
public function connect()
{
	extract($this->config);$this->pdo=new PDO($dns,$username,$password,$params);$this->config=NULL;$this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}


/**
 * Quotes a string for use in a query
 * 
 * @param mixed $value to quote
 * @return string
 */
public function quote($value)
{
	if(!$this->pdo)$this->connect();return $this->pdo->quote($value);
}


/**
 * Run a SQL query and return a single column (i.e. COUNT(*) queries).
 *
 * @param string $sql query to run
 * @param array $params the prepared query params
 * @param int $column the optional column to return
 * @return mixed
 */
public function column($sql, array $params = NULL, $column = 0)
{
	return($statement=$this->query($sql,$params))?$statement->fetchColumn($column):NULL;
}


/**
 * Run a SQL query and return a single row object
 *
 * @param string $sql query to run
 * @param array $params the prepared query params
 * @param string $object the optional name of the class for this row
 * @return array
 */
public function row($sql, array $params = NULL, $object = NULL)
{
	return(($statement=$this->query($sql,$params))?(($row=$statement->fetch(PDO::FETCH_OBJ))&&$object?new$object($row):$row):NULL);
}


/**
 * Run a SQL query and return an array of row objects or an array
 * consisting of all values of a single column.
 *
 * @param string $sql query to run
 * @param array $params the optional prepared query params
 * @param int $column the optional column to return
 * @return array
 */
public function fetch($sql, array $params = NULL, $column = NULL)
{
	return(($statement=$this->query($sql,$params))?($column===NULL?$statement->fetchAll(PDO::FETCH_OBJ):$statement->fetchAll(PDO::FETCH_COLUMN,$column)):NULL);
}

/**
 * Run a SQL query and return the statement object
 *
 * @param string $sql query to run
 * @param array $params the prepared query params
 * @return PDOStatement
 */
public function query($sql, array $params = NULL)
{
	$this->type=='mysql'&&$sql=str_replace('"','`',$sql);benchmark();self::$last_query=$sql;$stmt=$this->_query($sql,$params);self::$queries[$this->type][]=(benchmark()+array(2=>$sql));return $stmt;
}


/**
 * Run the actual SQL query and return the statement object
 *
 * @param string $sql query to run
 * @param array $params the prepared query params
 * @return PDOStatement
 */
protected function _query($sql, array $params = null)
{
	if(!$this->pdo)$this->connect();if($params){$stmt=$this->pdo->prepare($sql);$stmt->execute($params);}else{$stmt=$this->pdo->query($sql);}return$stmt;
}


/**
 * Run a DELETE SQL query and return the number of rows deleted
 *
 * @param string $sql query to run
 * @param array $params the prepared query params
 * @return int
 */
public function delete($sql, array $params = NULL)
{
	return(($statement=$this->query($sql, $params))?$statement->rowCount():FALSE);
}


/**
 * Builds an INSERT statement using the values provided
 *
 * @param string $table the table name
 * @param array $data the column => value pairs
 * @return int
 */
public function insert($table, $data)
{
	$sql='INSERT INTO "'.$table.'" ("'.implode('","',array_keys($data)).'")VALUES('.rtrim(str_repeat('?,',count($data)),',').')';return$this->query($sql,array_values($data))?$this->pdo->lastInsertId():0;
}


/**
 * Builds an UPDATE statement using the values provided.
 * Create a basic WHERE section of a query using the format:
 * array('column' => $value) or array("column = $value")
 *
 * @param string $table the table name
 * @param array $data the column => value pairs
 * @return int
 *
 */
public function update($table, $data, array $where = NULL)
{
	$q='UPDATE "'.$table.'" SET "'.implode('" = ?,"',array_keys($data)).'" = ? WHERE ';list($a,$b)=self::where($where);return(($stmt=$this->query($q.$a,array_merge(array_values($data),$b)))?$stmt->rowCount():NULL);
}


/**
 * Create a basic, single-table SQL query
 *
 * @param string $c columns
 * @param string $t table
 * @param array $w array of where conditions
 * @param int $l limit
 * @param int $o offset
 * @param array $ord array of order by conditions
 * @return array of SQL + values
 */
public static function select($c, $t, $w = array(), $l = NULL, $o = 0, $ord = array())
{
	$s = "SELECT $c FROM \"$t\"";list($w,$v)=DB::where($w);if($w)$s.=" WHERE $w";return array($s.DB::order_by($ord).($l?" LIMIT $o,$l":''),$v);
}


/**
 * Generate the SQL WHERE clause options from an array
 *
 * @param array $where array of column => $value indexes
 * @return array
 */
public static function where(array $where = NULL)
{
	$a=$s=array();if($where){foreach($where as$c=>$v){if(is_int($c))$s[]=$v;else{$s[]="\"$c\" = ?";$a[]=$v;}}}return array(join(' AND ',$s),$a);
}


/**
 * Create the ORDER BY clause for MySQL and SQLite (still working on PostgreSQL)
 * 
 * @param array $fields to order by
 */
public static function order_by(array $fields = NULL)
{
	if($fields){$s=' ORDER BY ';foreach($fields as$k=>$v)$s.="\"$k\" $v, ";return substr($s, 0, -2);}
}


/**
 * Generate the SQL to join two tables
 *
 * @param string $t1 existing table name
 * @param string $t2 the table to join
 * @param boolean $f TRUE join the first table primary key to second table foreign key
 * @param string $j the join type (LEFT,RIGHT,INNER)
 * @return string
 */
public static function join($t1,$t2,$f=1,$j='LEFT')
{
	return" $j JOIN $t2 ON ".($f?"\"$t1\".\"id\" = \"$t2\".\".{$t1}_id\"":"\"$t1\".\"{$t2}_id\" = \"$t2\".\"id\"");
}


/**
 * Generate an IN() selection from an array of numeric ID's.
 * 
 * @param array $ids
 * @return string
 */
public static function in(array $ids)
{
	return" in ('".implode("','",array_map('to_int',$ids))."')";
}

}

// END