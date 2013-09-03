<?php

 
defined('_ROOT') or die(__FILE__);
$current_catid = $request['parentid'];
if($_POST){
	$current_catid = $_POST['catid'];
	$arr = array(
		'catid'=>$_POST['catid']
	);
	if($request['id']) $oClass->move($request['id'],$_POST['catid']);
	if($_POST['pro']) foreach(explode(',',$_POST['pro']) as $id) $oClass->move($id,$_POST['catid']);
	clear_sql_cache();
	$query_string = $_SERVER['QUERY_STRING'];
	parse_str($query_string,$result);
	unset($result['act'],$result['id']);
	$hook->redirect('?'.http_build_query($result,NULL,'&'));
}
	
$tpl->setfile(array(
	'body'=>'content.move.tpl',
));
$breadcrumb->assign("","Move to category");

$tree = $oCategory->tree($request['type'],0,'&nbsp;',1);
foreach($tree as $rs){
	$rs['prefix'] = $rs['prefix'].'|&mdash;';
	$rs['selected'] = $rs['id']==$current_catid?'selected':'';
	$tpl->assign($rs,'category');
}



$request['breadcrumb'] = $breadcrumb->parse();


$tpl->assign($request);
?>