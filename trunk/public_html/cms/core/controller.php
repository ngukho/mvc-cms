<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

class Controller extends CaoBox{
	var $module = 'home';
	var $module_name;
	var $action = 'view';
	var $lang;
	var $page = 0;
	var $uri;
	var $url = array();
	var $root_dir;
	var $root_dir_absolute;
	var $params;
	var $project;
	var $ext;
	var $modules = array('switch'=>array());
	var $domain;
	var $model;
	var $port;
	//
	function Controller($rewrite = false,$htaccess = false){
		parent::__construct();
		global $cfg;
		$this->lang = $this->lang?$this->lang:$cfg["lang"];
		$this->uri = rtrim($_SERVER[$GLOBALS['cfg']['server_var']],'\\/').'/';
		$this->domain = 'http'.(isset($_SERVER['HTTPS']) && strtoupper($_SERVER['HTTPS']) != 'OFF'?'s':'').'://'.$_SERVER['SERVER_NAME'];
		$project = rtrim(dirname($_SERVER['SCRIPT_NAME']),'\\/').'/';		
		if($rewrite){
			if($htaccess===true){
				$this->root_dir = './';
				$this->uri = str_replace("index.php/","",strtok($this->uri, "?"));				
				$this->root_dir_absolute = $project;				
				if($project!='/'){
					$this->params = explode('/',str_replace($project,'',$this->uri));
				}else{
					$this->params = explode('/',ltrim($this->uri,'/'));
				}
			}else{
				
				$file = $htaccess===false?'index.php':$htaccess;
				$tmp = explode('/'.$file.'/',strtok($this->uri, "?"));				
				$this->params = explode('/',rtrim($tmp[1],"/"));
				$this->root_dir = "./$file/";
				$this->root_dir_absolute = "$project$file/";
			}					
			if($this->params[0]){
				$arrmod = pathinfo($this->params[0]);
				$this->module = $arrmod["filename"];
			}
			if(isset($this->params[1])){
				$arrmod = pathinfo($this->params[1]);
				$this->action = $arrmod["filename"];
			}
		}else{
			if(isset($_GET['mod'])) $this->module = $_GET['mod'];
			if(isset($_GET['act'])) $this->action = $_GET['act'];		
		}	
		
		$this->module_name = $this->module;
		$this->project = $project;
	}
	
	function load_ext(){
		$model = $this->model;
		$controller = &$this;
		if(is_dir(_ROOT.APPLICATION."ext/")){
			$dir = dir(_ROOT.APPLICATION."ext/");
			while ($file = $dir->read())  if (substr($file, -4) == '.php') require $dir->path.$file;
		}
		
	}	
	function load(){		
		// check master module
		if(isset($_GET['lang'])  && file_exists(_ROOT.APPLICATION."languages/".$_GET['lang'].'/index.php')) $this->lang = $_GET['lang'];
		if(!file_exists(_ROOT.APPLICATION."languages/".$this->lang.'/index.php')) $this->lang = $this->lang;
		
		//get action		
		$this->action=$this->act();
		//print_r($this->action);exit;
		if(!file_exists(_ROOT.APPLICATION."modules/master/header.php")) $this->getError("Module MASTER:HEADER doesn't exists");
		if(!file_exists(_ROOT.APPLICATION."modules/master/footer.php")) $this->getError("Module MASTER:FOOTER doesn't exists");
		if(!file_exists(_ROOT.APPLICATION."modules/".$this->module.'/index.php')) $this->module = '404';//$this->getError("Module ".$this->module." doesn't exists");
		if(!file_exists(_ROOT.APPLICATION."modules/".$this->module.'/action.'.$this->action.'.php')){
			if(file_exists(_ROOT.APPLICATION."modules/".$this->module.'/action.view.php')) $this->action = 'view';
			else $this->getError("Action ".$this->action." doesn't exists");
		}
		
		
		$model = $this->model;
		if(isset($GLOBALS['core_ext'])) foreach($GLOBALS['core_ext'] as $ext){
			$ext_file = _CORE.APPLICATION.'core/ext/'.$ext.'.php';
			if(file_exists($ext_file)) require $ext_file;
		}
		

		$system  = $this;
		if(file_exists(_ROOT.APPLICATION.'modules/master/hooks.php')) require(_ROOT.APPLICATION.'/modules/master/hooks.php');
		$hook = new bHooker;
		
		
		$hook->web_header();
		require(_ROOT.APPLICATION.'config.php');
		require(_ROOT.APPLICATION.'defined.php');
		
		
		
		require _ROOT.APPLICATION."languages/".$this->lang.'/index.php';
		if(file_exists(_ROOT.APPLICATION."modules/master/functions.php")) require(_ROOT.APPLICATION."modules/master/functions.php");
		if(file_exists(_ROOT.APPLICATION."modules/master/classes.php")) require(_ROOT.APPLICATION."modules/master/classes.php");
		require _ROOT.APPLICATION."modules/master/header.php";
		
		if(file_exists(_ROOT.APPLICATION."modules/master/user.php"))
			require _ROOT.APPLICATION."modules/master/user.php";
		
		
		//run only for this module/action
		if(file_exists(_ROOT.APPLICATION."modules/master/".$this->module.'.'.$this->action.'.php')){
			require _ROOT.APPLICATION."modules/master/".$this->module.'.'.$this->action.'.php';
		}elseif(file_exists(_ROOT.APPLICATION."modules/master/".$this->module.'.php')){
			require _ROOT.APPLICATION."modules/master/".$this->module.'.php';
		}
		
		
		if(file_exists(_ROOT.APPLICATION."modules/".$this->module.'/classes.php'))
			require _ROOT.APPLICATION."modules/".$this->module.'/classes.php';
		if(file_exists(_ROOT.APPLICATION."modules/".$this->module.'/functions.php'))
			require _ROOT.APPLICATION."modules/".$this->module.'/functions.php';
		if($this->module) require _ROOT.APPLICATION."modules/".$this->module.'/index.php';
		if($this->action) require _ROOT.APPLICATION."modules/".$this->module.'/action.'.$this->action.'.php';
		if(file_exists(_ROOT.APPLICATION."modules/".$this->module."/footer.php")) require(_ROOT.APPLICATION."modules/".$this->module."/footer.php");
		require _ROOT.APPLICATION."modules/master/footer.php";
		
		$hook->web_footer();
	}
	function act(){
		$acttemp = $this->modules["data"][$this->module][$this->lang]["module_actions"];
		if (count($acttemp)>0){
			foreach($acttemp as $key=>$value){				
				if ($value==$this->action){					
					$this->action = $key;	
					break;			
				}
			}			
		}
		return $this->action;
	}
	
	
}

?>