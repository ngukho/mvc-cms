<?php

class Dashboard_GroupController extends AdminController
{

	public function __construct()
	{
		parent::__construct();
	}

	public function indexAction() 
	{
		
	}	
	
	public function listAction() 
	{
		$this->_view->box_title = "Edit Group";
		
		$objGroup = new Base_Group();
		$rsGroups = $objGroup->getRowset();
		$this->_view->rsGroups = $rsGroups;

		$this->renderView('dashboard/group/list');
	}
	
	public function addAction()
	{
		// TODO : Check validate
		
		$this->_view->box_title = "Add Group";
		
		$acls = new Base_ModuleAcls(__APP_PATH.'/config/acls.php');
		$this->_view->arrAcls = $acls->getModuleAcls();
		$this->_view->link_url = base_url(). 'dashboard/group/add';
		
		if ($this->oParams->isPost())
		{
			$group_name = $this->oParams->varPost('group_name','');
			$role = str_replace(" ", "_", trim($group_name));
			
			$permission = $this->oParams->varPost('permission',NULL);
			$is_admin = $this->oParams->varPost('is_admin','0');
			
			// TODO : Check validate
			if ($permission == NULL)
			{
				
			}
			
			$permission = serialize($permission);
			
			$data = array(
				"role" => $role,					
				"group_name" => $group_name,
				"level" => $this->oParams->varPost('level','0'),
				"is_admin" => $is_admin,
				"acl_resources" => $permission					
			);
			
			$objGroup = new Base_Group();
			$last_id = $objGroup->insert($data);
		}

		$this->renderView('dashboard/group/_form');
	}
	
	public function editAction($group_id)
	{
		// TODO : Check validate
		
		$this->_view->box_title = "Edit Group";
				
		$acls = new Base_ModuleAcls(__APP_PATH.'/config/acls.php');
		$this->_view->arrAcls = $acls->getModuleAcls();
		$this->_view->link_url = base_url(). 'dashboard/group/edit/'.$group_id;
		$this->_view->group_id = $group_id;
		
		$objGroup = new Base_Group();
		$rowGroup = $objGroup->get($group_id);

		$this->_view->rowGroup = $rowGroup;
		$this->_view->arrAclResources = unserialize($rowGroup['acl_resources']);
		
		if ($this->oParams->isPost())
		{
			$group_name = $this->oParams->varPost('group_name','');
			// Khong cho thay doi role
				
			$permission = $this->oParams->varPost('permission',NULL);
			$is_admin = $this->oParams->varPost('is_admin','0');
				
			// TODO : Check validate
			if ($permission == NULL)
			{
		
			}
				
			$permission = serialize($permission);
				
			$data = array(
					"group_name" => $group_name,
					"level" => $this->oParams->varPost('level','0'),
					"is_admin" => $is_admin,
					"acl_resources" => $permission
			);

			$objGroup->update($group_id,$data);
		}		
		
		$this->renderView('dashboard/group/_form');
	}
	
	
	public function deleteAction($group_id)
	{
		// TODO : Check validate
		
		$objGroup = new Base_Group();
		$rowGroup = $objGroup->delete($group_id);
				
		redirect("dashboard/group/list");
	}

}
