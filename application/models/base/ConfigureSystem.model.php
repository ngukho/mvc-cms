<?php

class Base_ConfigureSystem extends Base_BaseModel
{
	protected $_table_name = TB_CONFIGURE_SYSTEM;
	protected $_primary_key = 'code';
	
	function Base_ConfigureSystem()
	{
		parent::__construct();
	}
	
	function getGroups($group_id = null)
	{
		$str = empty($group_id) ? 1 : ' id = ' . $group_id;
		$result = $this->query("SELECT * FROM ".TB_CONFIGURE_SYSTEM_GROUP." WHERE $str AND active = 1 ORDER BY order_id DESC");
	
		if(!empty($group_id))
			return $result->fetch();
		
		$data = array();
		while(false != ($row = $result->fetch()))
		{
			$data[] = $row;
		}
		return $data;
	}
	
	function getAllGroups($group_id = null)
	{
		$str = empty($group_id) ? 1 : ' id = ' . $group_id;
		$result = $this->query("SELECT * FROM ".TB_CONFIGURE_SYSTEM_GROUP." WHERE $str AND active = 1 ORDER BY order_id DESC");
	
		$data = array();
		while(false != ($row = $result->fetch()))
		{
			$con_data = $this->getGroupConfigure($row['id']);
			$row['config_data'] = $con_data;
			$data[] = $row;
		}
		return $data;
	}
	
	function getGroupConfigure($group_id = null)
	{
		$str = empty($group_id) ? 1 : ' group_id = ' . $group_id;
// 		$result = $this->query("SELECT code, name, value, note, group_id, set_function, is_system FROM ".TB_CONFIGURE_SYSTEM." WHERE $str ORDER BY is_system DESC");
		$result = $this->query("SELECT * FROM ".TB_CONFIGURE_SYSTEM." WHERE $str ORDER BY is_system DESC");		
	
		$data = array();
		while(false != ($row = $result->fetch()))
		{
			$data[] = $row;
		}
		return $data;
	}
	
	function getGroupConfigureData($group_id = null)
	{
		$str = empty($group_id) ? 1 : ' group_id = ' . $group_id;
		$result = $this->query("SELECT * FROM ".TB_CONFIGURE_SYSTEM." WHERE $str ORDER BY is_system DESC");
	
		$data = array();
		while(false != ($row = $result->fetch()))
		{
			$data[] = $row;
		}
		return $data;
	}

	function updateConfigSystem($group_id, $code, $value)
	{
		$condition = " code = '$code' AND group_id = $group_id ";
		return $this->updateWithCondition($condition,$value);
	}

}

?>