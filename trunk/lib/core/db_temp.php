<?php

class ExtController
{
	public $att = array();
    private $_table = "";
	protected $_setvalue = "";
	protected $_field = "";
    protected $_arrCondition = array();
	public $Condition = "";
	public $Query = "";

	function __construct($table="")
	{
		require_once(CON_LIB . 'DBCommon.htm');
        //
		if ($table != "")
			$this->_table = $table;
		//Set Attribute for Class
		$this->setAttribute();
	}

    function __destruct()
    {
        unset($this->att);
        unset($this->_table,$this->_setvalue,$this->_field);
        unset($this->_arrCondition);
        unset($this->Condition);
    }

    function __set($key, $value)
    {
        if (isset($this->att[$key]))
        {
            $this->att[$key] = $value;
        }
        else
            return FALSE;
    }

    function __get($key)
    {
        if (isset($this->att[$key]))
        {
            return $this->att[$key];
        }
        return FALSE;
    }    
    
	//print Attribute
	public function PrintAttribute()
	{
		$str = "";
		foreach ($this->att as $key=>$value)
		{
			$str .= $key . "<br/>";
		}
		echo $str . "<hr/>";
	}

	private function setInsertValue()
	{
		$this->resetVariable();
		foreach ($this->att as $key=>$value)
		{
			if ($value != "")
			{
				//$this->_field .= mb_substr($key,3) . ",";	// cat bo "att" o dau bien ex: attID
				$this->_field .= $key . ",";
				$this->_setvalue .= "'$value',";
			}
            elseif (is_numeric($value))
            {
                $this->_field .= $key . ",";
				$this->_setvalue .= "'$value',";
            }
		}
		$this->_field = mb_substr($this->_field,0,-1);
		$this->_setvalue = mb_substr($this->_setvalue,0,-1);
	}

	private function setUpdateValue()
	{
		$this->resetVariable();
		foreach($this->att as $key=>$value)
		{
			if ($value != "")
            {
				//$this->_setvalue .= mb_substr($key,3) . " = '" . $value . "', ";
				$this->_setvalue .= $key . " = '" . $value . "', ";
            }
            elseif (is_numeric($value))
            {
                $this->_setvalue .= $key . " = '" . $value . "', ";
            }
		}
		$this->_setvalue = mb_substr($this->_setvalue,0,-2);
	}

	//Set Attribute for Class
	private function setAttribute()
	{
        if ($this->_table != "")
        {
            $oDB = new DBCommon();
            if ($oDB->db_open())
            {
                $sQuery = " SHOW FIELDS FROM " . $this->_table ;
                $arrComList = $oDB->db_select($sQuery);
                $oDB->db_close();
                //
                //unset($this->att);
                //
                $arrTemp = array();
                $arrEmtpy = array();
                foreach ($arrComList as $row)
                {
                    $arrTemp[] = $row->Field;
                    $arrEmtpy[] = '';
                }
                $this->att = array_combine($arrTemp,$arrEmtpy);
            }
        }
	}

	private function resetVariable()
	{
		$this->_field 	 = "";
		$this->_setvalue = "";
	}

	private function ResetValue()
	{
		foreach ($this->att as $key=>$value)
		{
			$this->att[$key] = "";
		}
        $this->_arrCondition = array();
        $this->Condition = "";
		//$this->Query = "";
	}

    /**************************************************
    * param:    @field
    *           @value
    *           @type (0:Normal;1:AND;2:OR
    **************************************************/
    var $i = 1;
    public function AddCondition($field,$operation="=",$value,$type=0)
    {
		if (is_string($type))
		{
			$type = strtoupper($type);
			switch($type)
			{
				case 'AND':
					$type = 1;
				break;
				case 'OR':
					$type = 2;
				break;
                case '':
                    $type = 0;
                break;
				default:
					$type = 0;
				break;
			}
		}
        $arrTmp = array("field"=>$field,"value"=>$value,"operation"=>$operation,"type"=>$type);
        array_push($this->_arrCondition,$arrTmp);
    }

    private function CreateCondition()
    {
        if ($this->_arrCondition != NULL)
        {
            $this->Condition = "";
            foreach($this->_arrCondition as $obj)
            {
                switch ($obj['type'])
                {
                    case 0:
						if ($obj['operation'] == '' && $obj['value'] == '')
							$this->Condition .= $obj['field'] . " ";
						else
							$this->Condition .= $obj['field'] . " " . $obj['operation'] . " '" . $obj['value'] . "' ";
                    break;
                    //
                    case 1:
						if ($obj['operation'] == '' && $obj['value'] == '')
							$this->Condition .= $obj['field'] . " AND ";
						else
							$this->Condition .= $obj['field'] . " " . $obj['operation'] . " '" . $obj['value'] . "' AND ";
                    break;
                    //
                    case 2:
						if ($obj['operation'] == '' && $obj['value'] == '')
							$this->Condition .= $obj['field'] . " OR ";
						else
							$this->Condition .= $obj['field'] . " " . $obj['operation'] . " '" . $obj['value'] . "' OR ";
                    break;
                }
            }
        }
        if (trim($this->Condition) != "")
            $this->Condition = " WHERE " . $this->Condition;
    }

    public function Select($fields,$table)
    {
        if (is_array($fields) && $fields != NULL)
        {
            if ($table == "")
                $table = $this->_table;
            //
            $this->CreateCondition();
            //
            $getFields = implode(", ",$fields);
            $sQuery  = " SELECT " . $getFields;
            $sQuery .= " FROM $table ";
            $sQuery .= " " . $this->Condition;
			//
			$this->Query = $sQuery;
            //
            $oDB = new DBCommon();
            if ($oDB->db_open())
            {
                $result = $oDB->db_select($sQuery);
                $oDB->db_close();
                //
                $this->ResetValue();
                //
				if ($result != NULL)
					return $result;
				return FALSE;
            }
        }
        return FALSE;
    }

	public function SelectQuery($query)
	{
		//
		$oDB = new DBCommon();
		if ($oDB->db_open())
		{
			$this->Query = $query;
			$result = $oDB->db_select($query);
            $oDB->db_close();
			//
			if ($result != NULL)
				return $result;
			return FALSE;
		}
		return FALSE;
	}

    public function SelectRow($query,$row=0)
    {
        //
		$oDB = new DBCommon();
		if ($oDB->db_open())
		{
			$this->Query = $query;
			$result = $oDB->db_select($query);
            $oDB->db_close();
			//
			if ($result != NULL)
				return $result[$row];
			return FALSE;
		}
		return FALSE;
    }

    public function SelectOne($query)
    {
        //
		$oDB = new DBCommon();
		if ($oDB->db_open())
		{
            $query = trim($query);
            $arrText = explode(" ",$query);
            $arrText = explode(",",$arrText[1]);
            //
			$this->Query = $query;
			$result = $oDB->db_select($query);
            $oDB->db_close();
            //
			if ($result != NULL)
				return $result[0]->$arrText[0];
			return FALSE;
		}
		return FALSE;
    }

    public function ExecuteQuery($query)
	{
		//
		$oDB = new DBCommon();
		if ($oDB->db_open())
		{
			$this->Query = $query;
			$result = $oDB->db_excute($query);
            $oDB->db_close();
			//
			return $result;
		}
		return FALSE;
	}

	public function InsertInto($table)
	{
		//Set value
		$this->setInsertValue();
		if ($this->_field == "" && $this->_setvalue == "")
			return FALSE;
        //
        if ($table == "")
            $table = $this->_table;
		//Write SQL
		$iQuery  = " INSERT INTO $table ($this->_field) ";
		$iQuery .= " VALUES($this->_setvalue) ";
		//
		$this->Query = $iQuery;
		//
		$oDB = new DBCommon();
		if ($oDB->db_open())
		{
			$result = $oDB->db_insert($iQuery);
			$oDB->db_close();
			//Reset Value
			$this->ResetValue();
			//
			return $result;
		}
		return FALSE;
	}

	public function UpdateTo($table)
	{
		//set value
		$this->setUpdateValue();
		if ($this->_setvalue == "")
			return FALSE;
        //
        if ($table == "")
            $table = $this->_table;
        //
		$this->CreateCondition();
        //Write SQL
		$uQuery  = " UPDATE $table ";
		$uQuery .= " SET $this->_setvalue ";
		$uQuery .= " " . $this->Condition . " ";
		//
		$this->Query = $uQuery;
		//
		$oDB = new DBCommon();
		if ($oDB->db_open())
		{
			$result = $oDB->db_change($uQuery);
			$oDB->db_close();
			//Reset value
			$this->ResetValue();
			//
			return $result;
		}
		return FALSE;
	}

	public function DeleteFrom($table)
	{
        $this->CreateCondition();
		if ($this->Condition == "")
			return FALSE;
        //
        if ($table == "")
            $table = $this->_table;
        //
		//Write SQL
		$dQuery  = " DELETE FROM $table ";
		$dQuery .= " " . $this->Condition . " ";
		//
		$oDB = new DBCommon();
		if ($oDB->db_open())
		{
			$result = $oDB->db_change($dQuery);
			$oDB->db_close();
			//Reset value
			$this->ResetValue();
			//
			return $result;
		}
		return FALSE;
	}

	public function InsertQuery($query)
	{
		//
		$oDB = new DBCommon();
		if ($oDB->db_open())
		{
			$result = $oDB->db_insert($query);
			$oDB->db_close();
			//
			return $result;
		}
		return FALSE;
	}
	
	
/*
   +--------------------------------------------------------------------------
   |   {Function Name : AddBindCondition}
   |   ========================================
   |   {Description : add condition for query}
   |   ========================================
   |   {Parameters : (String) $condition, (Array) $params }
   |   ========================================
   |   {Return Values : }
   |   ========================================   
   |   {Coder : DucBui 12/14/2010 }   
   +--------------------------------------------------------------------------
*/
    public function AddBindCondition($condition,$params = NULL)
    {
		$this->_arrCondition = NULL;
		if(!is_null($params))
		{
			$condition = $this->compileBinds($condition,$params);    	
		}
		$this->Condition = $condition;		
    }	

/*
   +--------------------------------------------------------------------------
   |   {Function Name : getRow}
   |   ========================================
   |   {Description : get a row}
   |   ========================================
   |   {Parameters : (String) $condition, (Array) $params }
   |   ========================================
   |   {Return Values : }
   |   ========================================   
   |   {Coder : DucBui 12/14/2010 }      
   +--------------------------------------------------------------------------
*/    
	public function getRow($condition,$params = NULL)
	{
		$result = $this->getRowset($condition,$params);
		if(isset($result[0])) return $result[0];
		return FALSE;
	}

/*
   +--------------------------------------------------------------------------
   |   {Function Name : getRowset}
   |   ========================================
   |   {Description : get a any row}
   |   ========================================
   |   {Parameters : (String) $condition, (Array) $params, (String) $order_by, (Int) $start, (Int) $end }
   |   ========================================
   |   {Return Values : }
   |   ========================================   
   |   {Coder : DucBui 12/14/2010 }      
   +--------------------------------------------------------------------------
*/    	
	public function getRowset($condition = NULL,$params = NULL,$order_by = NULL,$start = 0,$end = 0)
	{
		$strFields = implode(",",array_keys($this->att));
		$sSql = "SELECT " . $strFields . " FROM " . $this->_table;
		if(!is_null($condition))
		{
			if(!is_null($params))
			{
				$condition = $this->compileBinds($condition,$params);
			}
			$sSql .= " WHERE " . $condition;
		}		
		if(!is_null($order_by))
		{
			$sSql .= " ORDER BY " . $order_by;
		}
		if($end > 0)
		{
			$sSql .= " LIMIT {$start},{$end} ";
		}		
		
		return $this->SelectQuery($sSql);
	}
	
/*
   +--------------------------------------------------------------------------
   |   {Function Name : getTotalRow}
   |   ========================================
   |   {Description : get total row}
   |   ========================================
   |   {Parameters : (String) $condition, (Array) $params }
   |   ========================================
   |   {Return Values : }
   |   ========================================   
   |   {Coder : DucBui 12/14/2010 }      
   +--------------------------------------------------------------------------
*/
	public function getTotalRow($condition = NULL,$params = NULL)
	{
		$sSql = "SELECT COUNT(*) AS TotalRow FROM " . $this->_table;
		if(!is_null($condition))
		{
			if(!is_null($params))
			{
				$condition = $this->compileBinds($condition,$params);
			}			
			$sSql .= " WHERE " . $condition;
		}		
		$result = $this->SelectQuery($sSql);
		return $result[0]->TotalRow;
	}

/*
   +--------------------------------------------------------------------------
   |   {Function Name : compileBinds}
   |   ========================================
   |   {Description :}
   |   ========================================
   |   {Parameters : (String) $sql, (Array) $binds }
   |   ========================================
   |   {Return Values : }
   |   ========================================   
   |   {Coder : DucBui 12/14/2010 }      
   +--------------------------------------------------------------------------
*/		
	private function compileBinds($sql, $binds)
	{
		if (strpos($sql, '?') === FALSE)
		{
			return $sql;
		}
		
		if ( ! is_array($binds))
		{
			$binds = array($binds);
		}
		
		// Get the sql segments around the bind markers
		$segments = explode('?', $sql);

		// The count of bind should be 1 less then the count of segments
		// If there are more bind arguments trim it down
		if (count($binds) >= count($segments)) {
			$binds = array_slice($binds, 0, count($segments)-1);
		}

		// Construct the binded query
		$result = $segments[0];
		$i = 0;
		foreach ($binds as $bind)
		{
			$result .= $this->escape($bind);
			$result .= $segments[++$i];
		}

		return $result;
	}	
	
/*
   +--------------------------------------------------------------------------
   |   {Function Name : escape}
   |   ========================================
   |   {Description : Escapes data based on type, sets boolean and null types}
   |   ========================================
   |   {Parameters : (String) $condition, (Array) $params }
   |   ========================================
   |   {Return Values : }
   |   ========================================   
   |   {Coder : DucBui 12/14/2010 }   
   +--------------------------------------------------------------------------
*/	
	private function escape($str)
	{
		if (is_string($str))
		{
			if(function_exists(addslashes))
			{
				$str = "'".addslashes($str)."'";	
			}
			else 
			{
				$str = "'".$str."'";				
			}
		}
		elseif (is_bool($str))
		{
			$str = ($str === FALSE) ? 0 : 1;
		}
		elseif (is_null($str))
		{
			$str = 'NULL';
		}

		return $str;
	}	


}
?>