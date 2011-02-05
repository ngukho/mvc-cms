<?php

class Site_HomeController extends BaseController
{

	public function __construct()
	{
		parent::__construct();
	}

	public function indexAction() 
	{
	    $this->_view->title = 'Welcome to Bui Van Tien Duc MVC RENDER';
	    $this->renderView('site/home/index');
	}
	
	public function partRenderAction($title)
	{
	    $this->_view->title = 'Day la phan noi dung duoc render vao';
	    $this->_view->render_title = $title;
	    return $this->_view->fetch('site/home/part_render');
	}
	
	public function renderAction()
	{
		$this->_view->title = 'Day la trang dung chuc nang renderAction';
		$this->_view->part_render = FrontController::run(new Request('site/home/part-render',array('Title duoc truyen vao')));
		$this->renderView('site/home/render');
	}	

}
