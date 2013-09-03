<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

class ClassModel extends Model{
	function ClassModel(){
		parent::__construct();
	}
	
	function view($type=0,$q = NULL,$where = 1,$start = 0,$limit = 0,$sorted_by = "c.order_id"){
		$order = "";
		if($sorted_by && $sorted_order) $order = ",$sorted_by $sorted_order";
		$cond = "c.type = ".intval($type);
		if($q) $where .= " AND ln.name LIKE '%".addslashes($q)."%'";
		return $this->db->query("SELECT * FROM ".$this->prefix."options c,".$this->prefix."options_ln ln WHERE c.id = ln.id AND ln.ln = '".$this->lang."' AND $cond AND $where ORDER BY $sorted_by".($limit?" LIMIT $start,$limit":""));
	}
	
	function get($id = 0){
		return $this->db->query("SELECT * FROM ".$this->prefix."options WHERE id = ".intval($id));
	}

	function get_ln($id){
		$result =  $this->db->query("SELECT ln.* FROM ".$this->prefix."options_ln ln,".$this->prefix."language l WHERE l.active = 1 AND ln.ln = l.ln AND ln.id = ".intval($id)." ORDER BY l.is_default DESC,l.order_id");
		$data = NULL;
		while($rs = $result->fetch()){
			$data[$rs['ln']] = $rs;
		}
		return $data;
	}
	function insert($data){
		$result = $this->db->insert($this->prefix.'options',$data);
		$insert_id = $this->db->insert_id();
		if($insert_id){
			$this->db->query("UPDATE ".$this->prefix."options SET order_id = order_id + 1 WHERE  `type` = ".intval($data['type'])."  AND `id` < ".intval($insert_id));
		}
		return $insert_id;
		
	}
	function update_ln($id,$ln,$data){
		$data['id'] = intval($id);
		$data['ln'] = $ln;
		return $this->db->replace($this->prefix.'options_ln',$data);
	}
	function update($id,$data){
		return $this->db->update($this->prefix.'options',$data," id = ".intval($id));
	}
	
	
	function active($id,$val = -1){
		if($val==0) $v = ' 0';
		elseif($val==1) $v = '1';
		else $v = ' ABS(`active` - 1)';
		return $this->db->query("UPDATE ".$this->prefix."options SET active = $v WHERE id = ".intval($id));
	}
	
	

	function delete($id){
		$result = $this->db->query("SELECT * FROM ".$this->prefix."options WHERE id = ".intval($id));
		$content = $result->fetch();
		if($content){
			@unlink(_UPLOAD.$content['icon']);
			@unlink(_UPLOAD.$content['image']);
			@unlink(_UPLOAD.'thumb/'.$content['image']);
			@unlink(_UPLOAD.'thumb/'.$content['ln_image']);
			$result = $this->db->query("SELECT * FROM ".$this->prefix."options_ln WHERE id = ".intval($id));
			while($rs = $result->fetch()){
				@unlink(_UPLOAD.$rs['ln_icon']);
				@unlink(_UPLOAD.$rs['ln_image']);
			}
			$this->db->delete($this->prefix."options","id = ".intval($id));
			$this->db->delete($this->prefix."options_ln","id = ".intval($id));
			$this->db->query("UPDATE ".$this->prefix."options SET order_id = order_id - 1 WHERE type = ".$content['type']."  AND order_id > ".$content['order_id']);

		}
	}
	function statusfield($field,$id){
	
		return $this->db->query("UPDATE ".$this->prefix."options SET `$field` = ABS(`$field` - 1) WHERE id = ".intval($id));
	}
	
}
?>