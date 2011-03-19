<?php

class Site_HomeController extends AdminController
{

	public function __construct()
	{
		parent::__construct();
	}

	public function indexAction() 
	{
		$_SESSION['tet'] = 'Bui van tien duc';
	    $this->_view->title = 'Welcome to Bui Van Tien Duc MVC RENDER : ' . $_SESSION['tet'];
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
		$this->oSession->userdata['c'] = 2000;
		$this->_view->title = 'Day la trang dung chuc nang renderAction --- '.$this->oSession->userdata['test'];
		$this->_view->part_render = Module::run(new Request('site/home/part-render',array('Title duoc truyen vao '.$this->oSession->userdata['c'])));
		$this->renderView('site/home/render');
	}	

}
