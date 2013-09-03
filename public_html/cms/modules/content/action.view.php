<?php

 
defined('_ROOT') or die(__FILE__);
if($_POST){
	$refresh = '?'.http_build_query($_GET);
	if($_POST['content_action']=='delete'){
		if($_POST['pro']) foreach($_POST['pro'] as $id) $oClass->delete($id);
		clear_sql_cache();
		$hook->redirect($refresh);
	}elseif($_POST['content_action'] == 'top'){
		if($_POST['pro']) foreach($_POST['pro'] as $id) $oClass->top($id,1);
		clear_sql_cache();
		$hook->redirect($refresh);
	
	}elseif($_POST['content_action'] == 'intop'){
		if($_POST['pro']) foreach($_POST['pro'] as $id) $oClass->top($id,0);
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
	
	}elseif($_POST['content_action']=='move'){
		$current_catid = $request['parentid'];
		$tpl->setfile(array(
			'body'=>'content.move.tpl',
		));
		$tpl->assign(array(
			'products'=>implode(',',$_POST['pro']),
			'form_action'=>'?mod=content&act=move&parentid='.$request['parentid'].'&type='.$request['type'],
		));
		$tree = $oCategory->tree($request['type'],0,'&nbsp;',1);
		foreach($tree as $rs){
			$rs['prefix'] = $rs['prefix'].'|&mdash;';
			$rs['selected'] = $rs['id']==$current_catid?'selected':'';
			$tpl->assign($rs,'category');
		}
		$breadcrumb->assign("","Move to category");
	}else{
		$hook->redirect($refresh);
	}

}else{
	$filter_userid = isset($acl_user_act["filteruser"])?true:false;		
	$request["action_update"] = $request["action_updatecat"]= $request["delrecord"] =$request["deletecat"]=$request["action_new"]="hide";	
	if (isset($acl_user_act["edit"]) || intval($_SESSION['admin_login']['is_admin'])==1){
		$request["action_update"] = $request["action_updatecat"]= "show";
	}
	if (isset($acl_user_act["delete"]) || intval($_SESSION['admin_login']['is_admin'])==1){
		$request["delrecord"] =$request["deletecat"]= "show";
	}
	if (isset($acl_user_act["new"]) || intval($_SESSION['admin_login']['is_admin'])==1){
		$request["action_new"] = $request["action_newcat"]= "show";
		$tpl->box("addnew_cat_items");
	}
	
	$arrdata = $_GET;
	unset($arrdata["parentid"],$arrdata["type"],$arrdata["parentid"],$arrdata["token"]);
	$request["theUrl"] = '?'.http_build_query($arrdata,'','&');

	$current_order = $cfg_type['sort_default_order']=='DESC'?'DESC':'ASC';
	if($request['order'] == 'DESC' || $request['order'] == 'ASC') $current_order = $request['order'];
	
	$tpl->setfile(array(
		'body'=>$cfg_type['tpl_view']?$cfg_type['tpl_view']:'content.tpl',
	));
	$fields = $oMaster->fields('content');
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
	
	
	$catOrderField = array(
		'name'=>'ln.name',
		'date'=>'c.`date`',
		'order_id'=>'c.order_id',
		'active'=>'c.active',
	);	
	$cond = 1;
	if ($filter_userid && intval($_SESSION['admin_login']['is_admin'])<=0){
		$cond = "(c.userid=".intval($_SESSION['admin_login']['id'])." or c.access REGEXP ',".intval($_SESSION['admin_login']['id']).",')";
	}	
	$cond .= isset($_GET["active"])?" and c.active=".intval($_GET["active"]):"";
	$cond_conent = "";
	if ($_GET["relatedid"]>0){
		$cond_conent =" and c.related_contentid=".intval($_GET["relatedid"]);
	}
	$total = $oClass->count_rows($request['type'],$request['parentid'],$request['q'],$cond.$cond_conent);
	
	$startCat = 0;
	$limitCat = 0;
	if(!$total){
		$request['display_checkall'] = 'display: none;';
		$status_fields = '';
		$cat_status_fields = '';
		$startCat = intval($request['page'])*LIMIT;
		$limitCat = LIMIT;
	}
	$request['status_fields'] = $status_fields;
	$request['class_status_fields'] = $class_status_fields;
	
	
	$catsort_order = ($cfg_type['sort_default']?$cfg_type['sort_default']:'c.order_id').' '.$current_order;
	if($request['by'] && in_array($request['by'],array_keys($catOrderField))) $catsort_order = $catOrderField[$request['by']].' '.$request['order'];
	
	if($cfg_type['sort_order']) $catsort_order .= ','.$cfg_type['catsort_order'];
	if(!$total){
		$totalCat = $oCategory->count_rows($request['type'],$request['parentid'],$request['q']);	
	}
	$cat = $oCategory->view($request['type'],$request['parentid'],$request['q'],$catsort_order,$startCat,$limitCat, $cond);
	$k = 1;
	$listUser = $oUser->view();
	while($rs = $cat->fetch()){
		$rs = $hook->format($rs);
		$rs["author"] = isset($listUser[$rs['userid']])?$listUser[$rs['userid']]["fullname"]:"";
		$delcat = 1;
		if($cfg_type['nodelcat_ids'] && in_array($rs['id'],explode(',',$cfg_type['nodelcat_ids']))) $delcat = 0;		
		if($delcat) $rs['delcat'] = 'show';
		$rs['status_fields'] = $total?implode('</td><td class="th-action status_fields">',$array_cat_status_fields):'';
		$rs['row'] = $k%2?1:2;
		$rs["update_link"] = $request["action_updatecat"]?'?mod=category&p='.$system->module.'&act=update&id='.$rs['id'].'&parentid='.$request['parentid'].'&type='.$request['type']:"";
		$rs["delete_link"] = $request["deletecat"]?'?mod=category&p='.$system->module.'&act=delete&id='.$rs['id'].'&parentid='.$request['parentid'].'&type='.$request['type']:"";
		//if(!in_array('deletecat',$cfg_type['act'])) $delcat = 0;
		$tpl->assign($rs,'category');
		$k++;
	}
	$acl = NULL;
	if($_SESSION['admin_login']['permission']=='ALL'){
		$acl['all_content'] = 1;
	}else{
		$acl = unserialize($_SESSION['admin_login']['permission']);
	}
	
	
	$sort_default = $cfg_type['sort_default']?$cfg_type['sort_default']:'order_id';

	$key = $request['by']?$request['by']:$sort_default;
	$val = $current_order=='DESC'?'&darr;':'&uarr;';
	$url_orderby = array($key=>$val);
	$tpl->merge($url_orderby,'orderby');
	
	$request["page"] = intval($request["page"])<=0?1:intval($request["page"]);	
	$start = LIMIT * (intval($request['page'])-1);
	
	//if(!$acl['all_content']) $cond .= " AND c.userid = ".intval($_SESSION['admin_login']['id']);

	$url = '?mod='.$system->module.'&parentid='.intval($request['parentid']).'&type='.intval($request['type']).'&by='.$key.'&order='.($request['order']=='DESC'?'DESC':'ASC');
	
	
	if(!$total){
		$objPage = new Pages();
		$objPage->First = "First";
		$objPage->Last = "Last";
		$objPage->Next = "Next";
		$objPage->Prev = "Prev";
		$request['divpage'] = $objPage->multipages($totalCat, LIMIT, $request['page'], $url."&page=%page%");	
		$tpl->assign($request);
	}else{		
		$objPage = new Pages();
		$objPage->First = "First";
		$objPage->Last = "Last";
		$objPage->Next = "Next";
		$objPage->Prev = "Prev";
		$objPage->current = '<strong>%page%</strong>';
		$request['divpage'] = $objPage->multipages($total, LIMIT, $request['page'], $url."&page=%page%");					
	}
	$request['start'] = $start;
	$request['comment_name'] = $cfg_type['comment_name']?$cfg_type['comment_name']:$languages['comment'];
	$request['featuredon_name'] = $cfg_type['featuredon_name']?$cfg_type['featuredon_name']:$languages['featuredon'];	
	
	$orderField = array(
		'name'=>'ln.name',
	);
	$sort_order = $sort_default.' '.$current_order;
	if($request['by'] && in_array($request['by'],array_keys($orderField))) $sort_order = $orderField[$request['by']].' '.$request['order'];
	if($request['by'] && in_array($request['by'],$fields)) $sort_order = 'c.'.$request['by'].' '.$request['order'];
	
	if($cfg_type['sort_order']) $sort_order .= ','.$cfg_type['sort_order'];
	$pro = $oClass->view($request['type'],$request['parentid'],$request['q'],$cond.$cond_conent,$start,LIMIT,$sort_order);
	
	$k = 1;
	$request['subcontent_name'] = stripslashes($cfg_type['sub_name']);
	$request['subcontent_show'] = in_array('subcontent',$show_actions)?'show':'';	
	while($rs = $pro->fetch()){
		$rs = $hook->format($rs);
		if($cfg_type['thumb_field'] && $rs[$cfg_type['thumb_field']]){
			$rs['thumb_field'] = '<a href="'._UPLOAD.$rs[$cfg_type['thumb_field']].'" class="mb" style="margin-right: 4px;"><img align="left" src="'._UPLOAD.$rs[$cfg_type['thumb_field']].'" width="40" height="40" /></a> ';
		}	
		$rs["preview_link"] = $rs["update_link"] = $request["theUrl"].'&act=preview&id='.$rs['id'].'&parentid='.$request['parentid'].'&type='.$request['type'];	
		$rs["update_link"] = $request["action_update"]?$request["theUrl"].'&act=update&id='.$rs['id'].'&parentid='.$request['parentid'].'&type='.$request['type']:"";
		$rs["delete_link"] = $request["delrecord"]?$request["theUrl"].'&act=delete&id='.$rs['id'].'&parentid='.$request['parentid'].'&type='.$request['type']:"";
		
		$rs["author"] = isset($listUser[$rs['userid']])?$listUser[$rs['userid']]["fullname"]:"";
		$rs['list_field'] = $rs[$cfg_type['list_field']]?$rs[$cfg_type['list_field']]:$rs['name'];
		$del =1;
		if($cfg_type['nodel_ids'] && in_array($rs['id'],explode(',',$cfg_type['nodel_ids']))) $del = 0;		
		$rs['featureon'] = $rs['featuredon']?'edit':'add';
		
		$str_status_fields = array();
		foreach($cfg_type['main_fields'] as $code=>$info) if($info['chose'] && $info['type'] == 'status'){
			$str_status_fields[] = '<a href="?mod='.$system->module.'&amp;act=statusfield&amp;field='.$code.'&amp;id='.$rs['id'].'&amp;parentid='.$request['parentid'].'&amp;type='.$request['type'].'" title="'.$info['description'].'"><img src="'.$tpl->tpl_dir.'images/icons_default/tick'.intval($rs[$code]).'.png" /></a>';
		}
		
		$rs['actionexternal'] = $cfg_type['actionexternal']?sprintf($cfg_type['actionexternal'],$rs["id"]):"";		
		$rs['status_fields'] = $total?implode('</td><td class="th-action status_fields">',$str_status_fields):'';
		$rs['row'] = $k%2?1:2;
		$rs['classup'] =  $k + $start ==1?'hide':'';
		$rs['classdown'] = ($k + $start) < $total?'':'hide';		
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
	if($cfg_type['action_featuredon_cat_level'] > -1){
		$aPath = $oCategory->xpath($_GET['parentid']);
		$level = count($aPath);	
		if (in_array($level,explode(',',$cfg_type['action_featuredon_cat_level']))){				
			$action['action_featuredon_cat_level'] = 'show';
	  	}
	}	
	$tpl->assign($action);
	if(in_array('move',$show_actions)) $tpl->box('move');
	if(in_array('top:table-cell',$show_actions)) $tpl->box('top');

	
	if($cfg_type['button']['header_name'] == '') $cfg_type['button']['header_name'] = $languages['name'];
	if($cfg_type['button']['header_date'] == '') $cfg_type['button']['header_date'] = $languages['date'];
	if($cfg_type['button']['header_order'] == '') $cfg_type['button']['header_order'] = $languages['order'];
	if($cfg_type['button']['header_status'] == '') $cfg_type['button']['header_status'] = $languages['status'];
	if($cfg_type['button']['header_actions'] == '') $cfg_type['button']['header_actions'] = $languages['actions'];
	if($cfg_type['button']['tools_copy'] == '') $cfg_type['button']['tools_copy'] = $languages['with_selected'];
	
	if($cfg_type['button']['new_item'] == '') $cfg_type['button']['new_item'] = $languages['new_record'];
	if($cfg_type['button']['status_hover_item'] == '') $cfg_type['button']['status_hover_item'] = $languages['status'];
	if($cfg_type['button']['move_item'] == '') $cfg_type['button']['move_item'] = $languages['move'];
	if($cfg_type['button']['delete_item'] == '') $cfg_type['button']['delete_item'] = $languages['delete'];
	if($cfg_type['button']['active_item'] == '') $cfg_type['button']['active_item'] = $languages['activate'];
	if($cfg_type['button']['inactive_item'] == '') $cfg_type['button']['inactive_item'] = $languages['inactivate'];
	if($cfg_type['button']['edit_item'] == '') $cfg_type['button']['edit_item'] = $languages['edit'];
	
	if($cfg_type['button']['new_category'] == '') $cfg_type['button']['new_category'] = $languages['new_category'];
	if($cfg_type['button']['status_hover_cat'] == '') $cfg_type['button']['status_hover_cat'] = $languages['status'];
	if($cfg_type['button']['delete_category'] == '') $cfg_type['button']['delete_category'] = $languages['delete'];
	if($cfg_type['button']['edit_category'] == '') $cfg_type['button']['edit_category'] = $languages['edit'];
	$tpl->merge($cfg_type['button'],'button');


	if(in_array('enable_search',$show_actions)) $tpl->box('search_function');
	
	if($request['parentid']) $tpl->box('breadcrumb_cat');
}
$request['switchorder'] =  $current_order=='DESC'?'ASC':'DESC';
$request['breadcrumb'] = $breadcrumb->parse();
$request['parentid'] = intval($request['parentid']);
$request['msg'] = stripslashes($request['msg']);
$tpl->assign($request);
$action = array();

?>