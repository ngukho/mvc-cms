<?php
/**
 * @Name: CaoBox v1.0
 * @author LinhNMT <w2ajax@gmail.com>
 * @link http://code.google.com/p/caobox/
 * @copyright Copyright &copy; 2009 phpbasic
 */
 
defined('_ROOT') or die(__FILE__);
//if($access!='ALL') $hook->redirect('./');
if($_POST){
	$refresh = '?'.http_build_query($_GET,'','&');
	if($_POST['act_delete']){
		if($_POST['pro']) foreach($_POST['pro'] as $id) { $oClass->delete($id);}
		$hook->redirect($refresh);
	}elseif($_POST['act_active']){
		if($_POST['pro']) foreach($_POST['pro'] as $id) {$oClass->actives($id,1);}
		$hook->redirect($refresh);
	
	}elseif($_POST['act_inactive']){
		if($_POST['pro']) foreach($_POST['pro'] as $id) { $oClass->actives($id,0);}
		$hook->redirect($refresh);
	
	}else{
		$hook->redirect($refresh);
	}

}else{	
	$tpl->setfile(array(
		'body'=>'member.tpl',
	));
	$cond = " active=0";
	$cat = $oClass->view($cond);
	$total = $cat->num_rows();
	
	$orderby = "timestamp DESC";
	
	$start = LIMIT * intval($_GET['page']);
	$url = './?mod='.$system->module.'&parentid='.intval($_GET['parentid']).'&type='.intval($_GET['type']);
	$dp = new paging($url,$total,LIMIT);
	$request['divpage'] = $dp->simple();
	
	$cat = $oClass->view($cond,$start,LIMIT,$orderby);
	while($rs = $cat->fetch()){
		//$rs['delete'] = $rs['is_admin']?'':'style="display: inline;"';
		$rs['checked'] = $rs['active']?'checked':'';
		$rs['avatar'] = $rs['avatar']?'<a href="'._UPLOAD.$rs['avatar'].'" class="divbox"><img src="'._UPLOAD.$rs['avatar'].'" width="30" height="30" /></a>':'';
		$tpl->assign($rs,'user');
	}
	$breadcrumb->reset();
	$menu = explode('.',$_SESSION['cms_menu']);
	$breadcrumb->assign("",$MenuName[$menu[0]]);
	$level = $MenuLink[$menu[0]][$menu[1]];
	$breadcrumb->assign($level['link'],$level['name']);	
}
$request['breadcrumb'] = $breadcrumb->parse();
$tpl->assign($request);
$action = array();



?>