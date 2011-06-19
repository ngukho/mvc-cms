<?php

class ActiveRecord_ActiveRecordController extends BaseController 
{

	function __construct()
	{
		parent::__construct();
	}
	
	public function indexAction()
	{
		return $this->forward('active-record/active-record/show');
	}
	
	public function showAction($offset = 0) 
	{
	    $this->_view->title = 'ActiveRecord Database MVC';
	    
	    $oContentCats = new ContentCats(2);
	    
	    $rsCat = $oContentCats->find();
	    
	    $oContents = new Contents();
	    
	    $items_per_page = 15;
	    $offset = ($offset % $items_per_page != 0 ? 0 : $offset);
	    
		$rs = $oContents->find(NULL,'sort ASC',NULL,"$offset,$items_per_page");
	    
	    $pages = new Paginator();
	    $pages->current_url = base_url() . 'active-record/active-record/show/%d';
	    $pages->offset = $offset;
	    $pages->items_per_page = $items_per_page;
	    
		$pages->items_total = $oContents->getTotalRow();
		$pages->mid_range = 7;
		$pages->paginate();
		
		$this->_view->pages = $pages;
	    $this->_view->rs = $rs;
	    
   	    $this->_view->rsCat = $rsCat;
	    
	    $this->_view->filter_link = base_url() . 'active-record/active-record/show';
	    $this->_view->add_link = base_url() . 'active-record/active-record/ar-add-model/';
	    $this->_view->edit_link = base_url() . 'active-record/active-record/ar-edit-model/';
	    $this->_view->delete_link = base_url() . 'active-record/active-record/ar-delete-model/';
	    
	    $this->renderView('active-record/active-record/show');
	}	

	public function arDeleteModelAction($id)
	{
		$content = new Contents($id);
	    if(!empty($content))
	    {
	    	$content->destroy();
	    }
	    redirect('active-record/active-record');
	}
	
	public function arAddModelAction()
	{
		$this->_view->title = 'Active Record Add Form';
		$this->_view->link = base_url() . 'active-record/active-record/ar-add-model';
		
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
				redirect('active-record/active-record');
			}
			
			$this->_view->errorMessage = $val->errorMessage();
			$this->_view->data = $_POST;
		}
		
		$this->renderView('active-record/active-record/_form');
	}
	
	public function arEditModelAction($id)
	{
		$this->_view->title = 'Model Edit Form';		
		$this->_view->link = base_url() . 'active-record/active-record/ar-edit-model/' . $id;		
		
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
		    	redirect('active-record/active-record');
			}
			$this->_view->errorMessage = $val->errorMessage();
			$this->_view->data = $_POST;
		}
		
		$this->renderView('active-record/active-record/_form');
	}	
	
}

?>
