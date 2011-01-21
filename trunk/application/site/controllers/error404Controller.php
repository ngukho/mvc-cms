<?php

class error404Controller extends BaseController implements IController
{
	/**
	*
	* Constructor, duh
	*
	*/
	public function __construct()
	{
		parent::__construct();
	}

	/**
	*
	* The index function
	*
	* @access	public
	*
	*/
	public function index()
	{
		$this->_view->_disableLayout = true;
		$this->renderView('error404');
	}

}
