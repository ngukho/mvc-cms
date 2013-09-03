<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

$oClass = new ClassModel;
$breadcrumb = new breadcrumb;
$request['query_string'] = '?'.$_SERVER['QUERY_STRING'];
$url = $_GET;
unset($url['act']);
$request['http_referer'] = '?'.http_build_query($url);
$table_row = ie()?'block':'table-row';



$cfg_type = array();
if($request['type']){
	$result = $oConfigure->getMod(" `module`='".$system->module."' AND typeid=".intval($request['type']));
	$tmp = $result->fetch();
	$request['module_description'] = nl2br($tmp['content']);
	if($tmp['data']) $cfg_type = unserialize($tmp['data']);
}	
//
$show_actions = $cfg_type['act']?$cfg_type['act']:array();


$show_fields = $cfg_type['show_fields']?explode(',',$cfg_type['show_fields']):array();

if(in_array('image',$show_actions)&&$cfg_type['main_icon']['chose']) $show_fields[] = 'icon';
if(in_array('image',$show_actions)&&$cfg_type['main_img']['chose']) $show_fields[] = 'image';
if(in_array('ln_image',$show_actions)&&$cfg_type['ln_main_icon']['chose']) $show_fields[] = 'ln_icon';
if(in_array('ln_image',$show_actions)&&$cfg_type['ln_main_img']['chose']) $show_fields[] = 'ln_image';
if(in_array('gallery',$show_actions)) $show_fields[] = 'gallery';
if(in_array('enable_date',$show_actions)) $show_fields[] = 'enable_date';
$dragdrop_plugin =  $cfg_type['sort_default'] == 'order_id' && $cfg_type['sort_default_order']!='DESC' && ($request['by'] == '' || $request['by'] == 'order_id') && $request['order']!='DESC';
if(in_array('drapdrop_content',$show_actions) && $dragdrop_plugin) {
	$tpl->box('drapdrop_content');
	$request['icon_updown'] = 'show';
}

$tmp = $cfg_type['gallery_fields']?explode(',',$cfg_type['gallery_fields']):array();
if(in_array('gallery',$cfg_type['act']) && $cfg_type['gallery_icon']['chose']) $tmp[] = 'icon';
foreach($tmp as $v) $show_fields[] = 'gallery_'.$v;//.':block';


?>