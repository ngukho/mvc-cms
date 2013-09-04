<?php

class Dashboard_UserController extends AdminController
{

	public function __construct()
	{
		parent::__construct();
	}

	public function indexAction() 
	{
		if(!$this->isLogged())
			redirect('dashboard/member/login');
			
	    return $this->forward('dashboard/user/list');
	}	
	
	public function listAction() 
	{
		$this->_view->title = 'PHP MVC Framework';
		$this->renderView('dashboard/user/list');
	}
	
	

}
