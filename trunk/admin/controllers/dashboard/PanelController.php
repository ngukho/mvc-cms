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
		
// 		http://php.net/manual/en/simplexmlelement.attributes.php
				
		echo "<pre>";
		print_r(__APP_PATH.'/acl.xml');
		echo "</pre>";
		
		$xml = file_get_contents(__APP_PATH.'/acl.xml');
		$xml_data = simplexml_load_string($xml);
		
		echo "<pre>";
		print_r($xml_data->module[0]->attributes());
		echo "</pre>";

		echo "<pre>";
		print_r($xml_data->module[0]->controller[0]->attributes()->name);
		echo "</pre>";		
		
		echo "<pre>";
		print_r($xml_data->module[0]->controller[1]->attributes());
		echo "</pre>";
		
		echo "<pre>";
		print_r($xml_data->module[0]->controller[1]);
		echo "</pre>";		
				
		echo "<pre>";
		print_r($xml_data);
		echo "</pre>";
		
		
		foreach ($xml_data as $k => $v) {
			$array[$k] = (string)$v;
		}

		
		echo "<pre>";
		print_r($array);
		echo "</pre>";
		die();
		
		
		
		
		die();
		
		$this->renderView('dashboard/panel/permission');
	}	
	
	public function renderLeftNavAction()
	{
		return $this->_view->fetch('dashboard/panel/nav');
	}	

}
