<?php

defined('_ROOT') or die(__FILE__);

class ClassModel extends Model{
	function ClassModel(){
		parent::__construct();
	}
	
	function get_module($module='product',$typeid=0){
		return $this->db->query("SELECT * FROM ".$this->prefix."configure_mod WHERE `module`='".addslashes($module)."' AND typeid = ".intval($typeid));
	}
	
	function max_module($module){
		return $this->db->query("SELECT MAX(typeid) maxid FROM ".$this->prefix."configure_mod WHERE `module` = '$module'");
	}
	
	function insert_module($module,$typeid,$name=""){
		return $this->db->query("INSERT INTO ".$this->prefix."configure_mod(`module`,typeid,name) VALUES('$module',$typeid,'$name')");
	}
	
	function check_html($id){
		return $this->db->query("SELECT id FROM ".$this->prefix."html WHERE id = ".intval($id));
	}
	
	function insert_html($id){
		return $this->db->query("REPLACE INTO ".$this->prefix."html(id) VALUES(".intval($id).")");
	}
	
	function delete_options($typeid){
		$result = $this->db->query("SELECT id FROM ".$this->prefix."configure_mod WHERE typeid = ".intval($typeid));
		
	}
	
	
	function update_module($module,$typeid,$data){
		return $this->db->update($this->prefix.'configure_mod',$data," `module`= '".addslashes($module)."' AND typeid = ".intval($typeid));
	}
	
	function update($data){
		foreach($data as $key=>$val) $this->db->update($this->prefix.'configure',array('value'=>addslashes($val))," code = '$key'");
		return true;
	}
	
	function clear_data(){
		$this->db->query("TRUNCATE TABLE `".$this->prefix."category_ln`");
		$this->db->query("TRUNCATE TABLE `".$this->prefix."category`");
		$this->db->query("TRUNCATE TABLE `".$this->prefix."comment`");
		$this->db->query("TRUNCATE TABLE `".$this->prefix."gallery`");
		$this->db->query("TRUNCATE TABLE `".$this->prefix."content`");
		$this->db->query("TRUNCATE TABLE `".$this->prefix."content_options`");
		$this->db->query("TRUNCATE TABLE `".$this->prefix."content_ln`");
		$this->db->query("TRUNCATE TABLE `".$this->prefix."html`");
		$this->db->query("TRUNCATE TABLE `".$this->prefix."html_ln`");
		$this->db->query("TRUNCATE TABLE `".$this->prefix."options`");
		$this->db->query("TRUNCATE TABLE `".$this->prefix."options_ln`");
		$this->db->query("TRUNCATE TABLE `".$this->prefix."session`");
		$this->db->query("TRUNCATE TABLE `".$this->prefix."contentsub`");
		$this->db->query("TRUNCATE TABLE `".$this->prefix."contentsub_ln`");
		$this->db->query("TRUNCATE TABLE `".$this->prefix."module`");
		$this->db->query("TRUNCATE TABLE `".$this->prefix."module_ln`");
	}
	
	function clear_configure(){
		$this->db->query("TRUNCATE TABLE `".$this->prefix."configure_mod`");
	}
	function fields($table, $arrfields=array()){
		$aField = $arrTemp = array();		
		$fields_query = $this->db->query("SHOW FULL FIELDS FROM ".$this->prefix . $table);		
		while ($fields = $fields_query->fetch()) {
			$arrTemp[$fields["Field"]] = $fields;
		}			
		if (is_array($arrfields)){
			foreach($arrfields as $val){
				if (isset($arrTemp[$val])){
					$aField[] = $arrTemp[$val];
					unset($arrTemp[$val]);
					
				}				
			}			
		}
		foreach($arrTemp as $val){
				$aField[] = $val;
		}
		return $aField;
	}
	
	function input_type($name,$c,$status = false,$params = ''){
		$type = array(
			'input'=>'Input',
			'checkbox'=>'Check Box',
			'radio'=>'Radio',
			'selectbox'=>'Select Box',
			'textarea'=>'Textarea',
			'tinymce'=>'TextEditor Advance',
			'simplemce'=>'TextEditor Simple',
			'simplemce1'=>'TextEditor Simple 1',
			'simplemce2'=>'TextEditor Simple 2',
			'simplemce3'=>'TextEditor Simple 3',
		);
		if($status) $type['status'] = 'As status field';
		$str = '<select name="'.$name.'" '.($params?$params:'').'>';
		foreach($type as $key=>$val) $str .= '<option value="'.$key.'" '.($key==$c?'selected':'').'>'.$val.'</option>';
		$str .= '</select>';
		return $str;
	}
	
	function input_status_default($name,$c,$params = NULL){
		$type = array(
			'0'=>'InActive',
			'1'=>'Active',
		);
		$str = '<select name="'.$name.'" '.($params?$params:'').'>';
		foreach($type as $key=>$val) $str .= '<option value="'.$key.'" '.($key==$c?'selected':'').'>'.$val.'</option>';
		$str .= '</select>';
		return $str;
	}
	
	function insert($arr){
		return $this->db->insert($this->prefix."configure",$arr);
	}
}
?>