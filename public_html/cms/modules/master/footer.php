<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}


$html = $tpl->display();
if(is_dir($tpl->tpl_dir.'images/icons_'.$login_user['setting']['icon']))
	$html = str_replace('images/icons_default/','images/icons_'.$login_user['setting']['icon'].'/',$html);
echo $html;
$tpl->cache();
?>