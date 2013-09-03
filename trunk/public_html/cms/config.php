<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

//$cfg['lang'] = 'en';//if you want to run this application in 1 language ( no switch languages)
$cfg['template'] = 'Default';
$cfg['root_admin'] = true;
$cfg['client'] = 'Sin Ultra Lounge';
$cfg['sef'] = true;
$cfg['configip'] = '118.69.68.222';
// config includes files for application
include _ROOT.APPLICATION.'libraries/php4/common.php';
include _ROOT.APPLICATION.'libraries/common/functions.php';
include _ROOT.APPLICATION.'libraries/common/breadcrumb.php';
include _ROOT.APPLICATION.'libraries/common/image.php';
include _ROOT.APPLICATION.'libraries/common/page.php';
include _ROOT.APPLICATION.'libraries/smtp/class.phpmailer.php';
include _ROOT.APPLICATION.'libraries/common/email.php';
require_once _ROOT.APPLICATION.'libraries/common/Pages.php';

$i = -1;
$i++;
$MenuName[$i]["name"] = 'Homepage';
$MenuName[$i]["class"] = "djs";
$MenuLink[$i][] = array('name'=>'Manage Slide','link'=>'?mod=content&type=2');

$i++;
$MenuName[$i]["name"] = 'Venue';
$MenuName[$i]["class"] = "djs";
$MenuLink[$i][] = array('name'=>'Manage Venue','link'=>'?mod=content&type=4');

$i++;
$MenuName[$i]["name"] = 'Gallery';
$MenuName[$i]["class"] = "djs";
$MenuLink[$i][] = array('name'=>'Manage Gallery','link'=>'?mod=content&type=5');

$i++;
$MenuName[$i]["name"] = 'DJS';
$MenuName[$i]["class"] = "djs";
$MenuLink[$i][] = array('name'=>'List DJS','link'=>'?mod=options&type=1');

$i++;
$MenuName[$i]["name"] = 'Event';
$MenuName[$i]["class"] = "djs";
$MenuLink[$i][] = array('name'=>'List Event','link'=>'?mod=content&type=3');


////
$i++;
$MenuName[$i]["name"] = 'Administrators';
$MenuName[$i]["class"] = "admin";
$MenuLink[$i][] = array('name'=>'Manage Admin Users','link'=>'?mod=user','class'=>'users');
$MenuLink[$i][] = array('name'=>'Manage Admin Group','link'=>'?mod=group','class'=>'users');
$MenuLink[$i][] = array('name'=>'Languages','link'=>'?mod=language','class'=>'languages');
$MenuLink[$i][] = array('name'=>'Configure','link'=>'?mod=configure','class'=>'configure');
$MenuLink[$i][] = array('name'=>'Defined pages','link'=>'?mod=module','class'=>'pages');

$i++;
$MenuName[$i]["name"] = 'Tools';
$MenuName[$i]["class"] = "tools";
$MenuLink[$i][] = array('name'=>'Database backup','link'=>'?mod=tools&act=backup','class'=>'dbbackup');
//$MenuLink[$i][] = array('name'=>'TinyMCE Editor','link'=>'?mod=tools&act=tinymce');
$MenuLink[$i][] = array('name'=>'Server info','link'=>'?mod=tools&act=serverinfo','class'=>'serverinfo');
$MenuLink[$i][] = array('name'=>'Google Analytics','link'=>'?mod=analytic','class'=>'analytic');
?>