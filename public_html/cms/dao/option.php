<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

class OptionDAO extends Model{
	function OptionDAO(){
		parent::__construct();
	}
	
	function view($type=0){
		return $this->db->query("SELECT * FROM ".$this->prefix."options c,".$this->prefix."options_ln ln WHERE c.id = ln.id AND ln.ln = '".$this->lang."' AND c.type = ".intval($type));
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
	function insertOpt($data){
		$result = $this->db->replace($this->prefix.'content_options',$data);
		return $this->db->insert_id();
		
	}
	

}

?>