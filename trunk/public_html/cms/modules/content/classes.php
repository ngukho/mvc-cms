<?php

defined('_ROOT') or die(__FILE__);

class ClassModel extends Model{
	function ClassModel(){
		parent::__construct();
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
	
	function viewall($type=0,$catid = -1,$q = NULL,$where = 1,$start = 0,$limit = 0,$sorted_by = "c.timestamp",$sorted_order = "DESC"){
		$cond = $type?"c.type in (".$type.")":1;
		$order = "";
		if($sorted_by && $sorted_order) $order = ",$sorted_by $sorted_order";
		if($catid >=0) $cond .= " AND c.catid = ".intval($catid);
		if($q) $where .= " AND ln.name LIKE '%".addslashes($q)."%'";
		return $this->db->query("SELECT * FROM ".$this->prefix."content c,".$this->prefix."content_ln ln WHERE c.id = ln.id AND ln.ln = '".$this->lang."' AND $cond AND $where ORDER BY FIND_IN_SET(c.type, '".$type."'), ln.name, c.order_id,c.date DESC ".($limit?" LIMIT $start,$limit":""));
	}
	
	function get($id = 0){
		return $this->db->query("SELECT * FROM ".$this->prefix."content WHERE id = ".intval($id));
	}

	function get_ln($id){
		$result =  $this->db->query("SELECT ln.* FROM ".$this->prefix."content_ln ln,".$this->prefix."language l WHERE l.active = 1 AND ln.ln = l.ln AND ln.id = ".intval($id)." ORDER BY l.is_default DESC,l.order_id");
		$data = NULL;
		while($rs = $result->fetch()){
			$data[$rs['ln']] = $rs;
		}
		return $data;
	}
	function insert($data){
		$result = $this->db->insert($this->prefix.'content',$data);
		$insert_id = $this->db->insert_id();
		if($insert_id) $this->db->query("UPDATE ".$this->prefix."content SET order_id = order_id + 1 WHERE catid = ".intval($data['catid'])." AND `type` = ".intval($data['type'])." AND `id` < ".intval($insert_id));
		return $insert_id;
		
	}
	function update_ln($id,$ln,$data){
		$data['id'] = intval($id);
		$data['ln'] = $ln;
		return $this->db->replace($this->prefix.'content_ln',$data);
	}
	function update($id,$data){
		return $this->db->update($this->prefix.'content',$data," id = ".intval($id));
	}
	
	
	function active($id,$val = -1){
		if($val==0) $v = ' 0';
		elseif($val==1) $v = '1';
		else $v = ' ABS(`active` - 1)';
		return $this->db->query("UPDATE ".$this->prefix."content SET active = $v WHERE id = ".intval($id));
	}
	
	function statusfield($field,$id){
	
		return $this->db->query("UPDATE ".$this->prefix."content SET `$field` = ABS(`$field` - 1) WHERE id = ".intval($id));
	}
	

	function delete($id){
		$result = $this->db->query("SELECT * FROM ".$this->prefix."content WHERE id = ".intval($id));
		$content = $result->fetch();
		if($content){
			@unlink(_UPLOAD.$content['icon']);
			@unlink(_UPLOAD.$content['image']);
			@unlink(_UPLOAD.'thumb/'.$content['image']);
			@unlink(_UPLOAD.'thumb/'.$content['ln_image']);
			$result = $this->db->query("SELECT * FROM ".$this->prefix."content_ln WHERE id = ".intval($id));
			while($rs = $result->fetch()){
				@unlink(_UPLOAD.$rs['ln_icon']);
				@unlink(_UPLOAD.$rs['ln_image']);
			}
			$this->db->delete($this->prefix."content","id = ".intval($id));
			$this->db->delete($this->prefix."content_ln","id = ".intval($id));
			$this->db->query("UPDATE ".$this->prefix."content SET order_id = order_id - 1 WHERE type = ".$content['type']." AND catid = ".$content['catid']." AND order_id > ".$content['order_id']);
		}
	}
	
	function deleteOpt($content_id = 0,$options_type = 0, $options_id = 0){
		$cond = " page='content' ";
		if($content_id) $cond .= " AND content_id = ".intval($content_id);
		if($options_type) $cond .= " AND options_type = ".intval($options_type);
		if($options_id) $cond .= " AND options_id = ".intval($options_id);
		
		return $this->db->query("DELETE FROM ".$this->prefix."content_options WHERE $cond");

	
	}
	
	function insertOpt($data){
		$result = $this->db->replace($this->prefix.'content_options',$data);
		return $this->db->insert_id();
		
	}
	
	
	function move($id, $to_catid){
		$arr = array(
			'catid'=>intval($to_catid),
			'order_id'=>0,
		);
		$result = $this->db->query("SELECT * FROM ".$this->prefix."content WHERE id = ".intval($id));
		$content = $result->fetch();
		$this->db->update($this->prefix."content",$arr,' id = '.intval($id));
		if($content){
			$this->db->query("UPDATE ".$this->prefix."content SET order_id = order_id - 1 WHERE type = ".$content['type']." AND catid = ".$content['catid']." AND order_id > ".$content['order_id']);
			$this->db->query("UPDATE ".$this->prefix."content SET order_id = order_id + 1 WHERE type = ".$content['type']." AND catid = ".intval($to_catid)." AND id != ".intval($id));
		}
	}
}
?>