<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}
	$aField = $oMaster->fields('options');
	$aStatusField = array();
	foreach($cfg_type['main_fields'] as $code=>$info) if($info['chose'] && $info['type'] == 'status'){
		if($code==$request['field']) $aStatusField = $info;
	}
	
	$result = $oClass->get($request['id']);
	$content = $result->fetch();
	//echo $content[$request['field']];exit();
	//if(in_array($request['field'],$aField)){
		$msg = '';
		$oClass->statusfield($request['field'],$request['id']);
	//}else{
		$msg = 'The field <strong>'.$request['field'].'</strong> don\'t exitst, please contact webadmin.' ;
	//}
	clear_sql_cache();
	$query_string = $_SERVER['QUERY_STRING'];
	parse_str($query_string,$result);
	$mod = $result['p'];
	unset($result['act'],$result['id'],$result['c'],$request['msg'],$request['field']);
	$result['msg'] = $msg;
	$hook->redirect('?'.http_build_query($result,NULL,'&'));
	// refresh
?>