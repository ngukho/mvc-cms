<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

require _ROOT.APPLICATION.'config.inc.php';
require _CORE.APPLICATION.'core/caobox.php';
require _CORE.APPLICATION.'core/hooks.php';
require _CORE.APPLICATION.'core/controller.php';
require _CORE.APPLICATION.'core/scripts/driver/'.$cfg['driver'].'.php';
require _CORE.APPLICATION.'core/scripts/db.php';
require _CORE.APPLICATION.'core/model.php';
require _CORE.APPLICATION.'core/scripts/template.php';
require _CORE.APPLICATION.'core/view.php';
?>