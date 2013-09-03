<?php
/**
 * @Name: CaoBox v1.0
 * @author LinhNMT <w2ajax@gmail.com>
 * @link http://code.google.com/p/caobox/
 * @copyright Copyright &copy; 2009 phpbasic
 */
 
defined('_ROOT') or die(__FILE__);
//if($access!='ALL') $hook->redirect('./');
$cond = 1;
$location = array("Diamond"=>"Diamond Plaza","ParksonLTT"=>"Parkson Lê Thánh Tôn","ParksonHV"=>"Parkson Hùng Vương","NowZone"=>"NowZone");
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
		if ( $rs["id"] == $request["weekid"] || !isset($_GET["weekid"])) {
			$rs["active"] ='selected="selected"';	
			$request["weekid"] = $rs["id"];	
			$_GET["weekid"] =  $rs["id"];
		}					
		$tpl->assign($rs, "listvideoweek");
	}
	$url = '?'.http_build_query($_GET,'','&');
	if ($_POST["btnSearch"] || $_REQUEST["q"]!=""){
		$cond .= " and (fullname like '%".$_REQUEST["q"]."%' or email like '%".$_REQUEST["q"]."%' or idno like '%".$_REQUEST["q"]."%' or tel like '%".$_REQUEST["q"]."%')";
		$text = "&q=". str_replace(array(' ', '<', '>', '&', '{', '}', '*'), array('+'), $_REQUEST["q"]);
		$request["url_current"] = $text;
		$url .= $text;
		$request["search_text"] = str_replace(array(' ', '<', '>', '&', '{', '}', '*'), array(' '), $_REQUEST["q"]);		
	}	
	if ($request["weekid"]>0){
		$cond .= " and obj_id=".$request["weekid"];
	}
	$request["url_current"] .= intval($_GET['page'])>0?"&page=".intval($_GET['page']):"";
	$tpl->setfile(array(
		'body'=>'member_gallery/member_gallery.tpl',
	));
	$enabled = intval($_GET["enabled"]);
	$cond .= " and active=".$enabled ;
	$cond .= " and blocked=".intval($_GET["blocked"]);
	$cat = $oClass->view($cond);
	$total = $cat->num_rows();
	
	$request["page"] = intval($request["page"])<=0?1:intval($request["page"]);	
	$start = LIMIT * (intval($request['page'])-1);	
	
	if ($total>LIMIT){
		$objPage = new Pages();
		$objPage->First = "First";
		$objPage->Last = "Last";
		$objPage->Next = "Next";
		$objPage->Prev = "Prev";
		$request['divpage'] = $objPage->multipages($total, LIMIT, $request['page'], $url."&page=%page%");	
		$tpl->assign($request);
	}
	$orderby = " id desc"; 
	
	$cat = $oClass->view($cond,$start,LIMIT,$orderby);	
	while($rs = $cat->fetch()){
		$rs['fullname'] = ucwords(mb_strtolower($rs['fullname'],"utf8"));
		$rs['contest_pic'] = $rs['contest_pic']?'<a class="mb" href="'._UPLOAD.$rs['contest_pic'].'" class="divbox">Xem hình</a>':'';		

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