<?php

class Dashboard_MemberController extends AdminController
{

	public function __construct()
	{
		parent::__construct();
	}

	public function indexAction() 
	{
	    $this->forward('dashboard/member/login');
	}	
	
	public function loginAction() 
	{
		// user : tienduc - pw : 123456
		if(!empty($_POST))
		{
			$username = $_POST['username'];
			$password = $_POST['password'];
			
			$users = new Users();
			$row = $users->checkLogin($username, $password);
			
			if($row)
			{
				$this->oSession->userdata['current_admin'] = $row;
				$this->oSession->userdata['is_logged'] = TRUE;
				redirect('dashboard/panel/show');
			}
		}

		$this->_layout_path = "admin/layout_login";
		$this->renderView('dashboard/member/login');
// 		$this->oResponse->setOutput($this->_view->fetch('dashboard/member/login'), $this->oConfig->config_values['application']['config_compression']);
	}
	
	public function logoutAction()
	{
		unset($this->oSession->userdata['current_admin']);
		unset($this->oSession->userdata['is_logged']);
		redirect('dashboard/member/login');		
	}
	
	

}
