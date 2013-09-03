<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

class ClassModel extends Model{
	function ClassModel(){
		parent::__construct();
	}
	
	function get($id = 0){
		return $this->db->query("SELECT * FROM ".$this->prefix."html WHERE id = ".intval($id));
	}
	
	function get_ln($id){
		$result =  $this->db->query("SELECT ln.* FROM ".$this->prefix."html_ln ln,".$this->prefix."language l WHERE l.active = 1 AND ln.ln = l.ln AND ln.id = ".intval($id)." ORDER BY l.is_default DESC,l.order_id");
		$data = NULL;
		while($rs = $result->fetch()){
			$data[$rs['ln']] = $rs;
		}
		return $data;
	}
	
	function update($id,$data){
		return $this->db->update($this->prefix.'html',$data," id = ".intval($id));
	}
	
	function update_ln($id,$ln,$data){
		$data['id'] = intval($id);
		$data['ln'] = $ln;
		return $this->db->replace($this->prefix.'html_ln',$data);
	}

	
	function delete($id){
		$result = $this->db->query("SELECT * FROM ".$this->prefix."html WHERE id = ".intval($id));
		if($cat = $result->fetch()){
			@unlink(_UPLOAD.$cat['icon']);
			@unlink(_UPLOAD.$cat['image']);
			@unlink(_UPLOAD.'thumb/'.$cat['image']);
			$data = array(
				'icon'=>'',
				'image'=>'',
				'file_extra'=>'',
				'fields_extra'=>'',
				'date'=>date('Y-m-d'),
			);
			$this->db->update($this->prefix."html",$data,"id=".intval($id));
			$this->db->delete($this->prefix."html_ln","id = ".intval($id));
		}
	}
}
?>