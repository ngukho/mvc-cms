<?php

class Dashboard_PanelController extends AdminController
{

	public function __construct()
	{
		parent::__construct();
	}

	public function indexAction() 
	{
	    $this->forward('dashboard/panel/show');
	}	
	
	public function showAction() 
	{
		$row = $this->oSession->userdata['current_admin'];
//		echo "<pre>";
//		print_r($row['username']);
//		echo "</pre>";
//		exit();
		$this->_view->title = 'PHP MVC framework';
		$this->renderView('dashboard/panel/show');
	}
	
	

}
