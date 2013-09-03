<?php
/**
 * @Name: Sofresh CMS v1.0
 * @copyright Copyright &copy; 2011 Sofresh 
 */
defined('_ROOT') or die(__FILE__);

$_GET["id"] = $_GET["id"]>0?$_GET["id"]:0;
if($_POST){
	$data= array();
	if(intval($_POST['delete_image'])>0){				
		@unlink(_UPLOAD.$_POST['current_image']);
		$_POST['current_image'] = "";
		$image = "";
	}	
	if ($_FILES["image"]){
		$filename = $_POST["detail"]["fullname"]?str2url($_POST["detail"]["fullname"]."-".$_POST["detail"]["keys"]):time();
		$image = upload('image',_UPLOAD,$filename.'-',$image_exts);
		if ($_POST['delete_image'] || (!empty($icon) && trim($_POST['current_image']) != "")){
			@unlink(_UPLOAD.$_POST['current_image']);				
		}
		
		if(is_array($image)){
			$error .= $image['msg'].'<br />';
		}
		else{
			if (empty($image)){
				$_POST["detail"]["image"] = $_POST['current_image'];
			}
			else{
				@unlink(_UPLOAD.$_POST['current_image']);
				$_POST["detail"]["image"] = $image;
			}
		}
		/*if($image){
			$oImg = new image(_UPLOAD.$icon);
			$oImg->resize($cfg_type['main_icon']['w'],$cfg_type['main_icon']['h'],_UPLOAD.$icon);
		}*/
		
	}
	$data = $_POST["detail"];
	if ($_GET["id"]>0){		
		$oClass->update($_GET['id'],$data);
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
		}else{
			$_SESSION['updated'] = true;
		}
		if($warning) $result['msg'] = $warning;
		$hook->redirect('?'.http_build_query($result,NULL,'&'));
	}
}
$tpl->setfile(array(
	'body'=>'member_gallery/member_gallery.update.tpl',
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
$listdata["image_url"] = ($listdata["image"]!="" && file_exists(_UPLOAD.$listdata["image"]))?'<a class="mb" href="'._UPLOAD.$listdata["image"].'"><img width="250" src="'._UPLOAD.$listdata["image"].'" /></a>':"No Picture";
$listdata["active"] = $listdata["active"]>0?'checked="checked"':'';
$tpl->merge($listdata,'user');
?>