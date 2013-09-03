<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

$oClass = new ClassModel;
$breadcrumb = new breadcrumb;
$request['query_string'] = '?'.$_SERVER['QUERY_STRING'];
$url = $_GET;
unset($url['act']);
$request['http_referer'] = '?'.http_build_query($url);
$request['action_modify'] = $cfg['root_admin']?'show':'';
$request['action_update_module'] = $cfg['root_admin']?'table_list':'hide';



?>