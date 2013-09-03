<?php 
	if(!defined('_ROOT')) {
	exit('Access Denied');
}
	$tpl->reset();	
	$id = intval($_GET["id"]);
	if($_GET["sk"] == "listteam")
	{
		$tpl->setfile(array('body'=>'member.listdetail.tpl',));
		$arrdetail = $oClass->get_detail($id);
		while($arrcontent = $arrdetail->fetch())
		{
			$tpl->assign($arrcontent, "listteam");
		}
	}
	else
	{
		$tpl->setfile(array('body'=>'member.detail.tpl',));
		if ($id > 0)
		{
			$result = $oClass->get($id);
			$listdetail = $result->fetch();
			$listdetail["status"] = $listdetail["confirm"] == 1 ? "Đã xác thực" : "Chưa xác thực";
			$tpl->merge($listdetail, "user");
		}		
	}
	$breadcrumb->reset();
	$menu = explode('.',$_SESSION['cms_menu']);
	$breadcrumb->assign("",$MenuName[$menu[0]]);
	$level = $MenuLink[$menu[0]][$menu[1]];
	$breadcrumb->assign($level['link'],$level['name']);	
	
	$request['breadcrumb'] = $breadcrumb->parse();
	$tpl->assign($request);
	$action = array();

?>