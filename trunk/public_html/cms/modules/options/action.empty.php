<?php

 
if(!defined('_ROOT')) {
	exit('Access Denied');
}

$tpl->reset();
$tpl->setfile(array(
	'body'=>'options.empty.tpl',
));
$total = 0;

$result = $oClass->view($request['type']);
$total += $result->num_rows();
while($rs = $result->fetch()){
	$tpl->assign($rs,'product');
}
if(!$total) $request['empty_data'] = '<tr><td>This module are already empty data.</td></tr>';
$tpl->assign($request);
?>