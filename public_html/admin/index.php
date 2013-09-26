<?php

try
{
	// define the site path __SITE_PATH : c:\xampp\htdocs\adv_mvc
	define ('__SITE_PATH', realpath(dirname(dirname(dirname(__FILE__)))));
	
	// __SITE_URL : /adv_mvc/
 	define ('__SITE_URL', dirname(str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME'])).'/');
 	
	// __BASE_URL : /adv_mvc/admin/
 	define ('__BASE_URL', str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']));
 	
 	define ('__ASSET_URL', __BASE_URL);
 	define ('__TEMPLATE_URL', __ASSET_URL.'flaty_template/');
 	
 	define ('__IMAGE_URL', __ASSET_URL.'images/');
 	define ('__CSS_URL', __ASSET_URL.'css/');
 	define ('__JS_URL', __ASSET_URL.'js/');
 	
 	define ('__PUBLIC_JS_URL', __SITE_URL.'assets/js/');
 	
	// the application directory path 
	define ('__APP_PATH', __SITE_PATH.'/admin');	
	define ('__VIEW_PATH', __APP_PATH.'/views');	
	define ('__LAYOUT_PATH', __SITE_PATH.'/layouts');	
	define ('__HELPER_PATH', __SITE_PATH.'/application/helpers');
	define ('__CONFIG_PATH', __SITE_PATH.'/application/config');
	
	define ('__UPLOAD_DATA_PATH', realpath(dirname(dirname(__FILE__))) . '/data/upload/');	
		
	/*** include the helper ***/
 	$_autoload_helpers = array();
 	$lang = NULL;
 	$config = NULL;
	
	require __SITE_PATH . '/startup.php';
	
	$config->config_values['application']['default_uri'] = "dashboard/member/login";
	
 	/*** a new registry object ***/
 	$registry = new Registry();
 	
 	// Session
 	$oSession = new Session();
 	$registry->oSession = $oSession;
 	
 	$configureModule = new Base_ConfigureModule();
 	$configure_mod = $configureModule->configure_mod();
 	$configure_mod['default_global_lang'] = $lang;
 	$registry->oConfigureModule = $configure_mod; 	
 	
	// Response
	$response = new Response();
	$response->addHeader('Content-Type: text/html; charset=utf-8');
	$registry->oResponse = $response; 
 	
	// Config
	$registry->oConfig = $config;

	// Parameter
	$parameter = new Parameter();
	$registry->oParameter = $parameter;	
	
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
