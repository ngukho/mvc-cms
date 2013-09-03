<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}
define('_BACKUP',_ROOT.'data/backup/');
define('_CACHE',_ROOT.'data/cache/');
define('_UPLOAD','../data/upload/',true);
define('DATE_FORMAT','Y-m-d',true);
define('LIMIT',100,true);
?>