<?php

class BaseController  implements IController
{
	protected $_front , $_controller , $_action , $_view, $_content = null;

	public function __construct()
	{
		$this->_front = FrontController::getInstance();
		$this->_controller = $this->_front->getController();
		$this->_action = $this->_front->getAction();
		$this->_registry = $this->_front->getRegistry();
		
		$this->_view = new View();
	}

	public function renderView($path)
	{
		$this->_view->content = $this->_view->fetch($path);
		$result = $this->_view->loadLayout();			
		$this->_front->setBody($result);			
	}

}
