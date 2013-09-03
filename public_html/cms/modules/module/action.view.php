<?php

 
if(!defined('_ROOT')) {
	exit('Access Denied');
}

if($_POST){
	$refresh = '?'.http_build_query($_GET);
	if($_POST['content_action']=='delete'){
		if($_POST['pro']) foreach($_POST['pro'] as $id) $oClass->delete($id);
		clear_sql_cache();
		$hook->redirect($refresh);
	}elseif($_POST['content_action'] == 'active'){
		if($_POST['pro']) foreach($_POST['pro'] as $id) $oClass->active($id,1);
		clear_sql_cache();
		$hook->redirect($refresh);
	
	}elseif($_POST['content_action'] == 'inactive'){
		if($_POST['pro']) foreach($_POST['pro'] as $id) $oClass->active($id,0);
		clear_sql_cache();
		$hook->redirect($refresh);
	
	}else{
		$hook->redirect($refresh);
	}

}else{

	$tpl->setfile(array(
		'body'=>'module.tpl',
	));
	
	
	
	
	$pro = $oClass->view($request['type'],$request['q'],1,0,0,$cfg_type['sorted_by'],$cfg_type['sorted_order']);
	$total = $pro->num_rows();
	if(!$total) $request['display_checkall'] = 'style="display: none;"';
	while($rs = $pro->fetch()){
		$rs = $hook->format($rs);
		if($cfg_type['thumb_field'] && $rs[$cfg_type['thumb_field']]){
			if($cfg_type['thumb_field']=='timestamp') $rs['thumb_field'] ='<em class="red">['.date(DATE_FORMAT,strtotime($rs['timestamp'])).']</em>&nbsp;';
			else $rs['thumb_field'] = '<a href="'._UPLOAD.$rs[$cfg_type['thumb_field']].'" class="mb" style="margin-right: 4px;"><img align="left" src="'._UPLOAD.$rs[$cfg_type['thumb_field']].'" width="40" height="40" /></a> ';
		}
		$rs['list_field'] = $rs[$cfg_type['list_field']]?$rs[$cfg_type['list_field']]:$rs['name'];
		$tpl->assign($rs,'product');
	}
	
	if($request['q']){
		$req = $_GET;
		unset($req['q'],$req['cmd']);
		$request['search_result'] = ' <a href="?'.http_build_query($req).'">Clear search</a>';
	}
	
	$breadcrumb->reset();
	$menu = explode('.',$_SESSION['cms_menu']);
	$breadcrumb->assign("",$MenuName[$menu[0]]);
	$level = $MenuLink[$menu[0]][$menu[1]];
	$breadcrumb->assign($level['link'],$level['name']);
	
	$action = array();
	if($show_actions) foreach($show_actions as $act){
		$tmp = explode(':',$act);
		$action['action_'.$tmp[0]] = ' show';
	}
	$tpl->assign($action);
}
$request['breadcrumb'] = $breadcrumb->parse();
$tpl->assign($request);
$action = array();

?>