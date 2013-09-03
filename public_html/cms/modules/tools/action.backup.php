<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}
if($_GET['do']=='delete'){
	if($_GET['file'] && file_exists(_BACKUP.$_GET['file'])){
		unlink(_BACKUP.$_GET['file']);
	}
	$hook->redirect('./?mod='.$system->module.'&act='.$system->action);
}
if($_GET['do']=='download'){
	if($_GET['file'] && file_exists(_BACKUP.$_GET['file'])){
		if ($fp = fopen(_BACKUP . $_GET['file'], 'rb')) {
		$buffer = fread($fp, filesize(_BACKUP . $_GET['file']));
		fclose($fp);
		
		header('Content-type: application/x-octet-stream');
		header('Content-disposition: attachment; filename=' . $_GET['file']);
		
		echo $buffer;
		
		exit;
		}
	}
	$hook->redirect('./?mod='.$system->module.'&act='.$system->action);
}
if($_POST){	
	$backup_file = backup_tables(_BACKUP, $cfg['server'],$cfg['port'],$cfg['usr'],$cfg['psw'],$cfg['name']);
	if($_POST['download_only']){
	      header('Content-type: application/x-octet-stream');
          header('Content-disposition: attachment; filename=' . $backup_file);

          readfile(_BACKUP . $backup_file);
          unlink(_BACKUP . $backup_file);
		  exit();
	}
	$hook->redirect('./?mod='.$system->module.'&act='.$system->action);

}



if($_GET['do'] == 'new'){
	$tpl->setfile(array(
		'body'=>'tools.'.$system->action.'.new.tpl',
	));

}else{
	$tpl->setfile(array(
		'body'=>'tools.'.$system->action.'.tpl',
	));
    $dir = dir(_BACKUP);
    $contents = array();
    while ($file = $dir->read()) {
      if (!is_dir(_BACKUP . $file) && in_array(substr($file, -3), array('zip', 'sql', '.gz'))) {
        $contents[] = array('name'=>$file,'date'=>date('Y-m-d H:i:s',fileatime($dir->path.$file)),'size'=>number_format(filesize($dir->path.$file)/1024, 2));
      }
    }
    sort($contents);
	
	foreach($contents as $rs){
		$tpl->assign($rs,'files');
	}
	$request = array();
	$request['_BACKUP'] = _BACKUP;
	
	$tpl->assign($request);

}
?>