<?php

class Site_IndexController extends BaseController
{

	public function __construct()
	{
		parent::__construct();
	}

	public function indexAction() 
	{
//		$_SESSION['test'] = 12;
		$this->oSession->userdata['test'] = 12;
	    $this->_view->title = 'Welcome to Bui Van Tien Duc MVC';
	    $this->renderView('site/home/index');
	}
	
}
