<?php

Class Paging_PageController Extends BaseController 
{

	function __construct()
	{
		parent::__construct();
	}
	
	public function indexAction() 
	{
	    $this->_view->title = 'Welcome to Bui Van Tien Duc MVC';
	    $this->renderView('paging/page/index');
	}
	
	public function paginatorAction($offset = 0) 
	{
	    $this->_view->title = 'Welcome to Paginator MVC';
	    
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
		$offset = ($offset % $items_per_page != 0 ? 0 : $offset);
		
	    $pages = new Paginator();
	    $pages->current_url = base_url() . 'paging/page/paginator/%d';
	    $pages->offset = $offset;
	    $pages->items_per_page = $items_per_page;
	    
		$pages->items_total = 1252;
		$pages->mid_range = 10;
		$pages->paginate();
		
		$this->_view->pages = $pages;
		
		$this->renderView('paging/page/paginator');
	}	

}

?>
