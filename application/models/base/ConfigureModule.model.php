<?php

class Base_ConfigureModule extends Base_BaseModel
{
	protected $_table_name = TB_CONFIGURE_MODULE;
	protected $_primary_key = 'id';
	
	function Base_ConfigureModule()
	{
		parent::__construct();
	}

	function configure_mod()
	{
		$configure_mod = array();
				
		$result = $this->query("/*qc=on*/ SELECT * FROM ".TB_CONFIGURE_MODULE." WHERE 1 ORDER BY `module`,typeid");
		
		$rs = $result->fetch();
		while($rs)
		{
			$data = unserialize($rs['data']);
			$order_default = $data['sort_default']?$data['sort_default']:'order_id';
			$order_default .= " ".($data['sort_default_order'] == 'DESC'?'DESC':'ASC');
			$sort_order = $order_default;
			if($data['sort_order']) $sort_order .= ",".$data['sort_order'];
			$catsort_order = $order_default;
			if($data['catsort_order']) $catsort_order .= ",".$data['catsort_order'];
			$configure_mod[$rs['module']][$rs['typeid']] = array(
					'languages'=>intval($data['languages']),
					'sort_order'=>$sort_order,
					'catsort_order'=>$catsort_order,
			);
			$rs = $result->fetch();
		}
// 		$result->cache();
	
		$result = $this->query("SELECT * FROM ".TB_LANGUAGE." WHERE is_default = 1");
		$default_lang = $result->fetch();
		$configure_mod['default_lang'] = $default_lang['ln'];
// 		$result->cache();
	
		return $configure_mod;
	
	}
	

}

?>