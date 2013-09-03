<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

class ClassModel extends Model{
	function ClassModel(){
		parent::__construct();
	}
	function insert($data, $tablename){
		$result = $this->db->insert($this->prefix.$tablename,$data);
		return $this->db->insert_id();				
		
	}
	function update_ln($id,$ln,$data){
		$data['id'] = intval($id);
		$data['ln'] = $ln;
		return $this->db->replace($this->prefix.'content_ln',$data);
	}
	
}
?>