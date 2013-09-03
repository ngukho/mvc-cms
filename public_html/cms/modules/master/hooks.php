<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}
/*
// list functions can hook ( functions were defined in core/hooks.php)
	function web_header() // load first
	function web_footer() // load last
	function module_before() //load before module run
	function module_after() // load after modul run
	function url($arr_parametters = NULL)
	function redirect($url = NULL)
	function dateformat($date = NULL,$format = 'd/m/Y')
	function output($string = NULL,$html  = 1)
	function encrypt($string) // current we are  using md5
	function cookie($name,$value,$path,$domain)
*/

function redirect($url = NULL){
	if(!$url) return false;
	$token = isset($_GET['token'])?$_GET['token']:session_id();
	parse_str($url,$data);
	if(!$data['token']){
		if(strpos($url,'?') === false) $url .= '?token='.$token;
		else $url .= '&token='.$token;
	}
	header('location: '.$url);
	exit();
}
?>