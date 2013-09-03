<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}
if(isset($_SESSION['admin_login'])) unset($_SESSION['admin_login']);
$hook->redirect('./?mod=user&act=login');



?>