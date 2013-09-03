<?php

 
defined('_ROOT') or die(__FILE__);
if($_GET['do']=='new'){
	$result = $oClass->max_module($request['module']);
	$data = $result->fetch();
	$oClass->insert_module($request['module'],intval($data['maxid'])+1,$request['name']);
	clear_sql_cache();
	$hook->redirect('?mod=configure');
}

if($_GET['do']=='newhtml'){
	$oClass->insert_html($request['typeid']);
	clear_sql_cache();
	$hook->redirect('?mod=configure');
}

if($_GET['do']=='copy'){
	$result = $oClass->get_module($request['module'],$request['from']);
	$data = $result->fetch();
	$oClass->update_module($request['module'],$request['typeid'],array('data'=>$data['data']));
	clear_sql_cache();
	$hook->redirect('?mod=configure');
}

if($_POST){
	// update main	
	$name = $_POST['name'];
	$content = $_POST['content'];
	unset($_POST['name'],$_POST['Submit'],$_POST['content']);
	$arr = array(
		'name'=>$name,
		'content'=>$content,
		'data'=>serialize($_POST)
	);
	
	
	$oClass->update_module($request['module'],$request['typeid'],$arr);

	//clear_cache_configure();
	// refresh
	
	$query_string = $_SERVER['QUERY_STRING'];
	parse_str($query_string,$result);
	unset($result['act'],$result['module'],$result['typeid'],$result['page_or_id']);
	clear_sql_cache();
	$hook->redirect('?'.http_build_query($result,NULL,'&'));
}


$tpl->setfile(array(
	'body'=>'configure.module.'.$request['module'].'.tpl',
));

$result = $oClass->get_module($request['module'],$request['typeid'],$request['page_or_id']);
$cfg = $result->fetch();
$data = array();
$request['name'] = $cfg['name'];
$request['content'] = $cfg['content'];
if($cfg['data']) $data = unserialize($cfg['data']);
$arrCfg = array();

$fields = $oClass->fields($request['module'],array_keys($data['main_fields']));
foreach($fields as  $rs){
	if($rs['Comment'] != 'system'){
		$rs['code'] = $rs['Field'];
		$rs['datatype'] = ucfirst($rs['Type']);
		$rs['chose_checked'] = $data['main_fields'][$rs['code']]['chose']?'checked':'';
		$rs['require_checked'] = $data['main_fields'][$rs['code']]['require']?'checked':'';
		$rs['status_checked'] = $data['main_fields'][$rs['code']]['status']?'checked':'';
		$rs['name'] = $data['main_fields'][$rs['code']]['name'];
		$rs['number'] = $data['main_fields'][$rs['code']]['number']?$data['main_fields'][$rs['code']]['number']:0;
		$rs['cls_status'] = $data['main_fields'][$rs['code']]['type'] == 'status'?'':'hide';
		$rs['type'] = $oClass->input_type('main_fields['.$rs['code'].'][type]', $data['main_fields'][$rs['code']]['type'],true,' onchange="fields_status(this);" style="width:90px;"');
		$rs['status_default'] = $oClass->input_status_default('main_fields['.$rs['code'].'][status_default]', $data['main_fields'][$rs['code']]['status_default']);
		$rs["options"] = $data['main_fields'][$rs['code']]['options'];
				
		$rs['widthxheight'] = $data['main_fields'][$rs['code']]['widthxheight'];
		$rs['description'] = $data['main_fields'][$rs['code']]['description'];
		$rs['require_msg'] = $data['main_fields'][$rs['code']]['require_msg'];
		$tpl->assign($rs,'main_field');
	}
}

$ln_fields = $oClass->fields($request['module'].'_ln', array_keys($data['ln_fields']));

foreach($ln_fields as  $rs){
	if($rs['Comment'] != 'system'){
		$rs['code'] = $rs['Field'];
		$rs['datatype'] = ucfirst($rs['Type']);
		$rs['chose_checked'] = $data['ln_fields'][$rs['code']]['chose']?'checked':'';
		$rs['require_checked'] = $data['ln_fields'][$rs['code']]['require']?'checked':'';
		//$rs['status_checked'] = $data['ln_fields'][$rs['code']]['status']?'checked':'';
		$rs['name'] = $data['ln_fields'][$rs['code']]['name'];
		$rs['type'] = $oClass->input_type('ln_fields['.$rs['code'].'][type]', $data['ln_fields'][$rs['code']]['type'],false,' style="width:90px;"');
		
		$rs['widthxheight'] = $data['ln_fields'][$rs['code']]['widthxheight'];
		$rs['description'] = $data['ln_fields'][$rs['code']]['description'];
		$rs['require_msg'] = $data['ln_fields'][$rs['code']]['require_msg'];
		$tpl->assign($rs,'ln_field');
	}
}
if($request['module'] == 'content'){

	$cat_fields = $oClass->fields('category', array_keys($data['cat_fields']));
	foreach($cat_fields as  $rs){
		if($rs['Comment'] != 'system'){
			$rs['code'] = $rs['Field'];
			$rs['datatype'] = ucfirst($rs['Type']);
			$rs['chose_checked'] = $data['cat_fields'][$rs['code']]['chose']?'checked':'';
			$rs['require_checked'] = $data['cat_fields'][$rs['code']]['require']?'checked':'';
			$rs['status_checked'] = $data['cat_fields'][$rs['code']]['status']?'checked':'';
			$rs['name'] = $data['cat_fields'][$rs['code']]['name'];
			$rs['type'] = $oClass->input_type('cat_fields['.$rs['code'].'][type]', $data['cat_fields'][$rs['code']]['type'],false,' style="width:90px;"');
			
			$rs['widthxheight'] = $data['cat_fields'][$rs['code']]['widthxheight'];
			$rs['description'] = $data['cat_fields'][$rs['code']]['description'];
			$rs['require_msg'] = $data['cat_fields'][$rs['code']]['require_msg'];
			$rs['category_level'] = $data['cat_fields'][$rs['code']]['category_level'];	
			$tpl->assign($rs,'cat_field');
		}
	}
	
	$ln_catfields = $oClass->fields('category_ln', array_keys($data['ln_catfields']));
	foreach($ln_catfields as  $rs){
		if($rs['Comment'] != 'system'){
			$rs['code'] = $rs['Field'];
			$rs['datatype'] = ucfirst($rs['Type']);
			$rs['chose_checked'] = $data['ln_catfields'][$rs['code']]['chose']?'checked':'';
			$rs['require_checked'] = $data['ln_catfields'][$rs['code']]['require']?'checked':'';
			//$rs['status_checked'] = $data['ln_catfields'][$rs['code']]['status']?'checked':'';
			$rs['name'] = $data['ln_catfields'][$rs['code']]['name'];
			$rs['type'] = $oClass->input_type('ln_catfields['.$rs['code'].'][type]', $data['ln_catfields'][$rs['code']]['type'],false,' style="width:90px;"');

			$rs['widthxheight'] = $data['ln_catfields'][$rs['code']]['widthxheight'];
			$rs['description'] = $data['ln_catfields'][$rs['code']]['description'];
			$rs['require_msg'] = $data['ln_catfields'][$rs['code']]['require_msg'];
			$rs['category_level'] = $data['ln_catfields'][$rs['code']]['category_level'];			
			$tpl->assign($rs,'ln_catfield');
		}
	}

}
$tpl->merge($data['button'],'button');
//action
if($data['act']) foreach($data['act'] as $v){
	$tmp = explode(':',$v);
	$arrCfg[$tmp[0].'_checked'] = 'checked';
}

if($request['module'] == 'content'){
	$cat = $oConfigure->getMod("`module` = 'options'");
	while($rs = $cat->fetch()){
		$rs['checked'] = is_array($data['options'])&&in_array($rs['typeid'],$data['options'])?'checked':'';
		$rs['selected_'.$data['options_type'][$rs['typeid']]] = 'selected';
		$tpl->assign($rs,'options');
	}
	$cat = $oConfigure->getMod("`module` = 'options'");
	while($rs = $cat->fetch()){
		$rs['checked'] = is_array($data['options_cat'])&&in_array($rs['typeid'],$data['options_cat'])?'checked':'';
		$rs['options_cat_level'] = $data['options_cat_level'][$rs['typeid']];
		$rs['selected_'.$data['options_type'][$rs['typeid']]] = 'selected';
		$tpl->assign($rs,'options_cat');
	}
}

//show fields
$arrCfg['rows_per_page'] = $data['rows_per_page'];

$arrCfg['tpl_view'] = $data['tpl_view']?stripslashes($data['tpl_view']):$request['module'].'.tpl';
$arrCfg['tpl_update'] = $data['tpl_update']?stripslashes($data['tpl_update']):$request['module'].'.update.tpl';
$arrCfg['cattpl_update'] = $data['cattpl_update']?stripslashes($data['cattpl_update']):'category.update.tpl';
$arrCfg['sub_show_fields'] = stripslashes($data['sub_show_fields']);
$arrCfg['sub_name'] = stripslashes($data['sub_name']);
$arrCfg['featuredon_name'] = stripslashes($data['featuredon_name']);
$arrCfg['actionexternal'] = stripslashes($data['actionexternal']);
$arrCfg['comment_name'] = stripslashes($data['comment_name']);
$arrCfg['show_fields'] = stripslashes($data['show_fields']);
$arrCfg['list_field'] = stripslashes($data['list_field']);
$arrCfg['gallery_fields'] = stripslashes($data['gallery_fields']);
$arrCfg['gallery_name'] = stripslashes($data['gallery_name']);
$arrCfg['nlevel'] = intval($data['nlevel']);
$arrCfg['category_fields'] = stripslashes($data['category_fields']);
$arrCfg['category_required'] = stripslashes($data['category_required']);
$arrCfg['required_fields'] = stripslashes($data['required_fields']);
$arrCfg['thumb_field'] = stripslashes($data['thumb_field']);
$arrCfg['web_title'] = stripslashes($data['web_title']);
$arrCfg['meta_keywords'] = stripslashes($data['meta_keywords']);
$arrCfg['meta_desc'] = stripslashes($data['meta_desc']);
$arrCfg['noproduct_level'] = $cfg['data']?stripslashes($data['noproduct_level']):'-1';
$arrCfg['noproduct_cat'] = $cfg['data']?stripslashes($data['noproduct_cat']):'-1';
$arrCfg['nosubcat_level'] = $cfg['data']?stripslashes($data['nosubcat_level']):'-1';
$arrCfg['nosubcat_cat'] = $cfg['data']?stripslashes($data['nosubcat_cat']):'-1';
$arrCfg['nodelcat_ids'] = $cfg['data']?stripslashes($data['nodelcat_ids']):'-1';
$arrCfg['nodel_ids'] = $cfg['data']?stripslashes($data['nodel_ids']):'-1';
$arrCfg['nodelcat_level_checked'] = $data['nodelcat_level']?'checked':'';
$arrCfg['nodelcat_cat_checked'] = $data['nodelcat_cat']?'checked':'';
$arrCfg['sort_default_'.$data['sort_default']] = 'selected';
$arrCfg['sort_default_order'] = $data['sort_default_order'];
$arrCfg['sort_order'] = $data['sort_order']?stripslashes($data['sort_order']):'';
$arrCfg['sort_order_alias'] = $data['sort_order_alias'];
$arrCfg['catsort_order'] = $data['catsort_order']?stripslashes($data['catsort_order']):'';
$arrCfg['catsort_order_alias'] = $data['catsort_order_alias'];
$arrCfg['relatedcontent'] = $data['relatedcontent'];
$arrCfg['extra_category'] = $data['extra_category'];
$arrCfg['extra_category_name'] = $data['extra_category_name'];
$arrCfg['extra_category_mod'] = $data['extra_category_mod'];
$arrCfg['extra_category_mod_checked'] = $data['extra_category_mod']>=1?'checked':'';

//
$arrCfg['main_icon_chose'] = $data['main_icon']['chose']?'checked':'';
$arrCfg['main_icon_w'] = intval($data['main_icon']['w']);
$arrCfg['main_icon_h'] = intval($data['main_icon']['h']);
$arrCfg['main_icon_name'] = stripslashes($data['main_icon']['name']);
$arrCfg['main_icon_desc'] = stripslashes($data['main_icon']['desc']);

$arrCfg['main_img_chose'] = $data['main_img']['chose']?'checked':'';
$arrCfg['main_img_w'] = intval($data['main_img']['w']);
$arrCfg['main_img_h'] = intval($data['main_img']['h']);
$arrCfg['main_img_name'] = stripslashes($data['main_img']['name']);
$arrCfg['main_img_desc'] = stripslashes($data['main_img']['desc']);

$arrCfg['main_thumb_chose'] = $data['main_thumb']['chose']?'checked':'';
$arrCfg['main_thumb_w'] = intval($data['main_thumb']['w']);
$arrCfg['main_thumb_h'] = intval($data['main_thumb']['h']);

$arrCfg['ln_main_icon_chose'] = $data['ln_main_icon']['chose']?'checked':'';
$arrCfg['ln_main_icon_w'] = intval($data['ln_main_icon']['w']);
$arrCfg['ln_main_icon_h'] = intval($data['ln_main_icon']['h']);
$arrCfg['ln_main_icon_name'] = stripslashes($data['ln_main_icon']['name']);
$arrCfg['ln_main_icon_desc'] = stripslashes($data['ln_main_icon']['desc']);

$arrCfg['ln_main_img_chose'] = $data['ln_main_img']['chose']?'checked':'';
$arrCfg['ln_main_img_w'] = intval($data['ln_main_img']['w']);
$arrCfg['ln_main_img_h'] = intval($data['ln_main_img']['h']);
$arrCfg['ln_main_img_name'] = stripslashes($data['ln_main_img']['name']);
$arrCfg['ln_main_img_desc'] = stripslashes($data['ln_main_img']['desc']);

$arrCfg['ln_main_thumb_chose'] = $data['ln_main_thumb']['chose']?'checked':'';
$arrCfg['ln_main_thumb_w'] = intval($data['ln_main_thumb']['w']);
$arrCfg['ln_main_thumb_h'] = intval($data['ln_main_thumb']['h']);

$arrCfg['gallery_icon_chose'] = $data['gallery_icon']['chose']?'checked':'';
$arrCfg['gallery_icon_w'] = intval($data['gallery_icon']['w']);
$arrCfg['gallery_icon_h'] = intval($data['gallery_icon']['h']);
$arrCfg['gallery_icon_desc'] = stripslashes($data['gallery_icon']['desc']);

$arrCfg['gallery_img_chose'] = $data['gallery_img']['chose']?'checked':'';
$arrCfg['gallery_img_w'] = intval($data['gallery_img']['w']);
$arrCfg['gallery_img_h'] = intval($data['gallery_img']['h']);
$arrCfg['gallery_img_desc'] = stripslashes($data['gallery_img']['desc']);

$arrCfg['gallery_thumb_chose'] = $data['gallery_thumb']['chose']?'checked':'';
$arrCfg['gallery_thumb_w'] = intval($data['gallery_thumb']['w']);
$arrCfg['gallery_thumb_h'] = intval($data['gallery_thumb']['h']);

$arrCfg['catimg_icon_chose'] = $data['catimg_icon']['chose']?'checked':'';
$arrCfg['catimg_icon_w'] = intval($data['catimg_icon']['w']);
$arrCfg['catimg_icon_h'] = intval($data['catimg_icon']['h']);
$arrCfg['catimg_icon_desc'] = stripslashes($data['catimg_icon']['desc']);

$arrCfg['catimg_icon2_chose'] = $data['catimg_icon2']['chose']?'checked':'';
$arrCfg['catimg_icon2_w'] = intval($data['catimg_icon2']['w']);
$arrCfg['catimg_icon2_h'] = intval($data['catimg_icon2']['h']);

$arrCfg['catimg_img_chose'] = $data['catimg_img']['chose']?'checked':'';
$arrCfg['catimg_img_w'] = intval($data['catimg_img']['w']);
$arrCfg['catimg_img_h'] = intval($data['catimg_img']['h']);
$arrCfg['catimg_img_desc'] = stripslashes($data['catimg_img']['desc']);

$arrCfg['catimg_thumb_chose'] = $data['catimg_thumb']['chose']?'checked':'';
$arrCfg['catimg_thumb_w'] = intval($data['catimg_thumb']['w']);
$arrCfg['catimg_thumb_h'] = intval($data['catimg_thumb']['h']);

$arrCfg['ln_catimg_icon_chose'] = $data['ln_catimg_icon']['chose']?'checked':'';
$arrCfg['ln_catimg_icon_w'] = intval($data['ln_catimg_icon']['w']);
$arrCfg['ln_catimg_icon_h'] = intval($data['ln_catimg_icon']['h']);

$arrCfg['ln_catimg_icon2_chose'] = $data['ln_catimg_icon2']['chose']?'checked':'';
$arrCfg['ln_catimg_icon2_w'] = intval($data['ln_catimg_icon2']['w']);
$arrCfg['ln_catimg_icon2_h'] = intval($data['ln_catimg_icon2']['h']);

$arrCfg['ln_catimg_img_chose'] = $data['ln_catimg_img']['chose']?'checked':'';
$arrCfg['ln_catimg_img_w'] = intval($data['ln_catimg_img']['w']);
$arrCfg['ln_catimg_img_h'] = intval($data['ln_catimg_img']['h']);

$arrCfg['ln_catimg_thumb_chose'] = $data['ln_catimg_thumb']['chose']?'checked':'';
$arrCfg['ln_catimg_thumb_w'] = intval($data['ln_catimg_thumb']['w']);
$arrCfg['ln_catimg_thumb_h'] = intval($data['ln_catimg_thumb']['h']);
$arrCfg['selected_languages_'.$data['languages']] = 'selected';
$arrCfg['selected_cat_languages_'.$data['cat_languages']] = 'selected';

$arrCfg['gallerycat_icon_chose'] = $data['gallerycat_icon']['chose']?'checked':'';
$arrCfg['gallerycat_icon_w'] = intval($data['gallerycat_icon']['w']);
$arrCfg['gallerycat_icon_h'] = intval($data['gallerycat_icon']['h']);

$arrCfg['gallerycat_img_chose'] = $data['gallerycat_img']['chose']?'checked':'';
$arrCfg['gallerycat_img_w'] = intval($data['gallerycat_img']['w']);
$arrCfg['gallerycat_img_h'] = intval($data['gallerycat_img']['h']);

$arrCfg['gallerycat_thumb_chose'] = $data['gallerycat_thumb']['chose']?'checked':'';
$arrCfg['gallerycat_thumb_w'] = intval($data['gallerycat_thumb']['w']);
$arrCfg['gallerycat_thumb_h'] = intval($data['gallerycat_thumb']['h']);
$arrCfg['gallerycat_in_level'] = $data['gallerycat_in_level'];
$arrCfg['catimg_level'] = $data['catimg_level'];
$arrCfg['action_featuredon_cat_level'] = $data['action_featuredon_cat_level'];


$tpl->assign($arrCfg);
$breadcrumb->assign("","Edit configure type");



//$breadcrumb->reset();

//$breadcrumb->assign("./?mod=product","Manage Products");

$request['breadcrumb'] = $breadcrumb->parse();
$tpl->assign($request);


/// file extra
if($data['file_extra']['name']){
	foreach($data['file_extra']['name'] as $k=>$name) if($name){
		$rs = array();
		$rs['name'] = $name;
		$rs['code'] = $data['file_extra']['code'][$k];
		$rs['type'] = $data['file_extra']['type'][$k];
		$rs['note'] = $data['file_extra']['note'][$k];
		$tpl->assign($rs,'file_extra');
		
	}
}

//fields extra
if($data['fields_extra']['name']){
	foreach($data['fields_extra']['name'] as $k=>$name) if($name){
		$rs = array();
		$rs['name'] = $name;
		$rs['code'] = $data['fields_extra']['code'][$k];
		$rs['type'] = $oClass->input_type('fields_extra[type][]',$data['fields_extra']['type'][$k]);
		$rs['note'] = $data['fields_extra']['note'][$k];
		$tpl->assign($rs,'fields_extra');
		
	}
}


// language fields extra
if($data['ln_fields_extra']['name']){
	foreach($data['ln_fields_extra']['name'] as $k=>$name) if($name){
		$rs = array();
		$rs['name'] = $name;
		$rs['code'] = $data['ln_fields_extra']['code'][$k];
		$rs['type'] = $oClass->input_type('ln_fields_extra[type][]',$data['ln_fields_extra']['type'][$k]);
		$rs['note'] = $data['ln_fields_extra']['note'][$k];
		$tpl->assign($rs,'ln_fields_extra');
		
	}
}

/// status extra
if($data['status_fields']['code']){
	foreach($data['status_fields']['code'] as $k=>$name) if($name){
		$rs = array();
		$rs['name'] = $data['status_fields']['name'][$k];
		$rs['code'] = $data['status_fields']['code'][$k];
		$rs['note'] = $data['status_fields']['note'][$k];
		$rs['default'] = $data['status_fields']['default'][$k];
		$tpl->assign($rs,'status_fields');
		
	}
}

?>