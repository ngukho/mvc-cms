<?php

 
defined('_ROOT') or die(__FILE__);

if($_POST){

	//clear_cache_configure();
	// refresh
	
	$query_string = $_SERVER['QUERY_STRING'];
	parse_str($query_string,$result);
	unset($result['act'],$result['module'],$result['typeid'],$result['page_or_id']);
	$hook->redirect('?'.http_build_query($result,NULL,'&'));
}


$tpl->setfile(array(
	'body'=>'configure.fields.tpl',
));

$request['display_update'] = 'style="display: block;"';
$breadcrumb->assign("","Edit configure fields");



//$breadcrumb->reset();

//$breadcrumb->assign("./?mod=product","Manage Products");

$request['breadcrumb'] = $breadcrumb->parse();
$tpl->assign($request);

?>