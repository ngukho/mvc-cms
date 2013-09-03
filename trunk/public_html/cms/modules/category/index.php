<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

$oClass = new ClassModel;
$breadcrumb = new breadcrumb;

$request['query_string'] = '?'.$_SERVER['QUERY_STRING'];
$request['http_referer'] = $_SERVER['HTTP_REFERER'];

$aPath = $oCategory->xpath($_GET['parentid']);
$xPath = '<a href="?mod=content&type='.$_GET['type'].'">Root</a>';
if($aPath) for($i=count($aPath)-1;$i>=0;$i--){
	$xPath .=  ' &raquo; <a href="?mod=content&parentid='.$aPath[$i]['id'].'&type='.$aPath[$i]['type'].'">'.$aPath[$i]['name'].'</a>';
	$request['http_referer'] = $request['http_referer']?$request['http_referer']:'?mod=content&parentid='.$aPath[$i]['id'].'&type='.$aPath[$i]['type'].'';
}
else
{
	$request['http_referer'] = '?mod=content&type='.$_GET['type'].'';
}
$tpl->assign(array('xpath' => $xPath));

$cfg_type = array();

$result = $oConfigure->getMod(" `module`='content' AND typeid=".intval($request['type']));
$tmp = $result->fetch();
if($tmp['data']) $cfg_type = unserialize($tmp['data']);
$show_actions = $cfg_type['act']?$cfg_type['act']:array();
$show_fields = array();
/*Check Level Category*/
$aPath = $oCategory->xpath($_GET['parentid']);
$level = count($aPath);

$arrlevel = $cfg_type['catimg_level']?explode(",",$cfg_type['catimg_level']):"";
if(in_array('catimg',$show_actions) && $cfg_type['catimg_icon']['chose'] && (in_array($level, $arrlevel) || $arrlevel=="")) $show_fields[] = 'icon';
if(in_array('catimg',$show_actions) && $cfg_type['catimg_img']['chose'] && (in_array($level, $arrlevel) || $arrlevel=="")) $show_fields[] = 'image';

if(in_array('ln_catimg',$show_actions) && $cfg_type['ln_catimg_icon']['chose']) $show_fields[] = 'ln_icon';
if(in_array('ln_catimg',$show_actions) && $cfg_type['ln_catimg_img']['chose']) $show_fields[] = 'ln_image';
if(in_array('enable_catdate',$show_actions)) $show_fields[] = 'enable_catdate';

$arrlevel = $cfg_type['gallerycat_in_level']?explode(",",$cfg_type['gallerycat_in_level']):"";
if(in_array('gallerycat',$show_actions) &&  in_array($level,$arrlevel)) 
	$show_fields[] = 'gallerycat';
else if(in_array('gallerycat',$show_actions) && $cfg_type['gallerycat_in_level']=="")
	$show_fields[] = 'gallerycat';

?>