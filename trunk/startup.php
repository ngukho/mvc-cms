<?php  if ( ! defined('__SITE_PATH')) exit('No direct script access allowed');

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
	require __APP_PATH . '/config/constants.php';
	
 	/*** registry auto load ***/
	spl_autoload_register(null, FALSE);
	spl_autoload_extensions('.php, .class.php, .lang.php, .model.php');

	// model loader
	function modelLoader($class)
	{
		$filename = $class . '.model.php';
		$file = __SITE_PATH . "/application/models/$filename";
		if (file_exists($file) == TRUE)
		{
			include_once $file;
			return TRUE;
		}
		return FALSE;
	}

	// autoload libs
	function libLoader($class)
	{
		$filename = $class . '.class.php';
		$file = __SITE_PATH . '/lib/' . $filename;
		if (file_exists($file) == TRUE)
		{
			include_once $file;
			return TRUE;
		}
		
		$paths = explode('_', $class);
		if($paths[0] == "Zend")
		{
			$file = __SITE_PATH . '/lib/' . str_replace('_', '/', $class) . '.php';
			if (file_exists($file)) 
			{
				include_once $file;
				return TRUE;
			}    			
		}
		
		return FALSE;
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
	
	/* ---------------------------------------------------------- */
	
 	/*** include the helper ***/
	helperLoader(array_merge($_autoload_helpers,array('common')));
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
