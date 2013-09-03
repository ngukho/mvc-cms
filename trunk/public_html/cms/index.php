<?php
define('_ROOT',rtrim(dirname(dirname(__FILE__)),'/').'/',true);
define('_CORE',_ROOT,true);
define('APPLICATION','cms/',true);
//date_default_timezone_set('Asia/Ho_Chi_Minh');
date_default_timezone_set('Asia/Krasnoyarsk');
$ini_session = ini_get('session.save_path');

include _CORE.APPLICATION.'bootstrap.php';

//Controller 
	// argurment 1:  rewrite url ?
	// argument 2:  use mod rewrite ?
	// argument 3: multi language 
$controller = new Controller(false,false,false);
$controller->model = new Model;
$controller->model->db->query("SET NAMES 'UTF8'");
$controller->load();
?>