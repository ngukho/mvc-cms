<?php

class PdoDatabase_PdoModelController Extends AdminController
{

	function __construct()
	{
		parent::__construct();
	}
	
	public function indexAction()
	{
		return $this->forward('pdo-database/pdo-model/pdo-model');
	}
	
	public function pdoModelAction($offset = 0) 
	{
		$b = new Test_ExModel_Car();
		print "<pre>";
		print_r($b->show());
		print "</pre>";
		exit();
		
	    $this->_view->title = 'PDO Model Database MVC';
	    
	    $users = new Users();
	    
	    $items_per_page = 15;
	    $offset = ($offset % $items_per_page != 0 ? 0 : $offset);
	    
		$rs = $users->getRowSet(NULL,array(),'user_id DESC',$offset,$items_per_page);
	    
	    $pages = new Paginator();
	    $pages->current_url = base_url() . 'pdo-database/pdo-model/pdo-model/%d';
	    $pages->offset = $offset;
	    $pages->items_per_page = $items_per_page;
	    
		$pages->items_total = $users->getTotalRow();
		$pages->mid_range = 7;
		$pages->paginate();
		
		$this->_view->pages = $pages;
	    $this->_view->rs = $rs;		
	    
	    $this->_view->add_link = base_url() . 'pdo-database/pdo-model/pdo-add-model/';
	    $this->_view->edit_link = base_url() . 'pdo-database/pdo-model/pdo-edit-model/';
	    $this->_view->delete_link = base_url() . 'pdo-database/pdo-model/pdo-delete-model/';
	    
	    $this->renderView('pdo-database/pdo-model/index');
	}	

	public function pdoDeleteModelAction($id)
	{
		$users = new Users();
		$row = $users->get($id);
	    if(!empty($row))
	    {
	    	$users->delete($id);
	    }
	    redirect('pdo-database/pdo-model/pdo-model');
	}
	
	public function pdoAddModelAction()
	{
		$this->_view->title = 'Model Add Form';
		$this->_view->link = base_url() . 'pdo-database/pdo-model/pdo-add-model';
		
		$val = new Validation();
		$val->source = $_POST;
		
		if(!empty($_POST))
		{
			$val = new Validation();
			$val->source = $_POST;
			$val->addValidator(array('name' => 'first_name','type' =>'string','required'=>true));
			$val->addValidator(array('name' => 'last_name','type' =>'string','required'=>true));
			$val->addValidator(array('name' => 'email','type' =>'email','required'=>true));
			$val->addValidator(array('name' => 'address','type' =>'string','required'=>true));
			$val->run();
			
			if(!$val->hasError())
			{
				$users = new Users();
				$data = array(
					'first_name' => $_POST['first_name'],
					'last_name' => $_POST['last_name'],
					'email' => $_POST['email'],
					'address' => $_POST['address']
				);
				$users->insert($data);
				redirect('pdo-database/pdo-model/pdo-model');
			}
			
			$this->_view->errorMessage = $val->errorMessage();
			$this->_view->data = $_POST;
		}
		
		$this->renderView('pdo-database/pdo-model/_form');
	}
	
	public function pdoEditModelAction($id)
	{
		$this->_view->title = 'Model Edit Form';		
		$this->_view->link = base_url() . 'pdo-database/pdo-model/pdo-edit-model/' . $id;		
		
		$users = new Users();
		$row = $users->get($id);
		
	    if(empty($row))
	    	redirect('pdo-database/pdo-model/pdo-model');

		$this->_view->data = $row;
	    	
		if(!empty($_POST))
		{
			$val = new Validation();
			$val->source = $_POST;
			$val->addValidator(array('name' => 'first_name','type' =>'string','required'=>true));
			$val->addValidator(array('name' => 'last_name','type' =>'string','required'=>true));
			$val->addValidator(array('name' => 'email','type' =>'email','required'=>true));
			$val->addValidator(array('name' => 'address','type' =>'string','required'=>true));
			$val->run();
			
			if(sizeof($val->errors) == 0)
			{
				$data = array(
					'first_name' => $_POST['first_name'],
					'last_name' => $_POST['last_name'],
					'email' => $_POST['email'],
					'address' => $_POST['address']
				);				
				$users->update($id,$data);
		    	redirect('pdo-database/pdo-model/pdo-model');
			}
			$this->_view->errorMessage = $val->errorMessage();
			$this->_view->data = $_POST;
		}
		
		$this->renderView('pdo-database/pdo-model/_form');
	}	
	
}

?>
