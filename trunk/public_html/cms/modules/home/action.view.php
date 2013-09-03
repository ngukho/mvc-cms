<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}
//$hook->redirect($system->domain."".$system->project."?mod=member&enabled=0&menu=6.1");
$tpl->setfile(array('body'=>'home.tpl'));

$breadcrumb->reset();
$breadcrumb->assign("",$cfg['client']);
$breadcrumb->assign('?mod=home','Dashboard');
$request['breadcrumb'] = $breadcrumb->parse();
$tpl->assign($request);
	
?>