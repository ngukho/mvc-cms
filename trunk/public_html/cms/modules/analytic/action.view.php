<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}
$tpl->setfile(array(
	'body'=>'analytic.tpl',
));

if(!$_SESSION['ga']){
	$tpl->box('ga_login');
}else{
	$tpl->box('ga_account');
}
?>
