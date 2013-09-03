<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}
if ($_POST){
	$msg = "";
	if ($_FILES["file"]["name"]=="")
	{
		$msg = "Please enter file Excel";
	}
	else
	{
		$infofile = pathinfo($_FILES["file"]["name"]);
		if (strtolower($infofile["extension"])!="xls" && strtolower($infofile["extension"])!="xlsx")	
		{
			$msg =  'Not allow extentions file, you can only upload file xlsx, xls';
		}
	}		
	if ($msg==""){
		require_once (_ROOT.'libraries/excel/excel_reader2.php');	
		//$data = new Spreadsheet_Excel_Reader(_UPLOAD."excel/TPMG Inventory.xls");		
		$data = new Spreadsheet_Excel_Reader($_FILES["file"]["tmp_name"]);		
		$arrData = $data->dumptoarray(0);				
		if (!empty($arrData) && is_array($arrData))
		{			
			unset($arrData[1],$arrData[2],$arrData[3]);
			if (count($arrData)>0){
				foreach($arrData as $val){
					switch(trim($val[7])){
						case "Giải nhất":
							$val[7] = 1;
							break;
						case "Giải II":
							$val[7] = 2;
							break;	
						case "Giải III":
							$val[7] = 3;
							break;
						default:
							$val[7] = 4;
							break;
					}
					if ($val[4]!=""){
						$data = array(
							"uid"=>0,
							"username"=>trim($val[4]),
							"contest_name"=>trim($val[2]),
							"contest_desc"=>trim($val[5]),
							"contest_like"=>trim($val[3]),
							"contest_prize"=>trim($val[7]),
							"prize_week"=>1,
						);
						$oClass->insert($data, "winner");
					}
				}
			}
		}
		$msg =  'imported successfully';
	}
}
$tpl->setfile(array(
		'body'=>'tools.import.excel.tpl',
));
$tpl->assign(array("msg"=>$msg));
?>