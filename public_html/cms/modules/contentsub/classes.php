<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

class ClassModel extends Model{
	function ClassModel(){
		parent::__construct();
	}
	
	function get($id = 0){
		return $this->db->query("SELECT * FROM ".$this->prefix."gallery WHERE id = ".intval($id));
	}
	
	
}
?>