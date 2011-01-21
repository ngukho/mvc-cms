<?php
class Group extends PDORecord
{
	const REGEX_GROUP_NAME = "/^[a-zA-Z0-9\s.\-]+$/";
	
	function __construct($objConnection = null)
	{
		$this->setTableName('groups');
		$this->setPrimaryKeys('id');
		
		parent::__construct($objConnection);
	}
	
	function getPersons()
	{
		$strSql  = 'SELECT person.* ';
		$strSql .= 'FROM person ';
		$strSql .= 'JOIN person_groups ON person_groups.person_id = person.id ';
		$strSql .= 'WHERE person_groups.group_id = ?;';
		$objPeople = new PDORecordset($strSql);
		$objPersons->execute(array($this->getId()), "Person");
		
		return $objPersons;
	}
	
	function removeAllPersons()
	{
		$strSql  = 'DELETE FROM person_groups ';
		$strSql .= 'WHERE group_id=?;';
		$objPersonGroups = new PDORecordset($strSql,$this->getConnection());
		
		return $objPersonGroups->execute(array($this->getId()));
	}
	
	static function isInvalidName($strName)
	{
		if (empty($strName))
		{
			 return "Group Name is required.";
		}
		
		if(!preg_match(self::REGEX_GROUP_NAME, $strName))
		{
			return "Invalid Group Name.";
		}
		
		return false;
	}
}
?>