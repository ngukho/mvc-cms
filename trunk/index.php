<?php
session_start();

try
{
	// define the site path __SITE_PATH : c:\xampp\htdocs\adv_mvc
	$site_path = realpath(dirname(__FILE__));
	define ('__SITE_PATH', $site_path);
	
	// __BASE_URL : /adv_mvc/
 	define ('__BASE_URL', str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']));
 	
	// the application directory path 
	define ('__APP_PATH', __SITE_PATH.'/application');

 	/*** include the helper ***/
	include __SITE_PATH . '/lib/' . 'html_helper.php';
	
	spl_autoload_register(null, false);
	spl_autoload_extensions('.php, .class.php, .lang.php, .model.php');

	// model loader
//	function modelLoader($class)
//	{
//		$filename = $class . '.model.php';
//		$file = __APP_PATH . "/models/$filename";
//		if (file_exists($file) == false)
//		{
//			return false;
//		}
//		include_once $file;
//	}
//
//
//	// autoload controllers
//	function controllerLoader($class)
//	{
//		$filename = $class . '.php';
//		$file = __APP_PATH . "/controllers/$filename" ;
//		if (file_exists($file) == false)
//		{
//			return false;
//		}
//		include_once $file;
//	}

	// autoload libs
	function libLoader($class)
	{
		$filename = $class . '.class.php';
		// hack to remove namespace 
		$file = __SITE_PATH . '/lib/' . $filename;
		if (file_exists($file) == false)
		{
			return false;
		}
		include_once $file;
	}

	spl_autoload_register('libLoader');
//	spl_autoload_register('modelLoader');
//	spl_autoload_register('controllerLoader');

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
	date_default_timezone_set($config->config_values['application']['timezone']);

	/**
	 *
	 * @custom error function to throw exception
	 *
	 * @param int $errno The error number
	 *
	 * @param string $errmsg The error message
	 *
	 */
	function MvcErrorHandler($errno, $errmsg)
	{
		throw new MvcException($errmsg, $errno);
	}
	/*** set error handler level to E_WARNING ***/
	// set_error_handler('MvcErrorHandler', $config->config_values['application']['error_reporting']);
	
 	/*** a new registry object ***/
 	$registry = new Registry();

	// Initialize the FrontController
	$front = FrontController::getInstance();
	$front->setRegistry($registry);
	$front->route();

	echo $front->getBody();
}
catch(MvcException $e)
{
	//show a 404 page here
	echo 'FATAL:<br />';
	echo $e->getMessage();
	echo $e->getLine();
}
// catch exceptions from the php exception class
catch( Exception $e )
{
	echo $e->getMessage();
}