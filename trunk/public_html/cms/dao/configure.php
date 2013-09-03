<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

class ConfigureDAO extends Model{
	function ConfigureDAO(){
		parent::__construct();
		
	}
	

	
	function getMod($cond  = 1){
		return $this->db->query("SELECT * FROM ".$this->prefix."configure_mod WHERE $cond ORDER BY `module`,typeid");
	}
	
	
	
	function view($cond = 1){
		return $this->db->query("SELECT * FROM ".$this->prefix."configure WHERE $cond ORDER BY is_system DESC");
	}
	
	
	function group($cond = 1){
		return $this->db->query("SELECT * FROM ".$this->prefix."configure_group WHERE `active` = 1 AND $cond ORDER BY order_id");
	}

}

?>