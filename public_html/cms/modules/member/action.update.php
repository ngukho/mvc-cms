<?php
/**
 * @Name: Sofresh CMS v1.0
 * @copyright Copyright &copy; 2011 Sofresh 
 */
if(!defined('_ROOT')) {
	exit('Access Denied');
}

$_GET["id"] = $_GET["id"]>0?$_GET["id"]:0;
$request = array();
if($_POST){
	$data= array();
	$data = $_POST["detail"];
	$where = " and email='".$data["email"]."' and id <>  ".intval($_GET["id"]." and keys <> CONTACT_PAGE");
	$arrcheck = $oClass->CheckExist($where);	
	if ($arrcheck["id"]>0){
		$request["strMSG"] = '<div class="error">The email is exist in database.</div>';
	}
	else{
		if ($_GET["id"]>0){					
			$oClass->update($_GET['id'],$data);			
			$request["strMSG"] = '<div class="error succe">'.$languages['data_updated'].'</div>';			
			///print_r($oClass);exit;	
			//$result['id'] = $_GET["id"];	
		}
		else{
			$_GET["id"] =  $oClass->insert($data);			
		}
		if(!$error){
			$query_string = $_SERVER['QUERY_STRING'];
			parse_str($query_string,$result);
		
			if($_POST['back']){
				unset($result['act'],$_GET["id"],$result['do']);
				if($warning) $result['msg'] = $warning;
				$hook->redirect('?'.http_build_query($result,NULL,'&'));
			}else{
				$_SESSION['updated'] = true;
			}			
		}
	}
}
$tpl->setfile(array(
	'body'=>'member.update.tpl',
));
$result = $oClass->get($_GET["id"]);
$listdata = $result->fetch();
foreach($arrsex as $key=>$val){
	$data = array();
	$data["selected"] = $listdata["sex"]==$key?'selected="selected"':'';
	$data["name"] = $val;
	$data["id"] = $key;			
	$tpl->assign($data, "listsex");

}
$listdata["avatar"] = ($listdetail["avatar"]!="" && file_exists(_UPLOAD.$listdetail["avatar"]))?'<a href="'._UPLOAD.$listdetail["avatar"].'"><img src="'._UPLOAD.$listdetail["avatar"].'" /></a>':"";
$listdata["active"] = $listdata["active"]>0?'checked="checked"':'';
$tpl->merge($listdata,'user');
$tpl->assign($request);
?>