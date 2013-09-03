<?php
/**
 * @Name: CaoBox v1.0
 * @author LinhNMT <w2ajax@gmail.com>
 * @link http://code.google.com/p/caobox/
 * @copyright Copyright &copy; 2009 phpbasic
 */
defined('_ROOT') or die(__FILE__);

	if ($_POST){
		if ($_POST["codeinput"]!="" && $_POST["location"]!=""){
			$data["active"] = 1;
			$data["worked"] = $_POST["location"];	
			$data["purchased"] = $_POST["codeinput"];	
			$obj= $oClass->view(" purchased='".$data["purchased"]."' and active=1",0,1);
			if ($obj->num_rows()<=0){	
				$oClass->update($request['id'],$data);
				//die("Nhận mã code thành công");
			}
			else{
				echo "-1"; exit;
			}
		}
	}
	else{
		$oClass->active($request['id']);
	}
	//die('1');
	// refresh
	$query_string = $_SERVER['QUERY_STRING'];
	parse_str($query_string,$result);
	unset($result['act'],$result['id'],$result['c']);
	$hook->redirect('?'.http_build_query($result,'','&'));
	//die('?'.http_build_query($result,'','&'));
?>