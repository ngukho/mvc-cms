<?php

defined('_ROOT') or die(__FILE__);

function textfield($name = 'textfield',$value=''){
	$str = '<input type="text" name="'.$name.'" value="'.$value.'"';
	$str .= ' />';
	return $str;
}
function textarea($value = ''){
	$str .= '<textarea name="value" rows="5">';
	$str .= htmlentities($value,ENT_QUOTES,'utf-8');
	$str .= '</textarea>';
	return $str;
}

function options_radio($array = array(),$typeid,$values=array()){
	$str .= '<ul>';	
	foreach($array as $id=>$v){
		$str .= '<li><input type="radio" name="options['.$typeid.'][]" class="no_width" id="radio_'.$id.'"  value="'.$id.'"';
		if(is_array($values) && in_array($id,$values)) $str .= ' checked="checked"';
		$str .=' /> <label for="radio_'.$id.'">'.$v.'</label></li>';
	}
	$str .= '</ul>';	
	return $str;
}
function options_checkbox($array = array(),$typeid,$values=array()){
	$str .= '<ul>';	
	foreach($array as $id=>$v){
		$str .= '<li><input type="checkbox" name="options['.$typeid.'][]" class="no_width" id="radio_'.$id.'"  value="'.$id.'"';
		if(is_array($values) && in_array($id,$values)) $str .= ' checked="checked"';
		$str .=' /> <label for="checkbox_'.$id.'">'.$v.'</label></li>';
	}
	$str .= '</ul>';	
	return $str;
}

function options_dropdown($array = array(),$typeid, $values  = array(),$params = ""){
	if(!count($array)) return false;
	$str = '<select name="options['.$typeid.'][]"'.($params?$params:'').' >';
	foreach($array as $id=>$v) $str .='<option value="'.$id.'"'.(is_array($values)&&in_array($id,$values)?'selected':'').'>'.$v.'</option>';
	$str .= '</select>';
	return $str;
}

?>