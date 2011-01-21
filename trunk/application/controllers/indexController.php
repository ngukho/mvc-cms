<?php

class indexController extends BaseController
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index() 
	{
	    $this->_view->title = 'Welcome to Bui Van Tien Duc MVC';
	}
	
	public function part_render($bien1,$bien2 = NULL)
	{
//		$this->_view->_disableLayout = true;
	    $this->_view->title = 'Day la phan noi dung duoc render vao---';
	    $this->_view->render_title = "Tui dang lam gi vay ??? --- {$bien1} ---- {$bien2}";
	    return $this->_view->parser('index/part_render');
	}
	
	public function render()
	{
		$this->_view->title = 'Day la trang dung chuc nang renderAction';
//		$this->_view->part_render = $this->_view->renderAction('index','part_render',array('render_title'=>'Render Content'));
		$this->_view->part_render = FrontController::run("index/part_render",array("tham so 1"));
		$this->renderView('index/render');
	}	

}
