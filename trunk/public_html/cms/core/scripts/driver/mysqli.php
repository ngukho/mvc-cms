<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

class bDataStore extends CaoBox{
	function connect($dbhost,$dbport = '3306',$dbuser,$dbpass,$dbname){
		$conn = @mysqli_connect($dbhost.':'.$dbport,$dbuser,$dbpass) or $this->getError('Cannot connect to mysql');
		mysqli_select_db($dbname,$conn) or $this->getError("Cannot find $dbname");
		mysqli_query("SET NAMES 'UTF8'",$conn);
		return $conn;
	}

	function query($sql,$conn){
		$sql = mysqli_query($sql,$conn);
		return $sql;
	}
	function insert_id($conn){
		$lastid =  mysqli_insert_id($conn);// or $this->getError($this->error($this->conn));
		return $lastid;
	}
	
	function num_rows($result){
		return  mysqli_num_rows($result);
	}
	
	function affected_rows($conn){
		return mysqli_affected_rows($conn);
	}
	
	function fetch($result,$conn){
		if($result){
			$rs = @mysqli_fetch_assoc($result);
			if(isset($rs)) return $rs; 
		}
	}
	
	function limit($sql, $start = 0,$limit = 0){
		return $sql. ($limit ? " LIMIT ".intval($start).",".intval($limit):"");
	}
	
	function free($result){
		mysqli_free_result($result);
	}
	function error($conn){
		$error = array(
			'no' => mysqli_errno($conn),
			'msg'=> mysqli_error($conn),
		);
		return $error;
	}
	
	function close($conn){
		mysqli_close($conn);
	}
	
}
?>