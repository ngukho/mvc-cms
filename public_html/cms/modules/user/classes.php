<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

class ClassModel extends Model{
	function ClassModel(){
		parent::__construct();
	}
	
	function view($cond = 1){
		return $this->db->query("SELECT * FROM ".$this->prefix."user WHERE $cond ORDER BY id");
	}
	function viewGroup($cond = 1){
		return $this->db->query("SELECT * FROM ".$this->prefix."user_group WHERE $cond ORDER BY id");
	}
	function get($id){
		return $this->db->query("SELECT * FROM ".$this->prefix."user  WHERE id = ".intval($id));
	}
	
	function insert($data){
		return $this->db->insert($this->prefix."user",$data);
	}
	
	function update($id,$data){
		$this->db->update($this->prefix."user",$data," id = ".intval($id));
	}
	
	function delete($id){
		$this->db->delete($this->prefix."user"," id = ".intval($id));
	}
	
}
?>