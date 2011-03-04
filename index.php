<?php

try
{
	// define the site path __SITE_PATH : c:\xampp\htdocs\adv_mvc
	define ('__SITE_PATH', realpath(dirname(__FILE__)));
	
	// __SITE_URL : /adv_mvc/
 	define ('__SITE_URL', str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']));
	
	// __BASE_URL : /adv_mvc/
 	define ('__BASE_URL', __SITE_URL);
 	
 	define ('__IMAGE_URL', __SITE_URL.'images/');
 	define ('__CSS_URL', __SITE_URL.'css/');
 	define ('__ASSET_URL', __SITE_URL.'assets/');
 	
	// the application directory path 
	define ('__APP_PATH', __SITE_PATH.'/application');
	define ('__VIEW_PATH', __APP_PATH.'/views');	
	define ('__LAYOUT_PATH', __SITE_PATH.'/layouts');
	define ('__HELPER_PATH', __APP_PATH.'/helpers');
	
	/*** include the helper ***/
 	$_autoload_helpers = array();
	
	require __SITE_PATH . '/startup.php';
	
 	/*** a new registry object ***/
 	$registry = new Registry();
 	
 	// Session
 	$oSession = new Session();
 	$registry->oSession = $oSession;
 	
	// Response
	$response = new Response();
	$response->addHeader('Content-Type: text/html; charset=utf-8');
	$registry->oResponse = $response; 
 	
	$registry->oConfig = $config; 
	
	// Initialize the FrontController
	$front = FrontController::getInstance();
	$front->setRegistry($registry);
	
	/*
		// Cau hinh cho cac action nay chay dau tien 
	$front->addPreRequest(new Request('run/first/action')); 
	$front->addPreRequest(new Request('run/second/action'));
	*/
	
	$front->dispatch();
	
	// Output
	$response->output();	
}
catch(MvcException $e)
{
	if($config->config_values['application']['display_errors'])
		show_error($e->getMessage());
	else 				
		//show a 404 page here
		show_404();
}
catch(Exception $e)
{
	die('FATAL : '.$e->getMessage().' : '.$e->getLine());
}
