<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

$tpl->setfile(array(
	'body'=>'language.tpl',
));

$cat = $oLanguage->view();
while($rs = $cat->fetch()){
	$tpl->assign($rs,'language');
}





$breadcrumb->reset();
if(!$_SESSION['cms_menu']) $_SESSION['cms_menu'] = '0.0';
if($_GET['menu']){
	$_SESSION['cms_menu'] = $_GET['menu'];
}
$menu = explode('.',$_SESSION['cms_menu']);
$breadcrumb->assign("",$MenuName[$menu[0]]);
$level = $MenuLink[$menu[0]][$menu[1]];
$breadcrumb->assign($level['link'],$level['name']);
$request['breadcrumb'] = $breadcrumb->parse();
$request['hide_language_access'] = $cfg['root_admin']?'':'hide';
$tpl->assign($request);




$show_actions = array('delete');
$action = array();
foreach($show_actions as $act) $action['action_'.$act] = 'style="display: inline;"';
$tpl->assign($action);

?>