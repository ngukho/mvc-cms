<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

$oClass = new ClassModel;
$breadcrumb = new breadcrumb;
$request['query_string'] = '?'.$_SERVER['QUERY_STRING'];
$request['http_referer'] = $_SERVER['HTTP_REFERER'];
$result = $oConfigure->getMod(" `module`='".$request['p']."' AND typeid=".intval($request['type']));
$tmp = $result->fetch();
if($tmp['data']) $cfg_type = unserialize($tmp['data']);

$aPath = $oCategory->xpath($_GET['parentid']);
$xPath = '<a href="?mod=content&type='.$arr['type'].'">Root</a>';
if($aPath) for($i=count($aPath)-1;$i>=0;$i--){
	$xPath .=  ' &raquo; <a href="?mod=content&parentid='.$aPath[$i]['id'].'&type='.$aPath[$i]['type'].'">'.$aPath[$i]['name'].'</a>';
}
$tpl->assign(array('xpath' => $xPath));

?>