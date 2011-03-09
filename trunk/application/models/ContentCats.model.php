<?php

class ContentCats extends SimpleActiveRecord 
{
	protected $primaryKey = 'id';
	protected $tableName = TB_CONTENT_CATS;
	
	public $has_many = array( 'oContents' => 'Contents:cat_id' );
//	public $belongs_to = array( 'customer' => 'Customer' );
//	public $serialize = 'meta';

}

?>