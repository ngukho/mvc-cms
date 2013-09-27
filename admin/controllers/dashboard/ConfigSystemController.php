<?php

class Dashboard_ConfigSystemController extends AdminController
{

	public function __construct()
	{
		parent::__construct();
	}

	public function indexAction() 
	{
		return $this->forward('dashboard/config-system/list');
	}	
	
	public function listAction() 
	{
		$oConfigSys = new Base_ConfigureSystem();
		$data = $oConfigSys->getAllGroups();
		
		$this->_view->arrConfigData = $data;
		$this->_view->save_link = base_url() . 'dashboard/config-system/save';
		
		$this->renderView('dashboard/config-system/list');
	}
	
	public function saveAction($group_id)
	{
		$post = $this->oParams->post; 
		$oConfigSys = new Base_ConfigureSystem();
		foreach ($post as $key => $value)
		{
			$num = $oConfigSys->updateConfigSystem($group_id, $key, array("value" => $value ));
		}
		
		redirect('dashboard/config-system/list');		
	}
	

}
