<?php

 
defined('_ROOT') or die(__FILE__);
if($access!='ALL') $hook->redirect('./');
$tpl->setfile(array('body'=>'group.tpl',));
$cat = $oClass->view();
while($rs = $cat->fetch()){
	$rs['delete'] = $rs['is_admin']?'':'style="display: inline;"';
	$tpl->assign($rs,'group');
}




$breadcrumb->reset();
$menu = explode('.',$_SESSION['cms_menu']);
$breadcrumb->assign("",$MenuName[$menu[0]]);
$level = $MenuLink[$menu[0]][$menu[1]];
$breadcrumb->assign($level['link'],$level['name']);
$request['breadcrumb'] = $breadcrumb->parse();


$tpl->assign($request);


?>