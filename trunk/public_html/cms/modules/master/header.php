<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}
$oMaster = new MasterModel;
$cache_dao = _CACHE.'dao_cms.php';
if(!file_exists($cache_dao)){
	$file_str = '<?php'."\n";
	$dir = dir(_ROOT.APPLICATION."/dao/");
	while ($file = $dir->read()) {
	  if (substr($file, -4) == '.php') {
		$class = ucfirst(substr($file,0,-4));
		$class_dao = $class.'DAO';
		require _ROOT.APPLICATION.'dao/'.$file;
		$file_str .= 'require _ROOT.APPLICATION.\''."dao/".$file."';\n";
		if(class_exists($class_dao)){
			${'o'.$class} = new $class_dao;
			$file_str .= '$o'.$class.' = new '.$class_dao.";\n";
		}
	  }
	}
	$file_str .= '?>';
	$fp = @fopen($cache_dao,'w');
	if($fp){
		fwrite($fp,$file_str);
		fclose($fp);
	}
}else{
	include $cache_dao;
}

$request = $_GET;
$token = isset($request['token'])?$request['token']:session_id();

$request['type'] = intval($request['type']);
$request['parentid'] = intval($request['parentid']);
if(!$_SESSION['cms_menu']) $_SESSION['cms_menu'] = '0.0';
if($_GET['menu']){
	$_SESSION['cms_menu'] = $request['menu'];
}
$menu = explode('.',$_SESSION['cms_menu']);

//unset($_SESSION);
if($GLOBALS['controller']->module=='user' && $GLOBALS['controller']->action=='login'){
	if(isset($_SESSION['admin_login'])) $hook->redirect('./');
}else{
	if(!isset($_SESSION['admin_login'])) $hook->redirect('./?mod=user&act=login');
}

$result = $oConfigure->view();
while($rs = $result->fetch()){
	$cfg[$rs['code']] = addslashes($rs['value']);
}

/* --------------------- LEFT MENU ----------------------------*/
$tpl = new View('template',$languages); 
$tpl->cache = _CACHE.'cms_';


$image_exts = array('.jpg','.jpeg','.gif','.png');
$login_user = $_SESSION['admin_login'];
$login_user['setting'] = $login_user['data']?unserialize($login_user['data']):array();
$skin_dir = is_dir($tpl->tpl_dir.'skins/'.$login_user['setting']['skin'])?$tpl->tpl_dir.'skins/'.$login_user['setting']['skin']:$tpl->tpl_dir.'skins/default';


//Load main template
//if(!$skin_dir) $tpl->getWarning('The skin '.$login_user['skin'].' don\'t exists');
//if(!$login_user['setting']['skin'] || !$skin_dir) $login_user['setting']['skin'] = 'default';

$tpl->setfile(array(
	'main'=>'master.tpl',
),$skin_dir);

$tpl->setfile(array(
	'master_header'=>'master_header.tpl',
	'master_footer'=>'master_footer.tpl',
));
$tpl->assign(array(
	'module'=>$system->module,
	'action'=>$system->action,
	'language'=>$system->lang,
	'page'=>$system->page,
	'_UPLOAD'=>_UPLOAD,
	'web_client'=>$cfg['client'],
	'system_year'=>date('Y'),
));

$tpl->merge($login_user,'login_user');

if($cfg['language_tab'] == 'tab'){
	$tpl->box('language_tab');
}

if($_SESSION['admin_login']['permission'] == 'ALL') $access = 'ALL';
else $access = unserialize($_SESSION['admin_login']['permission']);


$user_permission = $access['action']?$access['action']:array();
if($access == 'ALL'){
	$user_permission = array(
		'edit'=>1,
		'new'=>1,
		'delete'=>1,
	);
	
}

$ucp = array();
if(!$user_permission['new']) $ucp['new'] = '_d';
if(!$user_permission['edit']) $ucp['edit'] = '_d';
if(!$user_permission['delete']) $ucp['delete'] = '_d';
$tpl->merge($ucp,'ucp');
$tpl->merge($user_permission,'access');
//print_r($login_user['setting']['menu']);
if($access!='ALL') array_pop($MenuName);
$n = count($MenuName);
for($i=0;$i< $n;$i++) if($access=='ALL' || $access['act'][$i]){
	$links = '';
	$_cms_menu = explode('.',$_SESSION['cms_menu']);
	$class_hide = $login_user['setting']['menu'][$i]?'':'style="display: none;"';
	foreach($MenuLink[$i] as $j=>$arr) if($access=='ALL' || $access['act'][$i][$j]){
		$links .= '<a href="'.$arr['link'].'&menu='.$i.'.'.$j.'&token='.$token.'" class="left_'.($arr['class']?$arr['class']:'normal').($_SESSION['cms_menu']==$i.'.'.$j?' active':'').'" '.$class_hide.'>'.$arr['name'].'</a>';
	}
	$tpl->assign(array(
		'name'=>$MenuName[$i]["name"],
		'id'=>$i,
		'links'=>$links,
		'class'=>$MenuName[$i]["class"]!=""?$MenuName[$i]["class"]:$MenuLink[$i][0]['class_home'],
		'firslink'=>$MenuLink[$i][0]['link'].'&menu='.$i.'.0',
		'active'=>$i == $_cms_menu[0]?'active':'',
		'cls_cms'=>$i >= $n-2 ?'menu_cms':'',
	),'leftmenu');

}
$acl_user_act = is_array($access["module_act"])?$access["module_act"][$_cms_menu[0]]:array();

$tpl->merge($languages,'lang');
$tpl->merge($cfg,'cfg');
$tpl->merge((array)$system,'system');
?>