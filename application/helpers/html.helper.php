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
    function h($str)
    {
        return nl2br(htmlspecialchars($str));
    }
}

if ( ! function_exists('n'))
{
    function n($str)
    {
        return number_format($str, 2, '.', ',');
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
