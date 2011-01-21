<?php
class PersonGroup extends PDORecord
{
	function __construct($objConnection = null)
	{
		$this->setTableName('person_groups');
		$this->setPrimaryKeys('id');
		
		parent::__construct($objConnection);
	}
}
?>