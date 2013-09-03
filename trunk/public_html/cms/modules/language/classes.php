<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

class ClassModel extends Model{
	function ClassModel(){
		parent::__construct();
	}
	
	function active($ln){
		return $this->db->query("UPDATE ".$this->prefix."language SET active = ABS(active - 1) WHERE ln ='".addslashes($ln)."'");
	}
	
	function set_default($ln){
		$this->db->query("UPDATE ".$this->prefix."language SET is_default = 0");
		return $this->db->query("UPDATE ".$this->prefix."language SET is_default = 1 WHERE ln ='".addslashes($ln)."'");
	}
	
	function update($ln,$data){
		return $this->db->update($this->prefix."language",$data," ln = '".addslashes($ln)."'");
	}
}
?>