<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}
ob_start();
phpinfo();
$html = ob_get_contents();
ob_end_clean();
$tpl->setfile(array(
	'body'=>'tools.serverinfo.tpl',
));
preg_match('/<body[^>]*>(.*).<\/body>/is',$html,$data);
$request['phpinfo'] = $data[1];
$tpl->assign($request);

?>