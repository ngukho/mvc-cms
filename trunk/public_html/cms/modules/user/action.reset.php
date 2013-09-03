<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}
$userid = $_SESSION['admin_login']['id'];
if($access=='ALL' && $request['id']) $userid = $request['id'];
if($_POST){
	if(!$_POST['password'] || $_POST['password']!= $_POST['confirm']) $request['msg'] = 'New password &amp; Cofirm password are must same';
	
	if(!$request['msg']){
		$arr = array(
			'password'=>md5($_POST['password']),
		);
		
/*		if($do=='new' && $access == 'ALL'){
			$arr['permission'] = 'ALL';
			$lastid = $oClass->insert($arr);
		}else{
*/			
		$oClass->update($userid,$arr);
		$log_act = 'Reset password for userId: '.$userid;
		if($userid == $_SESSION['admin_login']['id']){
			$log_act = 'Reset password';
		}
		$oMaster->user_log($log_act);
			
		//}
	}

	
	
	
	// refresh
	if($userid == $_SESSION['admin_login']['id']){
		if(!$request['msg']) $request['msg'] = 'Your password has been updated!';
	}elseif(!$request['msg']){
		$query_string = $_SERVER['QUERY_STRING'];
		parse_str($query_string,$result);
		unset($result['mod'],$result['act'],$result['id'],$result['do']);
		$result['mod'] = $access=='ALL'?'user':'home';
		$result['msg'] = 'The password has been updated';
		$hook->redirect('?'.http_build_query($result));
	}
}


$tpl->setfile(array(
	'body'=>'user.reset.tpl',
));


if($do=='new'){
	$cat_ln = array();
	$breadcrumb->assign("","New");
}else{
	$cat = $oClass->get($userid);
	$tpl->assign($cat->fetch());
	$breadcrumb->assign("","Reset password",$request['bread']);
}
if($userid == $_SESSION['admin_login']['id']){
	$breadcrumb->reset();
	$breadcrumb->assign('','CMS');
	$breadcrumb->assign('','Reset password');
}

$request['breadcrumb'] = $breadcrumb->parse();

$tpl->assign($request);




?>