<?php

class Contents extends SimpleActiveRecord
{
//	public $has_many = array( 'blogposts' => 'Blogpost' );

//	public $serialize = 'meta';

	protected $primaryKey = 'id';
	protected $tableName = TB_CONTENTS;

//	public $has_many = array( 'oContents' => 'Contents:cat_id' );	
//	public $belongs_to = array( 'oContentCat' => 'ContentCats:cat_id' );
	public $belongs_to = array( 
		'oContentCat' => array(
			'columns' => 'cat_id' ,
			'refTableClass' => 'ContentCats:id')
	);
	
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