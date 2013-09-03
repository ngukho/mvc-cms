<?php

 
defined('_ROOT') or die(__FILE__);
$table = $_GET['tbl'];
if($_POST){
	$oClass->db->query("ALTER TABLE `".$oClass->prefix."$table` ADD `".$_POST['field']."` ".$_POST['type'].($_POST['length']?"( ".$_POST['length']." )":"")." NULL DEFAULT ".($_POST['default']?"'".$_POST['default']."'":"NULL").";");
	die('Please refresh the page to see your update!');
}else{
	$tpl->reset();
	$tpl->setfile(array(
		'body'=>'configure.addfield.tpl',
	));
}
//ALTER TABLE `php_content` ADD `testfield` VARCHAR( 255 ) NULL DEFAULT '4';
?>