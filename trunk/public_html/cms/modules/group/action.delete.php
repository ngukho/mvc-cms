<?php

defined('_ROOT') or die(__FILE__);
if($access!='ALL') $hook->redirect('./');

if($request['c']){
	$oClass->delete($request['id']);
	$oMaster->user_log('Deleted userId: '.$request['id']);
	// refresh
	$query_string = $_SERVER['QUERY_STRING'];
	parse_str($query_string,$result);
	unset($result['act'],$result['id'],$result['c']);
	$hook->redirect('?'.http_build_query($result));
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