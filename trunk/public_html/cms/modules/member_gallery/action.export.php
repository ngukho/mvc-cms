<?php
/**
 * @Name: CaoBox v1.0
 * @author LinhNMT <w2ajax@gmail.com>
 * @link http://code.google.com/p/caobox/
 * @copyright Copyright &copy; 2009 phpbasic
 */
 
defined('_ROOT') or die(__FILE__);
//if($access!='ALL') $hook->redirect('./');
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename=member'.date('Ymd').'.xls');
$tpl->reset();
$tpl->setfile(array(
	'body'=>'member_gallery/member.export.tpl',
));

$aOption = array();
$result = $oOption->view(1);
while($rs = $result->fetch()){
	$aOption[$rs['id']] = $rs['name'];
}
$cond  = " active=1";
if (intval($_GET["weekid"])>0){
		$cond .= " and obj_id=".intval($_GET["weekid"]);
	}
	
$cat = $oClass->view($cond);
while($rs = $cat->fetch()){
	//$rs['delete'] = $rs['is_admin']?'':'style="display: inline;"';
	$rs['active'] = $rs['active']?'Activated':'Not activate';
	$rs['blocked'] = $rs['blocked']?'Blocked':'Not block';
	$rs["link"] = "http://www.facebook.com/Dumex/?sk=app_262656060501586&app_data=".$rs['id'];
	$rs["contest_pic"] = "http://fbapp.thuthachdaudoi.com/data/upload/".$rs["contest_pic"];
	$tpl->assign($rs,'user');
}

?>