<?php

class Site_IndexController extends BaseController
{

	public function __construct()
	{
		parent::__construct();
	}

	public function indexAction() 
	{
	    $this->_view->title = 'Welcome to Bui Van Tien Duc MVC';
	    $this->renderView('site/home/index');
	}
	
}
