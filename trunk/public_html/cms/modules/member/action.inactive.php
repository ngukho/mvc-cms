<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}
	$result = $oConfigure->view();
	while($rs = $result->fetch()) $cfg[$cfg['code']] = $cfg['value'];
	
	//print_r($cfg);
	$result = $oClass->get($request['id']);
	$data = $result->fetch();
	
	//print_r($data);
	/*if($data['active'] == 1){
		$oClass->active($request['id']);
		//echo $data['email'];exit();
		$email = new Email($data['email'],'Bellavita -  Email is block','inactivate.tpl');
		$email->connect($cfg);
		$email->assign($data);
		if($email->send()){
			die('1');
		}else{
			die('['.$data['id'].'-'.$data['email'].']Current mail server  cannot this email, please try again!');
		}
	}*/
	
	//die('0');
	// refresh
	$query_string = $_SERVER['QUERY_STRING'];
	parse_str($query_string,$result);
	unset($result['act'],$result['id'],$result['c']);
	$hook->redirect('?'.http_build_query($result,'','&'));
?>