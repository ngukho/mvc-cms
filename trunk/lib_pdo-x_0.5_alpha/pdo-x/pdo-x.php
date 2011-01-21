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

/* 
*  pdo-x is designed for use in PHP5.1 and above with PDO support.  Make sure
*  that the necessary extensions are enabled in your php.ini file.  For more
*  information visit: http://php.net/pdo
*/

// PostgreSQL DSN
if (!defined('PDO_DATABASE_DSN'))
{
	define ('PDO_DATABASE_DSN','pgsql:host=localhost;dbname=pdo-x-test');
}

// MySQL DSN
if (!defined('PDO_DATABASE_DSN'))
{
	define ('PDO_DATABASE_DSN','mysql:host=localhost;dbname=pdo-x-test');
}

// DB username and password
if (!defined('PDO_DATABASE_USERNAME'))
{
	define ('PDO_DATABASE_USERNAME','');
}
if (!defined('PDO_DATABASE_PASSWORD'))
{
	define ('PDO_DATABASE_PASSWORD','');
}

// Include core classes
include_once ("PDORecord.class.php");
include_once ("PDORecordset.class.php");
?>