<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}


	$oGallery->active($request['id']);
	// refresh
	$query_string = $_SERVER['QUERY_STRING'];
	parse_str($query_string,$result);
	if($request['pid']){
		unset($result['mod'],$result['act'],$result['id'],$result['pid'],$result['c'],$result['p']);
		$hook->redirect('?mod='.$p.'&act=update&id='.$pid.'&'.http_build_query($result));
	}else{
		unset($result['act'],$result['id'],$result['c']);
		$hook->redirect('?'.http_build_query($result));
	}
?>