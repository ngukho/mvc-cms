<?php


if(!defined('_ROOT')) {
	exit('Access Denied');
}

$oClass->set_default($request['ln']);
clear_sql_cache();
$query_string = $_SERVER['QUERY_STRING'];
parse_str($query_string,$result);
unset($result['act'],$result['ln']);
$hook->redirect('?'.http_build_query($result,NULL,'&'));

?>