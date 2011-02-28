<?php

abstract class AdminController extends BaseController 
{

	public function __construct()
	{
		parent::__construct();
		$this->_layout_path = 'admin/default';
		$current_url = "{$this->_module}/{$this->_controller}/{$this->_action}";
		$allow_url = array('dashboard/member/index','dashboard/member/login','dashboard/member/logout');
		if(in_array($current_url, $allow_url))
		{
			return; 
		}
		
		if(!$this->isLogged())
		{
			show_404();
		}
		$this->_view->current_admin = $this->oSession->userdata['current_admin'];
		$this->_view->is_logged = $this->oSession->userdata['is_logged'];
		
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
