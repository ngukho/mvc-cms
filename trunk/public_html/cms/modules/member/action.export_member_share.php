<?php

 
if(!defined('_ROOT')) {
	exit('Access Denied');
}
//if($access!='ALL') $hook->redirect('./');
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename=member'.date('Ymd').'.xls');
$tpl->reset();
$tpl->setfile(array(
	'body'=>'member.export.share.tpl',
));
$cond  = " active=1";
if (intval($_GET["weekid"])>0){
	$request["weekid"] = intval($_GET["weekid"]);
}
$result = $oClass->db->query("SELECT user_id, count(id) as total FROM dumex__logs where obj_id in (SELECT id FROM `dumex__member_gallery` WHERE obj_id=".$request["weekid"]." and active=1) and obj_type='share_facebook' and user_id>0 group by user_id order by total desc");
$listuser = "";
$comas = "";
while($rs = $result->fetch()){
	$Logs[$rs["user_id"]] = $rs["total"];
	$listuser .= $comas."".$rs["user_id"];
	$comas = ",";
}
$cond .= " and blocked=".intval($_GET["blocked"])." and id in (".$listuser.")";
$orderby = "FIELD(id, ".$listuser.")";
$cat = $oClass->view($cond,0,0,$orderby);	
while($rs = $cat->fetch()){
	//$rs['delete'] = $rs['is_admin']?'':'style="display: inline;"';
	$rs["facebook"] = strlen($rs["facebookid"])>3?"<a target=\"_blank\" href=\"http://www.facebook.com/profile.php?id=".$rs["facebookid"]."\">Facebook Profile</a>":"Không có";
	$rs['numshare'] = $Logs[$rs["id"]];
	$tpl->assign($rs,'user');
}

?>