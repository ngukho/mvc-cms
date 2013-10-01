<?php 

return array(
		
	"dashboard_user" => array(
				
			"name" => "Users",
			"icon" => "icon-user",
				
			"sub_menus" => array(
					array(
							"name" => "List Users",
							"link" => "dashboard/user/list",
					),
					array(
							"name" => "Add User",
							"link" => "dashboard/user/add",
					)					
			),
	),		
		
	"dashboard_group" => array(
			
			"name" => "Groups",
			"icon" => "icon-group",
			
			"sub_menus" => array(
					array(
							"name" => "List Groups",
							"link" => "dashboard/group/list",
					),
					array(
							"name" => "Add User",
							"link" => "dashboard/group/add",
					)					
			),			
	),
		
	"dashboard_config-system" => array(
				
			"name" => "Configure System",
			"icon" => "icon-gears",
			"link" => "dashboard/config-system/list"
			
	)		
		
		
);