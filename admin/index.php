<?php

try
{
	// define the site path __SITE_PATH : c:\xampp\htdocs\adv_mvc
	define ('__SITE_PATH', realpath(dirname(dirname(__FILE__))));
	
	// __SITE_URL : /adv_mvc/
 	define ('__SITE_URL', dirname(str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME'])).'/');
 	
	// __BASE_URL : /adv_mvc/admin/
 	define ('__BASE_URL', str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']));
 	
 	define ('__IMAGE_URL', __SITE_URL.'images/');
 	define ('__CSS_URL', __SITE_URL.'css/');
 	define ('__ASSET_URL', __SITE_URL.'assets/');
 	
	// the application directory path 
	define ('__APP_PATH', __SITE_PATH.'/admin');
	define ('__VIEW_PATH', __APP_PATH.'/views');	
	define ('__LAYOUT_PATH', __SITE_PATH.'/layouts');
	define ('__HELPER_PATH', __SITE_PATH.'/application/helpers');
	
	require __SITE_PATH . '/lib/core/FrontController.class.php';
	require __SITE_PATH . '/lib/core/IController.class.php';
	require __SITE_PATH . '/lib/core/BaseController.class.php';
	require __SITE_PATH . '/lib/core/Request.class.php';
	require __SITE_PATH . '/lib/core/Response.class.php';
	require __SITE_PATH . '/lib/core/View.class.php';
	require __SITE_PATH . '/lib/core/Registry.class.php';
	require __SITE_PATH . '/lib/core/Config.class.php';
	require __SITE_PATH . '/lib/core/Model.class.php';
	require __SITE_PATH . '/lib/core/MvcException.class.php';
	
	// Load config files
	require __SITE_PATH . '/application/config/constants.php';
	
 	/*** include the helper ***/
 	$autoload_helpers = array('common');
 	
 	/*** registry auto load ***/
	spl_autoload_register(null, FALSE);
	spl_autoload_extensions('.php, .class.php, .lang.php, .model.php');

	// model loader
	function modelLoader($class)
	{
		$filename = $class . '.model.php';
		$file = __SITE_PATH . "/application/models/$filename";
		if (file_exists($file) == FALSE)
		{
			return FALSE;
		}
		include_once $file;
	}

	// autoload libs
	function libLoader($class)
	{
		$filename = $class . '.class.php';
		$file = __SITE_PATH . "/lib/$filename";
		if (file_exists($file) == FALSE)
		{
			return FALSE;
		}
		include_once $file;
	}
	
	spl_autoload_register('libLoader');
	spl_autoload_register('modelLoader');
	
	// Load helper function
	function helperLoader($functions)
	{
		if(!is_array($functions))
			$functions = array($functions);
			
		foreach ($functions as $function)
		{
			$file_path = __HELPER_PATH . "/{$function}.helper.php";
			if(file_exists($file_path))
				include_once $file_path;
		}			
	}
	helperLoader($autoload_helpers);
	/* ---------------------------------------------------------- */
	
	// Load language  
	$config = Config::getInstance();
	$lang = $config->config_values['application']['language'];
	$filename = strtolower($lang) . '.lang.php';
	$file = __APP_PATH . '/lang/' . $filename;
	include $file;

	if (!function_exists('class_alias')) {
	    function class_alias($original, $alias) {
	        eval('abstract class ' . $alias . ' extends ' . $original . ' {}');
	    }
	}
	// alias the lang class
	class_alias($lang,'Lang');
	/* -------------- */
	
	// set the timezone
//	date_default_timezone_set($config->config_values['application']['timezone']);

	/*** set error handler level to E_WARNING ***/
	set_error_handler('_exception_handler', $config->config_values['application']['error_reporting']);
	
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
