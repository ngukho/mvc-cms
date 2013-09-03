<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

$oClass = new ClassModel;
$breadcrumb = new breadcrumb;

extract($_GET);
$request = $_GET;
$request['type'] = intval($type);
$request['parentid'] = intval($parentid);
$request['query_string'] = '?'.$_SERVER['QUERY_STRING'];
$request['http_referer'] = $_SERVER['HTTP_REFERER'];

$cfg_type = array();
if($request['p']=='content'){
	$result = $oConfigure->getMod(" `module`='".$request['p']."' AND typeid=".intval($request['type']));
}elseif($request['p']=='html'){
	$result = $oConfigure->getMod(" `module`='html' AND typeid=".intval($request['pid']));
}

$tmp = $result->fetch();
if($tmp['data']) $cfg_type = unserialize($tmp['data']);
$show_actions = $cfg_type['act']?$cfg_type['act']:array();
$show_fields = $cfg_type['gallery_fields']?explode(',',$cfg_type['gallery_fields']):array();
if(in_array('gallery',$show_actions) && $cfg_type['gallery_icon']['chose']) $show_fields[] = 'icon';

?>