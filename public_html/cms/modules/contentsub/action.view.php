<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

$tpl->setfile(array(
	'body'=>'contentsub.tpl',
));

$cat = $oComment->get($request['p'],$request['pid']);
while($rs = $cat->fetch()){
	//$rs['mem'] = $rs['username']?$rs['username']:'Visitor';
	$tpl->assign($rs,'comment');
}


$breadcrumb->assign("","Comments");



$request['breadcrumb'] = $breadcrumb->parse();

$request['LINK'] = '?'.http_build_query($_GET);

$back = $_GET;
unset($back['mod'],$back['p'],$back['pid'],$back['gp']);
if($request['p']=='gallery'){
	$request['BACK'] = '?mod='.$request['p'].'&p='.$request['gp'].'&'.http_build_query($back);
}else{
	$request['BACK'] = '?mod='.$request['p'].'&'.http_build_query($back);
}
$tpl->assign($request);




$show_actions = array('delete');
$action = array();
foreach($show_actions as $act) $action['action_'.$act] = 'style="display: inline;"';
$tpl->assign($action);

?>