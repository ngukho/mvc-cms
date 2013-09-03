<?php

defined('_ROOT') or die(__FILE__);
if($access!='ALL') $hook->redirect('./');

$oClass = new ClassModel;
$breadcrumb = new breadcrumb;
extract($_GET);

if($cfg['root_admin']) $tpl->box('root_admin');
?>