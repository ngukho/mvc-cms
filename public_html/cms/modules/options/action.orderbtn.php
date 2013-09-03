<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

$result = $oClass->db->query("SELECT MAX(`order_id`) maxorder FROM ".$oClass->prefix."options WHERE  `type` = ".intval($request['type']));
$max = $result->fetch();
$result = $oClass->get($request['id']);
$data = $result->fetch();

if(($request['do'] == 'up' && $data['order_id']) ||  ($request['do'] == 'down' && $data['order_id'] < $max['maxorder'])){
	$new_order = $request['do'] == 'up'?$data['order_id'] - 1:$data['order_id'] + 1;
	$oClass->db->query("UPDATE ".$oClass->prefix."options SET order_id = ".intval($data['order_id'])."  WHERE  `type` = ".intval($request['type'])." AND order_id = ".$new_order);
	$oClass->update($request['id'],array('order_id'=>$new_order));
	
	clear_sql_cache();
}
	// refresh
	$query_string = $_SERVER['QUERY_STRING'];
	parse_str($query_string,$result);
	$mod = $result['p'];
	unset($result['act'],$result['id'],$result['c']);
	$hook->redirect('?'.http_build_query($result,NULL,'&'));
?>