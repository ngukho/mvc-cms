<?php


if(!defined('_ROOT')) {
	exit('Access Denied');
}
$tpl->reset();
if (isset($_POST['orders'])) {
	$orders = explode('&', $_POST['orders']);
	$key= 0;
	foreach($orders as $item) {
		$item = explode('=', $item);
		$item = explode('_', $item[1]);
		$oClass->update($item[1],array('order_id'=>$key));		
		$key = $key+1;
	}	
}

?>