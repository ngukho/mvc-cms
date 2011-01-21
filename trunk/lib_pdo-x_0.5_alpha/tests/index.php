<?php
// PostgreSQL
//define ('PDO_DATABASE_DSN','pgsql:host=localhost;dbname=pdo-x-test');
define ('PDO_DATABASE_DSN','mysql:host=localhost;dbname=pdo-x-test');
define ('PDO_DATABASE_USERNAME','root');
define ('PDO_DATABASE_PASSWORD','');

include_once('../pdo-x/pdo-x.php');

define ('PASSED','<span style="color: green;">passed</span>');
define ('FAILED','<span style="color: red;">failed</span>');

class TestRecord extends PDORecord
{
	function __construct($objConnection = null)
	{
		$this->setTableName('test');
		$this->setPrimaryKeys('id');
		
		parent::__construct($objConnection);
	}
}

class Tests
{
	function run()
	{
		$this->test_PDORecord();
		$this->testPDORecordset();
	}
	
	function test_PDORecord()
	{
		echo 'PDORecord Tests<br/>';
		
		// Test Instantiation
		echo 'Instantiation: ';
		try
		{
			$objTestRecord = new TestRecord();
			if ($objTestRecord instanceof testRecord)
			{
				echo PASSED;
			}
			else
			{
				echo FAILED;
			}
		}
		catch(Exception $e)
		{
			echo FAILED;
			//echo '<br/><br/>' . $e . '<br/>';
		}
		echo '<br/>';
		
		
		// Test Create Record
		echo 'Create Record: ';
		try
		{
			$objTestRecord = new TestRecord();
			$objTestRecord->name = rand(0,32767) . 'test' . rand(0,32767);
			$objTestRecord->number = rand(0,32767);
			$objTestRecord->save();
			$recordId = $objTestRecord->id;
			if ($recordId > 0)
			{
				echo PASSED;
			}
			else
			{
				echo FAILED;
			}
		}
		catch(Exception $e)
		{
			echo FAILED;
			//echo '<br/><br/>' . $e . '<br/>';
		}
		echo '<br/>';
		
		// Test Load
		echo 'Load Record: ';
		try
		{
			$objTestRecord = new TestRecord();
			$objTestRecord->id = 1;
			$objTestRecord->load();
			
			if ($objTestRecord->name == 'John')
			{
				echo PASSED;
				//echo '<pre>';
				//print_r($objTestRecord);
				//echo '</pre>';
			}
			else
			{
				echo FAILED;
			}
		}
		catch(Exception $e)
		{
			echo FAILED;
			//echo '<br/>' . $e . '<br/>';
		}
		echo '<br/>';
		
		// Test Save
		echo 'Save Record: ';
		try
		{
			$number = rand(0, 32767);
			$objTestRecord->number = $number;
			$objTestRecord->save();
			$objTestRecord = new TestRecord();
			$objTestRecord->id = 1;
			$objTestRecord->load();
			if ($objTestRecord->number == $number)
			{
				echo PASSED;
			}
			else
			{
				echo FAILED;
			}
		}
		catch(Exception $e)
		{
			echo FAILED;
			//echo '<br/><br/>' . $e . '<br/>';
		}
		echo '<br/>';
		
		// Test Transactions
		echo 'Transaction: ';
		$objConnection = TestRecord::getNewConnection();
		$objConnection->beginTransaction();
		try
		{		
			$objTestRecord = new TestRecord($objConnection);
			$objTestRecord->name = 'bob';
			$objTestRecord->number = rand(0,32767);
			$objTestRecord->save();
			
			$objTestRecord = new TestRecord($objConnection);
			$objTestRecord->name = null;
			$objTestRecord->save();
			
			$objConnection->commit();
		}
		catch(Exception $e)
		{
			$objConnection->rollback();
			$objTestRecord = new TestRecord();
			$objTestRecord->name = 'bob';
			if (!$objTestRecord->load())
			{
				echo PASSED;
			}
			else
			{
				echo FAILED;
			}
			//echo '<br/><br/>' . $e . '<br/>';
		}
		echo '<br/>';
		
		// Test Delete Record
		echo 'Delete Record: ';
		try
		{
			$objTestRecord = new TestRecord();
			$objTestRecord->id = $recordId;
			$objTestRecord->delete();
			
			$objTestRecord = new TestRecord();
			$objTestRecord->id = $recordId;
			
			if (!$objTestRecord->load())
			{
				echo PASSED;
				//echo '<pre>';
				//print_r($objTestRecord);
				//echo '</pre>';
			}
			else
			{
				echo FAILED;
			}
		}
		catch(Exception $e)
		{
			echo FAILED;
			//echo '<br/>' . $e . '<br/>';
		}
		echo '<br/>';
	}
	
	function testPDORecordset()
	{
		echo '<br/>PDORecordset Tests<br/>';
		// Test Save
		echo 'Instantiation: ';
		try
		{
			$objRecordset = new PDORecordset('SELECT * FROM test;');
			
			if ($objRecordset instanceof PDORecordset)
			{
				echo PASSED;
			}
			else
			{
				echo FAILED;
			}
		}
		catch(Exception $e)
		{
			echo FAILED;
			//echo '<br/><br/>' . $e . '<br/>';
		}
		echo '<br/>';
		
		// Test Load
		echo 'Load Recordset: ';
		try
		{	
			$objRecordset->execute();
			if ($objRecordset->count() > 0)
			{
				echo PASSED;
			}
			else
			{
				echo FAILED;
			}
		}
		catch(Exception $e)
		{
			echo FAILED;
			//echo '<br/><br/>' . $e . '<br/>';
		}
		echo '<br/>';
		
		// Test Iterator
		echo 'Iterate Recordset: ';
		try
		{	
			$boolRecords = true;
			foreach($objRecordset as $objRecord)
			{
				if (!$objRecord instanceof PDORecord)
				{
					$boolRecords = false;
				}
			}
			$objRecordset = new PDORecordset('SELECT * FROM test WHERE name IS NULL;');
			$objRecordset->execute();
			$intCount = 0;
			foreach($objRecordset as $objRecord)
			{
				$intCount++;
			}
			if ($boolRecords && $intCount == $objRecordset->count())
			{
				echo PASSED;
			}
			else
			{
				echo FAILED . $intCount .'=='. $objRecordset->count();
			}
		}
		catch(Exception $e)
		{
			echo FAILED;
			//echo '<br/><br/>' . $e . '<br/>';
		}
		echo '<br/>';
		
		// Test Iterator
		echo 'Prepared Statement: ';
		try
		{	
			$objRecordset = new PDORecordset("SELECT * FROM test WHERE name LIKE ?;");
			$objRecordset->execute(array('John'));
			if ($objRecordset->count() == 1)
			{
				echo PASSED;
			}
			else
			{
				echo FAILED;
			}
		}
		catch(Exception $e)
		{
			echo FAILED;
			//echo '<br/><br/>' . $e . '<br/>';
		}
		echo '<br/>';
		
		// Test Iterator
		echo 'Record Class: ';
		try
		{	
			$objRecordset = new PDORecordset("SELECT * FROM test WHERE name LIKE ?;");
			$objRecordset->execute(array('John'), 'TestRecord');
			if ($objRecordset->count() == 1 && $objRecordset->current() instanceof TestRecord)
			{
				echo PASSED;
			}
			else
			{
				echo FAILED;
			}
		}
		catch(Exception $e)
		{
			echo FAILED;
			//echo '<br/><br/>' . $e . '<br/>';
		}
		echo '<br/>';
	}
}

$objTests = new Tests();
$objTests->run();
?>