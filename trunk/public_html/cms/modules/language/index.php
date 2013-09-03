<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}
if($access!='ALL') $hook->redirect('./');

$oClass = new ClassModel;
$breadcrumb = new breadcrumb;


?>