<?php


if(!defined('_ROOT')) {
	exit('Access Denied');
}

$oClass->active($request['id']);
clear_sql_cache();
$query_string = $_SERVER['QUERY_STRING'];
parse_str($query_string,$result);
unset($result['act'],$result['id']);
$hook->redirect('?'.http_build_query($result,NULL,'&'));

?>