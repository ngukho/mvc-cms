<?php	
	if(!defined('_ROOT') || $_SERVER['REMOTE_ADDR'] != $cfg['configip']) {
		exit('Access Denied');
	}
	$filename = _ROOT.'data/upload/export.txt';
	if (!file_exists($filename)) {
		$handle = fopen($filename, 'w') or die('Cannot open file:  '.$filename);
		 fclose($handle);
	}
	
	if($_POST)
	{
		if($_POST["sql"] != "")
		{
			$refresh = '?'.http_build_query($_GET);
			if(intval($_SESSION["admin_login"]["id"]) == 0){
				echo json_encode(array("id"=>0, "content"=>$refresh));exit;	}
			if (get_magic_quotes_gpc()) {
			  function stripslashes_deep($value)
			  {
			   	$value = is_array($value) ?
				array_map('stripslashes_deep', $value) :
				stripslashes($value);			 
			   	return $value;
			  }			 
			  $_POST = array_map('stripslashes_deep', $_POST);
			}
			$sql = $_POST["sql"];
			$sql = strtolower($sql);
			if(strpos($sql,'drop') !== false || strpos($sql,'truncate') !== false || strpos($sql,'delete') !== false){
				echo json_encode(array("id"=>1, "content"=>'CAN NOT DROP OR DELETE TABLE'));exit;	
			}			
			$arrMember = mysql_query($sql);			
			if (!$arrMember) {
				echo json_encode(array("id"=>1, "content"=>'Invalid query: ' . mysql_error()));exit;	
			}
			$arrTerm = array();
			while($arrcontent = mysql_fetch_assoc($arrMember)){
				$arrTerm[] = $arrcontent;		
			}
			$arrHeader = array_keys($arrTerm[0]);
			foreach($arrTerm as $key=>$values){	
				$tpl->assign($arrTerm[$key], "listBody");
				for($j=0; $j< sizeof($arrHeader); $j++)	{			
					 $tpl->assign(array("data"=>$arrTerm[$key][$arrHeader[$j]]), "listBody.sub");
				}
			}
			for($i=0; $i< sizeof($arrHeader); $i++)	
				$tpl->assign(array($arrHeader[$i],"header"),"listHeader");	
			$tpl->reset();
			$tpl->setfile(array('body'=>'ajaxexec.tpl',));	
			$MainContent = $tpl->parse();
			echo json_encode(array("id"=>1, "content"=>$MainContent));exit;	
			
			$query_string = $_SERVER['QUERY_STRING'];
			parse_str($query_string,$result);
			unset($result['mod'],$result['act'],$result['id'],$result['do']);
			if($do=='') $result['msg'] = 'Data has been updated. You must logout and login again to see what you updated!';
			$result['mod'] = $access=='ALL'?'user':'home';
			
			$hook->redirect('?'.http_build_query($result));
		}
		else
		{
			echo json_encode(array("id"=>0, "content"=>"QUERY NOT NULL"));exit;	
			
			$query_string = $_SERVER['QUERY_STRING'];
			parse_str($query_string,$result);
			unset($result['mod'],$result['act'],$result['id'],$result['do']);
			if($do=='') $result['msg'] = 'Data has been updated. You must logout and login again to see what you updated!';
			$result['mod'] = $access=='ALL'?'user':'home';
			
			$hook->redirect('?'.http_build_query($result));
		}
	}	
	$tpl->setfile(array('body'=>'exec.tpl',));	
	$arrTable = $oClass->getSql("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='".$GLOBALS['cfg']['name']."'");
	while($arrcontent = $arrTable->fetch()){
		$tpl->assign($arrcontent, "listTable");		
	}
	
	if (file_exists($filename)) {
		$handle = fopen($filename, 'r');
		$data = fread($handle,filesize($filename));
		
		if($data != ""){			
			$arrContent = json_decode($data,true);
			foreach($arrContent as $key=>$values){	
				$tpl->assign($values, "listSql");		
			}
		}
	}
	
	
/*	$arrSql = $oClass->getExport();
	while($arrcontent = $arrSql->fetch()){
		$tpl->assign($arrcontent, "listSql");		
	}*/
?>