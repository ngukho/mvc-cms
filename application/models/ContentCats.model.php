<?php

class Users extends Model 
{
	protected $_table_name = TB_USERS;
	protected $_primary_key = 'user_id';
	
	public function __construct()
	{
		parent::__construct();
	}

    public function checkLogin($username, $password)
    {
    	$row = $this->getRow('username = ? AND password = ?',array($username,$password));
    	return $row;
    }	
	
}

?>