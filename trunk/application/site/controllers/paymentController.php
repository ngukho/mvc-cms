<?php

class paymentController Extends BaseController 
{
	var $_GET_PHONE_LINK = "";
	 
	
	function __construct()
	{
		parent::__construct();
	}
	
	function cron()
	{
		
		$arrCron = array();
		$arrTime = array();
		
		//Khai bao mang thong tin cac cron
		$arrCron[0]["function"] = "cronPayment";
		$arrCron[0]["runTime"] = array("1,2");
		
		$arrCron[1]["function"] = "cronCheckPayment";
		$arrCron[1]["runTime"] = array("20,22");
		
		//Gio sever chay cron
		$hourSever = date("H");
	
		for($i=0; $i<count($arrCron); $i++)
		{
			$arrTime = $arrCron[$i]["runTime"];
			
			for($j=0; $j<count($arrTime); $j++)
			{
				$time = explode(",", $arrTime[$j]);
				
				if($time[0] <= $hourSever && $hourSever <= $time[1]) //Gio sever nam trong khoang gio chay cron
				{
					$function = $arrCron[$i]["function"];
					$this->$function();
				}
			}
		}
	}
	

	
	function cronPayment()
	{
		
	}
	
	function cronCheckPayment()
	{
		
	}
	
	private function soapPayment($phoneNumber)
	{
		// Xu ly goi SOAP service de xu ly thanh toan		
	}
	
	private function updateSendingPhoneNumber()
	{
		$arrPNumbers = explode(";",$this->getSendingPhoneNumber());
		$counter = 0;
		foreach ($arrPNumbers as $phone)
		{
			// Xu ly cap nhat cac so phone da gui SMS
			$counter++;
		}
		// Cap nhat vao database so luong record lay duoc va so luong record da xu ly 
		// count($arrPNumbers) va $counter		 
	}

	private function getSendingPhoneNumber()
	{
		/*
			-- Cap nhat vao payment row voi filename = ten file
			-- Mo ket noi den server Phone
				- Neu ket noi co loi
					* Cap nhat vao payment row voi status la loi ket noi
					* Tra ve chuoi rong cho ham goi
				- Ket noi thanh cong, lay duoc du lieu
					* Kiem tra du lieu neu la chuoi 'nodata'
						* Tra ve chuoi rong cho ham goi
					* Tra ve noi dung lay duoc						
		*/
	}
	

}

?>
