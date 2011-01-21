<?php 
include_once ('Group.class.php');
class GroupFunctions
{
	static function getGroups()
	{
		$strSql = 'SELECT * FROM groups ORDER BY name;';
		$objGroups = new PDORecordset($strSql);
		$objGroups->execute(array(), 'Group');
		
		return $objGroups;
	}
	
	static function getGroupPersons()
	{
		$strSql  = "SELECT groups.*, person.name as person_name, person.id as person_id, person_groups.id as person_group_id ";
		$strSql .= "FROM groups ";
		$strSql .= "LEFT JOIN person_groups ON person_groups.group_id=groups.id ";
		$strSql .= "LEFT JOIN person ON person.id=person_groups.person_id ";
		$strSql .= "ORDER BY name, person_name;";
		$objGroupsPersons = new PDORecordset($strSql);
		$objGroupsPersons->execute();
		
		return $objGroupsPersons;
	}
}
?>