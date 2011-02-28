<?php

class Dashboard_MemberController extends AdminController
{

	public function __construct()
	{
		parent::__construct();
	}

	public function indexAction() 
	{
	    $this->forward('dashboard/member/login');
	}	
	
	public function loginAction() 
	{
		$this->oResponse->setOutput($this->_view->fetch('dashboard/member/login'), $this->oConfig->config_values['application']['config_compression']);
	}

}
