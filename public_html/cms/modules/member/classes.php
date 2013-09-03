<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}
define('POSSITION_TYPE_ID', 1);
class ClassModel extends Model{
	function ClassModel(){
		parent::__construct();
	}
	
	function view($cond = 1,$start=0,$limit = 0,$orderby = "fullname"){
		return $this->db->query("SELECT * FROM ".$this->prefix."member WHERE $cond ORDER BY $orderby".($limit?" LIMIT $start,$limit":""));
	}
	function CheckExist($cond){
		$result  = $this->db->query("SELECT * FROM ".$this->prefix."member WHERE 1 $cond LIMIT 1");
		return $result->fetch();
	}
	function get($id){
		return $this->db->query("SELECT * FROM ".$this->prefix."member WHERE id = ".intval($id));
	}
	function get_detail($id){
		return $this->db->query("SELECT * FROM ".$this->prefix."member_detail WHERE memberid = ".intval($id)." order by id asc ");
	}
	function insert($data){
		return $this->db->insert($this->prefix."member",$data);
	}
	
	function update($id,$data){
		$this->db->update($this->prefix."member",$data," id = ".intval($id));
	}
	
	function delete($id){
		$this->db->delete($this->prefix."member"," id = ".intval($id));
		$this->db->delete($this->prefix."member_detail"," memberid = ".intval($id));
	}
	
	function blocked($id, $active=0){
		$this->db->query("UPDATE ".$this->prefix."member SET blocked = ABS(blocked - 1),active=".$active." WHERE id = ".intval($id));
	}
	function active($id){
		$result  = $this->db->query("UPDATE ".$this->prefix."member SET active = ABS(active - 1) WHERE id = ".intval($id));
		return $result->affected_rows();
	}
	function actives($id, $flag){
		$this->db->query("UPDATE ".$this->prefix."member SET active = ".$flag." WHERE id = ".intval($id));
	}
	function getCountGallery($cond = 1){
		return $this->db->query("SELECT count(*) as total, user_created FROM ".$this->prefix."member_gallery WHERE $cond group by user_created");
	}
	function Contentview($type = -1,$cond = 1,$start = 0,$limit = 0,$orderby = NULL){
		if(!$orderby) $orderby = $this->configure_mod['content'][$type]['sort_order'];
		$sql_order = $orderby?" ORDER BY $orderby":"";
		$cond_type = 1;
		if(is_array($type)) $cond_type = " c.type IN (".implode(',',$type).")"; 
		elseif($type>0) $cond_type = " c.type = ".intval($type);
		$result= $this->query("SELECT * FROM ".$this->prefix."content c,".$this->prefix."content_ln ln WHERE c.active = 1 AND $cond_type AND c.id = ln.id AND ln.ln = '".$this->lang."' AND $cond $sql_order".($limit?" LIMIT $start,$limit":""));		
		return $result;
	}
	function orderlist_count($cond = 1){
		return $this->db->mysql_results("SELECT count(id) FROM ".$this->prefix."member_detail WHERE $cond");
	}
	function orderlist($cond = 1,$start=0,$limit = 0,$orderby = " id desc"){
		return $this->db->query("SELECT * FROM ".$this->prefix."member_detail WHERE $cond ORDER BY $orderby".($limit?" LIMIT $start,$limit":""));
	}
	function CreateOrderID($visit = 0)
	{
		$visitortemp = $visit;
		if ($visit < 10)
            $visitortemp = "00000".$visitortemp;
        else if ($visit < 100)
            $visitortemp = "0000".$visitortemp;
        else if ($visit < 1000)
            $visitortemp = "000".$visitortemp;
        else if ($visit < 10000)
            $visitortemp = "00".$visitortemp;
        else if ($visit < 100000)
            $visitortemp = "0".$visitortemp;

		return $visitortemp;
	}
	
}
?>