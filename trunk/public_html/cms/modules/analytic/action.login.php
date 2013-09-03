<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}


$ga = new gapi($cfg['ga_email'],$cfg['ga_pasw'],isset($_SESSION['ga'])?$_SESSION['ga']:NULL);
if($_SESSION['ga']['token'] = $ga->getAuthToken()){
	$_SESSION['ga']['ga_emai'] = $cfg['ga_email'];
	$_SESSION['ga']['ga_pasw'] = $cfg['ga_pasw'];	
	echo '1';
}else{
	echo '0';
}
exit();
?>
