<?php
require_once("DBIni.inc.htm");
class DBCommon
{
	protected $_conn;
	protected $_db;

	function __construct()
	{
        define("MESSAGE","Please open connection to Database!!!");
	}
	
	function __destruct()
	{
		//$this->db_close();
	}
	
	private function mysql_connecting()
	{
		$this->_conn = mysql_connect(HOST_NAME, USER_NAME, PASSWORD);
		return $this->_conn;
	}
	
	function db_open()
	{
		if ($this->mysql_connecting() != false)
		{
			$this->_db = mysql_select_db(DATA_BASE,$this->_conn);
			if ($this->_db == true)
			{
				mysql_query("SET NAMES utf8");
				//mysql_set_charset ("UTF-8");
				return true;
			}
			else
				return false;
		}
		return false;
	}
	
	function db_close()
	{
		//var_dump($this->_conn);
		if (!$this->_conn)
		{
			echo mysql_errno() . ": " . mysql_error();
		}
		else
		{
			$rc = mysql_close($this->_conn);
			return $rc;
		}
	}
	
	function db_select($query)
	{
		if ($this->_conn)
		{
			$result = mysql_query($query);
			if (!$result)
			{
				echo mysql_errno() . ": " . mysql_error();
			}
			else
			{
				$arr = array();			
				while ($row = mysql_fetch_object($result))
				{	
					array_push($arr,$row);
				}
				mysql_free_result($result);	//Free Memory
				return $arr;
			}
		}
		else
			echo MESSAGE;
	}
	
    function db_excute($query)
    {
        if ($this->_conn)
		{
			$result = mysql_query($query);
			if (!$result)
			{
				echo mysql_errno() . ": " . mysql_error();
                return FALSE;
			}
			else
			{
                $arr = array();			
                while ($row = mysql_fetch_object($result))
                {	
                    array_push($arr,$row);
                }
                mysql_free_result($result);	//Free Memory
                if ($arr != NULL)
                    return $arr;
                else
                    return TRUE;
			}
		}
		else
			echo MESSAGE;
    }
    
	function db_insert($query)
	{
		if ($this->_conn)
		{
			$result = mysql_query($query);
			if (mysql_affected_rows() <= 0)
			{
				$errno = mysql_errno();
				if ($errno!=0)
                    echo $errno . ": " . mysql_error();
				return FALSE;
			}
			else
				return mysql_insert_id();
		}
		else
			echo MESSAGE;
	}
	
	function db_change($query)
	{
		if ($this->_conn)
		{
			$result = mysql_query($query);
			if (mysql_affected_rows() <= 0)
			{
				$errno = mysql_errno();
				if ($errno!=0)
					echo $errno . ": " . mysql_error();
				return FALSE;
			}
			else
				return TRUE;
		}
		else
			echo MESSAGE;
	}
	
	private function numOfRows($result)
	{
		if ($result)
		{
			return mysql_num_rows($result);
		}
		return 0;
	}
	
	private function numOfFields($result)
	{
		if ($result)
		{
			return mysql_num_fields($result);
		}
		return 0;
	}
	
	function listTables()
	{
		if ($this->db_open())
		{
			$sQuery = "SHOW TABLES FROM " . DATA_BASE . " ";
			$lstTables = $this->db_select($sQuery);
			$this->db_close();
		}
		echo "<pre>";
		print_r($lstTables);
		echo "</pre>";
	}
}
?>