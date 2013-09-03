<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

class CommentDAO extends Model{
	function CommentDAO(){
		parent::__construct();
	}
	
	
	function get($page,$pageid){
		$cond = $pageid>0?" AND c.pageid=".intval($pageid)."":"";
		return $this->db->query("SELECT c.* FROM ".$this->prefix."comment c
		WHERE c.`module`='$page' $cond ORDER BY `timestamp` DESC");
	}
	function getComment($page,$pageid){
		$cond = $pageid>0?" AND c.pageid=".intval($pageid)."":"";	
		return $this->db->query("SELECT c.*, m.fullname as contestname FROM ".$this->prefix."comment c left join ".$this->prefix."member_gallery m on (m.id=c.pageid)
		WHERE c.`module`='$page' $cond ORDER BY `timestamp` DESC");
	}


}

?>