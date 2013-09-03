<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

	$oClass->featuredon($request['id']);
	clear_sql_cache();
	// refresh
	$query_string = $_SERVER['QUERY_STRING'];
	parse_str($query_string,$result);
	unset($result['mod'],$result['act'],$result['id'],$result['p']);
	$hook->redirect('?mod='.$request['p'].'&'.http_build_query($result,NULL,'&'));
?>