<?php

defined('_ROOT') or die(__FILE__);

$oClass = new ClassModel;
$breadcrumb = new breadcrumb;
extract($_GET);
$request = $_GET;
$request['type'] = intval($type);
$request['parentid'] = intval($parentid);
$request['query_string'] = '?'.$_SERVER['QUERY_STRING'];
$request['http_referer'] = $_SERVER['HTTP_REFERER'];


?>