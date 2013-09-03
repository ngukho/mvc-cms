<?php (defined('__SITE_PATH')) OR exit('No direct script access allowed');

abstract class AdminController extends BaseController 
{
	protected $_current_admin;
	
	public function __construct()
	{
		parent::__construct();
		$this->_layout_path = 'admin/default';
		
		$this->checkAccess();
	}
	
	private function checkAccess()
	{
	    // These pages get past permission checks
	    $ignored_pages = array(
	    		'dashboard/panel/index',
	    		'dashboard/member/index',
	    		'dashboard/member/login',
	    		'dashboard/member/logout'
   		);

	    // Check if the current page is to be ignored
		// Day la Request hien tai , khong phai request cua site (fix forward function)
	    $current_page = $this->oRequest->getRouter();
	    
	    // Dont need to log in, this is an open page
		if(in_array($current_page, $ignored_pages))
		{
			return TRUE;
		}

		else if (!$this->isLogged())
		{
			// ket thuc chuong trinh
			// thong bao link nay khong ton tai
			show_404();
			exit();
		}
		else 
		{
			$this->_current_admin = $this->oSession->userdata['current_admin'];
			$this->_view->current_admin = $this->oSession->userdata['current_admin'];
			$this->_view->is_logged = $this->oSession->userdata['is_logged'];
			return TRUE;
		}
		
		// Kiem tra permission cua account

		// Admins can go straight in
//		else if ($this->user->group === 'admin')
//		{
//			return TRUE;
//		}

		// Well they at least better have permissions!
//		else if ($this->user)
//		{
//			// We are looking at the index page. Show it if they have ANY admin access at all
//			if($current_page == 'admin/index' && $this->permissions)
//			{
//				return TRUE;
//			}
//
//			else
//			{
//				// Check if the current user can view that page
//				 return in_array($this->module, $this->permissions);
//			}
//		}

		// god knows what this is... erm...
		return FALSE;
	}

	public function isLogged()
	{
		if(isset($this->oSession->userdata['is_logged']) && $this->oSession->userdata['is_logged'] === TRUE)
			return TRUE;
		else
			return FALSE;
	}

//	protected function renderView($path)
//	{
//		$this->_view->content = $this->_view->fetch($path);
//		$result = $this->_view->renderLayout($this->_layout_path);
//		$this->oResponse->setOutput($result, $this->oConfig->config_values['application']['config_compression']);
//	}
	
	
}

	
	
