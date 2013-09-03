<?php

defined('_ROOT') or die(__FILE__);

class ClassModel extends Model{
	function ClassModel(){
		parent::__construct();
	}
	
	function view($cond = 1){
		return $this->db->query("SELECT * FROM ".$this->prefix."user_group WHERE $cond ORDER BY id");
	}
	
	function get($id){
		return $this->db->query("SELECT * FROM ".$this->prefix."user_group  WHERE id = ".intval($id));
	}
	
	function insert($data){
		return $this->db->insert($this->prefix."user_group",$data);
	}
	
	function update($id,$data){
		$this->db->update($this->prefix."user_group",$data," id = ".intval($id));
	}
	
	function delete($id){
		$this->db->delete($this->prefix."user_group"," id = ".intval($id));
	}
	
}
?>