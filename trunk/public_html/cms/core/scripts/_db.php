<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}
class DB extends CaoBox{
/*	var $connect;
	var $result;
	var $sql;
	var $data;
	var $cache;
	var $cachefile;
*/	
	//private vars
	var $db;
	var $num = 0;
	function DB($server,$port,$user,$pass,$name,$prefix=NULL){
		$this->db = new bDataStore;
		$this->db->connect($server,$port,$user,$pass,$name);
	}
	
	function insert($table,$data = array()){
		foreach($data as $key=>$value) $data[$key] = addslashes($value);
		$sql = "INSERT INTO `".$table."`(`".implode('`,`',array_keys($data))."`) VALUES('".implode("','",$data)."')";
		return $this->db->query($sql);
	}
	
	function replace($table,$data = array()){
		foreach($data as $key=>$value) $data[$key] = addslashes($value);
		$sql = "REPLACE INTO `".$table."`(`".implode('`,`',array_keys($data))."`) VALUES('".implode("','",$data)."')";
		return $this->db->query($sql);
	}

	function update($table,$data = array(),$cond=0){
		$sql = "UPDATE `".$table."` SET ";
		foreach($data as $field=>$value) $sql .= "`$field`='".addslashes($value)."',";
		$sql = substr($sql,0,-1);
		$sql .=" WHERE $cond";
		return $this->db->query( $sql);
	}
	
	function delete($table,$cond = 0){
		$sql = "DELETE FROM `".$table."` WHERE $cond";
		return $this->db->query($sql);
	}
	
	function query($sql){
		$db = new bDataStore;
		return $db->query($sql);
	}
	
	
	function limit($sql,$start,$limit){
		return parent::limit($sql,$start,$limit);
	}
	
	function cache(){}
	
}
?>