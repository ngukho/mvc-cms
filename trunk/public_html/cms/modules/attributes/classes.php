<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

class ClassModel extends Model{
	function ClassModel(){
		parent::__construct();
	}
	
	function view($obj_id=0, $obj_type="", $cond=1){	
		$cond .= $obj_id>0?" and obj_id=".$obj_id:"";
		$cond .= $obj_type!=""?" and obj_type='".$obj_type."'":"";
		return $this->db->query("SELECT * FROM ".$this->prefix."attributes c inner join ".$this->prefix."attributes_ln ln on (c.id = ln.id) WHERE ln.ln = '".$this->lang."' and $cond ORDER BY order_id asc");
	}
	
	function get($id = 0){
		return $this->db->query("SELECT * FROM ".$this->prefix."attributes WHERE id = ".intval($id));
	}

	function get_ln($id){
		$result =  $this->db->query("SELECT ln.* FROM ".$this->prefix."attributes_ln ln,".$this->prefix."language l WHERE l.active = 1 AND ln.ln = l.ln AND ln.id = ".intval($id));
		$data = NULL;
		while($rs = $result->fetch()){
			$data[$rs['ln']] = $rs;
		}
		return $data;
	}
	function insert($data){
		$result = $this->db->insert($this->prefix.'attributes',$data);
		return $this->db->insert_id();
		
	}
	function update_ln($id,$ln,$data){
		$data['id'] = intval($id);
		$data['ln'] = $ln;
		return $this->db->replace($this->prefix.'attributes_ln',$data);
	}
	function update($id,$data){
		return $this->db->update($this->prefix.'attributes',$data," id = ".intval($id));
	}
	
	
	function active($id,$val = -1){
		if($val==0) $v = ' 0';
		elseif($val==1) $v = '1';
		else $v = ' ABS(`active` - 1)';
		return $this->db->query("UPDATE ".$this->prefix."attributes SET active = $v WHERE id = ".intval($id));
	}
	
	

	function delete($id){
		$result = $this->db->query("DELETE FROM ".$this->prefix."attributes WHERE id = ".intval($id));
		$result = $this->db->query("DELETE FROM ".$this->prefix."attributes_ln WHERE id = ".intval($id));
	}
	
}
?>