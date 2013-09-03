<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

class UserDAO extends Model{
	function UserDAO(){
		parent::__construct();
	}
	function view(){
		$list = array();
		$result = $this->db->query("SELECT * FROM ".$this->prefix."user");
		while($rs = $result->fetch()){
			$list[$rs["id"]]  =$rs;
		}
		return $list;
	}
	
}

?>