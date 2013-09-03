<?php

class Dashboard_PanelController extends AdminController
{

	public function __construct()
	{
		parent::__construct();
	}

	public function indexAction() 
	{
		if(!$this->isLogged())
			redirect('dashboard/member/login');
			
	    return $this->forward('dashboard/panel/show');
	}	
	
	public function showAction() 
	{
		$this->_view->title = 'PHP MVC Framework';
		$this->renderView('dashboard/panel/show');
	}
	
	

}
