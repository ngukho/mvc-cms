<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

class ClassModel extends Model{
	function ClassModel(){
		parent::__construct();
	}
	
	function get($id = 0){
		return $this->db->query("SELECT * FROM ".$this->prefix."category WHERE id = ".intval($id));
	}
	
	function get_ln($id){
		$result =  $this->db->query("SELECT ln.* FROM ".$this->prefix."category_ln ln,".$this->prefix."language l WHERE l.active = 1 AND ln.ln = l.ln AND ln.id = ".intval($id)." ORDER BY l.is_default DESC,l.order_id");
		$data = NULL;
		while($rs = $result->fetch()){
			$data[$rs['ln']] = $rs;
		}
		return $data;
	}
	
	function update_ln($id,$ln,$data){
		$data['id'] = intval($id);
		$data['ln'] = $ln;
		return $this->db->replace($this->prefix.'category_ln',$data);
	}
	
	function update($id,$data){
		return $this->db->update($this->prefix.'category',$data," id = ".intval($id));
	}
	
	function active($id){
		return $this->db->query("UPDATE ".$this->prefix."category SET active = ABS(active - 1) WHERE id = ".intval($id));
	}
	function featuredon($id){
		return $this->db->query("UPDATE ".$this->prefix."category SET featureon = ABS(featureon - 1) WHERE id = ".intval($id));
	}
	function top($id){
		return $this->db->query("UPDATE ".$this->prefix."category SET `top` = ABS(`top`- 1) WHERE id = ".intval($id));
	}
	function insert($data){
		$this->db->insert($this->prefix.'category',$data);
		$insert_id = $this->db->insert_id();
		if($insert_id) $this->db->query("UPDATE ".$this->prefix."category SET order_id = order_id + 1 WHERE parentid = ".intval($data['parentid'])." AND `type` = ".intval($data['type'])." AND `id` < ".intval($insert_id));
		return $insert_id;
	}
	
	
	function delete($id,$update_order = false){
		$result = $this->db->query("SELECT * FROM ".$this->prefix."category WHERE id = ".intval($id));
		$cat = $result->fetch();
		if($cat){
			$result = $this->db->query("SELECT * FROM ".$this->prefix."content WHERE catid = ".intval($id));
			while($rs = $result->fetch()){
				$this->db->delete($this->prefix."content","id = ".$rs['id']);
				@unlink(_UPLOAD.$rs['icon']);
				@unlink(_UPLOAD.$rs['image']);
				@unlink(_UPLOAD.'thumb/'.$rs['image']);
				@unlink(_UPLOAD.'thumb/'.$rs['ln_image']);
				$result_ln = $this->db->query("SELECT * FROM ".$this->prefix."content_ln WHERE id = ".intval($id));
				while($rs_ln = $result_ln->fetch()){
					@unlink(_UPLOAD.$rs_ln['ln_icon']);
					@unlink(_UPLOAD.$rs_ln['ln_image']);
				}
				$this->db->delete($this->prefix."content_ln","id = ".$rs['id']);
			}
			$this->db->delete($this->prefix."category","id = ".intval($id));
			
			@unlink(_UPLOAD.$cat['icon']);
			@unlink(_UPLOAD.$cat['image']);
			@unlink(_UPLOAD.'thumb/'.$cat['image']);
			$result = $this->db->query("SELECT * FROM ".$this->prefix."category_ln WHERE id = ".intval($id));
			while($rs = $result->fetch()){
				@unlink(_UPLOAD.$rs['ln_icon']);
				@unlink(_UPLOAD.$rs['ln_image']);
			}
			$this->db->delete($this->prefix."category_ln","id = ".intval($id));
			if($update_order) $this->db->query("UPDATE ".$this->prefix."category SET order_id = order_id - 1 WHERE type = ".$cat['type']." AND parentid = ".$cat['parentid']." AND order_id > ".$cat['order_id']);
		}
	}
	
	function deleteOpt($content_id = 0,$options_type = 0, $options_id = 0){
		$cond = " page='category' ";
		if($content_id) $cond .= " AND content_id = ".intval($content_id);
		if($options_type) $cond .= " AND options_type = ".intval($options_type);
		if($options_id) $cond .= " AND options_id = ".intval($options_id);
		
		return $this->db->query("DELETE FROM ".$this->prefix."content_options WHERE $cond");

	
	}	
	function insertOpt($data){
		$result = $this->db->replace($this->prefix.'content_options',$data);
		return $this->db->insert_id();
		
	}
	
}
?>