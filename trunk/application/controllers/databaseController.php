<?php

Class databaseController Extends BaseController 
{

	function __construct()
	{
		parent::__construct();
	}
	
	public function index() 
	{
	    $this->_view->title = 'Normal Database MVC';
	    
	    $db = Db::getInstance();
	    
//	    $db->query("SELECT * FROM customers")->fetch();
	    
		// Get one row
	    $row = $db->query("SELECT count(user_id) As Total FROM users")->fetch();
	    
	    $items_per_page = 15;
	    $offset = isset($_GET['offset'])?($_GET['offset'] % $items_per_page != 0?0:$_GET['offset']):0;
//	    $limit = " limit " . ($current_page - 1) * $items_per_page . "," . $items_per_page;
	    
	    $rs = $db->query("SELECT * FROM users ORDER BY user_id DESC limit {$offset},{$items_per_page} ");
	    
	    $pages = new Paginator();
	    $pages->current_url = base_url() . 'database/index';
	    $pages->offset = $offset;
	    $pages->items_per_page = $items_per_page;
	    
		$pages->items_total = $row['Total'];
		$pages->mid_range = 7;
		$pages->paginate();
		
		$this->_view->pages = $pages;
	    $this->_view->rs = $rs;
	    
	    $this->_view->add_link = base_url() . 'database/add';
	    $this->_view->edit_link = base_url() . 'database/edit';
	    $this->_view->delete_link = base_url() . 'database/delete';
	    
	    $this->renderView('database/index');

	}
	
	public function delete()
	{
		$id = $_GET['id'];
	    $db = Db::getInstance();
	    $row = $db->query("SELECT * FROM users WHERE user_id = " . $id)->fetch();
	    if(!empty($row))
	    {
			$db->query("DELETE FROM users WHERE user_id = " . $id);
	    }
	    redirect('database/index');
	}
	
	public function add()
	{
		$this->_view->title = 'Normal Add Form';
		$this->_view->link = base_url() . 'database/add';
		
		if(!empty($_POST))
		{
			$sql = "INSERT INTO users(first_name,last_name,email,address) VALUES('{$_POST['first_name']}','{$_POST['last_name']}','{$_POST['email']}','{$_POST['address']}')";	
	    	$db = Db::getInstance();
	    	$db->query($sql);
			redirect('database/index');
		}
		
		$this->renderView('database/_form');
	}
	
	public function edit()
	{
		$db = Db::getInstance();
		$this->_view->title = 'Normal Edit Form';
		$id = $_GET['id'];
		$this->_view->link = base_url() . 'database/edit/id/' . $id;
	    $row = $db->query("SELECT * FROM users WHERE user_id = " . $id)->fetch();
		
	    if(empty($row))
	    	redirect('database/index');
	    	
		if(!empty($_POST))
		{
			$sql = "UPDATE users SET first_name = '{$_POST['first_name']}',last_name = '{$_POST['last_name']}',email = '{$_POST['email']}',address = '{$_POST['address']}' WHERE user_id = {$id}";
	    	$db->query($sql);
	    	redirect('database/index');
		}
		
		$data = array(
			'first_name' => $row['first_name'],
			'last_name' => $row['last_name'],
			'email' => $row['email'],
			'address' => $row['address'],
		);
		
		$this->_view->data = $data;
		$this->renderView('database/_form');
	}	
	
	
	public function model() 
	{
	    $this->_view->title = 'Model Database MVC';
	    
	    $users = new Users();
	    
	    $users->select('count(user_id) As Total');
	    $row = $users->query()->fetch();
	    
	    $items_per_page = 15;
	    $offset = isset($_GET['offset'])?($_GET['offset'] % $items_per_page != 0?0:$_GET['offset']):0;
	    
	    $users->select();
	    $users->orderBy('user_id','DESC');
	    $users->limit($offset,$items_per_page);
	    $rs = $users->query();
	    
	    $pages = new Paginator();
	    $pages->current_url = base_url() . 'database/model';
	    $pages->offset = $offset;
	    $pages->items_per_page = $items_per_page;
	    
		$pages->items_total = $row['Total'];
		$pages->mid_range = 7;
		$pages->paginate();
		
		$this->_view->pages = $pages;
	    $this->_view->rs = $rs;		
	    
	    $this->_view->add_link = base_url() . 'database/add_model';
	    $this->_view->edit_link = base_url() . 'database/edit_model';
	    $this->_view->delete_link = base_url() . 'database/delete_model';
	    
	    $this->renderView('database/index');	    
	}	

	public function delete_model()
	{
		$id = $_GET['id'];
		$users = new Users();
		$row = $users->get($id);
	    if(!empty($row))
	    {
	    	$users->delete($id);
	    }
	    redirect('database/model');
	}
	
	public function add_model()
	{
		$this->_view->title = 'Model Add Form';
		$this->_view->link = base_url() . 'database/add_model';
		
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
				redirect('database/model');
			}
			
			$this->_view->errorMessage = $val->errorMessage();
			$this->_view->data = $_POST;
		}
		
		$this->renderView('database/_form');
	}
	
	public function edit_model()
	{
		$this->_view->title = 'Model Edit Form';		
		$id = $_GET['id'];
		$this->_view->link = base_url() . 'database/edit_model/id/' . $id;		
		
		$users = new Users();
		$row = $users->get($id);
		
	    if(empty($row))
	    	redirect('database/model');

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
		    	redirect('database/model');
			}
			$this->_view->errorMessage = $val->errorMessage();
			$this->_view->data = $_POST;
		}
		
		$this->renderView('database/_form');
	}	
	
	
}

?>
