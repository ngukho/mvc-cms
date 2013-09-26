<?php  

define('_TB_PREFIX', $config->config_values['database_master']['db_prefix']);
define('TB_USERS', _TB_PREFIX . 'users');
define('TB_CONTENTS', _TB_PREFIX . 'tb_content');
define('TB_CONTENT_CATS', _TB_PREFIX . 'tb_content_cat');
define('TB_PRODUCTS', _TB_PREFIX . 'tb_product');
define('TB_PRODUCT_CATS', _TB_PREFIX . 'tb_product_cat');


define('TB_CATEGORY', _TB_PREFIX . 'category');
define('TB_CATEGORY_LN', _TB_PREFIX . 'category_ln');

define('TB_CONTENT', _TB_PREFIX . 'content');
define('TB_CONTENT_LN', _TB_PREFIX . 'content_ln');
define('TB_CONTENT_OPTIONS', _TB_PREFIX . 'content_options');

define('TB_OPTIONS', _TB_PREFIX . 'options');
define('TB_OPTIONS_LN', _TB_PREFIX . 'options_ln');

define('TB_HTML', _TB_PREFIX . 'html');
define('TB_HTML_LN', _TB_PREFIX . 'html_ln');

define('TB_GALLERY', _TB_PREFIX . 'gallery');

define('TB_CONFIGURE_MODULE', _TB_PREFIX . 'configure_mod');

define('TB_LANGUAGE', _TB_PREFIX . 'language');

define('TB_COMMENT', _TB_PREFIX . 'comment');

define('TB_CONFIGURE_SYSTEM', _TB_PREFIX . 'configure');
define('TB_CONFIGURE_SYSTEM_GROUP', _TB_PREFIX . 'configure_group');

define('TB_MEMBER', _TB_PREFIX . 'member');
define('TB_MEMBER_DETAIL', _TB_PREFIX . 'member_detail');

define('CONFIG_FACEBOOK_GROUP_ID', '6');
define('CONFIG_CALTEX2013_GROUP_ID', '7');
define('CODE_CALTEX2013_GIFT_QUANTUM_1', 'caltex2013_gift_quatum_1');
define('CODE_CALTEX2013_GIFT_QUANTUM_2', 'caltex2013_gift_quatum_2');
define('CODE_CALTEX2013_LIMIT_GIFT_PER_USER', 'caltex2013_limit_gift_per_user');
define('CODE_CALTEX2013_LIMIT_WIN_IN_EVENT', 'caltex2013_limit_win_in_event');





/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 					'ab');
define('FOPEN_READ_WRITE_CREATE', 				'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 			'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

