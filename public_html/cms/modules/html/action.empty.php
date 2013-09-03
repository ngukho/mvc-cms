<?php

 
if(!defined('_ROOT')) {
	exit('Access Denied');
}
$tpl->reset();
if($request['c']){
	$oClass->delete($request['id']);
	clear_sql_cache();
}
$hook->redirect('?mod=configure');
?>