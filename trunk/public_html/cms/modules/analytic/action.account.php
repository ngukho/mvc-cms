<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}



if($_SESSION['ga']['token']){
	$ga = new gapi($_SESSION['ga']['ga_emai'],$_SESSION['ga']['ga_pasw']);
	$ga->requestAccountData();
	foreach($ga->getResults() as $result)
	{
	  echo $result . ' (' . $result->getProfileId() . ")<br />";
	}
}else{
	echo '0';
}
exit();

?>
