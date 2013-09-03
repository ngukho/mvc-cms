<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

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
		$result = $oClass->active($request['id']);
		if($result){
			$detail = $oClass->get($request['id'])->fetch();						
				if ($_GET["enabled"]==0){
					$detail["website"] = $system->domain.$system->project;
					if ($detail["email"]!=""){	
						$detail["message"] = "The account is active. Now you can login <a href=\"".$detail["website"]."index.php/tenant-resources\">Login</a>";
						//$detail["message"] = "The account is Blocked";
						$email = new Email($detail["email"],'TPMG - Your account is active','alert_user_active.tpl');
						$email->connect($cfg);	
						$email->tpl->assign($detail);
						$email->Send();
					}								
			}
		}
	}
	//die('1');
	// refresh
	$query_string = $_SERVER['QUERY_STRING'];
	parse_str($query_string,$result);
	unset($result['act'],$result['id'],$result['c']);
	$hook->redirect('?'.http_build_query($result,'','&'));
	//die('?'.http_build_query($result,'','&'));
?>