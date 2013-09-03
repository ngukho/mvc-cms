<?php

defined('_ROOT') or die(__FILE__);
extract($_GET);
$oClass->clear_data();
clear_sql_cache();
$hook->redirect('?mod='.$system->module);
?>