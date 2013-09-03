<?php


if(!defined('_ROOT')) {
	exit('Access Denied');
}
$tpl->reset();
if($_POST){
	$url = str_replace('[]','data[]',$_POST['data']);
	parse_str($url,$result);
	if($result['data']) foreach($result['data'] as $ordid=>$id) $oGallery->update($id,array('order_id'=>$ordid));
}

?>