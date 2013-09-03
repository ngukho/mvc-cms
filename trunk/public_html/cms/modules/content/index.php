<?php

defined('_ROOT') or die(__FILE__);

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


$show_fields = array();

if(in_array('image',$show_actions)&&$cfg_type['main_icon']['chose']) $show_fields[] = 'icon';
if(in_array('image',$show_actions)&&$cfg_type['main_img']['chose']) $show_fields[] = 'image';
if(in_array('ln_image',$show_actions)&&$cfg_type['ln_main_icon']['chose']) $show_fields[] = 'ln_icon';
if(in_array('ln_image',$show_actions)&&$cfg_type['ln_main_img']['chose']) $show_fields[] = 'ln_image';
if(in_array('gallery',$show_actions)) $show_fields[] = 'gallery';
$dragdrop_plugin =  $cfg_type['sort_default'] == 'order_id' && $cfg_type['sort_default_order']!='DESC' && ($request['by'] == '' || $request['by'] == 'order_id') && $request['order']!='DESC';
if(in_array('enable_date',$show_actions)) $show_fields[] = 'enable_date';
if(in_array('drapdrop_content',$show_actions) && $dragdrop_plugin){
	$tpl->box('drapdrop_content');
	$request['icon_updown'] = 'show';
}
if(in_array('drapdrop_cat',$show_actions) && $dragdrop_plugin)  $tpl->box('drapdrop_cat');

$tmp = $cfg_type['gallery_fields']?explode(',',$cfg_type['gallery_fields']):array();
if(in_array('gallery',$cfg_type['act']) && $cfg_type['gallery_icon']['chose']) $tmp[] = 'icon';
foreach($tmp as $v) $show_fields[] = 'gallery_'.$v;//.':block';


if(in_array('newcat',$show_actions) && intval($_GET['parentid'])){
	$aPath = $oCategory->xpath($_GET['parentid']);
	$level = count($aPath);
	$xPath = '<a href="?mod='.$system->module.'&type='.$request['type'].'">'.($cfg_type['button']['root']?$cfg_type['button']['root']:$languages['root']).'</a>';
	if($aPath) for($i=$level-1;$i>=0;$i--){
		$xPath .=  ' &raquo; <a href="?mod='.$system->module.'&parentid='.$aPath[$i]['id'].'&type='.$aPath[$i]['type'].'">'.stripslashes($aPath[$i]['name']).'</a>';
		
	}
	if($level && $cfg_type['button']['new_subcategory']) $cfg_type['button']['new_category']= $cfg_type['button']['new_subcategory'];
	$tpl->assign(array('xpath' => $xPath));
	
	
}

$newcat = 1;
$delcat = 1;
if(!in_array('newcat',$show_actions)) $newcat = 0;
if(!in_array('deletecat',$show_actions)) $delcat = 0;	

if($cfg_type['nlevel'] > -1 && $level >= $cfg_type['nlevel']) $newcat = 0;
if($cfg_type['nosubcat_level']> -1 && in_array($level,explode(',',$cfg_type['nosubcat_level']))){
	$newcat = 0;
	if($cfg_type['nodelcat_level']) $delcat = 0;
}
if($cfg_type['nosubcat_cat'] > -1 && in_array($request['parentid'],explode(',',$cfg_type['nosubcat_cat']))){
	$newcat = 0;
	if($cfg_type['nodelcat_cat']) $delcat = 0;
}
if($newcat) $show_actions[] = 'newcategory';


$newrecord = 1;
$delrecord = 1;

if(!in_array('new',$show_actions)) $newrecord = 0;	
if(!in_array('delete',$show_actions)) $delrecord = 0;	


if($cfg_type['noproduct_level'] > - 1 && in_array($level,explode(',',$cfg_type['noproduct_level']))){
	$newrecord = 0;
	if($cfg_type['nodel_level']) $delrecord = 0;
}
if($cfg_type['noproduct_cat'] > -1 && in_array($request['parentid'],explode(',',$cfg_type['noproduct_cat']))){
	$newrecord = 0;
	if($cfg_type['nodel_cat']) $delrecord = 0;
}
if($newrecord) $show_actions[] = 'newrecord';




?>