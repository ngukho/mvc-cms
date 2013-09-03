<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

class ContentDAO extends Model{
	function ContentDAO(){
		parent::__construct();
	}
	
	
	function options($content_id = 0,$options_type = 0, $options_id = 0, $page='content'){
		$cond = 1;
		if($content_id) $cond .= " AND content_id = ".intval($content_id);
		if($options_type) $cond .= " AND content_id = ".intval($options_type);
		if($options_id) $cond .= " AND content_id = ".intval($options_id);
		if($page) $cond .= " AND page = '".$page."'";
		return $this->db->query("SELECT * FROM ".$this->prefix."content_options WHERE $cond");
		
	}
	function view($type=0,$catid = -1,$q = NULL,$where = 1,$start = 0,$limit = 0,$sorted_by = "c.`order_id`"){
		$cond = "c.type = ".intval($type);
		$order = "";
		if($sorted_by && $sorted_order) $order = ",$sorted_by $sorted_order";
		if($catid >=0) $cond .= " AND c.catid = ".intval($catid);
		if($q) $where .= " AND ln.name LIKE '%".addslashes($q)."%'";
		return $this->db->query("SELECT * FROM ".$this->prefix."content c,".$this->prefix."content_ln ln WHERE c.id = ln.id AND ln.ln = '".$this->lang."' AND $cond AND $where ORDER BY $sorted_by".($limit?" LIMIT $start,$limit":""));
	}
	function count_rows($type=0,$catid = -1,$q = NULL,$where = 1){
		$cond = "c.type = ".intval($type);
		if($catid >=0) $cond .= " AND c.catid = ".intval($catid);
		if($q) $where .= " AND ln.name LIKE '%".addslashes($q)."%'";
		$sql = $this->db->query("SELECT count(c.id) as total FROM ".$this->prefix."content c,".$this->prefix."content_ln ln WHERE c.id = ln.id AND ln.ln = '".$this->lang."' AND $cond AND $where ");
		$rs = $sql->fetch();
		return $rs["total"];
	}


}

?>