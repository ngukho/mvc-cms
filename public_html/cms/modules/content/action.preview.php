<?php

defined('_ROOT') or die(__FILE__);

$fields = $oMaster->fields('content');
$ln_fields = $oMaster->fields('content_ln');

$tpl->setfile(array(
	'body'=>'content.preview.tpl',
));

$request['error'] = $error;
if($_SESSION['updated']){
	$request['back_list_0'] = 'selected';
	$request['msg'] = $languages['data_updated'];
	unset($_SESSION['updated']);
}
$content = array();
if($request['do']=='new'){
	$cat_ln = array();
	$breadcrumb->assign("","New");
	$request['access_action'] = 'access_new';
	$request['date'] = date('Y-m-d');
	$file_extra = array();
	foreach($cfg_type['main_fields'] as $code=>$info) if($info['chose'] && $info['type'] == 'status'){
		$content[$code] = $info['status_default'];
	}
}else{
	$result = $oClass->get($request['id']);
	$content = $result->fetch();
	if ($content['userid']!=intval($_SESSION['admin_login']['id']) && intval($_SESSION['admin_login']['is_admin'])!=1){	
		unset($_GET["act"],$_GET["do"]);
		$hook->redirect("?act=accessdenie&".http_build_query($_GET));
	}
	$request["action_update_check"] = 'checked';
	$cat_ln = $oClass->get_ln($_GET['id']);	
	$file_extra = $content['file_extra']?unserialize($content['file_extra']):array();
	$fields_extra = $content['fields_extra']?unserialize($content['fields_extra']):array();
	$tpl->assign($hook->format($content));
	$breadcrumb->assign("","Edit",$request['bread']);
	$request['display_update'] = 'show';
	$request['access_action'] = 'access_edit';
	//if(!$request['date']) $request['date'] = date('Y-m-d');
}

$required_fields = array();
$str_main_fields = '';
foreach($cfg_type['main_fields'] as $code=>$info){
	if($info['chose'] && $info['type'] != 'status' && in_array($code,$fields)){	
		$style = 'readonly="readonly" disabled="disabled"';
		$str_main_fields .= '<tr>
	  <td class="textLabel">'.$info['name'].' '.$arr['ln_alias'].'</td>
	  <td>'.html_input($info['type'],$code,$content[$code],' title="'.$info['require_msg'].'" '.$style.'',$info['options']).'
		<span class="description">'.$info['description'].'</span>
	  </td>
	</tr>';	
	}
}


$request['main_fields'] = $str_main_fields;
$lang_cond = "active = 1";
$lang_cond .= $cfg_type['languages']?" AND is_default=1":"";
$lang = $oLanguage->view($lang_cond);
if(!$lang->num_rows()){
	$request['msg'] = $languages['require_least_language_enable'];
	$request['hide_no_languages'] = 'hide';
}



while($rs = $lang->fetch()){
	$arr = array_merge($rs,$cat_ln[$rs['ln']]?$cat_ln[$rs['ln']]:array());
	
	
	$ln_fields_extra = unserialize($arr['ln_fields_extra']);
	$arr = $hook->format($arr);
	$str_ln_fields = '';
	$required_ln_fields = array();
	foreach($cfg_type['ln_fields'] as $code=>$info){
		if($info['chose'] && in_array($code,$ln_fields)){			
			$str_ln_fields .= '<tr>
      <td class="textLabel">'.$info['name'].' '.$arr['ln_alias'].'</td>
      <td>'.$arr[$code].'</td>
    </tr>';
			if($info['require']) { $required_ln_fields[] = $code; }
		
		}
	}
	$arr['ln_fields'] = $str_ln_fields;
	//$arr['ln_fields_extra'] = sprintf($ln_fields_extra,$arr['ln_alias'], $arr['ln'],'');
	$arr['default_ln'] = $cfg['language_tab'] == 'tab'?($arr['is_default']?'active':'hide'):'';
	$arr['tab_default'] = $arr['is_default']?'class="active"':'';
	$str = '';
	if($cfg_type['ln_fields_extra']['name']){
		foreach($cfg_type['ln_fields_extra']['name'] as $k=>$name) if($name){
			$str .= '<tr>
				<td class="textLabel">'.$name.' '.$arr['ln_alias'].'</td>
				<td>'.html_input($cfg_type['ln_fields_extra']['type'][$k],'ln_fields_extra['.$arr['ln'].']['.$cfg_type['ln_fields_extra']['code'][$k].']',$ln_fields_extra[$cfg_type['ln_fields_extra']['code'][$k]]).'</td>
			</tr>';
			
		}
	}
	
	$arr['ln_fields_extra'] = $str;
	
	$tpl->assign($arr,'language');
}

// load gallery
$result = $oGallery->get($system->module,$request['id']);
while($rs = $result->fetch()){
	$rs['name'] = $rs['name']?$rs['name'].'<br />':'';
	$tpl->assign($rs,'gallery');
}
$request['breadcrumb'] = $breadcrumb->parse();
$request['size_icon'] = demension_size($cfg_type['main_icon']['w'],$cfg_type['main_icon']['h']);
$request['size_icon'] = $request['size_icon']!=""?$request['size_icon']:$cfg_type['main_icon']['desc'];
$request['field_icon'] = $cfg_type['main_icon']['name']?$cfg_type['main_icon']['name']:'Icon';

$request['size_image'] = demension_size($cfg_type['main_img']['w'],$cfg_type['main_img']['h']);
$request['field_image'] = $cfg_type['main_img']['name']?$cfg_type['main_img']['name']:'Image';
$request['size_image'] = $request['size_image']!=""?$request['size_image']:$cfg_type['main_img']['desc'];

$request['size_ln_icon'] = demension_size($cfg_type['ln_main_icon']['w'],$cfg_type['ln_main_icon']['h']);
$request['size_ln_icon'] = $request['size_ln_icon']!=""?$request['size_ln_icon']:$cfg_type['ln_main_icon']['desc'];
$request['ln_field_icon'] = $cfg_type['ln_main_icon']['name']?$cfg_type['ln_main_icon']['name']:'Icon';

$request['size_ln_image'] = demension_size($cfg_type['ln_main_img']['w'],$cfg_type['ln_main_img']['h']);
$request['size_ln_image'] = $request['size_ln_image']!=""?$request['size_ln_image']:$cfg_type['ln_main_img']['desc'];
$request['ln_field_image'] = $cfg_type['ln_main_img']['name']?$cfg_type['ln_main_img']['name']:'Image';


$request['size_gallery_icon'] = demension_size($cfg_type['gallery_icon']['w'],$cfg_type['gallery_icon']['h']);
$request['size_gallery_icon'] = $request['size_gallery_icon']!=""?$request['size_gallery_icon']:$cfg_type['gallery_icon']['desc'];

$request['size_gallery_image'] = demension_size($cfg_type['gallery_img']['w'],$cfg_type['gallery_img']['h']);
$request['size_gallery_image'] = $request['size_gallery_image']!=""?$request['size_gallery_image']:$cfg_type['gallery_img']['desc'];

$request['field_gallery_name'] = $cfg_type['gallery_name']?$cfg_type['gallery_name']:'Gallery';

$request['required_fields'] = count($required_fields)?"'".implode("','",$required_fields)."'":'';
$request['required_ln_fields'] = count($required_ln_fields)?"'".implode("','",$required_ln_fields)."'":'';
if(in_array('drapdrop_gallery',$show_actions))  $tpl->box('drapdrop_gallery');
if(in_array('enable_editor',$cfg_type['act'])) $tpl->box('enable_editor');

$request['msg'] = $error;
$show = array();
if($show_fields) foreach($show_fields as $field){
	//$a = explode(':',$field);
	$show['display_'.$field] = 'show';
	$show['is_'.$field] = 1;
}
$tpl->assign($show);
$action = array();
foreach($show_actions as $act) $action['action_'.$act] = 'show';
$tpl->assign($action);


// Load options
$attributes = array();
if($request['do'] != 'new'){
	$result = $oContent->options($request['id']);
	while($rs = $result->fetch()){
		$attributes[$rs['options_type']][] = $rs['options_id'];
	}
}


if(count($cfg_type['options'])){
	$opt_type = $oConfigure->getMod("`module` = 'options' AND typeid IN(".implode(',',$cfg_type['options']).")");
	while($rs = $opt_type->fetch()){
		$result = $oOption->view($rs['typeid']);
		if($cfg_type['options_content_type'][$rs['typeid']] == 2) $aOpt = array(0=>'--');
		else $aOpt = array();
		while($opt = $result->fetch()){
			$aOpt[$opt['id']] = $opt['name'];
		}		
		switch($cfg_type['options_content_type'][$rs['typeid']]){
			case '1':
				$rs['values'] = '<div class="option_checkbox listopts'.$rs['typeid'].'" id="opt'.$rs['typeid'].'" >'.options_checkbox($aOpt,$rs['typeid'],$attributes[$rs['typeid']]).'</div>';
				$rs['check_all'] = "<input type=\"checkbox\" name=\"all\" value=\"".$rs['typeid']."\" class=\"checkallopts\" id=\"checkallopts".$rs['typeid']."\" /> <label for=\"checkallopts".$rs['typeid']."\">Check All</label>";
				$rs['js_string'] = "<input type=\'checkbox\' name=\'options[".$rs['typeid']."][]\' value=\'%value\' />%label";
				break;
			case '2': 
				$rs['values'] = options_dropdown($aOpt,$rs['typeid'],$attributes[$rs['typeid']],' id="opt'.$rs['typeid'].'" ');
				$rs['js_string'] = "<option selected=\'selected\' name=\'options[".$rs['typeid']."][]\' value=\'%value\'>%label</option>";
				break;
			case '3':
				$rs['values'] = options_dropdown($aOpt,$rs['typeid'],$attributes[$rs['typeid']],'  id="opt'.$rs['typeid'].'"  size="5" multiple="multiple"');
				$rs['js_string'] = "<option selected=\'selected\' name=\'options[".$rs['typeid']."][]\' value=\'%value\'>%label</option>";
				break;
			default: //0
				$rs['values'] = '<div class="option_checkbox" id="opt'.$rs['typeid'].'" >'.options_radio($aOpt,$rs['typeid'],$attributes[$rs['typeid']]).'</div>';
				$rs['js_string'] = "<input type=\'radio\' name=\'options[".$rs['typeid']."][]\' value=\'%value\'  checked=\'checked\' />%label";
				break;
		}				
		$tpl->assign($rs,'options');
	}
}
// file extra
if($cfg_type['file_extra']['name']){
	foreach($cfg_type['file_extra']['name'] as $k=>$name) if($name){
		$rs = array();
		$rs['stt'] = $k;
		$rs['name'] = $name;
		$rs['code'] = $cfg_type['file_extra']['code'][$k];
		$rs['file'] = $file_extra[$rs['code']];
		$rs['type'] = $cfg_type['file_extra']['type'][$k];
		$rs['note'] = $cfg_type['file_extra']['note'][$k];
		$tpl->assign($rs,'file_extra');
		
	}
}

foreach($cfg_type['main_fields'] as $code=>$info) if($info['chose'] && $info['type'] == 'status'){
	$rs = array();
	$rs['name'] = $info['name'];
	$rs['code'] = $code;
	$rs['note'] = $info['description'];
	$rs['value'] = html_status($code,$content[$code]);
	$tpl->assign($rs,'status_fields');
}

// fields extra
if($cfg_type['fields_extra']['name']){
	foreach($cfg_type['fields_extra']['name'] as $k=>$name) if($name){
		$rs = array();
		$rs['stt'] = $k;
		$rs['name'] = $name;
		$rs['value'] = html_input($cfg_type['fields_extra']['type'][$k],'fields_extra['.$cfg_type['fields_extra']['code'][$k].']',$fields_extra[$cfg_type['fields_extra']['code'][$k]]);
		$tpl->assign($rs,'fields_extra');
		
	}
}
/*relatedcontent*/
if (strlen($cfg_type["relatedcontent"])>0 && $cfg_type["relatedcontent"]!="")
{	
	$request['relatedcontent_name'] = $cfg_type['relatedcontent_name']?$cfg_type['relatedcontent_name']:'Related Content';
	$opt_content = $oConfigure->getMod("module = 'content' AND typeid IN (".$cfg_type["relatedcontent"].")");
	while($val = $opt_content->fetch()){
		$arrtype[$val["typeid"]]  = $val["name"];
	}	
	$relatedcontent = $oClass->viewall($cfg_type["relatedcontent"]);
	$isgroup = false;
	while($rs = $relatedcontent->fetch()){
		$rs = $hook->format($rs);	
		$rs["groupname"] ="";
		if ($rs["id"]==intval($content["related_contentid"])){
			if ($rs["type"]!=$isgroup && $rs["groupname"]==""){
				$isgroup  = $rs["type"];
				$rs["groupname"] = $arrtype[intval($rs["type"])];
			}						
			$tpl->assign($rs,'listcontent');
		}
		
	}
	$tpl->assign(array("display_related_contentid"=>"show"));
	
}
if (strlen($cfg_type["extra_category"])>0 && $cfg_type["extra_category"]!="")
{	
	$request["extra_category_mode"] = $cfg_type["extra_category_mode"]>=1?'multiple="multiple"':'';
	$request['display_extra_category'] = "show";
	$request['ln_extra_category_name'] = $cfg_type['extra_category_name']?$cfg_type['extra_category_name']:'Extra Category';
	
	$tree = $oCategory->tree($cfg_type["extra_category"],0,'&nbsp;',1);
	$content['extra_catid'] = rtrim(ltrim($content['extra_catid'],","),",");
	$arr_extracatid = $content['extra_catid']!=""?explode(",",$content['extra_catid']):array();
	foreach($tree as $rs){
		$rs['prefix'] = $rs['prefix'].'|&mdash;';
		$rs['selected'] = in_array($rs['id'],$arr_extracatid)?'selected':'';
		$tpl->assign($rs,'extra_category_list');
	}

}	
if (strlen($cfg_type["assin_userid_name"])>0 && $cfg_type["assin_userid_name"]!=""){
	$assinUList = $cfg_type['assin_userid_access']!=""?explode(",",$cfg_type['assin_userid_access']):array();
	$uid = $_SESSION['admin_login']['id'];
	if (in_array($uid,$assinUList) || $_SESSION['admin_login']['is_admin']){	
		$content['access'] = rtrim(ltrim($content['access'],","),",");
		$arrUserList = $content['access']!=""?explode(",",$content['access']):array();	
		$request['display_assin_userid_name'] = "show";
		$request['assin_userid_name'] = $cfg_type["assin_userid_name"];	
		$objuser = $oUser->view();
		while($rs = $objuser->fetch()){	
			$rs['selected'] = in_array($rs['id'],$arrUserList)?'selected':'';
			$tpl->assign($rs,'user_access');
		}
	}
}

if($request['parentid']) $tpl->box('breadcrumb_cat');
$tpl->assign($request);

?>