<?php

defined('_ROOT') or die(__FILE__);
if ($_POST && !empty($_POST["configure"])){
	$oClass->update($_POST["configure"]);
	//clear_cache_configure();
	$oMaster->user_log('Updated value: '.$_POST['value'].' for configure: '.$_GET['code']);
	clear_sql_cache();
	$hook->redirect('?mod=configure&gid='.$_GET['gid']);	
}
extract($_GET);
$request = $_GET;
$request['type'] = intval($type);
$request['parentid'] = intval($parentid);

$tpl->setfile(array(
	'body'=>'configure.tpl',
));

$icons = $login_user['setting']['icon']?$login_user['setting']['icon']:'default';

$cat = $oConfigure->getMod("`module` = 'content'");
while($rs = $cat->fetch()){
	$rs['attr'] = $rs['is_attribute']?'<em>(Attr)</em> ':'';
	$tpl->assign($rs,'cfg_module');
}
$cat = $oConfigure->getMod("`module` = 'html'");
while($rs = $cat->fetch()){
	$result = $oClass->check_html($rs['typeid']);
	$rs['plus'] = $result->num_rows()?'':' <a href="#" onclick="newHTMLCfg('.$rs['typeid'].');return false;"><img src="template/images/icons_'.$icons.'/plus.jpg" border="0" /></a>';
	$tpl->assign($rs,'cfg_html');
}

$cat = $oConfigure->getMod("`module` = 'options'");
while($rs = $cat->fetch()){
	$tpl->assign($rs,'options');
}

$breadcrumb->reset();
$menu = explode('.',$_SESSION['cms_menu']);
$breadcrumb->assign("",$MenuName[$menu[0]]);
$level = $MenuLink[$menu[0]][$menu[1]];
$breadcrumb->assign($level['link'],$level['name']);


$request['breadcrumb'] = $breadcrumb->parse();

$k = 0;
$c_group = NULL;
$result = $oConfigure->group();
while($rs = $result->fetch()){
	$rs = $hook->format($rs);
	$active = '';
	if((!$_GET['gid'] && $k==0) || ($_GET['gid'] && $rs['id'] == $_GET['gid']) ){ $active = 'selected'; $c_group = $rs;}
	$rs['selected'] = $active;
	$tpl->assign($rs,'cfg_group');
	$k++;
}


$result = $oConfigure->view(" group_id = ".intval($c_group['id']));
while($rs = $result->fetch()){
	$rs = $hook->format($rs);
	$rs["set_function"] = $rs['set_function'];
	if($rs["set_function"]){
		eval('$value = '.$rs["set_function"]."'".$rs['value']."','configure[".$rs['code']."]');");
	}else{
		$value  = textfield("configure[".$rs['code']."]",$rs['value']);
	}
	$rs["value"] = $value;
	$tpl->assign($rs,'cfg_common');
}

// configure common values

$request['group_id'] = intval($c_group['id']);
$tpl->assign($request);

?>