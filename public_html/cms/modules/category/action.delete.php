<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}


if($request['c']){
	$tree = $oCategory->tree($request['type'],$request['id']);
	if($tree) foreach($tree as $rs) $oClass->delete($rs['id']);
	$oClass->delete($request['id'],true);
	clear_sql_cache();
	if($request['ajax']) die('1');
	// refresh
	$query_string = $_SERVER['QUERY_STRING'];
	parse_str($query_string,$result);
	$mod = $result['p'];
	unset($result['mod'],$result['act'],$result['id'],$result['c'],$result['p']);
	$hook->redirect('?mod='.$mod.'&'.http_build_query($result,NULL,'&'));
}else{

	
	$tpl->setfile(array(
		'body'=>'category.delete.tpl',
	));
	$breadcrumb->reset();
	$breadcrumb->assign("","Products");
	$breadcrumb->assign("./?mod=product","Manage Products");
	
	
	
	$request['breadcrumb'] = $breadcrumb->parse();
	$tpl->assign($request);
}	
?>