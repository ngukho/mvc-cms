<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

class LanguageDAO extends Model{
	function LanguageDAO(){
		parent::__construct();
	}
	


	function view($cond = 1){
		return $this->db->query("SELECT * FROM ".$this->prefix."language WHERE $cond ORDER BY is_default DESC");
	}


}

?>