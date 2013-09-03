<?php

Class ActiveRecord_UserModelController Extends BaseController 
{

	public function indexAction()
	{
		return $this->forward('active-record/user-model/show-model');
	}
	
	public function showModelAction($offset = 0) 
	{
	    $this->_view->title = 'Active Record Database MVC';
	    
	    $users = new Peoples();
	    
	    $items_per_page = 15;
	    $offset = ($offset % $items_per_page != 0 ? 0 : $offset);
	    
		$rs = $users->find(NULL,'user_id DESC',NULL,"$offset,$items_per_page");	    
		
	    $pages = new Paginator();
	    $pages->current_url = base_url() . 'active-record/user-model/show-model/%d';
	    $pages->offset = $offset;
	    $pages->items_per_page = $items_per_page;
	    
		$pages->items_total = $users->getTotalRow();
		$pages->mid_range = 7;
		$pages->paginate();
		
		$this->_view->pages = $pages;
	    $this->_view->rs = $rs;		
	    
	    $this->_view->add_link = base_url() . 'active-record/user-model/add-model/';
	    $this->_view->edit_link = base_url() . 'active-record/user-model/edit-model/';
	    $this->_view->delete_link = base_url() . 'active-record/user-model/delete-model/';
	    
	    $this->renderView('active-record/user-model/index');
	}	

	public function deleteModelAction($id)
	{
		$peoples = new Peoples();
		$row = $peoples->findFirst('user_id = ' . $id);
		
	    if(!empty($row))
	    {
	    	$row->destroy();
	    }
	    
	    redirect('active-record/user-model/show-model');
	}
	
	public function addModelAction()
	{
		$this->_view->title = 'Model Add Form';
		$this->_view->link = base_url() . 'active-record/user-model/add-model';
		
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
				$peoples = new Peoples();
				$data = array(
					'first_name' => $_POST['first_name'],
					'last_name' => $_POST['last_name'],
					'email' => $_POST['email'],
					'address' => $_POST['address']
				);
				$peoples->insert($data);
				redirect('active-record/user-model/show-model');
			}
			
			$this->_view->errorMessage = $val->errorMessage();
			$this->_view->data = $_POST;
		}
		
		$this->renderView('active-record/user-model/_form');
	}
	
	public function editModelAction($id)
	{
		$this->_view->title = 'Model Edit Form';		
		$this->_view->link = base_url() . 'active-record/user-model/edit-model/' . $id;		
		
		$peoples = new Peoples();
		$row = $peoples->findFirst('user_id = ' . $id);
		
	    if(empty($row))
	    	redirect('active-record/user-model/show-model');

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
				$row->update('user_id = ' . $id,$data);
		    	redirect('active-record/user-model/show-model');
			}
			$this->_view->errorMessage = $val->errorMessage();
			$this->_view->data = $_POST;
		}
		
		$this->renderView('active-record/user-model/_form');
	}	
	
}

?>
