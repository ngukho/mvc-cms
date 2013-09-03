<?php
if(!function_exists('http_build_query')){
	function http_build_query($arr = NULL,$sep = '&amp;'){
		if(!is_array($arr)) return $arr;
		$tmp = array();
		foreach($arr as $key=>$val) $tmp[] = "{$key}={$val}";
		return implode($sep,$tmp);
	}
}
if(!function_exists('strip_tags')){
	function strip_tags($string, $allowable_tags = NULL){
		return $string;
	}
}
?>