<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

class ClassModel extends Model{
	function ClassModel(){
		parent::__construct();
	}
	
	function active($id){
		return $this->db->query("UPDATE ".$this->prefix."comment SET active = ABS(active - 1) WHERE id =".intval($id));
	}
	
	function delete($id){
		$this->db->delete($this->prefix."comment","id = ".intval($id));
	}
}
?>