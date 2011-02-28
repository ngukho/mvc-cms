<?php

abstract class AdminController extends BaseController 
{

	public function __construct()
	{
		parent::__construct();
		$this->_layout_path = 'admin/default';
	}
	
//	protected function renderView($path)
//	{
//		$this->_view->content = $this->_view->fetch($path);
//		$result = $this->_view->renderLayout($this->_layout_path);
//		$this->oResponse->setOutput($result, $this->oConfig->config_values['application']['config_compression']);
//	}

	public function isLogged()
	{
		if(isset($this->oSession->userdata['is_logged']) && $this->oSession->userdata['is_logged'] === TRUE)
			return TRUE;
		else
			return FALSE;
	}

}
