<?php 

return array(
		
	"application" => array(
			"default_uri" 			=> "site/index/index",
			"error_reporting" 		=> E_ALL,
			"display_errors" 		=> 1,
			"language" 				=> "en",
			"timezone" 				=> "Asia/Ho_Chi_Minh",
			"site_name" 			=> "Simple MVC Framework",
			"version" 				=> "0.0.10",
			"currency" 				=> "USD",
			"domain" 				=> "ngukho.com",
			"config_compression" 	=> 0 //; config_compression = 0 -> 9
			),
		
	"database_master" => array(
			"db_type" 				=> "mysql",
			"db_name" 				=> "mvc",
			"db_hostname" 			=> "localhost",
			"db_username" 			=> "root",
			"db_password" 			=> "",
			"db_port" 				=> 3306,
			"db_prefix" 			=> ""
			),
		
	"database_slave" => array(
			"db_type" 				=> "mysql",
			"db_name" 				=> "mvc",
			"db_hostname" 			=> "localhost",
			"db_username" 			=> "root",
			"db_password" 			=> "",
			"db_port" 				=> 3306,
			"db_prefix" 			=> ""
	),
		
	"session" => array(
			"match_ip" 				=> FALSE,
			"match_fingerprint" 	=> TRUE,
			"match_token" 			=> FALSE,
			"session_name" 			=> "simple_mvc_session",
			"cookie_path" 			=> "/",
			"cookie_domain" 		=> NULL,
			"cookie_secure" 		=> NULL,
			"cookie_httponly" 		=> NULL,
			"regenerate" 			=> 300,
			"expiration" 			=> 7200,
			"session_database" 		=> TRUE,
			"table_name" 			=> "sessions",
			"primary_key" 			=> "session_id"
	),
		
	"mail" => array(
			"mailer_type" 			=> "system",
			"smtp_enable" 			=> YES,
			"smtp_auth" 			=> YES,						
			"smtp_server" 			=> "mail.example.com",
			"smtp_port" 			=> 25,
			"smtp_timeout" 			=> 30,
			"smtp_usr" 				=> "username",
			"smtp_psw" 				=> "password",
			"smtp_from_email" 		=> "admin@example.com",
			"smtp_from_name" 		=> "Duc Bui",
			"smtp_reply_email" 		=> "admin@example.com",
			"smtp_reply_name" 		=> "Duc Bui"			
	),
		
	"logging" => array(
			"log_level" 			=> 200,
			"log_handler" 			=> "file",
			"log_file" 				=> "/tmp/ngukho.log"
	)	
		
);

