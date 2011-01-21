<?php

/*** include PDO library ***/
require_once __SITE_PATH . '/lib/' . 'PDORecord.class.php';
require_once __SITE_PATH . '/lib/' . 'PDORecordset.class.php';


Class ModelBase extends PDORecord 
{

	protected $_table_name;
	protected $_primary_key;

	function __construct()
	{
		$this->setTableName($this->_table_name);
		$this->setPrimaryKeys($this->_primary_key);
		
		parent::__construct();
	}

	
}

?>
