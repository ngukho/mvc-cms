<?php

/**
 *
 * @Front Controller class
 *
 * @package Core
 *
 */

class FrontController
{

	protected $_module, $_controller, $_action, $_body, $_view , $_registry;
	protected $pre_request = array();	

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
		
	}
	
	public function addPreRequest($pre_request) 
	{
		$this->pre_request[] = $pre_request;
	}
	
	public function dispatch()
	{
		$request = new Request($_SERVER['QUERY_STRING']);
		
		$this->_module = $request->getModule(); 
		$this->_controller = $request->getController(); 
		$this->_action = $request->getAction(); 
		
		foreach ($this->pre_request as $pre_request) {
			$result = self::run($pre_request);;
					
			if ($result) 
			{
				$request = $result;
				break;
			}
		}
			
		while ($request) {
			$request = self::run($request);
		}
	}
	
	public static function run($request) 
	{
		$file   = $request->getFile();
		$class  = $request->getClass();
		$method = $request->getMethod();
		$args   = $request->getArgs();

		$front = self::getInstance();
		
		$registry = $front->getRegistry();
		
		$registry->oRequest = $request;
		
		$front->setRegistry($registry);
		
		if (file_exists($file)) 
		{		
			require_once($file);
			
			$rc = new ReflectionClass($class);
			// if the controller exists and implements IController
			if( $rc->implementsInterface( 'IController' ) )
			{
				$controller = $rc->newInstance();
				$classMethod = $rc->getMethod($method);			
				return $classMethod->invokeArgs($controller,$args);
			}
			else
			{
				show_error("Interface iController must be implemented");
			}
		}
		else 
		{
			show_error("Controller file not found");
		}
	}		

	/**
	*
	* Gets the controller, sets to default if not available
	*
	* @access	public
	* @return	string	The name of the controller
	*
	*/
	public function getModule()
	{
		return $this->_module;
	}

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
