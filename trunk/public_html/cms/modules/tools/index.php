<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}
$breadcrumb = new breadcrumb;
extract($_GET);
$request = $_GET;
$request['type'] = intval($request['type']);
$url = $_GET;
unset($url['do']);
$request['http_referer'] = '?'.http_build_query($url);
$oClass = new ClassModel;

$breadcrumb->reset();
$menu = explode('.',$_SESSION['cms_menu']);
$breadcrumb->assign("",$MenuName[$menu[0]]);
$level = $MenuLink[$menu[0]][$menu[1]];
$breadcrumb->assign($level['link'],$level['name']);
$request['breadcrumb'] = $breadcrumb->parse();


$tpl->assign($request);

?>