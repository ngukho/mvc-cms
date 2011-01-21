<?php
class Person extends PDORecord
{
	const REGEX_PERSON_NAME = "/^[a-zA-Z]+(([\'\,\.\- ][a-zA-Z ])?[a-zA-Z]*)*$/";
	const REGEX_EMAIL = '/^([a-zA-Z0-9])+\+?([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/';
	const REGEX_PHONE_US = '/^[(]?[2-9]{1}[0-9]{2}[) -.]{0,2}[0-9]{3}[- .]?[0-9]{4}[ ]?((x|ext)[.]?[ ]?[0-9]{1,5})?$/';
	
	function __construct($objConnection = null)
	{
		$this->setTableName('person');
		$this->setPrimaryKeys('id');
		
		parent::__construct($objConnection);
	}
	
	function getGroups()
	{
		$strSql  = 'SELECT groups.* ';
		$strSql .= 'FROM groups ';
		$strSql .= 'JOIN person_groups ON person_groups.group_id = groups.id ';
		$strSql .= 'WHERE person_groups.person_id = ?;';
		$objGroups = new PDORecordset($strSql);
		$objGroups->execute(array($this->getId()), "Group");
		
		return $objGroups;
	}
	
	function removeAllGroups()
	{
		$strSql  = 'DELETE FROM person_groups ';
		$strSql .= 'WHERE person_id=?;';
		$objPersonGroups = new PDORecordset($strSql,$this->getConnection());
		return $objPersonGroups->execute(array($this->getId()));
	}
	
	static function isInvalidName($strName)
	{
		if (empty($strName))
		{
			 return "Person Name is required.";
		}
		
		if(!preg_match(self::REGEX_PERSON_NAME, $strName))
		{
			return "Invalid Person Name";
		}
		
		return false;
	}
	
	static function isInvalidEmail($strEmail)
	{
		if(!empty($strEmail) && !preg_match(self::REGEX_EMAIL, $strEmail))
		{
			return "Invalid Email Address.";
		}
		
		return false;
	}
	
	static function isInvalidPhoneNumber($strPhoneNumber)
	{
		if(!empty($strPhoneNumber) && !preg_match(self::REGEX_PHONE_US, $strPhoneNumber))
		{
			return "Invalid Phone Number.";
		}
		
		return false;
	}
}
?>