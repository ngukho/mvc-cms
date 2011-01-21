<?php
/* Example Program for 
	pdo-x Data Access Library for PHP5
	Version 0.5 alpha
*/

// Define the Database Connection Parameters

define ('PDO_DATABASE_DSN','mysql:host=localhost;dbname=pdo-x-example');
define ('PDO_DATABASE_USERNAME','root');
define ('PDO_DATABASE_PASSWORD','');
/*
define ('PDO_DATABASE_DSN','pgsql:host=localhost;dbname=pdo-x-example');
define ('PDO_DATABASE_USERNAME','');
define ('PDO_DATABASE_PASSWORD','');
*/

// Include the core pdo-x library (which simply includes the PDORecord and
//  PDORecordset classes and sets the Database Connection Parameter defines
include_once('../pdo-x/pdo-x.php');

// Include the Data Access classes
include_once('./DataAccessObjects/PersonFunctions.class.php');
include_once('./DataAccessObjects/GroupFunctions.class.php');
include_once('./DataAccessObjects/PersonGroup.class.php');

/* ************************** */
/* FORM REQUEST HANDLERS		*/
/* ************************** */

/* Process Add Person Request */
/* ************************** */
if (array_key_exists('add_person', $_POST))
{
	$arrErrorMessages = array();
	
	// Use the static validation method on the Person Record to make sure this 
	//  is a valid name
	$mixedError = Person::isInvalidName($_POST['name']); 
	if ($mixedError)
	{
		$arrErrorMessages[] = $mixedError;
	}
	
	// If the name is valid, make sure that a Person doesn't already exist with
	//  that same name
	if (count($arrErrorMessages) == 0)
	{
		$objPerson = new Person();
		// If we set a field before we load the record, that field value will be
		//  part of the WHERE clause of the internal SELECT query generated when
		//  the load method() is called
		$objPerson->setName($_POST['name']);
	
		// Load returns true if a matching record is loaded
		if ($objPerson->load())
		{
			$boolHasErrors = true;
			$arrErrorMessages[] = "A Person with the name '".$_POST['name']."' already exists!";
		}
	}
	
	// Use the static validation method on the Person Record to make sure this 
	//  is a valid email address
	$mixedError = Person::isInvalidEmail($_POST['email']); 
	if ($mixedError)
	{
		$arrErrorMessages[] = $mixedError;
	}
	
	// Use the static validation method on the Person Record to make sure this 
	//  is a valid phone number
	$mixedError = Person::isInvalidPhoneNumber($_POST['phone_number']); 
	if ($mixedError)
	{
		$arrErrorMessages[] = $mixedError;
	}
	
	// If we have reached this point without any errors, then go ahead and set
	// create the new Person Record
	if (count($arrErrorMessages) == 0)
	{
		// Set the other fields
		$objPerson->setPhoneNumber($_POST['phone_number']);
		$objPerson->setEmail($_POST['email']);

		// Call the save method to create the new record.  If the record had a
		// values in the primary key, calling save() would update the existing
		// record.
		$objPerson->save();
		
		// Forward to this same page again to avoid posting the same data
		//  if reload is clicked
		header('Location: .');
		exit;
	}
	else
	{
		// Display any error messages
		foreach($arrErrorMessages as $strErrorMessage)
		{
			echo "<div>".$strErrorMessage."</div>";
		}
	}
}

/* Process Delete Person Request */
/* ************************** */
if (array_key_exists('delete_person', $_POST))
{
	// Get a new connection that we can use to make the delete queries atomic in
	//  a transaction, so the person and its associations to groups must be
	//  deleted together or not at all
	$objConnection = Person::getNewConnection();
	
	// Begin the Transaction
	$objConnection->beginTransaction();
	try
	{
		// Pass the connection object into the constructor of the Record so that
		// operations upon that record will happen within the transaction 
		$objPerson = new Person($objConnection);
		
		// Set the Id of the Person record to be deleted
		$objPerson->setId($_POST['id']);
		// call the remove all groups method which uses the same connection as
		// the Record and so will also be inside the transaction
		$objPerson->removeAllGroups();
		
		// Now that the group connection of the Person have been deleted, we
		//  can delete te record itself. 
		$objPerson->delete();
		
		// Call commit to commit the changes to the database
		$objConnection->commit();
	}
	catch(Exception $e)
	{
		// If for some reason the transaction fails, catch the error and roll back
		// any changes in the transaction that may have been successful
		$objConnection->rollback();
		
		//rethrow the error
		throw $e;
	}
	
	// Forward to this same page again to avoid posting the same data
	//  if reload is clicked
	header('Location: .');
	exit;
}

/* Process Add Person To Group Request */
/* *********************************** */
if (array_key_exists('add_person_group', $_POST))
{
	// Instantiate the PersonGroup Record 
	$objPersonGroup = new PersonGroup();
	// Set the Fields
	$objPersonGroup->setPersonId($_POST['person_id']);
	$objPersonGroup->setGroupId($_POST['group_id']);
	
	// Call the save method to create the new record
	$objPersonGroup->save();
	
	// Forward to this same page again to avoid posting the same data
	//  if reload is clicked
	header('Location: .');
	exit;
}

/* Process Remove Person From Group Request */
/* *********************************** */
if (array_key_exists('remove_person_group', $_POST))
{
	// Instantiate the PersonGroup Record
	$objPersonGroup = new PersonGroup();
	
	// Set the primary key of the record to be deleted
	$objPersonGroup->setId($_POST['id']);
	
	// Call the delete method
	$objPersonGroup->delete();
	
	// Forward to this same page again to avoid posting the same data
	//  if reload is clicked
	header('Location: .');
	exit;
}

/* Process Add Group Request */
/* ************************** */
if (array_key_exists('add_group', $_POST))
{
	$arrErrorMessages = array();
	
	// Use the static validation method on the Group Record to make sure this 
	//  is a valid group name
	$mixedError = Group::isInvalidName($_POST['name']); 
	if ($mixedError)
	{
		$arrErrorMessages[] = $mixedError;
	}
	
	// If we have no errors then procede to create the group
	if (count($arrErrorMessages) == 0)
	{
		// instantiate a new Group record
		$objGroup = new Group();

		// If we set a field before we load the record, that field value will be
		//  part of the WHERE clause of the internal SELECT query generated when
		//  the load method() is called
				
		$objGroup->setName($_POST['name']);
		
		// Load returns true if a matching record is loaded
		if ($objGroup->load())
		{
			$arrErrorMessages[] = "A Group with the name '".$_POST['name']."' already exists!";
		}
		else
		{
			// Call the save() method to create the new record
			$objGroup->save();
			
			// Forward to this same page again to avoid posting the same data
			//  if reload is clicked
			header('Location: .');
			exit;
		}
	}
	else
	{
		// Display any error messages
		foreach($arrErrorMessages as $strErrorMessage)
		{
			echo "<div>".$strErrorMessage."</div>";
		}
	}
}

/* Process Delete Group Request */
/* **************************** */
if (array_key_exists('delete_group', $_POST))
{
	// Get a new connection that we can use to make the delete queries atomic in
	//  a transaction, so the group and its associations to people must be
	//  deleted together or not at all
	$objConnection = Group::getNewConnection();
	
	// Begin the transaction
	$objConnection->beginTransaction();
	try
	{
		// Pass the connection object into the constructor of the Record so that
		// operations upon that record will happen within the transaction
		$objGroup = new Group($objConnection);
		
		// Set the Id of the Group record to be deleted
		$objGroup->setId($_POST['id']);
		
		// call the remove all persons method which uses the same connection as
		// the Record and so will also be inside the transaction
		$objGroup->removeAllPersons();
		
		// Now that the people connections of the Group have been deleted, we
		//  can delete te record itself. 
		$objGroup->delete();
		
		// Call commit to commit the changes to the database
		$objConnection->commit();
	}
	catch(Exception $e)
	{
		// If for some reason the transaction fails, catch the error and roll back
		// any changes in the transaction that may have been successful
		$objConnection->rollback();
		
		// rethrow the error
		throw $e;
	}
	// Forward to this same page again to avoid posting the same data when
	//  if reload is clicked
	header('Location: .');
	exit;
}

/* ************************** */
/* PRESENTATION GENERATION		*/
/* ************************** */

/* Generate the HTML to display the groups */
/* *************************************** */
$strGroupHtml = '';

//Get a Recordset of Groups with People
$objGroupPersons = GroupFunctions::getGroupPersons();

//Get a Recordset of all People
$objPersons = PersonFunctions::getPersons();

// Loop through the GroupPersons Recordset and create table rows,
//  input elements, and JavaScript
for($i = 0; $i < $objGroupPersons->count(); $i++)
{
	// Individual Records in Recordsets can be retrieved as if the
	//  Recordset were an array
	$objGroupPerson = $objGroupPersons[$i];
	
	// The Fields of a Record should be retrieved using get[ColumnName] getter
	//  methods. Doing so will allow you to override indivdiual getters to modify
	// the data if necessary (for instance for encrypting/decrypting passwords)   
	$strGroupHtml .='<tr>';
	$strGroupHtml .='<td><input type="button" onclick="deleteGroup('.
						$objGroupPerson->getId().
						'); return false;" value="Delete"/></td>';
	$strGroupHtml .='<td>'.$objGroupPerson->getName().'</td>';
	$strGroupHtml .='<td>';
	
	//The $arrGrouppersonIds keeps track of Person Ids that are already in the 
	// group so that they can be excluded from the add person select box
	$arrGroupPersonIds = array();
	
	// Because the SQL uses a LEFT JOIN, a record will exist for the group, even
	// if it doesn't have any people in it.  So we print the name if it exists
	// otherwise we print a non-breaking space to make sure that the <td> renders
	if ($objGroupPerson->getPersonName())
	{
		// Make sure we keep track of the Person Ids already in this group so they
		// can be excluded from the add person select box
		$arrGroupPersonIds[$objGroupPerson->getPersonId()] = 1;
		
		$strGroupHtml .='<div><input type="button" onclick="removePersonGroup(\''.
								$objGroupPerson->getPersonGroupId().
								'\'); return false;" value="X"/> '.
								$objGroupPerson->getPersonName()."</div>";
	}
	else
	{
		$strGroupHtml .= "&#160;";
	}
	
	// Advance through subsequent Records as long as the Group id is the same to
	//  list additional peple in this group   
	while (isset($objGroupPersons[$i+1]) 
			 && $objGroupPersons[$i+1]->getId() == $objGroupPerson->getId())
	{
		$i++;
		// Make sure we keep track of the Person Ids already in this group so they
		// can be excluded from the add person select box
		$arrGroupPersonIds[$objGroupPersons[$i]->getPersonId()] = 1;
		
		$strGroupHtml .='<div><input type="button" onclick="removePersonGroup(\''.
								$objGroupPersons[$i]->getPersonGroupId().
								'\'); return false;" value="X"/> '.
								$objGroupPersons[$i]->getPersonName()."</div>";
	}
	
	// If there are people that are still not in this group then display an add
	// person select box
	if ($objPersons->count() > count($arrGroupPersonIds))
	{
		$strGroupHtml .= '<select id="person_select_'.
								$objGroupPerson->getId().'">';
		// Loop through the people and create select options for each one
		foreach($objPersons as $objPerson)
		{
			// Exclude the person from the select if they are already a member of
			//  the group
			if (!$arrGroupPersonIds[$objPerson->getId()])
			{
				$strGroupHtml .='<option value="'.$objPerson->getId().'">'.
										$objPerson->getName().'</option>';
			}
		}						
		$strGroupHtml .='</select>';
		$strGroupHtml .='<input type="button" '.
							 'onclick="addPersonGroup(document.getElementById('.
							 '\'person_select_'.$objGroupPerson->getId().'\').value,'.
							 $objGroupPerson->getId().
							 '); return false;" value="Add"/>';
	}
	
	$strGroupHtml .='</td></tr>';
}

/* Generate the HTML to display the groups */
/* *************************************** */
$strPersonHtml = '';

// Get a Recordset of People in Groups
$objPersonGroups = PersonFunctions::getPersonGroups();

// Get a Recordset of all Groups
$objGroups = GroupFunctions::getGroups();

// Loop through the PersonGroups Recordset and create table rows,
//  input elements, and JavaScript
for($i = 0; $i < $objPersonGroups->count(); $i++)
{
	// Individual Records in Recordsets can be retrieved as if the
	//  Recordset were an array
	$objPersonGroup = $objPersonGroups[$i];
	
	// The Fields of a Record should be retrieved using get[ColumnName] getter
	//  methods. Doing so will allow you to override indivdiual getters to modify
	//  the data if necessary (for instance for encrypting/decrypting passwords)
	$strPersonHtml .='<tr>';
	$strPersonHtml .='<td><input type="button" onclick="deletePerson('.
							$objPersonGroup->getId().
							'); return false;" value="Delete"/></td>';
	$strPersonHtml .='<td>&#160;'.$objPersonGroup->getName().'</td>';
	$strPersonHtml .='<td>&#160;'.$objPersonGroup->getEmail().'</td>';
	$strPersonHtml .='<td>&#160;'.$objPersonGroup->getPhoneNumber().'</td>';
	$strPersonHtml .='<td>';
	
	//The $arrPersonGroupIds keeps track of Group Ids to which the Person already 
	// belongs so that they can be excluded from the add group select box
	$arrPersonGroupIds = array();
	
	// Because the SQL uses a LEFT JOIN, a record will exist for the person, even
	//  if they don't belong to any groups.  So we print the group name if it
	//  exists otherwise we print a non-breaking space to make sure that the
	// <td> renders
	if ($objPersonGroup->getGroupName())
	{
		// Make sure we keep track of the Group Ids to which this user already
		//  exists so they can be excluded from the add group select box
		$arrPersonGroupIds[$objPersonGroup->getGroupId()] = 1;
		
		$strPersonHtml.='<div><input type="button" onclick="removePersonGroup(\''.
								$objPersonGroup->getPersonGroupId().
								'\'); return false;" value="X"/> ' .
								$objPersonGroup->getGroupName()."</div>";
	}
	else
	{
		$strPersonHtml .= "&#160;";
	}
	
	// Advance through subsequent Records as long as the Person id is the same to
	//  list additional groups to which the person belongs 
	while (isset($objPersonGroups[$i+1]) 
			 && $objPersonGroups[$i+1]->getId() == $objPersonGroup->getId())
	{
		$i++;
		
		// Make sure we keep track of the Group Ids to which this user already
		//  exists so they can be excluded from the add group select box
		$arrPersonGroupIds[$objPersonGroups[$i]->getGroupId()] = 1;
		
		$strPersonHtml.='<div><input type="button" onclick="removePersonGroup(\''.
							 $objPersonGroups[$i]->getPersonGroupId().
							 '\'); return false;" value="X"/> ' .
							  $objPersonGroups[$i]->getGroupName()."</div>";
	}
	// If there are groups to which the person still does not belong then display
	// an add group select box
	if ($objGroups->count() > count($arrPersonGroupIds))
	{
		$strPersonHtml .= '<select id="group_select_'.
								 $objPersonGroup->getId().'">';
		// Loop through the groups and create select options for each one
		foreach($objGroups as $objGroup)
		{
			// Exclude the group from the select if the person is already a member
			// of the group
			if (!$arrPersonGroupIds[$objGroup->getId()])
			{
				$strPersonHtml .='<option value="'.$objGroup->getId().'">'.
										$objGroup->getName().'</option>';
			}
		}						
		$strPersonHtml .='</select>';
		$strPersonHtml .='<input type="button" onclick="addPersonGroup('.
								$objPersonGroup->getId().
								',document.getElementById(\'group_select_'.
								$objPersonGroup->getId().
								'\').value); return false;" value="Add"/>';
	}
	$strPersonHtml .='</td></tr>';
}
?>
<html>
	<head>
		<title>pdo-x example</title>
		<style type="text/css">
			table
			{
				width: 100%;
				margin-top: 15px;;
			}
			td
			{
				vertical-align: top;
				padding: 3px;
				border-top: 1px solid grey;
				border-right: 1px solid grey;
				font-size: 12px;
			}
			th
			{
				text-align: left;
				border-bottom: 1px solid grey;
			}
			input, select
			{
				font-size: 10px;
			}
			select
			{
				margin-top: 5px;
			}
			form
			{
				margin: 0;
				padding: 0 0 3px 0;
			}
			h1
			{
				margin: 0 0 5px 0;
				padding: 0;
			}
			.Panel
			{
				width: 550px;
				font-size: 12px;
				margin: 10px;
				border: 1px solid grey;
				padding: 10px;
			}
		</style>
		<script type="text/javascript">
			function $(Id)
			{
				return document.getElementById(Id);
			}
			function addPersonGroup(intPersonId, intGroupId)
			{
				$('add_person_group_person_id').value = intPersonId;
				$('add_person_group_group_id').value = intGroupId;
				$('add_person_group_form').submit();	
			}
			
			function removePersonGroup(intId)
			{
				$('remove_person_group_id').value = intId;
				$('remove_person_group_form').submit();
			}
			
			function deletePerson(intId)
			{
				$('delete_person_id').value = intId;
				$('delete_person_form').submit();
			}
			
			function deleteGroup(intId)
			{
				$('delete_group_id').value = intId;
				$('delete_group_form').submit();
			}
		</script>
	</head>
	<body>
		<noscript>
		  This example requires JavaScript to execute properly. Please enable
		  JavaScript in your browser
		</noscript>
		<div class="Panel">
			<h1>Groups</h1>
			<form id="add_group_form" method="post">
				Group Name: <input type="text" id="add_group_name" name="name" />
				<input type="submit" id="add_group_submit" name="add_group"
					 value="Add" />
			</form>
			
			<table border="0" cellpadding="0" cellspacing="0">
				<tbody>
					<tr>
						<th>&#160;</th>
						<th>Name</th>
						<th>People</th>
					</tr>
					<?php echo $strGroupHtml; ?> 
				</tbody>
			</table>
		</div>
		<div class="Panel">
			<h1>People</h1>
			<form id="add_person_form" method="post">
				Name: <input type="text" id="add_person_name" name="name" />
				Email: <input type="text" id="add_person_email" name="email" />
				Phone: <input type="text" id="add_person_phone" 
							name="phone_number" />
				<input type="submit" id="add_person_submit" name="add_person"
				  value="Add" />
			</form>
			<table border="0" cellpadding="0" cellspacing="0">
				<tbody>
					<tr>
						<th>&#160;</th>
						<th>Name</th>
						<th>Email</th>
						<th>Phone</th>
						<th>Groups</th>
					</tr>
					<?php echo $strPersonHtml; ?> 
				</tbody>
			</table>
		</div>
				
		<form id="add_person_group_form" method="post">
			<input type="hidden" name="add_person_group" value="1" />
			<input type="hidden" id="add_person_group_person_id"
				 name="person_id" />
			<input type="hidden" id="add_person_group_group_id" name="group_id" />
		</form>
		<form id="remove_person_group_form" method="post">
			<input type="hidden" name="remove_person_group" value="1" />
			<input type="hidden" id="remove_person_group_id" name="id" />
		</form>
		<form id="delete_person_form" method="post">
			<input type="hidden" name="delete_person" value="1" />
			<input type="hidden" id="delete_person_id" name="id" />
		</form>
		<form id="delete_group_form" method="post">
			<input type="hidden" name="delete_group" value="1" />
			<input type="hidden" id="delete_group_id" name="id" />
		</form>
	</body>
</html>