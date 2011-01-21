<?php
include_once ('Person.class.php'); 
class PersonFunctions
{
	static function getPersons()
	{
		$strSql = 'SELECT * FROM person ORDER BY name;';
		$objPersons = new PDORecordset($strSql);
		$objPersons->execute(array(), 'Person');
		
		return $objPersons;
	}
	
	static function getPersonGroups()
	{
		$strSql  = 'SELECT person.*, groups.id as group_id, groups.name as group_name, person_groups.id as person_group_id ';
		$strSql .= 'FROM person ';
		$strSql .= 'LEFT JOIN person_groups ON person_groups.person_id=person.id ';
		$strSql .= 'LEFT JOIN groups ON groups.id=person_groups.group_id ';
		$strSql .= 'ORDER BY name, group_name;';
		$objPersonGroups = new PDORecordset($strSql);
		$objPersonGroups->execute();
		
		return $objPersonGroups;
	}
}
?>