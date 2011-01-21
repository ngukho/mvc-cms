<?php
/*
* pdo-x Data Access Library for PHP5
* Version 0.5 alpha 
* Copyright (c) 2007, J. Max Wilson
* All rights reserved.
*
* Redistribution and use in source and binary forms, with or without
* modification, are permitted provided that the following conditions are met:
*     * Redistributions of source code must retain the above copyright
*       notice, this list of conditions and the following disclaimer.
*     * Redistributions in binary form must reproduce the above copyright
*       notice, this list of conditions and the following disclaimer in the
*       documentation and/or other materials provided with the distribution.
*     * Neither the name of J. Max Wilson nor the
*       names of any other contributors may be used to endorse or promote products
*       derived from this software without specific prior written permission.
*
* THIS SOFTWARE IS PROVIDED BY J. MAX WILSON "AS IS" AND ANY
* EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
* WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
* DISCLAIMED. IN NO EVENT SHALL J. MAX WILSON BE LIABLE FOR ANY
* DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
* (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
* LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
* ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
* (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
* SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

/**
	 * Class PDORecord
	 * Acts as a base class for accessing an individual record
	 * in a database table as part of the pdo-x Data Access library
	 *
	 * @return void
	 *
	 * @package	  pdo-x
	 *
	 * @author    J. Max Wilson
	 * @copyright &copy 2006
	 * @version   $Id
	 */
class PDORecord
{
	// Member Variables
	private $objConnection;
	private $strTableName;
	private $arrPrimaryKeys;
	private $arrRow;
	private $arrLoadedRow;
	private $boolIsNew;
	private $intColumnNamingConvention = self::UNDERSCORE_NAMES;
	const UNDERSCORE_NAMES = 1;
	const CAMELCASE_NAMES = 2;
	
	/**
	 * Construct a PDORecord object
	 *
	 * @author	J. Max Wilson
	 *
	 * @access	public	  
	 *	 
	 * @param	obj PDO optional connection to use for transaction
	 * 	 	 
	 * @return	object PDORecord
	 */
	public function __construct(&$objConnection = null)
	{
		if ($objConnection instanceof PDO)
		{
			$this->objConnection = $objConnection;
		}
		else
		{
			$this->objConnection = self::getNewConnection();
		}
		
		$this->boolIsNew = true;
	}
	
	/**
	 * Static method gets a new PDO object connection to a database
	 *
	 * @author	J. Max Wilson
	 *
	 * @access	public	  
	 *	 
	 * @param	string PDO DSN - "mysql:host=localhost;dbname=test" or 'pgsql:host=localhost;dbname=test'
	 * @param	string username to use for database connection 	 
	 * @param	string password to use for database connection
	 * 	 	 
	 * @return	object PDO connection
	 */
	public static function getNewConnection($strDsn = PDO_DATABASE_DSN, $strUsername = DB_USERNAME, $strPassword = DB_PASSWORD)
	{
		$objConnection = null;
		try
		{
			$objConnection = new PDO($strDsn, $strUsername, $strPassword);
			$objConnection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		}
		catch (PDOException $e)
		{
			throw $e;		
		}
		
		return $objConnection;
	} 
	
	/**
	 * Allows fields in the result array pulled from the database to be accessed
	 *  as if they were public member variables of the PDORecord	 
	 *
	 * @author 	J. Max Wilson
	 * 
	 * @param	string Fieldname is the key of the field in the row array for the record
	 * 	 	 
	 * @return	mixed value of field
	 */
	public function __get($strFieldName)
	{
		if (is_array($this->arrRow) && array_key_exists($strFieldName, $this->arrRow))
		{
			return $this->arrRow[$strFieldName];
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * Allows fields in the result array pulled from the database to be set as
	 *  if they were public member variables of PDORecord	 
	 *
	 * @author 	J. Max Wilson
	 * 
	 * @param	string Fieldname is the key of the field in the row array for the record
	 * @param	mixed Value to be assigned to the field 	 
	 * 	 	 
	 * @return	null
	 */
	public function __set($strFieldName, $mixedValue)
	{
		if ($this->intColumnNamingConvention == self::UNDERSCORE_NAMES)
			$strFieldName = (preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $strFieldName));
		$this->arrRow[$strFieldName] = $mixedValue;
	}
	
	/**
	 * Allows fields in the result array pulled from the database to be get or set
	 *  if they had public get and set methods
	 *  ->getFieldname();
	 *  ->setFieldname($value);
	 *
	 * On the get method it first tried to match the field to whatever follows the word 'get' exactly as
	 * it appears when the method is called.  If it can't find that field it attempts to find it in all lowercase
	 * if it can't find that field it converts CamelCase to lower case with underscores separating the words.
	 * 
	 * So if you have a field in the database called group_name it can be accessed as ->getGroupName();	 	 	  	 	 
	 *	 
	 * @author 	J. Max Wilson
	 * 
	 * @param	string method to be called
	 * @param	array method arguments 	 
	 * 	 	 
	 * @return	the return value of the method called
	 */
	public function __call($method, $args)
	{
		if (!method_exists($this->_obj, $method))
		{
			if (stripos($method, "get") === 0)
			{
				$strFieldName = substr($method,3);
				if (is_array($this->arrRow) && array_key_exists($strFieldName, $this->arrRow))
				{
					return $this->__get($strFieldName);
				}
				else if (is_array($this->arrRow) && array_key_exists(strtolower($strFieldName), $this->arrRow))
				{
					return $this->__get(strtolower($strFieldName));
				}
				else if (is_array($this->arrRow) && array_key_exists(strtoupper($strFieldName), $this->arrRow))
				{
					return $this->__get(strtoupper($strFieldName));
				}
				else 
				{
					$strFieldName = strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $strFieldName));
					if (is_array($this->arrRow) && array_key_exists($strFieldName, $this->arrRow))
					{
						return $this->__get($strFieldName);
					}
				}
				return $this->__get($strFieldName);
				
			}
			else if (stripos($method, "set") === 0)
			{
				$strFieldName = substr($method,3);
				if (is_array($this->arrRow) && array_key_exists($strFieldName, $this->arrRow))
				{
					return $this->__set($strFieldName,$args[0]);
				}
				else if (is_array($this->arrRow) && array_key_exists(strtolower($strFieldName), $this->arrRow))
				{
					return $this->__set(strtolower($strFieldName),$args[0]);
				}
				else if (is_array($this->arrRow) && array_key_exists(strtoupper($strFieldName), $this->arrRow))
				{
					return $this->__set(strtoupper($strFieldName),$args[0]);
				}
				else 
				{
					$strFieldNameUnderscored = strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $strFieldName));
					if (is_array($this->arrRow) && array_key_exists($strFieldNameUnderscored, $this->arrRow))
					{
						return $this->__set($strFieldNameUnderscored,$args[0]);
					}
				}
				
				return $this->__set($strFieldName,$args[0]);
			}
			
		}
		else
		{
			return call_user_func_array(array($this->_obj, $method), $args);
		}
		
		throw new Exception('Call to undefined method '.get_class($this).'::'.$method.'()');
	}
	
	/**
	 * method is used to set the table name when extending PDORecord 
	 *   to create an data access object for a specific table	 	 
	 *
	 * @author 	J. Max Wilson
	 * 
	 * @param	string Table Name 	 
	 * 	 	 
	 * @return	null
	 */
	function setTableName($strTableName)
	{
		$this->strTableName = $strTableName;
	}
	
	/**
	 * Gets the name of the table for the PDORecord object	 	 
	 *
	 * @author 	J. Max Wilson
	 * 
	 * @param	string Table Name 	 
	 * 	 	 
	 * @return	null
	 */
	public function getTableName()
	{
		return $this->strTableName;
	}
	
	/**
	 * method is used to set the primary keys when extending PDORecord 
	 *   to create an data access object for a specific table	 	 
	 *
	 * @author 	J. Max Wilson
	 * 
	 * @param	accepts variable arguments, each argument a string name of a primary key field	  	 
	 * 	 	 
	 * @return	null
	 */
	function setPrimaryKeys()
	{
		$this->arrPrimaryKeys = func_get_args();
	}
	
	/**
	 * Gets an array of strings of the fieldnames of the primary keys for the PDORecord	 	 
	 *
	 * @author 	J. Max Wilson 	 
	 * 	 	 
	 * @return	array of field names of the primary keys
	 */
	public function getPrimaryKeys()
	{
		return $this->arrPrimaryKeys;
	}
	
	/**
	 * Sets the naming convention for the DBcolumn names for converting between the 
	 * CamelCase of the pseudo Setters and the column names of the database	 
	 * Default is UNDERSCORE
	 * 
	 * PDORecord::UNDERSCORE_NAMES
	 * PDORecord::CAMELCASE_NAMES	 	 	 	 	 	 	 
	 *
	 * @author 	J. Max Wilson 	 
	 * 	 	 
	 * @return	array of field names of the primary keys
	 */
	public function setColumnNamingConvention($intConvention = self::UNDERSCORE_NAMES)
	{
		$this->intColumnNamingConvention = $intConvention;
	}
	
	/**
	 * Loads the PDORecord from the database based upon the field values set on
	 *  the object.  Will throw an error if more than one record corresponds to 
	 *  the fields set	 	 	 	 
	 *
	 * @author 	J. Max Wilson 	 
	 * 	 	 
	 * @return	bool true if the record was loaded, false if it failed
	 */
	public function load()
	{ 
		$strSql = "SELECT * FROM ";	
		$strSqlQuery = $this->strTableName . " WHERE ";
		$arrValues = array();
		$boolFirst = true;
		foreach($this->arrRow as $strKeyField => $strValue)
		{
			if ($strValue !== null)
			{
				if ($boolFirst != true)
				{
					$strSqlQuery .= " AND ";
				}
				else
				{
					$boolFirst = false;	
				}
				
				if (is_string($strValue))
				{
					$strSqlQuery .= $strKeyField . " LIKE ?";
					if(strtotime($strValue) !== false)
					{
						$strValue .= "%";  
					}
				}
				else if (is_bool($strValue))
				{
					$strSqlQuery .= $strKeyField . " = ?";
					$strValue = ($strValue?1:0);
				}
				else
				{
					$strSqlQuery .= $strKeyField . " = ?";
				}
				
				$arrValues[] = $strValue;
			}
		}
		$strSql .= $strSqlQuery . " LIMIT 1";
		
		$objStatement = $this->objConnection->prepare($strSql);	
		try
		{
			if ($objStatement->execute($arrValues))
			{ 	
				$arrTemp = $objStatement->fetch(PDO::FETCH_ASSOC);
				if (is_array($arrTemp) && count($arrTemp) > 0)
				{
					$this->arrRow = $arrTemp;
					
					$this->arrLoadedRow = $this->arrRow;
					$this->boolIsNew = false;
				}
				else
				{
					$objStatement->closeCursor();
					return false;
				}
				
				$objStatement->closeCursor();
				return true;
			}
			else
			{
				$objStatement->closeCursor();
			 	return false;
			}
		}
		catch(Exception $e)
		{
			throw new PDOException($e->getMessage() . "\n" . $strSql . "\n" . print_r($this->arrRow, true));
		}
	}
	
	/**
	 * Creates or Updates a new record in the database using the current field
	 *  values set on the object. 	 	 
	 *
	 * @author 	J. Max Wilson 	 
	 *
	 * @param bool refresh the fields from the database after creating a new record
	 * 	  to get any values set by triggers or sequences. Defaults to true.  If 
	 * 	  creating a large number of sequential records and you don't need the
	 * 	  assigned id after each one, set to false to avoid unnecessary SELECT
	 * 	  queries on the db for each save	 	 
	 * 	 	 	  	 	 
	 * @return	bool True if record was created/saved
	 */
	public function save($boolRefreshAfterCreate = true)
	{
		if ($this->boolIsNew)
		{
			$this->boolIsNew = false;
			return $this->create($boolRefreshAfterCreate);
		}
		else
		{
			return $this->update();
		}
	}
	
	/**
	 * Creates a new record in the database using the current field values set
	 *  on the object. 	 	 
	 *
	 * @author 	J. Max Wilson 	 
	 *
	 * @param bool refresh the fields from the database after creating a new record
	 * 	  to get any values set by triggers or sequences. Defaults to true.  If 
	 * 	  creating a large number of sequential records and you don't need the
	 * 	  assigned id after each one, set to false to avoid unnecessary SELECT
	 * 	  queries on the db for each save	  
	 *	 	 	 
	 * @return	bool True if record was created
	 */
	public function create($boolRefreshAfter = true)
	{
		$strSql = "INSERT INTO " . $this->strTableName . " (";
		$arrValues = array();
		$boolFirst = true;
		foreach($this->arrRow as $strField => $strValue)
		{
			if ($boolFirst != true)
			{
				$strSql .= ", ";
			}
			else
			{
				$boolFirst = false;	
			}
			
			$strSql .= $strField;
			if (is_bool($strValue))
			{
				$strValue = ($strValue?1:0);
			}
			$arrValues[] = $strValue;
		}
		$strSql .= ") VALUES (";
		for($i = 0; $i < sizeof($arrValues); $i++)
		{
			if ($i > 0)
			{
				$strSql .= ", ";
			}
			$strSql .= "?";
		}
		$strSql .= ")";
		
		$objStatement = $this->objConnection->prepare($strSql);
		try
		{
			if ($objStatement->execute($arrValues))
			{
				$objStatement->closeCursor();
				if ($boolRefreshAfter)
				{
					if (!$this->load())
					{
						return false;
					}
				}
				
				return true;
			}
			else
			{
				return false;
			}
		}
		catch (Exception $e)
		{
			throw new PDOException($e->getMessage() . "\n" . $strSql . "\n" . print_r($this->arrRow, true));
		}
	}
	
	/**
	 * Updates the record in the database using the current field values set on the object. 	 	 
	 *
	 * @author 	J. Max Wilson 	 
	 * 	 	 
	 * @return	bool True if record was updated
	 */
	public function update()
	{	
		$strSql = "UPDATE " . $this->strTableName . " SET ";
		$arrValues = array();
		$boolFirst = true;
		$boolHasChanged = false;
		foreach($this->arrRow as $strField => $strValue)
		{
			if ($this->arrLoadedRow[$strField] != $strValue)
			{
				$boolHasChanged = true;
			
				if ($boolFirst != true)
				{
					$strSql .= ", ";
				}
				else
				{
					$boolFirst = false;	
				}
				
				$strSql .= $strField . " = ?";
				if (is_bool($strValue))
				{
					$strValue = ($strValue?1:0);
				}
				$arrValues[] = $strValue;
			}
		}
		$strSql .= " WHERE ";
		$boolFirst = true;
		foreach($this->arrPrimaryKeys as $strKeyField)
		{
			if ($boolFirst != true)
			{
				$strSql .= " AND ";
			}
			else
			{
				$boolFirst = false;	
			}
			
			$strSql .= $strKeyField . " = ?";
			if (array_key_exists($strKeyField, $this->arrRow))
			{
				$arrValues[] = $this->arrRow[$strKeyField];
			}
			else if (array_key_exists(strtolower($strKeyField), $this->arrRow))
			{
				$arrValues[] = $this->arrRow[strtolower($strKeyField)];
			}
			else if (array_key_exists(strtoupper($strKeyField), $this->arrRow))
			{
				$arrValues[] = $this->arrRow[strtoupper($strKeyField)];
			}
			else
			{
				$strKeyFieldCamelCase = str_replace(" ","",ucwords(str_replace("_"," ",$strKeyField)));
				if (array_key_exists($strKeyFieldCamelCase, $this->arrRow))
				{
					$arrValues[] = $this->arrRow[$strKeyFieldCamelCase];
				}
				else
				{
					throw new Exception("Keyfield '$strKeyField' not found in row array." . print_r($this->arrRow, true));
				}
			}
			
		}
		
		//print $strSql;print_r($arrValues);
		if ($boolHasChanged)
		{
			$objStatement = $this->objConnection->prepare($strSql);
			try
			{
			
				if ($objStatement->execute($arrValues))
				{
					$objStatement->closeCursor();
					return true;
				}
				else
				{
					$objStatement->closeCursor();
					return false;
				}
			}
			catch (Exception $e)
			{
				throw new PDOException($e->getMessage() . "\n" . $strSql . "\n" . print_r($this->arrRow, true));
			}
		}
		else
		{
			return true;
		}
		
		return false;
	}
	
	/**
	 * loads the internal field value array with a provided array 	 	 
	 *
	 * @author 	J. Max Wilson 	 
	 * 
	 * @param	array Data
	 * 	 	 	 	 
	 * @return	null
	 */
	public function loadData($arrData)
	{
		$this->arrRow = $arrData;
		$this->arrLoadedRow = $this->arrRow;
		$this->boolIsNew = false;
	}
	
	/**
	 * returns the internal field value array 	 	 
	 *
	 * @author 	J. Max Wilson 	 
	 * 	 	 	 	 
	 * @return	array
	 */
	public function getData()
	{
		return $this->arrRow;
		
	}
	
	/**
	 * Gets the internal array of field values 	 	 
	 *
	 * @author 	J. Max Wilson 	 
	 * 	 	 
	 * @return	array Data
	 */
	public function getRowArray()
	{
		return $this->arrRow;
	}
	
	/**
	 * Deletes the record.  If the delete field has been set it will mark it as true
	 *  otherwise it will actually remove the record from the database tabe;	  	 	 
	 *
	 * @author 	J. Max Wilson 	 
	 * 	 	 
	 * @return	array Data
	 */
	public function delete()
	{
		$boolDeleted = false;
		
	
		$arrValues = array();
		$strSql = "DELETE FROM ".$this->strTableName." WHERE ";
		$boolFirst = true;
		foreach($this->arrPrimaryKeys as $strKeyField)
		{
			if ($boolFirst != true)
			{
				$strSql .= " AND ";
			}
			else
			{
				$boolFirst = false;	
			}
			
			$strSql .= $strKeyField . " = ?";
			if (array_key_exists($strKeyField, $this->arrRow))
			{
				$arrValues[] = $this->arrRow[$strKeyField];
			}
			else if (array_key_exists(strtolower($strKeyField), $this->arrRow))
			{
				$arrValues[] = $this->arrRow[strtolower($strKeyField)];
			}
			else if (array_key_exists(strtoupper($strKeyField), $this->arrRow))
			{
				$arrValues[] = $this->arrRow[strtoupper($strKeyField)];
			}
			else
			{
				$strKeyFieldCamelCase = str_replace(" ","",ucwords(str_replace("_"," ",$strKeyField)));
				if (array_key_exists($strKeyFieldCamelCase, $this->arrRow))
				{
					$arrValues[] = $this->arrRow[$strKeyFieldCamelCase];
				}
				else
				{
					throw new Exception("Keyfield '$strKeyField' not found in row array." . print_r($this->arrRow, true));
				}
			}
		}
		$objStatement = $this->objConnection->prepare($strSql);
		try
		{
			if ($objStatement->execute($arrValues))
			{
				$objStatement->closeCursor();
				$boolDeleted = true;
			}
		}
		catch(Exception $e)
		{
			throw new PDOException($e->getMessage() . "\n" . $strSql . "\n" . print_r($this->arrRow, true));
		}
		
		return $boolDeleted;
	}
	
	/**
	 * produces a DomDocument Node from the DomDocument provided representing the Record 	 	 
	 *
	 * @author 	J. Max Wilson
	 * 
	 * @param DOMDocument to use
	 * @param DOMNode to append resulting XML to
	 * @param string Name of Node for the record	 	 	 	  	 
	 * 	 	 
	 * @return	DomNode
	 */
	public function toXml($objDomDocument, $objParentNode = null, $strNodeName = null)
	{
		if ($strNodeName == null)
		{
			$strNodeName = strtolower(get_class($this));
		}
		
		$objRecordNode = $objDomDocument->createElement($strNodeName);
		
		if (is_array($this->arrRow))
		{
			foreach($this->arrRow as $strFieldName => $strValue)
			{
				$objFieldNode = $objDomDocument->createElement($strFieldName, htmlentities($strValue));
				$objRecordNode->appendChild($objFieldNode);
			}
		}
		
		if ($objParentNode != null)
		{
			$objParentNode->appendChild($objRecordNode);
		}
		
		return $objRecordNode;
	}
	
	/**
	 * Gets the PDO object connection for this record  	 	 
	 *
	 * @author 	J. Max Wilson 	 
	 * 	 	 
	 * @return	array Data
	 */
	public function getConnection()
	{
		return $this->objConnection;
	}
}
?>
