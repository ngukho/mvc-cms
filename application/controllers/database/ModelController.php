<?php

Class Database_ModelController Extends BaseController 
{

	function __construct()
	{
		parent::__construct();
	}
	
	public function indexAction($offset = 0) 
	{
	    $this->_view->title = 'Normal Database MVC';
	    
	    $db = Db::getInstance();
	    
//	    $db->query("SELECT * FROM customers")->fetch();
	    
		// Get one row
	    $row = $db->query("SELECT count(user_id) As Total FROM users")->fetch();
	    
	    $items_per_page = 15;
	    $offset = ($offset % $items_per_page != 0 ? 0 : $offset);
	    $rs = $db->query("SELECT * FROM users ORDER BY user_id DESC limit {$offset},{$items_per_page} ");
	    
	    $pages = new Paginator();
	    $pages->current_url = base_url() . 'database/model/index/%d';
	    $pages->offset = $offset;
	    $pages->items_per_page = $items_per_page;
	    
		$pages->items_total = $row['Total'];
		$pages->mid_range = 7;
		$pages->paginate();
		
		$this->_view->pages = $pages;
	    $this->_view->rs = $rs;
	    
	    $this->_view->add_link = base_url() . 'database/model/add/';
	    $this->_view->edit_link = base_url() . 'database/model/edit/';
	    $this->_view->delete_link = base_url() . 'database/model/delete/';
	    
	    $this->renderView('database/model/index');

	}
	
	public function deleteAction($id)
	{
	    $db = Db::getInstance();
	    $row = $db->query("SELECT * FROM users WHERE user_id = " . $id)->fetch();
	    if(!empty($row))
	    {
			$db->query("DELETE FROM users WHERE user_id = " . $id);
	    }
	    redirect('database/model/index');
	}
	
	public function addAction()
	{
		$this->_view->title = 'Normal Add Form';
		$this->_view->link = base_url() . 'database/model/add';
		
		if(!empty($_POST))
		{
			$sql = "INSERT INTO users(first_name,last_name,email,address) VALUES('{$_POST['first_name']}','{$_POST['last_name']}','{$_POST['email']}','{$_POST['address']}')";	
	    	$db = Db::getInstance();
	    	$db->query($sql);
			redirect('database/model/index');
		}
		
		$this->renderView('database/model/_form');
	}
	
	public function editAction($id)
	{
		$db = Db::getInstance();
		$this->_view->title = 'Normal Edit Form';
		$this->_view->link = base_url() . 'database/model/edit/' . $id;
	    $row = $db->query("SELECT * FROM users WHERE user_id = " . $id)->fetch();
		
	    if(empty($row))
	    	redirect('database/model/index');
	    	
		if(!empty($_POST))
		{
			$sql = "UPDATE users SET first_name = '{$_POST['first_name']}',last_name = '{$_POST['last_name']}',email = '{$_POST['email']}',address = '{$_POST['address']}' WHERE user_id = {$id}";
	    	$db->query($sql);
	    	redirect('database/model/index');
		}
		
		$data = array(
			'first_name' => $row['first_name'],
			'last_name' => $row['last_name'],
			'email' => $row['email'],
			'address' => $row['address'],
		);
		
		$this->_view->data = $data;
		$this->renderView('database/model/_form');
	}	
	
	public function modelAction($offset = 0) 
	{
	    $this->_view->title = 'Model Database MVC';
	    
	    $users = new Users();
	    
	    $users->select('count(user_id) As Total');
	    $row = $users->query()->fetch();
	    
	    $items_per_page = 15;
	    $offset = ($offset % $items_per_page != 0 ? 0 : $offset);
	    
	    $users->select();
	    $users->orderBy('user_id','DESC');
	    $users->limit($offset,$items_per_page);
	    $rs = $users->query();
	    
	    $pages = new Paginator();
	    $pages->current_url = base_url() . 'database/model/model/%d';
	    $pages->offset = $offset;
	    $pages->items_per_page = $items_per_page;
	    
		$pages->items_total = $row['Total'];
		$pages->mid_range = 7;
		$pages->paginate();
		
		$this->_view->pages = $pages;
	    $this->_view->rs = $rs;		
	    
	    $this->_view->add_link = base_url() . 'database/model/add-model/';
	    $this->_view->edit_link = base_url() . 'database/model/edit-model/';
	    $this->_view->delete_link = base_url() . 'database/model/delete-model/';
	    
	    $this->renderView('database/model/index');	    
	}	

	public function deleteModelAction($id)
	{
		$users = new Users();
		$row = $users->get($id);
	    if(!empty($row))
	    {
	    	$users->delete($id);
	    }
	    redirect('database/model/model');
	}
	
	public function addModelAction()
	{
		$this->_view->title = 'Model Add Form';
		$this->_view->link = base_url() . 'database/model/add-model';
		
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
			
			if(sizeof($val->errors) == 0)
			{
				$users = new Users();
				$users->addValue('first_name',$_POST['first_name']);
				$users->addValue('last_name',$_POST['last_name']);
				$users->addValue('email',$_POST['email']);
				$users->addValue('address',$_POST['address']);
				$users->insert();
				redirect('database/model/model');
			}
			
			$this->_view->errorMessage = $val->errorMessage();
			$this->_view->data = $_POST;
		}
		
		$this->renderView('database/model/_form');
	}
	
	public function editModelAction($id)
	{
		$this->_view->title = 'Model Edit Form';		
		$this->_view->link = base_url() . 'database/model/edit-model/' . $id;		
		
		$users = new Users();
		$row = $users->get($id);
		
	    if(empty($row))
	    	redirect('database/model/model');

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
				$users->addValue('first_name',$_POST['first_name']);
				$users->addValue('last_name',$_POST['last_name']);
				$users->addValue('email',$_POST['email']);
				$users->addValue('address',$_POST['address']);
				$users->update($id);
		    	redirect('database/model/model');
			}
			$this->_view->errorMessage = $val->errorMessage();
			$this->_view->data = $_POST;
		}
		
		$this->renderView('database/model/_form');
	}	
	
}

?>
