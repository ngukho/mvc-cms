<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

$userid = $_SESSION['admin_login']['id'];
if($access=='ALL' && $request['id']) $userid = $request['id'];

if($_POST){
	
	$data = array();
	$data['skin'] = $_POST['skin'];
	$data['icon'] = $_POST['icon'];
	$arr = array(
		'fullname'=>$_POST['fullname'],
		'username'=>$_POST['username'],
		'group'=>join($_POST["namegroup"] , ','),
		'data'=>serialize($data),
	);
	
	if($do=='new' && $access == 'ALL'){
		$arr['permission'] = 'ALL';
		$lastid = $oClass->insert($arr);
		$oMaster->user_log('Added user: '.$_POST['fullname']);
	}else{
		$oClass->update($userid,$arr);
		$log_act = 'Update userId: '.$userid;
		if($userid == $_SESSION['admin_login']['id']){
			$_SESSION['admin_login']['fullname'] = $arr['fullname'];
			$log_act = 'Update profile';
		}
		$oMaster->user_log($log_act);
	}

	
	
	
	// refresh
	
	$query_string = $_SERVER['QUERY_STRING'];
	parse_str($query_string,$result);
	unset($result['mod'],$result['act'],$result['id'],$result['do']);
	if($do=='') $result['msg'] = 'Data has been updated. You must logout and login again to see what you updated!';
	$result['mod'] = $access=='ALL'?'user':'home';
	
	$hook->redirect('?'.http_build_query($result));
}


$tpl->setfile(array(
	'body'=>'user.update.tpl',
));


if($do=='new'){
	$cat_ln = array();
	$breadcrumb->assign("","New");
	$u = array('setting'=>array());
	$arrGroup = $oClass->viewGroup();
	while ($rs = $arrGroup->fetch()) {
		$tpl->assign($rs , "listGroup");
	}	
	
}else{
	$cat = $oClass->get($userid);
	$u = $cat->fetch();
	$u['setting'] = $u['data']?unserialize($u['data']):array();
	$tpl->assign($u);
	$breadcrumb->assign("","Edit",$request['bread']);
	if($userid == $_SESSION['admin_login']['id']){
		$breadcrumb->reset();
		$breadcrumb->assign('','CMS');
		$breadcrumb->assign('','Profile');
	}
	
	$group = explode(",", $u["group"]);
	$arrGroup = $oClass->viewGroup();
	$gid = 0;
	while ($rs = $arrGroup->fetch()) {
		for($i = 0; $i < count($group); $i++)
		{
			if($group[$i] == $rs["id"])
				$gid = 'checked="checked"' ;
			else
				$gid = '' ;
		}		
		$rs["checked"] = $gid;
		$tpl->assign($rs , "listGroup");		
	}	
}
$dir = dir($tpl->tpl_dir."skins/");
while ($rs = $dir->read()) {
	if($rs != '.' && $rs != '..'){
		if(filetype($dir->path.$rs) == 'dir'){
			$arr = array();
			$arr['value'] = $rs;
			$arr['selected'] = $rs == $u['setting']['skin']?'selected':'';
			$arr['name'] = ucfirst($rs);
			$tpl->assign($arr,'skin');
		}
	}
}
$dir = dir($tpl->tpl_dir."images/");
while ($rs = $dir->read()) {
	if($rs != '.' && $rs != '..'){
		if(filetype($dir->path.$rs) == 'dir' && substr($rs,0,6) == 'icons_'){
			$arr = array();
			$arr['value'] = substr($rs,6);
			$arr['selected'] = substr($rs,6) == $u['setting']['icon']?'selected':'';
			$arr['name'] = ucfirst(substr($rs,6));
			$tpl->assign($arr,'icon');
		}
	}
}
$request['breadcrumb'] = $breadcrumb->parse();

$tpl->assign($request);




?>