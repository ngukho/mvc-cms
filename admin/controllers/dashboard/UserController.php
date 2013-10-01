<?php

class Dashboard_UserController extends AdminController
{

	public function __construct()
	{
		parent::__construct();
	}

	public function indexAction() 
	{
		// TODO : Check login - to do late 		
// 		if(!$this->isLogged())
// 			redirect('dashboard/member/login');
			
	    return $this->forward('dashboard/user/list');
	}	
	
	public function listAction() 
	{
		$this->_view->title = 'PHP MVC Framework';
		
		$objUser = new Base_User();
		
		$rsUsers = $objUser->getAllUser();
		
		$this->_view->rsUsers = $rsUsers;		
		
		$this->renderView('dashboard/user/list');
	}
	
	public function addAction()
	{
		$this->_view->box_title = "Add New User";
		$this->_view->link_url = base_url(). 'dashboard/user/add';		
		
		$objGroup = new Base_Group();
		$rsGroups = $objGroup->getRowset();
		$this->_view->rsGroups = $rsGroups;

		if ($this->oParams->isPost()) 
		{
			// TODO : Check validate
			$group_id = $this->oParams->varPost("group_id",array());
			$group_id = implode(",", $group_id);
			
			$data = array(
				"display_name" => $this->oParams->varPost("display_name",""),		
				"email" => $this->oParams->varPost("email",""),
				"password" => md5($this->oParams->varPost("pw","")),
				"group_id" => $group_id,
				"activated" => $this->oParams->varPost("activated",0)					
			);
			
			// TODO : Notify save successfully
			$oUser = new Base_User();
			$oUser->insert($data);			
		}
		
		$this->renderView('dashboard/user/add');
	}
	
	public function editAction($user_id)
	{
		// TODO : Check validate $user_id
				
		$this->_view->box_title = "Edit User";
		$this->_view->link_url = base_url(). 'dashboard/user/edit/'.$user_id;
	
		$objGroup = new Base_Group();
		$rsGroups = $objGroup->getRowset();
		$this->_view->rsGroups = $rsGroups;
		
		$oUser = new Base_User();
		
		if ($this->oParams->isPost())
		{
			// TODO : Check validate
			$group_id = $this->oParams->varPost("group_id",array());
			$group_id = implode(",", $group_id);
			
			$data = array(
				"display_name" => $this->oParams->varPost("display_name",""),
				"email" => $this->oParams->varPost("email",""),
				"group_id" => $group_id,
				"activated" => $this->oParams->varPost("activated",0)
			);			
			
			$reset_password = $this->oParams->varPost('reset_password',NULL);
			if ($reset_password != NULL)
			{
				// TODO : Check validate password
				$data['password'] = md5($this->oParams->varPost("pw",""));  
			}
				
			// TODO : Notify save successfully
			$oUser->update($user_id,$data);
		}		
		
		$rowUser = $oUser->get($user_id);
		
		$this->_view->rowUser = $rowUser;
		$this->_view->arrGroupIds = explode(",",$rowUser['group_id']);
		
		$this->renderView('dashboard/user/edit');
	}
	
	public function deleteAction($user_id)
	{
		// TODO : Check validate $user_id
		$oUser = new Base_User();
		$oUser->delete($user_id);
		redirect("dashboard/user/list");
	}
	
	public function activateAction($user_id)
	{
		// TODO : Check validate $user_id
		$oUser = new Base_User();
		$rowUser = $oUser->get($user_id);
		
		$data = array(
			"activated" => ($rowUser['activated'] == 0 ? 1 : 0)
		);
		
		// TODO : Notify save successfully
		$oUser->update($user_id,$data);		
		redirect("dashboard/user/list");	
	}	

}
