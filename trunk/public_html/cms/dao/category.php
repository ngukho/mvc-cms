<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

class CategoryDAO extends Model{
	function CategoryDAO(){
		parent::__construct();
	}
	
	function view($type=0,$parentid = 0,$q = NULL,$sorted_by = " c.`order_id`",$start = 0, $limit = 0, $cond="1"){
		$cond = $cond!="1"?$cond." and c.type = ".intval($type):" c.type = ".intval($type);
		if($q) $cond .= " AND ln.name LIKE '%".addslashes($q)."%'";
		else $cond .= "  AND c.parentid = ".intval($parentid);
		$order = "";
		if($sorted_by && $sorted_order) $order = ",$sorted_by $sorted_order";
		return $this->db->query("SELECT * FROM ".$this->prefix."category c,".$this->prefix."category_ln ln WHERE c.id = ln.id AND ln.ln = '".$this->lang."' AND $cond ORDER BY $sorted_by".($limit?" LIMIT $start,$limit":""));
	}
	function count_rows($type=0,$parentid = 0,$q = NULL){
		$cond = "c.type = ".intval($type);
		if($q) $cond .= " AND ln.name LIKE '%".addslashes($q)."%'";
		else $cond .= "  AND c.parentid = ".intval($parentid);		
		$sql = $this->db->query("SELECT count(c.id) as total FROM ".$this->prefix."category c,".$this->prefix."category_ln ln WHERE c.id = ln.id AND ln.ln = '".$this->lang."' AND $cond ");
		$rs = $sql->fetch($sql);
		return $rs["total"];		
	}
	
	function xpath($id,$result = NULL){
		if(!$result) $result = array();
		$sql = $this->db->query("SELECT * FROM ".$this->prefix."category c,".$this->prefix."category_ln ln WHERE c.id = ln.id AND ln.ln = '".$this->lang."' AND c.id = ".intval($id)." ORDER BY c.id");
		$rs = $sql->fetch($sql);
		if($rs){
			$result[] = $rs;
			$result = $this->xpath($rs['parentid'],$result);
		}
		return $result;
	}
	
	function tree($type=0,$parentid=0,$space='-',$content = NULL,$array = NULL){
		if(!$array) $array = array();
		if($content) $result = $this->query("SELECT c.*,ln.* FROM ".$this->prefix."category c,".$this->prefix."category_ln ln WHERE c.id=ln.id AND ln.ln='".$this->lang."' AND c.type=".intval($type)." AND c.parentid = ".intval($parentid));
		else $result = $this->query("SELECT * FROM ".$this->prefix."category WHERE type=".intval($type)." AND parentid = ".intval($parentid));
		while($rs = $result->fetch()){
			$rs['prefix'] = $space;
			$array[] = $rs;
			$array = $this->tree($type,$rs['id'],$rs['prefix'].$space,$content,$array);
		}
		return $array;
	}
	function insert($data){
		$this->db->insert($this->prefix.'category',$data);
		$insert_id = $this->db->insert_id();
		if($insert_id) $this->db->query("UPDATE ".$this->prefix."category SET order_id = order_id + 1 WHERE parentid = ".intval($data['parentid'])." AND `type` = ".intval($data['type'])." AND `id` < ".intval($insert_id));
		return $insert_id;
	}
	function update_ln($id,$ln,$data){
		$data['id'] = intval($id);
		$data['ln'] = $ln;
		return $this->db->replace($this->prefix.'category_ln',$data);
	}
	

}

?>