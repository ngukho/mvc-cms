<?php
/**
 * @Name: CaoBox v1.0
 * @author LinhNMT <w2ajax@gmail.com>
 * @link http://code.google.com/p/caobox/
 * @copyright Copyright &copy; 2009 phpbasic
 */
defined('_ROOT') or die(__FILE__);
define('POSSITION_TYPE_ID', 1);
class ClassModel extends Model{
	function ClassModel(){
		parent::__construct();
	}
	
	function view($cond = 1,$start=0,$limit = 0,$orderby = "fullname"){
		return $this->db->query("SELECT * FROM ".$this->prefix."member_gallery WHERE $cond ORDER BY $orderby".($limit?" LIMIT $start,$limit":""));
	}
	
	function get($id){
		return $this->db->query("SELECT * FROM ".$this->prefix."member_gallery WHERE id = ".intval($id));
	}
	function get_detail($id){
		return $this->db->query("SELECT * FROM ".$this->prefix."member_gallery_detail WHERE memberid = ".intval($id));
	}
	function insert($data){
		return $this->db->insert($this->prefix."member_gallery",$data);
	}
	
	function update($id,$data){
		$this->db->update($this->prefix."member_gallery",$data," id = ".intval($id));
	}
	
	function delete($id){
		$this->db->delete($this->prefix."member_gallery"," id = ".intval($id));		
	}
	
	function blocked($id, $active = 0){
		$this->db->query("UPDATE ".$this->prefix."member_gallery SET blocked = ABS(blocked - 1),active = ".$active." WHERE id = ".intval($id));
	}
	function active($id){
		$this->db->query("UPDATE ".$this->prefix."member_gallery SET active = ABS(active - 1) WHERE id = ".intval($id));
	}
	function actives($id, $flag){
		$this->db->query("UPDATE ".$this->prefix."member_gallery SET active = ".$flag." WHERE id = ".intval($id));
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
	
}
?>