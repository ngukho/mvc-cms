<?php
if(!defined('_ROOT')) {
	exit('Access Denied');
}
//if($access!='ALL') $hook->redirect('./');
$tpl->setfile(array(
		'body'=>'member.logs.tpl',
));
$orderby = " order by g.id desc"; 
$cond =" l.obj_type='share_facebook' and l.user_id=".intval($_GET["byid"]);
$cond .= " and g.active=1 and l.obj_type='share_facebook'";
//$cond .= " and g.blocked=".intval($_GET["blocked"]);
if ($_GET["weekid"]>0){
	$cond .=" and g.obj_id=".intval($_GET["weekid"]);
}
$cat = $oClass->db->query("select g.*, l.dateline from ".$oClass->prefix."logs l inner join ".$oClass->prefix."member_gallery g on (g.id=l.obj_id) where $cond $orderby");	
$i=1;
while($rs = $cat->fetch()){
	$rs["stt"] = $i;
	$i = $i+1;
	$rs['fullname'] = ucwords(mb_strtolower($rs['fullname'],"utf8"));
	$rs['contest_pic'] = $rs['contest_pic']?'<a class="mb" href="'._UPLOAD.$rs['contest_pic'].'" class="divbox">Xem hình</a>':'';		
	$rs['checked'] = $rs['active']?'checked':'';
	$tpl->assign($rs,'user');
}
if ($cat->num_rows()<=0){
	$request["msg"] = "Bạn chưa có chia sẻ bài viết nào cả";
}
else{
	$request["numshare"] = $cat->num_rows();
	$tpl->box("listitemssshow");
}
$breadcrumb->reset();
$menu = explode('.',$_SESSION['cms_menu']);
$breadcrumb->assign("",$MenuName[$menu[0]]);
$level = $MenuLink[$menu[0]][$menu[1]];
$breadcrumb->assign($level['link'],$level['name']);	
$request['breadcrumb'] = $breadcrumb->parse();
$request["username"] = $_GET["username"];
$tpl->assign($request);
$action = array();



?>