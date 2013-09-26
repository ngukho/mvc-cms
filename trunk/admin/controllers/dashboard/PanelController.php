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
	
	public function formViewAction()
	{
		
		$this->renderView('dashboard/panel/form');
	}

	public function tableViewAction()
	{
	
		$this->renderView('dashboard/panel/table');
	}

	public function blankPageAction()
	{
		$oConfigSys = new Base_ConfigureSystem();
		$data = $oConfigSys->getAllGroups();
		
		echo "<pre>";
		print_r($data);
		echo "</pre>";
		die();
		
		echo "<pre>";
		print_r($this->oConfigureModule);
		echo "</pre>";
		die();
	
		$this->renderView('dashboard/panel/blank');
	}	
	
	public function renderLeftNavAction()
	{
		return $this->_view->fetch('dashboard/panel/nav');
	}	

}
