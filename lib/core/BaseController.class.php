<?php
/**
 *
 * @Controller Interface
 *
 */

interface IController {}

abstract class BaseController implements IController
{
	protected $_front , $_module , $_controller , $_action , $_view, $_registry , $_layout_path = NULL;

	public function __construct()
	{
		$this->_front = FrontController::getInstance();
		$this->_module = $this->_front->getModule();
		$this->_controller = $this->_front->getController();
		$this->_action = $this->_front->getAction();
		$this->_registry = $this->_front->getRegistry();
		$this->_view = new View();
	}
	
	public function __get($key) 
	{
		return $this->_registry->{$key};
	}
	
	public function __set($key, $value) 
	{
		$this->_registry->{$key} = $value;
	}

	protected function forward($route, $args = array()) 
	{
		return new Request($route, $args);
	}	
	
	protected function renderView($path)
	{
		$this->_view->content = $this->_view->fetch($path);
		$result = $this->_view->renderLayout($this->_layout_path);
		$this->oResponse->setOutput($result, $this->oConfig->config_values['application']['config_compression']);
	}

}
