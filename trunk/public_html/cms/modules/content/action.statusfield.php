<?php

defined('_ROOT') or die(__FILE__);
	$aField = $oMaster->fields('content');
	$aStatusField = array();
	foreach($cfg_type['main_fields'] as $code=>$info) if($info['chose'] && $info['type'] == 'status'){
		if($code==$request['field']) $aStatusField = $info;
	}
	
	$result = $oClass->get($request['id']);
	$content = $result->fetch();
	//echo $content[$request['field']];exit();
	if(in_array($request['field'],$aField)){
		$msg = '';
		if($aStatusField['number'] >0 && $content[$request['field']] == 0){
			if($aStatusField['number']==1){
				$data = array();
				$data[$request['field']] = 0;
				$oClass->db->update($oClass->prefix."content",array($request['field']=>0)," type = ".intval($content['type'])." AND catid = ".intval($content['catid']));
				$oClass->db->update($oClass->prefix."content",array($request['field']=>1)," id = ".intval($request['id']));
			}else{
				$cond = 0;
				if($content[$request['field']]) $cond = 1;
				$result = $oClass->db->query("SELECT COUNT(id) total FROM ".$oClass->prefix."content WHERE type = ".intval($content['type'])." AND catid = ".intval($content['catid'])." AND ".$request['field']." = 1");
				$data  = $result->fetch();
				if($data['total'] < $aStatusField['number']) $cond = 1;
				else $msg = 'You can set "'.$aStatusField['name'].'" in ON over '.$aStatusField['number'];
				if($cond) $oClass->statusfield($request['field'],$request['id']);
			}
		}else{
			$oClass->statusfield($request['field'],$request['id']);
		}
	}else{
		$msg = 'The field <strong>'.$request['field'].'</strong> don\'t exitst, please contact webadmin.' ;
	}
	clear_sql_cache();
	$query_string = $_SERVER['QUERY_STRING'];
	parse_str($query_string,$result);
	$mod = $result['p'];
	unset($result['act'],$result['id'],$result['c'],$request['msg'],$request['field']);
	$result['msg'] = $msg;
	$hook->redirect('?'.http_build_query($result,NULL,'&'));
	// refresh
?>