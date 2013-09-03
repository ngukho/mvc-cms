<?php

 
if(!defined('_ROOT')) {
	exit('Access Denied');
}
//if($access!='ALL') $hook->redirect('./');
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename=member'.date('Ymd').'.xls');
$tpl->reset();
$tpl->setfile(array(
	'body'=>'member.export.tpl',
));
$cond = "active=1 and blocked=0";
$result = $oClass->getCountGallery($cond);
$listUser = array();
while($rs = $result->fetch()){
	$listUser[$rs["user_created"]] = $rs["total"];
}
$cond= " active=1 and blocked=0";
$orderby = "timestamp DESC, id desc";
$cat = $oClass->view($cond,0,0,$orderby);	
while($rs = $cat->fetch()){
	$rs['total'] = intval($listUser[$rs["id"]]);
	$rs["facebook"] = strlen($rs["facebookid"])>3?"<a target=\"_blank\" href=\"http://www.facebook.com/profile.php?id=".$rs["facebookid"]."\">Facebook Profile</a>":"Khong co";
	$rs['active'] = $rs['active']?'Activated':'Not activate';
	$rs['blocked'] = $rs['blocked']?'Blocked':'Not block';
	$tpl->assign($rs,'user');
}

?>