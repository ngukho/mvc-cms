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

	protected $_module, $_controller, $_action, $_registry;
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
		
		foreach ($this->pre_request as $pre_request) 
		{
			$result = Module::run($pre_request);
					
			if ($result) 
			{
				$request = $result;
				break;
			}
		}
			
		while ($request) {
			$request = Module::run($request);
		}
	}
	
	public function getModule()
	{
		return $this->_module;
	}

	public function getController()
	{
		return $this->_controller;
	}

	public function getAction()
	{
		return $this->_action;
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
