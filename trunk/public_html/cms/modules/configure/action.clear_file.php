<?php
defined('_ROOT') or die(__FILE__);
extract($_GET);
remove_dir(_UPLOAD);
remove_dir(_CACHE);
$hook->redirect('?mod='.$system->module);
?>