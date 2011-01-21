<?php

/**
 *
 * @Front Controller class
 *
 * @copyright Copyright (C) 2009 PHPRO.ORG. All rights reserved.
 *
 * @license new bsd http://www.opensource.org/licenses/bsd-license.php
 * @package Core
 *
 */

require __SITE_PATH . '/lib/' . 'IController.class.php';
require __SITE_PATH . '/lib/' . 'BaseController.class.php';
require __SITE_PATH . '/lib/' . 'View.class.php';

class FrontController
{

	protected $_module, $_controller, $_action, $_params, $_body, $_url , $_view , $_registry;

	public static $_instance;

	public static function getInstance()
	{
		if( ! (self::$_instance instanceof self) )
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	private function __construct()
	{
		$config = Config::getInstance();		
		$this->_view = new View();
		
		// set the controller
		$this->_uri = Uri::getInstance();
		
		if($this->_uri->fragment(0))
		{
			$this->_module = $this->_uri->fragment(0);
		}
		else
		{
			// get the default controller
			$default = $config->config_values['application']['default_module'];
			$this->_module = $default;
		}
		
		if($this->_uri->fragment(1))
		{
			$this->_controller = $this->_uri->fragment(1);
		}
		else
		{
			// get the default controller
			$default = $config->config_values['application']['default_controller'];
			$this->_controller = $default;
		}
		
		// the action
		if($this->_uri->fragment(2))
			$this->_action = $this->_uri->fragment(2);
		else
			$this->_action = 'index';
		
		$path = __APP_PATH ."/{$this->_module}/controllers/". ucfirst($this->_controller) . 'Controller.php';
	
		if (is_readable($path) != TRUE)
		{
			$this->_controller = $config->config_values['application']['error_controller'];
			$this->_action = 'index';			
			$path = __APP_PATH ."/index/controllers/". ucfirst($this->_controller) . 'Controller.php';
		}
		
		require_once($path);		

		// Load $_GET variable			
		$i = 3;
		while($this->_uri->fragment($i)){
			$_GET[$this->_uri->fragment($i)] = $this->_uri->fragment($i + 1);
			$i += 2;
		}			
		
	}
	
	/**
	 *
 	 * The route
	 *
	 * Checks if controller and action exists
 	 */
	public function route()
	{
		// check if the controller exists
		$con = ucfirst($this->_module)."_".ucfirst($this->getController())."Controller";
		$rc = new ReflectionClass( $con );
		// if the controller exists and implements IController
		if( $rc->implementsInterface( 'IController' ) )
		{
			$controller = $rc->newInstance();
			// check if method exists 
			if( $rc->hasMethod( $this->getAction() ) )
			{
				// if all is well, load the action
				$method = $rc->getMethod( $this->getAction() );
			}
			else
			{
				// load the default action method
				$config = Config::getInstance();
				$default = $config->config_values['application']['default_action'];
				$method = $rc->getMethod( $default );
			}
			$method->invoke( $controller );
		}
		else
		{
			throw new Exception("Interface iController must be implemented");
		}
	}

	public static function run($path,$args = array())
	{
		$r = explode('/', $path);
		$con = $r[0]."Controller";
		$act = $r[1];
		
		$rc = new ReflectionClass( $con );
		// if the controller exists and implements IController
		if( $rc->implementsInterface( 'IController' ) )
		{
			$controller = $rc->newInstance();
			// check if method exists 
			if( $rc->hasMethod( $act ) )
			{
				// if all is well, load the action
				$method = $rc->getMethod( $act );
			}
			else
			{
				// load the default action method
				$config = Config::getInstance();
				$default = $config->config_values['application']['default_action'];
				$method = $rc->getMethod( $default );
			}
			return $method->invokeArgs($controller,$args);
		}
		else
		{
			throw new Exception("Interface iController must be implemented");
		}
	}
	
	/*
	public function getParams()
	{
		return $this->_params;
	}
	*/

	/**
	*
	* Gets the controller, sets to default if not available
	*
	* @access	public
	* @return	string	The name of the controller
	*
	*/
	public function getController()
	{
		return $this->_controller;
	}

	/**
	*
	* Get the action
	*
	* @access	public
	* @return	string	the Name of the action
	*
	*/
	public function getAction()
	{
		return $this->_action;
	}

	public function getBody()
	{
		return $this->_body;
	}

	public function setBody($body)
	{
		$this->_body = $body;
	}
	
	public function getView()
	{
		return $this->_view;
	}

	public function getRegistry()
	{
		return $this->_registry;
	}

	public function setRegistry($registry)
	{
		$this->_registry = $registry;
	}		

} // end of class
