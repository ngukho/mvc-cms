<?php  

if ( ! function_exists('redirect'))
{
	function redirect($uri = '', $method = 'location', $http_response_code = 302)
	{
		if ( ! preg_match('#^https?://#i', $uri))
		{
			$uri = site_url($uri);
		}
		
		switch($method)
		{
			case 'refresh'	: header("Refresh:0;url=".$uri);
				break;
			default			: header("Location: ".$uri, TRUE, $http_response_code);
				break;
		}
		exit;
	}
}

if ( ! function_exists('current_site_url'))
{
	function current_site_url($uri = '')
	{
		$pageURL = 'http';
 		$pageURL .= "://";
 		if ($_SERVER["SERVER_PORT"] != "80") {
  			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 		} else {
  			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 		}
 		return $pageURL . $uri;		
	}
}

if ( ! function_exists('site_url'))
{
	function site_url($uri = '')
	{
		$pageURL = 'http';
 		$pageURL .= "://";
 		if ($_SERVER["SERVER_PORT"] != "80") {
  			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].base_url();
 		} else {
  			$pageURL .= $_SERVER["SERVER_NAME"].base_url();
 		}
 		return $pageURL . $uri;		
	}
}

if ( ! function_exists('base_url'))
{
    function base_url()
    {
        return str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
    }
}

if ( ! function_exists('h'))
{
    function h(&$str)
    {
    	return empty($str) ? '' : nl2br(htmlspecialchars($str));
    }
}

if ( ! function_exists('n'))
{
    function n(&$str)
    {
        return empty($str) ? '' : number_format($str, 2, '.', ',');
    }
}

if ( ! function_exists('html'))
{
    function html(&$str)
    {
        return empty($str) ? '' : $str;
    }
}

if ( ! function_exists('now_to_mysql'))
{
    function now_to_mysql()
    {
        return date('Y-m-d H:i:s');
    }
}

if ( ! function_exists('mysql_to_fulldate'))
{
    function mysql_to_fulldate($date)
    {
        if(empty($date) || $date=='0000-00-00 00:00:00')
            return '';
        return date("yyyy-mm-dd hh:MM:ss", strtotime($date));
    }
}

if ( ! function_exists('mysql_to_unix_timestamp'))
{
    function mysql_to_unix_timestamp($date)
    {
        if(empty($date) || $date=='0000-00-00 00:00:00')
            return '';
        return strtotime($date);
    }
}

/**
* Determines if the current version of PHP is greater then the supplied value
*
* Since there are a few places where we conditionally test for PHP > 5
* we'll set a static variable.
*
* @access	public
* @param	string
* @return	bool
*/
function is_php($version = '5.0.0')
{
	static $_is_php;
	$version = (string)$version;
	
	if ( ! isset($_is_php[$version]))
	{
		$_is_php[$version] = (version_compare(PHP_VERSION, $version) < 0) ? FALSE : TRUE;
	}

	return $_is_php[$version];
}

// ------------------------------------------------------------------------

/**
 * Tests for file writability
 *
 * is_writable() returns TRUE on Windows servers when you really can't write to 
 * the file, based on the read-only attribute.  is_writable() is also unreliable
 * on Unix servers if safe_mode is on. 
 *
 * @access	private
 * @return	void
 */
function is_really_writable($file)
{	
	// If we're on a Unix server with safe_mode off we call is_writable
	if (DIRECTORY_SEPARATOR == '/' AND @ini_get("safe_mode") == FALSE)
	{
		return is_writable($file);
	}

	// For windows servers and safe_mode "on" installations we'll actually
	// write a file then read it.  Bah...
	if (is_dir($file))
	{
		$file = rtrim($file, '/').'/'.md5(rand(1,100));

		if (($fp = @fopen($file, FOPEN_WRITE_CREATE)) === FALSE)
		{
			return FALSE;
		}

		fclose($fp);
		@chmod($file, DIR_WRITE_MODE);
		@unlink($file);
		return TRUE;
	}
	elseif (($fp = @fopen($file, FOPEN_WRITE_CREATE)) === FALSE)
	{
		return FALSE;
	}

	fclose($fp);
	return TRUE;
}

// ------------------------------------------------------------------------

/**
* Class registry
*
* This function acts as a singleton.  If the requested class does not
* exist it is instantiated and set to a static variable.  If it has
* previously been instantiated the variable is returned.
*
* @access	public
* @param	string	the class name being requested
* @param	bool	optional flag that lets classes get loaded but not instantiated
* @return	object
*/
function &load_class($class, $instantiate = TRUE)
{
	static $objects = array();

	// Does the class exist?  If so, we're done...
	if (isset($objects[$class]))
	{
		return $objects[$class];
	}

	// If the requested class does not exist in the application/libraries
	// folder we'll load the native class from the system/libraries folder.	
	if (file_exists(APPPATH.'libraries/'.config_item('subclass_prefix').$class.EXT))
	{
		require(BASEPATH.'libraries/'.$class.EXT);
		require(APPPATH.'libraries/'.config_item('subclass_prefix').$class.EXT);
		$is_subclass = TRUE;
	}
	else
	{
		if (file_exists(APPPATH.'libraries/'.$class.EXT))
		{
			require(APPPATH.'libraries/'.$class.EXT);
			$is_subclass = FALSE;
		}
		else
		{
			require(BASEPATH.'libraries/'.$class.EXT);
			$is_subclass = FALSE;
		}
	}

	if ($instantiate == FALSE)
	{
		$objects[$class] = TRUE;
		return $objects[$class];
	}

	if ($is_subclass == TRUE)
	{
		$name = config_item('subclass_prefix').$class;

		$objects[$class] =& instantiate_class(new $name());
		return $objects[$class];
	}

	$name = ($class != 'Controller') ? 'CI_'.$class : $class;

	$objects[$class] =& instantiate_class(new $name());
	return $objects[$class];
}

/**
 * Instantiate Class
 *
 * Returns a new class object by reference, used by load_class() and the DB class.
 * Required to retain PHP 4 compatibility and also not make PHP 5.3 cry.
 *
 * Use: $obj =& instantiate_class(new Foo());
 * 
 * @access	public
 * @param	object
 * @return	object
 */
function &instantiate_class(&$class_object)
{
	return $class_object;
}

/**
* Loads the main config.php file
*
* @access	private
* @return	array
*/
function &get_config()
{
	static $main_conf;

	if ( ! isset($main_conf))
	{
		if ( ! file_exists(APPPATH.'config/config'.EXT))
		{
			exit('The configuration file config'.EXT.' does not exist.');
		}

		require(APPPATH.'config/config'.EXT);

		if ( ! isset($config) OR ! is_array($config))
		{
			exit('Your config file does not appear to be formatted correctly.');
		}

		$main_conf[0] =& $config;
	}
	return $main_conf[0];
}

/**
* Gets a config item
*
* @access	public
* @return	mixed
*/
function config_item($item)
{
	static $config_item = array();

	if ( ! isset($config_item[$item]))
	{
		$config =& get_config();

		if ( ! isset($config[$item]))
		{
			return FALSE;
		}
		$config_item[$item] = $config[$item];
	}

	return $config_item[$item];
}


/**
* Error Handler
*
* This function lets us invoke the exception class and
* display errors using the standard error template located
* in application/errors/errors.php
* This function will send the error page directly to the
* browser and exit.
*
* @access	public
* @return	void
*/
function show_error($message, $status_code = 500)
{
//	$error =& load_class('Exceptions');
	$error = new MvcException();
	echo $error->show_error('An Error Was Encountered', $message, 'error_general', $status_code);
	exit;
}


/**
* 404 Page Handler
*
* This function is similar to the show_error() function above
* However, instead of the standard error template it displays
* 404 errors.
*
* @access	public
* @return	void
*/
function show_404($page = '')
{
//	$error =& load_class('Exceptions');
	$error = new MvcException();
	$error->show_404($page);
	exit;
}


/**
* Error Logging Interface
*
* We use this as a simple mechanism to access the logging
* class and send messages to be logged.
*
* @access	public
* @return	void
*/
function log_message($level = 'error', $message, $php_error = FALSE)
{
//	static $LOG;
//	
//	$config =& get_config();
//	if ($config['log_threshold'] == 0)
//	{
//		return;
//	}
//
//	$LOG =& load_class('Log');
//	$LOG->write_log($level, $message, $php_error);
}


/**
 * Set HTTP Status Header
 *
 * @access	public
 * @param	int 	the status code
 * @param	string	
 * @return	void
 */
function set_status_header($code = 200, $text = '')
{
	$stati = array(
						200	=> 'OK',
						201	=> 'Created',
						202	=> 'Accepted',
						203	=> 'Non-Authoritative Information',
						204	=> 'No Content',
						205	=> 'Reset Content',
						206	=> 'Partial Content',

						300	=> 'Multiple Choices',
						301	=> 'Moved Permanently',
						302	=> 'Found',
						304	=> 'Not Modified',
						305	=> 'Use Proxy',
						307	=> 'Temporary Redirect',

						400	=> 'Bad Request',
						401	=> 'Unauthorized',
						403	=> 'Forbidden',
						404	=> 'Not Found',
						405	=> 'Method Not Allowed',
						406	=> 'Not Acceptable',
						407	=> 'Proxy Authentication Required',
						408	=> 'Request Timeout',
						409	=> 'Conflict',
						410	=> 'Gone',
						411	=> 'Length Required',
						412	=> 'Precondition Failed',
						413	=> 'Request Entity Too Large',
						414	=> 'Request-URI Too Long',
						415	=> 'Unsupported Media Type',
						416	=> 'Requested Range Not Satisfiable',
						417	=> 'Expectation Failed',

						500	=> 'Internal Server Error',
						501	=> 'Not Implemented',
						502	=> 'Bad Gateway',
						503	=> 'Service Unavailable',
						504	=> 'Gateway Timeout',
						505	=> 'HTTP Version Not Supported'
					);

	if ($code == '' OR ! is_numeric($code))
	{
		show_error('Status codes must be numeric', 500);
	}

	if (isset($stati[$code]) AND $text == '')
	{				
		$text = $stati[$code];
	}
	
	if ($text == '')
	{
		show_error('No status text available.  Please check your status code number or supply your own message text.', 500);
	}
	
	$server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : FALSE;

	if (substr(php_sapi_name(), 0, 3) == 'cgi')
	{
		header("Status: {$code} {$text}", TRUE);
	}
	elseif ($server_protocol == 'HTTP/1.1' OR $server_protocol == 'HTTP/1.0')
	{
		header($server_protocol." {$code} {$text}", TRUE, $code);
	}
	else
	{
		header("HTTP/1.1 {$code} {$text}", TRUE, $code);
	}
}

//// underscored to upper-camelcase 
//// e.g. "this_method_name" -> "ThisMethodName" 
function upperCamelcase($string)
{
	return preg_replace('/(?:^|-)(.?)/e',"strtoupper('$1')",$string);
}

//// underscored to lower-camelcase 
//// e.g. "this_method_name" -> "thisMethodName" 
function lowerCamelcase($string)
{
	return preg_replace('/-(.?)/e',"strtoupper('$1')",$string);
}	

// camelcase (lower or upper) to hyphen 
// e.g. "thisMethodName" -> "this_method_name" 
// e.g. "ThisMethodName" -> "this_method_name"
// Of course these aren't 100% symmetric.  For example...
//  * this_is_a_string -> ThisIsAString -> this_is_astring
//  * GetURLForString -> get_urlfor_string -> GetUrlforString 
function camelcaseToHyphen($string)
{
	return strtolower(preg_replace('/([^A-Z])([A-Z])/', "$1-$2", $string));
}

function _autoload($class)
{
	if(class_exists($class)) return;

	$file = __SITE_PATH . "/application/models/" . $class .'.model.php';
	if (file_exists($file) == TRUE)
	{
		include_once $file;
		return TRUE;
	}
	
	$file = __SITE_PATH . '/lib/' . $class . '.class.php';
	if (file_exists($file) == TRUE)
	{
		include_once $file;
		return TRUE;
	}

	// Load Zend library
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
	
	// Load Model
	for ($i = 0; $i < count($paths) - 1 ; $i++) 
		$paths[$i] = camelcaseToHyphen($paths[$i]);	

	$file = __SITE_PATH . "/application/models/" . join('/',$paths) . '.model.php';
	if (file_exists($file) == TRUE)
	{
		include_once $file;
		return TRUE;
	}
	
	return FALSE;
}

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

/**
* Exception Handler
*
* This is the custom exception handler that is declaired at the top
* of Codeigniter.php.  The main reason we use this is permit
* PHP errors to be logged in our own log files since we may
* not have access to server logs. Since this function
* effectively intercepts PHP errors, however, we also need
* to display errors based on the current error_reporting level.
* We do that with the use of a PHP error template.
*
* @access	private
* @return	void
*/
function _exception_handler($severity, $message, $filepath, $line)
{	
	 // We don't bother with "strict" notices since they will fill up
	 // the log file with information that isn't normally very
	 // helpful.  For example, if you are running PHP 5 and you
	 // use version 4 style class functions (without prefixes
	 // like "public", "private", etc.) you'll get notices telling
	 // you that these have been deprecated.
	
	 
	if ($severity == E_STRICT)
	{
		return;
	}

	$error = new MvcException();	

//	$error =& load_class('Exceptions');

	// Should we display the error?
	// We'll get the current error_reporting level and add its bits
	// with the severity bits to find out.
	
	if (($severity & error_reporting()) == $severity)
	{
		$error->show_php_error($severity, $message, $filepath, $line);
	}

	return TRUE;	
	
	// Should we log the error?  No?  We're done...
//	$config =& get_config();
//	if ($config['log_threshold'] == 0)
//	{
//		return;
//	}
//
//	$error->log_exception($severity, $message, $filepath, $line);
}

// Error Handler
//function error_handler($errno, $errstr, $errfile, $errline) {
//	global $config, $log;
//	
//	switch ($errno) {
//		case E_NOTICE:
//		case E_USER_NOTICE:
//			$error = 'Notice';
//			break;
//		case E_WARNING:
//		case E_USER_WARNING:
//			$error = 'Warning';
//			break;
//		case E_ERROR:
//		case E_USER_ERROR:
//			$error = 'Fatal Error';
//			break;
//		default:
//			$error = 'Unknown';
//			break;
//	}
//		
//	if ($config->get('config_error_display')) {
//		echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
//	}
//	
//	if ($config->get('config_error_log')) {
//		$log->write('PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
//	}
//
//	return TRUE;
//}
//
//// Error Handler
//set_error_handler('error_handler');

function ip_address()
{
    static $ip = FALSE;
    
    if( $ip ) {
        return $ip;
    }
    //Get IP address - if proxy lets get the REAL IP address

    if (!empty($_SERVER['REMOTE_ADDR']) AND !empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = '0.0.0.0';
    }

    //Clean the IP and return it
    return $ip = preg_replace('/[^0-9\.]+/', '', $ip);
}

/**
 * Create a fairly random 32 character MD5 token
 *
 * @return string
 */

function token()
{
    return md5(str_shuffle(chr(mt_rand(32, 126)). uniqid(). microtime(TRUE)));
}

/**
 * Encode a string so it is safe to pass through the URI
 * @param string $string
 * @return string
 */

function base64_url_encode($string = NULL)
{
    return strtr(base64_encode($string), '+/=', '-_~');
}

/**
 * Decode a string passed through the URI
 *
 * @param string $string
 * @return string
 */

function base64_url_decode($string = NULL)
{
    return base64_decode(strtr($string, '-_~','+/='));
}