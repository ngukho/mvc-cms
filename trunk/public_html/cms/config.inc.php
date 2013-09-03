<?php
if(!defined('_ROOT')) {
	exit('Access Denied');
}

$config_data_values = require_once (_ROOT . "../application/config/config.php");

$cfg = array();
//databas configure
// $cfg['driver'] 	= 'mysql';
// $cfg['server'] 	= 'localhost';
// $cfg['port'] 		= '3306';
// $cfg['usr'] 		= 'root';
// $cfg['psw'] 		= '';
// $cfg['name'] 		= 'sinlounge';
// $cfg['prefix'] 		= 'sinlounge__';

$cfg['driver'] 		= $config_data_values['database_master']['db_type'];
$cfg['server'] 		= $config_data_values['database_master']['db_hostname'];
$cfg['port'] 		= $config_data_values['database_master']['db_port'];
$cfg['usr'] 		= $config_data_values['database_master']['db_username'];
$cfg['psw'] 		= $config_data_values['database_master']['db_password'];
$cfg['name'] 		= $config_data_values['database_master']['db_name'];
$cfg['prefix'] 		= $config_data_values['database_master']['db_prefix'];

//
$cfg['lang'] = 'en';
$cfg['error_report'] = E_ALL & ~E_WARNING & ~E_NOTICE;
$cfg['error_display'] = false;
$cfg['server_var'] = 'REQUEST_URI';
$cfg['cache'] = false;
$cfg['gzip'] = true;

//
$core_ext[] = 'session';

?>