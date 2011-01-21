<?php

Class pagingController Extends BaseController 
{

	function __construct()
	{
		parent::__construct();
	}
	
	public function index() 
	{
	    $this->_view->title = 'Welcome to Bui Van Tien Duc MVC';
	}
	
	public function paginator() 
	{
	    $this->_view->title = 'Welcome to Bui Van Tien Duc MVC';
	    
//	    $pages = new Paginator();
//	    $pages->current_url = base_url() . 'paging/index';
//	    $pages->current_page = isset($_GET['page']) ? $_GET['page'] : 1;
//	    
//		$pages->items_total = 1202;
//		$pages->mid_range = 7;
//		$pages->paginate();
//		
//		$this->_temp->pages = $pages;
		
		$items_per_page = 25;
		$offset = isset($_GET['offset'])?($_GET['offset'] % $items_per_page != 0?0:$_GET['offset']):0;
	    $pages = new Paginator();
	    $pages->current_url = base_url() . 'paging/paginator';
	    $pages->offset = $offset;
	    $pages->items_per_page = $items_per_page;
	    
		$pages->items_total = 1252;
		$pages->mid_range = 10;
		$pages->paginate();
		
		$this->_view->pages = $pages;
		

	}	

}

?>
