<?php

$tpl->setfile(array(
	'body'=>'content.accessdenie.tpl',
));
$request['switchorder'] =  $current_order=='DESC'?'ASC':'DESC';
$request['breadcrumb'] = $breadcrumb->parse();
$request['parentid'] = intval($request['parentid']);
$request['msg'] = stripslashes($request['msg']);
$tpl->assign($request);
$action = array();

?>