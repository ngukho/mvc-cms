<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

class ClassModel extends Model{
	function ClassModel(){
		parent::__construct();
	}
	
	function listNews(){
		return $this->db->select('product');
	}
	
	function listContent($cond = ""){
		return $this->db->query("SELECT * FROM ".$this->prefix."content a, ".$this->prefix."content_ln b  WHERE a.id = b.id and a.active = 0 order by a.id desc");
	}
/*	function listmodule($id = 0){
		return $this->db->query("SELECT * FROM ".$this->prefix."configure_mod  WHERE typeid=".$id." and module = 'content' ");
		
	}
*/}
?>