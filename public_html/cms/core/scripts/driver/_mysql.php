<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}
// 208 Nguyen Nguyen Trai, CentralPark
$keep_connect = false;
class bDataStore extends CaoBox{
	var $conn;
	var $result;
	
	function __construct(){
		$this->conn = $GLOBALS['keep_connect'];
	}
	function connect($dbhost,$dbport = '3306',$dbuser,$dbpass,$dbname){
		$this->conn = @mysql_pconnect($dbhost.':'.$dbport,$dbuser,$dbpass) or $this->getError('Cannot connect to mysql');
		$GLOBALS['keep_connect'] = $this->conn;
		mysql_select_db($dbname,$this->conn) or $this->getError("Cannot find $dbname");
		
	}

	function query($sql){
		$this->result = mysql_query($sql,$this->conn);
		return $this;
	}
	function insert_id(){
		$lastid =  mysql_insert_id($this->conn);// or $this->getError($this->error($this->conn));
		return $lastid;
	}
	
	function num_rows(){
		return  mysql_num_rows($this->result);
	}
	
	function affected_rows(){
		return mysql_affected_rows($this->conn);
	}
	
	function fetch(){
		if($this->result){
			$rs = @mysql_fetch_assoc($this->result);
			if(isset($rs)) return $rs; 
		}
	}
	
	
	function limit($sql, $start = 0,$limit = 0){
		return $sql. ($limit ? " LIMIT ".intval($start).",".intval($limit):"");
	}
	
	
	
	function free(){
		mysql_free_result($this->result);
	}
	
	function error(){
		$error = array(
			'no' => mysql_errno($this->conn),
			'msg'=> mysql_error($this->conn),
		);
		return $error;
	}
	
	function close(){
		mysql_close($this->conn);
	}
	
	function cache(){}
}
?>