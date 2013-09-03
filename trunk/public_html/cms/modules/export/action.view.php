<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}
$tpl->setfile(array('body'=>'export.tpl',));
	$arrMember = $oClass->getMember();
	
	$header = array();
	$rs = $arrHeader->fetch();
	foreach($rs as $keys => $value){
		$header[]["header"] = $keys;
	}
	foreach($header as $keys=>$value){
		$tpl->assign($value,"listHeader");
	}
	
	while($rs = $arrMember->fetch()){	
		$data = array();
		foreach($rs as $keys=>$value){
			$data[]["data"] = $value;
		}
		$tpl->assign($data,"dataRow");
		foreach($data as $keys=>$value){
			$tpl->assign($value,"dataRow.sub");
		}
	}
	
?>