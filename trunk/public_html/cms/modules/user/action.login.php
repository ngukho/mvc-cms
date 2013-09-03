<?php


if(!defined('_ROOT')) {
	exit('Access Denied');
}
if($_SESSION['admin_login']) $hook->redirect('./');
$msg = array();
if($_POST){
	$username = addslashes($_POST['username']);
	$password = addslashes($_POST['password']);
	$result = $oClass->view(" `username` = '$username' AND `password` = MD5('$password')");
	if($result->num_rows()){
		$arrContent = $result->fetch();
		$arrGroup = $oClass->viewGroup(" id in (".$arrContent["group"].")");	
		$arrTerm = array();
		$temp  =array();
		$permissionadmin = '';
		while($arrResult = $arrGroup->fetch())
		{			
			if($arrResult["permission"] == 'ALL')
			{
				$permissionadmin = 'ALL';
				break;
			}
			else
			{
				$arrTerm = unserialize($arrResult["permission"]);
				if (is_array($arrTerm["action"])){
					foreach($arrTerm["action"] as $key=>$val){
						$temp["action"][$key] = $val?$val:$temp["action"][$key];
					}
				}
				if (is_array($arrTerm["act"])){
					foreach($arrTerm["act"] as $key=>$val){
						foreach($val as $k=>$item){
							$temp["act"][$key][$k] = $item?$item:$temp["act"][$key][$k];
						}
					}
				}	
				if (is_array($arrTerm["module_act"])){
					foreach($arrTerm["module_act"] as $key=>$val){
						foreach($val as $k=>$item)
							$temp["module_act"][$key][$k] = $item?$item:$temp["module_act"][$key][$k];
					}
				}
			}
		}		
		$permission = serialize($temp);
		if($permissionadmin == 'ALL')
			$permission = 'ALL';
		$arrContent["permission"] = $permission;
		$_SESSION['admin_login'] = $arrContent;
		$oMaster->user_log('Logged in');
		$hook->redirect('./');
	}else{
		$msg['error'] = 'Username/Password is invalid!';
	}
	
	
}

$tpl->reset();

$tpl->setfile(array(
	'body'=>'user.login.tpl',
));
$tpl->assign($msg);

?>