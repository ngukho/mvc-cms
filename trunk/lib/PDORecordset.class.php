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
	 * Class PDORecordset
	 * Acts as a base class for accessing groups or "sets" of records
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
class PDORecordset implements Iterator, ArrayAccess
{
	// Member Variables
	private $objConnection;
	private $objStatement;
	private $strSql;
	private $arrSqlValues;
	private $arrFields;
	private $arrRows;
	private $strPDORecordClassName;
	private $intCurrentPos;
	private $objPDORecord;
	private $boolValid;
	
	/**
	 * Construct a PDORecordset object
	 *
	 * @author	J. Max Wilson
	 *
	 * @access	public	  
	 *	 
	 * @param	str SQL Prepared Statement for retrieving the set of records 	 
	 * @param	obj PDO optional connection to use for transaction
	 * 	 	 
	 * @return	object PDORecord
	 */
	public function __construct($strSql, $objConnection = null)
	{
		$arrValues = func_get_args();
		array_shift($arrValues);
		
		if ($objConnection instanceof PDO)
		{
			$this->objConnection = $objConnection;
			array_shift($arrValues);
		}
		else
		{
			$this->objConnection = self::getNewConnection();
		}
		
		if (sizeof($arrValues) > 0)
		{
			if (is_array($arrValues[0]))
			{
				$this->arrSqlValues = $arrValues[0];
			}
			else
			{
				$this->arrSqlValues = $arrValues;
			}
		}
		else
		{
			$this->arrSqlValues = array();
		}
		
		$this->objStatement = $this->objConnection->prepare($strSql, array(PDO::ATTR_CURSOR, PDO::CURSOR_SCROLL));
		$this->strSql = $strSql;
		$this->strPDORecordClassName = "Record";
		$this->arrFields = array();
		$this->arrRows = array();
		$this->intCurrentPos = 0;
		$this->boolValid = false;
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
	public function __set($strFieldName, $strValue)
	{
		$this->arrFields[$strFieldName] = $strValue;
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
		return $this->arrFields[$strFieldName];
	}
	
	/**
	 * Sets the values to be passed into the SQL prepared Statement 	 
	 *
	 * @author 	J. Max Wilson
	 * 
	 * @param	uses the variable number of function arguments an array of values	 
	 * 	 	 
	 * @return	null
	 */
	public function setSqlValues()
	{
		$this->arrSqlValues = func_get_args();
	}
	
	/**
	 * Sets the class Name to be used for each instance of a record 	 
	 *
	 * @author 	J. Max Wilson
	 * 
	 * @param	uses the variable number of function arguments an array of values	 
	 * 	 	 
	 * @return	null
	 */
	public function setPDORecordClassName($strClassName)
	{
		$this->strPDORecordClassName = $strClassName;
	}
	
	/**
	 * Executes the SQL and loads the set of records from the database using the SQL Prepared Statement
	 *
	 * @author 	J. Max Wilson
	 * 
	 * @param	array optional values to use for SQL Prepared Statement	 
	 * @param	uses the variable number of function arguments an array of values	 
	 * 	 	 
	 * @return	boolean True if recordset successfully loaded
	 */
	public function execute($arrValues = null, $strPDORecordClassName = "PDORecord")
	{
		$this->strPDORecordClassName = $strPDORecordClassName;
		if (is_array($arrValues) && sizeof($arrValues) > 0)
		{
			$this->arrSqlValues = $arrValues;
		}
		$this->objPDORecord = new $this->strPDORecordClassName();
		try
		{
			if ($this->objStatement->execute($this->arrSqlValues))
			{
				if (stripos($this->strSql, "select ") !== false)
				{
					$this->intCurrentPos = 0;
					$this->arrRows = $this->objStatement->fetchAll(PDO::FETCH_ASSOC);
					if (count($this->arrRows) > 0)
						$this->boolValid = true;
				}
				return true;
			}
		}
		catch(Exception $e)
		{
			throw new PDOException($e->getMessage() . "\n" . $this->strSql . "\n" . print_r($this->arrSqlValues, true));
		}
		
		return false;
	}
	
	/**
	 * Private method Loads the current record object with the data from result set 
	 *
	 * @author 	J. Max Wilson	 
	 * 	 	 
	 * @return	null
	 */
	private function loadRecord()
	{
		$this->objPDORecord->loadData($this->arrFields);
	}
	
	/**
	 * Implement the ArrayAccess interface: check to see if an offset exists 
	 *
	 * @author 	J. Max Wilson	 
	 * 	 	 
	 * @return	bool true if the record exists
	 */
	public function offsetExists ($offset)
	{
		return array_key_exists($offset,$this->arrRows);
	}
	
	/**
	 * Implement the ArrayAccess interface: get the record at the giving offset 
	 *
	 * @author 	J. Max Wilson	 
	 * 	 	 
	 * @return	PDORecord
	 */
	public function offsetGet($offset)
	{
		$objPDORecord = new $this->strPDORecordClassName();
		$objPDORecord->loadData($this->arrRows[$offset]);
		return $objPDORecord;
	}
	
	/**
	 * Implement the ArrayAccess interface: set the PDORecord at the giving offset 
	 *
	 * @author 	J. Max Wilson	 
	 * 	 	 
	 * @return	void
	 */
	public function offsetSet($offset, $value)
	{
		$this->arrRows[$offset] = $value->getData();
	}
	
	/**
	 * Implement the ArrayAccess interface: unset the PDORecord at the given offset 
	 *
	 * @author 	J. Max Wilson	 
	 * 	 	 
	 * @return	void
	 */
	public function offsetUnset($offset)
	{
		$this->arrRows[$offset] = null;
	}
	
	/**
	 * Implement the Iterator interface: Gets the number of rows in the loaded recordset 
	 *
	 * @author 	J. Max Wilson	 
	 * 	 	 
	 * @return	int Number of rows
	 */
	public function count()
	{
		$intRows = $this->objStatement->rowCount();
		if (!$intRows && sizeof($this->arrRows))
			$intRows = sizeof($this->arrRows);
			
		return $intRows;
	}
	
	/**
	 * Implement the Iterator interface: Set the recordset at the first record 
	 *
	 * @author 	J. Max Wilson	 
	 * 	 	 
	 * @return	null
	 */
	public function rewind()
	{
		$this->first();
	}
	
	/**
	 * Implement the Iterator interface: Returns the values of the key fields for the current record 
	 *
	 * @author 	J. Max Wilson	 
	 * 	 	 
	 * @return	array of Key field values
	 */
	public function key()
	{
		$arrKeyFields = $this->objPDORecord->getPrimaryKeys();
		$arrKeys = array();
		foreach($arrKeyFields as $strKeyFieldName)
		{
			$arrKeys[$strKeyFieldName] = $this->objPDORecord->$strKeyFieldName;
		}
		
		return $arrKeys;
	}
	
	/**
	 * Implement the Iterator interface: Returns a PDORecord of the current record in the set 
	 *
	 * @author 	J. Max Wilson	 
	 * 	 	 
	 * @return	PDORecord
	 */
	public function current()
	{	
		return $this->objPDORecord;
	}
	
	/**
	 * Implement the Iterator interface: Moves to the next record in the set 
	 *
	 * @author 	J. Max Wilson	 
	 * 	 	 
	 * @return	boolean True if moved to the next record false if End of Set
	 */
	public function next()
	{
		if ($this->intCurrentPos < $this->count() - 1)
		{
			$this->intCurrentPos++;
			$this->arrFields = $this->arrRows[$this->intCurrentPos];
			$this->loadRecord();
			$this->boolValid = true;
		}
		else
		{
			$this->boolValid = false;
		}
		
		return $this->boolValid;
		 
	}
	
	/**
	 * Implement the Iterator interface: Returns whether the current position in the set is valid 
	 *
	 * @author 	J. Max Wilson	 
	 * 	 	 
	 * @return	boolean true if valid
	 */
	public function valid()
	{
		return $this->boolValid;		
	}
	
	/**
	 * Implement the Iterator interface: Moves to the previous record in the set 
	 *
	 * @author 	J. Max Wilson	 
	 * 	 	 
	 * @return	boolean true if moved to record false if beginning of set
	 */
	public function previous()
	{
		if ($this->intCurrentPos > 0)
		{
			$this->intCurrentPos--;
			$this->arrFields = $this->arrRows[$this->intCurrentPos];
			$this->loadRecord();
			$this->boolValid = true;
		}
		else
		{
			$this->boolValid = false;
		}
		
		return $this->boolValid;
	}
	
	/**
	 * Implement the Iterator interface: Sets the current record to the first record in the set 
	 *
	 * @author 	J. Max Wilson	 
	 * 	 	 
	 * @return	null
	 */
	public function first()
	{
		$this->intCurrentPos = 0;
		if (count($this->arrRows) > 0)
		{
			$this->arrFields = $this->arrRows[$this->intCurrentPos];
			$this->loadRecord();
			$this->boolValid = true;
		}
		else
		{
			$this->boolValid = false;
		}
	}
	
	/**
	 * Implement the Iterator interface: Sets the current record to the last record in the set 
	 *
	 * @author 	J. Max Wilson	 
	 * 	 	 
	 * @return	null
	 */
	public function last()
	{	
		$this->intCurrentPos = $this->count() - 1;
		$this->arrFields = $this->arrRows[$this->intCurrentPos];
		$this->loadRecord();	
	}
	
	/**
	 * produces a DomDocument Node from the DomDocument provided representing the Recordset 	 	 
	 *
	 * @author 	J. Max Wilson
	 * 
	 * @param DOMDocument to use
	 * @param DOMNode to append resulting XML to
	 * @param string Name of Node for the recordset	 	 	 	  	 
	 * 	 	 
	 * @return	DomNode
	 */
	public function toXml($objDomDocument, $objParentNode = null, $strNodeName = null, $strChildNodeName = null)
	{
		if ($strNodeName == null)
		{
			$strNodeName = strtolower(get_class($this));
		}
		
		$objPDORecordsetNode = $objDomDocument->createElement($strNodeName);
		
		if ($objParentNode != null)
		{
			$objParentNode->appendChild($objPDORecordsetNode);
		}
		
		if ($this->count() > 0)
		{
			$this->first();
			do
			{
				$this->objPDORecord->toXml($objDomDocument, $objPDORecordsetNode, $strChildNodeName);
			}while ($this->next());
		}
		
		return $objPDORecordsetNode;
	}
	
	/**
	 * Gets the PDO object connection for this recordset  	 	 
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