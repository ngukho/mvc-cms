<?php
/**
 * @Name: CaoBox v1.0
 * @author LinhNMT <w2ajax@gmail.com>
 * @link http://code.google.com/p/caobox/
 * @copyright Copyright &copy; 2009 phpbasic
 */
 
defined('_ROOT') or die(__FILE__);

	$location = array("Diamond"=>"Diamond Plaza","ParksonLTT"=>"Parkson Lê Thánh Tôn","ParksonHV"=>"Parkson Hùng Vương","NowZone"=>"NowZone");
	$tpl->setfile(array(
		'body'=>'member_gallery/member_gallery.detail.tpl',
	));
	$id = intval($_GET["id"]);
	$arrsex = array(0=>"Không xác định",1=>"Nữ",2=>"Nam");
	if ($id > 0)
	{
		$result = $oClass->get($id);
		$listdetail = $result->fetch();		
		$listdetail["newsletter"] = $listdetail["newsletter"]==1?"Yes":"No";
		unset($_GET["act"],$_GET["id"]);
		$listdetail["back"] = "?".http_build_query($_GET,'','&');
		$listdetail["avatar"] = ($listdetail["avatar"]!="" && file_exists(_UPLOAD.$listdetail["avatar"]))?'<a class="mb" href="'._UPLOAD.$listdetail["avatar"].'"><img width="100" src="'._UPLOAD.$listdetail["avatar"].'" /></a>':"No Picture";
		$listdetail["contest_pic"] = ($listdetail["contest_pic"]!="" && file_exists(_UPLOAD.$listdetail["contest_pic"]))?'<a class="mb" href="'._UPLOAD.$listdetail["contest_pic"].'"><img width="250" src="'._UPLOAD.$listdetail["contest_pic"].'" /></a>':"No Picture";
		$listdetail["sex"] = $arrsex[intval($listdetail["sex"])];
		if (intval($listdetail["active"])<=0){
			$tpl->box("nhanqualocation");
			foreach($location as $key=>$val){
				$data = array();
				$data["name"] = $val;
				$data["id"] = $key;			
				$tpl->assign($data, "location");
			}
		}
		else{
			$tpl->box("nhanquaroi");			
			$temid = $listdetail["worked"];
			$listdetail["worked"] = $location[$temid];			
		}
		$tpl->merge($listdetail, "user");
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