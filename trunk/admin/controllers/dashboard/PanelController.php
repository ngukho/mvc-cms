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
		$this->renderView('dashboard/panel/blank');
	}

	public function permissionFormAction()
	{
		$acls = new Base_ModuleAcls(__APP_PATH.'/config/acls.php');
		
		
		$this->renderView('dashboard/panel/permission');
	}	
	
	public function renderLeftNavAction()
	{
		$menuInfo = require_once(__APP_PATH.'/config/left_menus.php');
		$this->_view->menuInfo = $menuInfo;
		return $this->_view->fetch('dashboard/panel/nav');
	}	

}
