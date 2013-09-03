<?php

if(!defined('_ROOT')) {
 exit('Access Denied');
}
//$model = new Model;
//$model->setCache('data/cache/');
$result =  $model->db->query("SELECT c.`module`,ln.* FROM ".$model->prefix."module c,".$model->prefix."module_ln ln WHERE c.id = ln.id AND c.active = 1");
$arrModule = array();
while($rs = $result->fetch()){
	$rs["module_actions"] =  unserialize($rs["module_actions"]);
	$controller->modules['main'][$rs['module_name']] = $rs;
	$controller->modules['data'][$rs['module']][$rs['ln']] = $rs;
	$controller->modules[$rs['ln']]['module'][$rs['module']] = $rs['module_name'];
	$controller->modules[$rs['ln']]['name'][$rs['module']] = $rs['name'];	
	foreach($rs["module_actions"] as $key=>$val){
		$controller->modules[$rs['ln']]['actions'][$rs['module']."_".$key] = $val;
	}
	$tmp = $controller->params;
	$tmp[0] = $rs['module_name'];
	$controller->modules['url'][$rs['module']][$rs['ln']] = count($tmp)?rtrim(implode('/',$tmp),'/'):'';
}
$result->cache();
if($controller->modules['main'][$controller->module]['ln']) $controller->lang = $controller->modules['main'][$controller->module]['ln'];
if(!$controller->params[0]){
	$result = $model->db->query("SELECT `ln` FROM ".$model->prefix."language WHERE `is_default` = 1");
	$data = $result->fetch();
	$controller->lang = $data['ln'];
	$result->cache();
}
if($controller->modules['main'][$controller->module]['module']) $controller->module = $controller->modules['main'][$controller->module]['module'];

?>