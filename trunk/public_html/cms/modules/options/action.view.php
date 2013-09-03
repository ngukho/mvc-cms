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
		'body'=>'options.tpl',
	));
	$sort_default = $cfg_type['sort_default']?$cfg_type['sort_default']:'order_id';
	$current_order = $cfg_type['sort_default_order']=='DESC'?'DESC':'ASC';
	if($request['order'] == 'DESC' || $request['order'] == 'ASC') $current_order = $request['order'];
	
	$key = $request['by']?$request['by']:$sort_default;
	$val =$current_order=='DESC'?'&darr;':'&uarr;';
	$url_orderby = array($key=>$val);
	$tpl->merge($url_orderby,'orderby');
	
	$fields = $oMaster->fields('options');
	$status_fields = '';
	$class_status_fields = 'hide';

	$array_status_fields = array();
	$array_cat_status_fields = array();
	foreach($cfg_type['main_fields'] as $code=>$info) if($info['chose'] && $info['type'] == 'status'){
		$href = './?mod='.$system->module.'&amp;parentid='.$request['parentid'].'&type='.$request['type'].'&amp;page='.$request['page'].'&amp;by='.$code.'&amp;order='.($current_order == 'DESC'?'ASC':'DESC');
		$array_status_fields[] = '<a href="'.$href.'">'.$info['name'].'</a> '.($code == $request['by']?($current_order == 'DESC'?'&darr;':'&uarr;'):'');
		$array_cat_status_fields[] = '&nbsp;';
	}
	$status_fields = implode('</th><th class="th-action">',$array_status_fields);
	$cat_status_fields = implode('</th><th class="th-action">',$array_cat_status_fields);
	$class_status_fields = 'status_fields';
	
	$pro = $oClass->view($request['type'],$request['q'],1);
	$total = $pro->num_rows();
	$start = LIMIT * intval($_GET['page']);
	$url = '?mod='.$system->module.'&parentid='.intval($request['parentid']).'&type='.intval($request['type']).'&by='.$key.'&order='.($request['order']=='DESC'?'DESC':'ASC');
	$dp = new paging($url,$total,LIMIT);
	$request['divpage'] = $dp->simple();
	if(!$total){
		$request['display_checkall'] = 'display: none;';
		$status_fields = '';
		$cat_status_fields = '';
	}
	$request['status_fields'] = $status_fields;
	$request['class_status_fields'] = $class_status_fields;
	
	$orderField = array(
		'name'=>'ln.name',
		'date'=>'c.`date`',
		'order_id'=>'c.order_id',
		'active'=>'c.active',
	);
	$sort_order = $sort_default.' '.$current_order;
	if($request['by'] && in_array($request['by'],array_keys($orderField))) $sort_order = $orderField[$request['by']].' '.$request['order'];
	
	if($cfg_type['sort_order']) $sort_order .= ','.$cfg_type['sort_order'];
	$pro = $oClass->view($request['type'],$request['q'],1,$start,LIMIT,$sort_order);
	
	$k = 1;
	if(!$total) $request['display_checkall'] = 'style="display: none;"';
	while($rs = $pro->fetch()){
		$rs = $hook->format($rs);
	$str_status_fields = array();
		foreach($cfg_type['main_fields'] as $code=>$info) if($info['chose'] && $info['type'] == 'status'){
			$str_status_fields[] = '<a href="?mod='.$system->module.'&amp;act=statusfield&amp;field='.$code.'&amp;id='.$rs['id'].'&amp;parentid='.$request['parentid'].'&amp;type='.$request['type'].'" title="'.$info['description'].'"><img src="'.$tpl->tpl_dir.'images/icons_default/tick'.intval($rs[$code]).'.png" /></a>';
		}
		if($cfg_type['thumb_field'] && $rs[$cfg_type['thumb_field']]){
			if($cfg_type['thumb_field']=='date') $rs['thumb_field'] ='<em class="red">['.date(DATE_FORMAT,strtotime($rs['date'])).']</em>&nbsp;';
			else $rs['thumb_field'] = '<a href="'._UPLOAD.$rs[$cfg_type['thumb_field']].'" class="mb" style="margin-right: 4px;"><img align="left" src="'._UPLOAD.$rs[$cfg_type['thumb_field']].'" width="40" height="40" /></a> ';
		}
		$rs['list_field'] = $rs[$cfg_type['list_field']]?$rs[$cfg_type['list_field']]:$rs['name'];
		$rs['classup'] = $k + $start ==1?'hide':'';
		$rs['classdown'] = ($k + $start) < $total?'':'hide';
		$rs['status_fields'] = $total?implode('</td><td class="th-action status_fields">',$str_status_fields):'';
		$tpl->assign($rs,'product');
		$k++;
	}
	
	if($request['q']){
		$req = $_GET;
		unset($req['q'],$req['cmd']);
		$request['search_result'] = ' <a href="?'.http_build_query($req).'">Clear search</a>';
	}else{
		$request['q'] = 'Enter a keyword';
	}
	
	$breadcrumb->reset();
	$menu = explode('.',$_SESSION['cms_menu']);
	$breadcrumb->assign("",$MenuName[$menu[0]]);
	$level = $MenuLink[$menu[0]][$menu[1]];
	$breadcrumb->assign($level['link'],$level['name']);
	
	$action = array();
	if($show_actions) foreach($show_actions as $act){
		$tmp = explode(':',$act);
		$action['action_'.$tmp[0]] = 'show';
	}
	$tpl->assign($action);
	if(in_array('move',$show_actions)) $tpl->box('move');
	if(in_array('top:table-cell',$show_actions)) $tpl->box('top');
	
	if($cfg_type['button']['header_name'] == '') $cfg_type['button']['header_name'] = $languages['name'];
	if($cfg_type['button']['header_order'] == '') $cfg_type['button']['header_order'] = $languages['order'];
	if($cfg_type['button']['header_status'] == '') $cfg_type['button']['header_status'] = $languages['status'];
	if($cfg_type['button']['header_actions'] == '') $cfg_type['button']['header_actions'] = $languages['actions'];
	if($cfg_type['button']['tools_copy'] == '') $cfg_type['button']['tools_copy'] = $languages['with_selected'];
	
	if($cfg_type['button']['new_item'] == '') $cfg_type['button']['new_item'] = $languages['new_record'];
	if($cfg_type['button']['status_hover_item'] == '') $cfg_type['button']['status_hover_item'] = $languages['status'];
	if($cfg_type['button']['delete_item'] == '') $cfg_type['button']['delete_item'] = $languages['delete'];
	if($cfg_type['button']['active_item'] == '') $cfg_type['button']['active_item'] = $languages['activate'];
	if($cfg_type['button']['inactive_item'] == '') $cfg_type['button']['inactive_item'] = $languages['inactivate'];
	if($cfg_type['button']['edit_item'] == '') $cfg_type['button']['edit_item'] = $languages['edit'];
	
	$tpl->merge($cfg_type['button'],'button');
	if(in_array('enable_search',$show_actions)) $tpl->box('search_function');

	
}
$request['breadcrumb'] = $breadcrumb->parse();
$request['switchorder'] =  $request['order']=='DESC'?'ASC':'DESC';
$request['start'] = $start;
$tpl->assign($request);
$action = array();

?>