<?php	
	if(!defined('_ROOT')) {
		exit('Access Denied');
	}	
	header('Content-type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename='.$system->domain.'_'.date('Ymdhis').'.xls');
	$tpl->reset();
	$tpl->setfile(array('body'=>'exporttest.tpl',));
	if($_POST["inputsql"] != "")
	{
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
		$strSql = $_POST["inputsql"];	
		$flag = true;
		$filename = _ROOT.'data/upload/export.txt';
		if (file_exists($filename)) {
			$handle = fopen($filename, 'r');
			$data = fread($handle,filesize($filename));
			$arrContent = json_decode($data,true);
			foreach($arrContent as $key=>$values){	
				if($values["sql"] == $strSql)
					$flag = false;
			}
			$arrContent[] = array(
				"name"=>'Day_'.date('d_m_Y_h_i_s'),
				"ip"=>$_SERVER["REMOTE_ADDR"],
				"date"=>date('d-m-y'),
				"sql"=>$strSql,
			);
			$arrData = json_encode($arrContent);
			if($flag){
				$handle = fopen($filename, 'w') or die('Cannot open file:  '.$filename);
				fwrite($handle, $arrData);			
			}
		}			
		$arrMember = $oClass->getSql($strSql);
		$arrTerm = array();
		while($arrcontent = $arrMember->fetch()){
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
	}
?>