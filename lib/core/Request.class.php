<?php

final class Request 
{
	protected $file;
	protected $class;
	protected $method;
	protected $dir_template;
	protected $args = array();
	
	protected $module;
	protected $controller;
	protected $action;

	public function __construct($route = NULL, $args = array()) 
	{
		$config = Config::getInstance();
		$display_errors = $config->config_values['application']['display_errors'];
		
		$this->parseUri($route);
		
		$moduleDir = __APP_PATH . '/controllers/' . $this->module;
		$controllerFile = __APP_PATH.'/controllers/'.$this->module.'/'.upperCamelcase($this->controller).'Controller.php';
		$controllerClass =  upperCamelcase($this->module).'_'.upperCamelcase($this->controller).'Controller';;
		
		$this->method = lowerCamelcase($this->action).'Action';
		$this->args = array_merge($this->args,$args); 
		
		if(!is_dir($moduleDir))
		{
			throw new MvcException("Module not found : {$moduleDir}");
		}
		
		if(is_file($controllerFile))
		{
			$this->file = $controllerFile;
			$this->class = $controllerClass;
			$this->dir_template = __VIEW_PATH . "/" . $this->module . '/' . $this->controller;
		}
		else 
		{
			throw new MvcException("Controller not found : {$controllerFile}");
		}
		
	}
	
	private function parseUri($route)
	{
		$config = Config::getInstance();
		
		// removes the trailing slash
//		$route = preg_replace("/\/$/", '', $route);
// 		/this/that/theother/ => this/that/theother
		$route = trim($route, '/');
		
		// get the default uri
		if(empty($route))
			$route = $config->config_values['application']['default_uri'];
			
		$path = '';
		$parts = explode('/', str_replace('../', '', $route));
		
		$i = 0;		
		foreach ($parts as $part) 
		{
			$path .= $part;
			if($i == 0)
			{
				$this->module = $path;
				$path .= '/';
				array_shift($parts);
				$i++;
				continue;
			}
			$this->controller = $part;
			array_shift($parts);
			break;
		}

		// Neu controller la rong . Route co dang [module]/
		if(empty($this->controller))
		{
			$this->controller = 'index';
		}

		$method = array_shift($parts);
				
		if ($method) {
			$this->action = $this->method = $method;
		} else {
			$this->action = $this->method = 'index';
		}
		
		$this->args = $parts;
		
	}
	
	public function getFile() {
		return $this->file;
	}
	
	public function getClass() {
		return $this->class;
	}
	
	public function getMethod() {
		return $this->method;
	}
	
	public function getDirTemplate() {
		return $this->dir_template;
	}
	
	public function getFileTemplate() {
		return $this->dir_template  . '/' . $this->method . '.phtml';
	}	
	
	public function getArgs() {
		return $this->args;
	}
	
	public function getRouter()	{
		return "{$this->module}/{$this->controller}/$this->action";		
	}
	
	public function getModule() {
		return $this->module;
	}

	public function getController() {
		return $this->controller;
	}

	public function getAction() {
		return $this->action;
	}
	
}
?>