<?php

 
if(!defined('_ROOT')) {
	exit('Access Denied');
}
//if($access!='ALL') $hook->redirect('./');
$cond = 1;
$_GET["enabled"] = !isset($_GET["enabled"])?1:intval($_GET["enabled"]);
if($_POST && !isset($_POST["btnSearch"])){		
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
	$objCotent = $oClass->Contentview(1,"1",0,0," c.id desc");
	$i = 1;
	$request["weekid"] = intval($_GET["weekid"]);
	while($rs = $objCotent->fetch()){		
		$rs["subject"] = "Chủ đề ".$i;
		$i  = $i +1;
		if ( $rs["id"] == $request["weekid"] || $_GET["weekid"]<=0) {
			$rs["active"] ='selected="selected"';	
			$request["weekid"] = $rs["id"];	
			$_GET["weekid"] =  $rs["id"];
		}					
		$tpl->assign($rs, "listvideoweek");
	}
	$result = $oClass->db->query("SELECT user_id, count(id) as total FROM dumex__logs where obj_id in (SELECT id FROM `dumex__member_gallery` WHERE obj_id=".intval($request["weekid"])." and active=1) and obj_type='share_facebook' and user_id>0 group by user_id order by total desc");
	$listuser = "";
	$comas = "";
	while($rs = $result->fetch()){
		$Logs[$rs["user_id"]] = $rs["total"];
		$listuser .= $comas."".$rs["user_id"];
		$comas = ",";
	}
	
	$request["header_active"] = "Active";
	if ($_GET["enabled"]==1){
		$request["header_active"] = "inActive";
	}
	$_SGET = $_GET;
	unset($_SGET["page"]);
	$url = '?'.http_build_query($_SGET,'','&');
	if ($_POST["btnSearch"] || $_REQUEST["q"]!=""){
		$cond .= " and (fullname like '%".$_REQUEST["q"]."%' or email like '%".$_REQUEST["q"]."%' or idno like '%".$_REQUEST["q"]."%')";
		$text = "&q=". str_replace(array(' ', '<', '>', '&', '{', '}', '*'), array('+'), $_REQUEST["q"]);
		$request["url_current"] = $text;
		$url .= $text;
		$request["search_text"] = str_replace(array(' ', '<', '>', '&', '{', '}', '*'), array(' '), $_REQUEST["q"]);		
	}
	$request["url_current"] .= intval($_GET['page'])>0?"&page=".intval($_GET['page']):"";
	$tpl->setfile(array(
		'body'=>'member.share.tpl',
	));
	//$cond .= isset($_GET["enabled"])?" and active=".intval($_GET["enabled"]):"";	
	$cond .= " and blocked=".intval($_GET["blocked"])." and id in (".$listuser.")";
		
	$cat = $oClass->view($cond);
	$total = $cat->num_rows();
	
	$orderby = "FIELD(id, ".$listuser.")";
	
	/*$start = LIMIT * intval($_GET['page']);
	//$url = './?mod='.$system->module.'&parentid='.intval($_GET['parentid']).'&type='.intval($_GET['type']);	
	$dp = new paging(preg_replace('/&page=[0-9]/iu',"",$url),$total,LIMIT);
	$request['divpage'] = $dp->simple();
	$request['url_link'] = $url;*/
	
	$request["page"] = intval($request["page"])<=0?1:intval($request["page"]);	
	$start = LIMIT * (intval($request['page'])-1);	
	if ($total>LIMIT){
		$objPage = new Pages();
		$objPage->First = "First";
		$objPage->Last = "Last";
		$objPage->Next = "Next";
		$objPage->Prev = "Prev";
		$request['divpage'] = $objPage->multipages($total, LIMIT, $request['page'], $url."&page=%page%");	
	}
	$request['url_link'] = $url;	
	$cat = $oClass->view($cond,$start,LIMIT,$orderby);	

	while($rs = $cat->fetch()){
		//$rs['delete'] = $rs['is_admin']?'':'style="display: inline;"';
		$rs["facebook"] = strlen($rs["facebookid"])>3?"<a target=\"_blank\" href=\"http://www.facebook.com/profile.php?id=".$rs["facebookid"]."\">Facebook Profile</a>":"Không có";
		$rs['numshare'] = $Logs[$rs["id"]];
		$tpl->assign($rs,'user');
	}
	$breadcrumb->reset();
	$menu = explode('.',$_SESSION['cms_menu']);
	$breadcrumb->assign("",$MenuName[$menu[0]]);
	$level = $MenuLink[$menu[0]][$menu[1]];
	$breadcrumb->assign($level['link'],$level['name']);	
}
$request['breadcrumb'] = $breadcrumb->parse();
if (isset($_GET["contact"])){
	$request["contact_url"] = "contact=1";	
}
else{
	$request["display_addnew"] = '<a href="?mod=member&act=update" class="btn l">Add New</a>';
}
$tpl->assign($request);
$action = array();



?>