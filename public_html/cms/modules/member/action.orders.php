<?php

 
if(!defined('_ROOT')) {
	exit('Access Denied');
}
$arrStatusPay = array("0"=>"Đang chờ xác nhận.", "1"=>"Đã được xác nhận và đang chờ thanh toán.", "2"=>"Đã thanh toán và đang chờ giao hàng", "3"=>"Đã được giao hàng");
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
		'body'=>'member.orderslist.tpl',
	));
	
	$total = $oClass->orderlist_count($cond);
	
	$orderby = "timestamp DESC, id desc";
	
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
	
	$cat = $oClass->orderlist($cond,$start,LIMIT,$orderby);	
	while($rs = $cat->fetch()){	
		$rs["orderid"]	 = $oClass->CreateOrderID($rs["id"]);		
		$rs["paystatus"] =  $arrStatusPay[$rs["payment_status"]];
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