<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

class ClassModel extends Model{
	function ClassModel(){
		parent::__construct();
	}
	
	function getSql($sql="")
	{
		return $this->db->query($sql);
	}
	
	function getMember($cond = "1=1",$start=0,$perpage=0, $orderby="")
	{		
		return $this->db->query("SELECT * FROM ".$this->prefix."member WHERE $cond ORDER BY id asc");
	}
	function insertData($data, $table){
		return $this->db->insert($this->prefix.$table,$data);
	}
	function insert_id(){
		return $this->db->insert_id();
	}
	function getExport()
	{		
		return $this->db->query("SELECT * FROM ".$this->prefix."export");
	}
}
?>