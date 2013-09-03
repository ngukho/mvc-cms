<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

if($_POST){
	unset($_POST['Submit']);
	$arr = array(
		'permission'=>serialize($_POST),
	);
	$oClass->update($request['id'],$arr);
	$oMaster->user_log('Set permission for userId: '.$request['id']);
	// refresh
	$query_string = $_SERVER['QUERY_STRING'];
	parse_str($query_string,$result);
	unset($result['act'],$result['id'],$result['c']);
	$hook->redirect('?'.http_build_query($result));
}


$tpl->setfile(array('body'=>'user.permission.tpl'));

$result = $oClass->get($request['id']);
$user = $result->fetch();
if($user['permission'] == 'ALL') $acl = 'ALL';
else $acl = unserialize($user['permission']);
array_pop($MenuName);
for($i=0;$i<count($MenuName);$i++){
	$actions = '';
	foreach($MenuLink[$i] as $j=>$arr) {		
	 	$actions .= ' <input class="no_width" type="checkbox"  id="chk_'.$i.$j.'" name="act['.$i.'][]" value="true"'.($acl=='ALL' || $acl['act'][$i][$j]?'checked':'').'> <label for="chk_'.$i.$j.'"> '.$arr['name'].'</label><br />';
	}
	$tpl->assign(array(
		'index'=>$i,
		'checked'=>$acl=='ALL' || $acl['act'][$i]['check']?'checked':'',
		'section'=>$MenuName[$i]["name"],
		'actions'=>$actions,
		'new'=>$acl["module_act"][$i]["new"]?'checked':'',
		'edit'=>$acl["module_act"][$i]["edit"]?'checked':'',
		'delete'=>$acl["module_act"][$i]["delete"]?'checked':'',
		'filteruser'=>$acl["module_act"][$i]["filteruser"]?'checked':'',
		'approved'=>isset($acl["module_act"][$i]["approved"])?'checked':'',
		
	),'permission');
}
$action = array();
$all_content = array();
$all_content[intval($acl['all_content']).'_checked'] = 'checked';
if($acl == 'ALL'){
	$action['action']['new'] = $action['action']['edit'] = $action['action']['deleted'] = 'checked';
	
}
else
{
	$action['action'] = $acl["action"];
}
$breadcrumb->assign('','Permission');
$request['breadcrumb'] = $breadcrumb->parse();

$tpl->assign($request);
$tpl->merge($action['action'],'action');
$tpl->merge($all_content,'all_content');

?>