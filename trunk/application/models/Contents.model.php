<?php

class Contents extends SimpleActiveRecord
{
//	public $has_many = array( 'blogposts' => 'Blogpost' );
//	public $belongs_to = array( 'customer' => 'Customer' );
//	public $serialize = 'meta';

	protected $primaryKey = 'id';
	protected $tableName = TB_CONTENTS;

//	public function __construct()
//	{
//		parent::__construct();
//	}
//
//    public function checkLogin($username, $password)
//    {
//    	$row = $this->getRow('username = ? AND password = ?',array($username,$password));
//    	return $row;
//    }	
	
}

?>