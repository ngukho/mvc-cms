<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}
$arrRoomType = array("bedroom"=>"Bedroom","bathroom"=>"Bathrooms", "dining"=>"Dining Room","living"=>"Living Room","laundry"=>"Laundry Room","media"=>"Media Room","office"=>"Office","kitchen"=>"Kitchen");
$arrLevelRoom = array("lower"=>"Lower Level", "main"=>"Main Level","upper"=>"Upper Level");
$request["obj_id"] = $_REQUEST["obj_id"]>0?$_REQUEST["obj_id"]:0;
$request["obj_type"] = $_REQUEST["obj_type"]?$_REQUEST["obj_type"]:"";
if($_POST){
	// update main
	$error = NULL;
	if(!$error){	
		$arr = array(
			'obj_id'=>$request["obj_id"],
			'obj_type'=>$request["obj_type"],
			'size'=>$_POST["size"],
			'level'=>$_POST["level"],
			'room'=>$_POST["room_type"]
		);
		if($request['do']=='new'){
			$lastid = $oClass->insert($arr);
		}else{
			$oClass->update($request['id'],$arr);
			$lastid = $request['id'];
		}
		// update _ln
		$ln_req = $cfg_type['required_fields']?explode(',',$cfg_type['required_fields']):array('name');
		
		if(!$cfg_type['required_fields'] || $_POST[$ln_req[0]]) foreach($_POST[$ln_req[0]] as $ln=>$val){
			if(!$cfg_type['required_fields'] || $val){
			if ($_POST['name'][$ln]!=""){
					$arr_ln  = array(
						'name'=>$_POST['name'][$ln],
						'ln'=>$ln,
						'content'=>$_POST['content'][$ln],					
					);
					$oClass->update_ln($lastid,$ln,$arr_ln);
			 }
			}
		}
				// refresh
		$query_string = $_SERVER['QUERY_STRING'];
		parse_str($query_string,$result);
	
		if($result['do']){
			unset($result['act'],$result['id'],$result['do']);
			$result['do'] = "new";
			$result['act'] = "update";			
		}else{
			$_SESSION['updated'] = true;
		}
		$hook->redirect('?'.http_build_query($result,NULL,'&'));

	}
}


$tpl->setfile(array(
	'body'=>'attributes.update.tpl',
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
}else{
	$cat_ln = $oClass->get_ln($_GET['id']);
	$result = $oClass->get($request['id']);
	$content = $result->fetch();
	$tpl->merge($hook->format($content),"detail");
	$breadcrumb->assign("","Edit",$request['bread']);
	$request['display_update'] = 'style="display: block;"';
	$request['access_action'] = 'access_edit';
}

$lang_cond = "active = 1";
$lang_cond .= $cfg_type['languages']?" AND is_default=1":"";
$lang = $oLanguage->view($lang_cond);
if(!$lang->num_rows()){
	$request['msg'] = $languages['require_least_language_enable'];
	$request['hide_no_language'] = 'hide';
}
$obj = $oClass->view($request['type'],$request['q'],1,0,0,$cfg_type['sorted_by'],$cfg_type['sorted_order']);

while($rs = $lang->fetch()){
	$arr = array_merge($rs,$cat_ln[$rs['ln']]?$cat_ln[$rs['ln']]:array());
	$arr = $hook->format($arr);
	$arr['default_ln'] = $cfg['language_tab'] == 'tab'?($arr['is_default']?'active':'hide'):'';
	$arr['tab_default'] = $rs['is_default']?'class="active"':'';	
	$tpl->assign($arr,'language');
}

$request['breadcrumb'] = $breadcrumb->parse();
$request['required_fields'] = $cfg_type['required_fields']?"'".str_replace(',',"','",$cfg_type['required_fields'])."'":'';
$tpl->assign($request);
$show = array();
if($show_fields) foreach($show_fields as $field){
	$a = explode(':',$field);
	$show['display_'.$a[0]] = 'style="display: '.($a[1]?$a[1]:$table_row).';"';
	$show['is_'.$a[0]] = 1;
}
$tpl->assign($show);

foreach($arrRoomType as $key=>$val){
	$rs = array();
	$rs["id"] =  $key;
	$rs["name"] =  $val;
	$rs["active"] = $content["room"]==$rs["id"]?'selected="selected"':'';
	$tpl->assign($rs,"listroom");
}
foreach($arrLevelRoom as $key=>$val){
	$rs = array();
	$rs["id"] =  $key;
	$rs["name"] =  $val;
	$rs["active"] = $content["level"]==$rs["id"]?'selected="selected"':'';
	$tpl->assign($rs,"listlevel");
}
/*List */
$pro = $oClass->view($request["obj_id"],$request["obj_type"]);
$total = $pro->num_rows();
if(!$total) $request['display_checkall'] = 'style="display: none;"';
while($rs = $pro->fetch()){
	$rs = $hook->format($rs);
	if($cfg_type['thumb_field'] && $rs[$cfg_type['thumb_field']]){
		if($cfg_type['thumb_field']=='timestamp') $rs['thumb_field'] ='<em class="red">['.date(DATE_FORMAT,strtotime($rs['timestamp'])).']</em>&nbsp;';
		else $rs['thumb_field'] = '<a href="'._UPLOAD.$rs[$cfg_type['thumb_field']].'" class="mb" style="margin-right: 4px;"><img align="left" src="'._UPLOAD.$rs[$cfg_type['thumb_field']].'" width="40" height="40" /></a> ';
	}
	$rs["level"] = isset($arrLevelRoom[$rs["level"]])?$arrLevelRoom[$rs["level"]]:"";
	$rs["room"] = isset($arrRoomType[$rs["room"]])?$arrRoomType[$rs["room"]]:"";
	$rs['list_field'] = $rs[$cfg_type['list_field']]?$rs[$cfg_type['list_field']]:$rs['name'];
	$tpl->assign($rs,'product');
}
/**/

?>