<?php
/**
 *
 * @View Class
 *
 * @copyright Copyright (C) 2009 PHPRO.ORG. All rights reserved.
 *
 * @license new bsd http://www.opensource.org/licenses/bsd-license.php
 * @package Core
 *
 */

class View
{
	
	private $layout_file = 'default';	
	
	public $_disableLayout = false;
	
	/**
	 *
	 * The constructor, duh
	 *
	 */
	public function __construct()
	{
		// parent::__construct(array(), ArrayObject::ARRAY_AS_PROPS);
		$this->template_dir = __APP_PATH . "/views";
		$this->layout_dir = __SITE_PATH . "/layouts";
		
	}

	/**
	 * The variable property contains the variables
	 * that can be used inside of the templates.
	 *
	 * @access private
	 * @var array
	 */
	private $variables = array();

	/**
	 * The directory where the templates are stored
	 *
	 * @access private
	 * @var string
	 */
	private $template_dir = null;
	
	/**
	 * The directory where the templates are stored
	 *
	 * @access private
	 * @var string
	 */
	private $layout_dir = null;	

	/**
	 * Adds a variable that can be used by the templates.
	 *
	 * Adds a new array index to the variable property. This
	 * new array index will be treated as a variable by the templates.
	 *
	 * @param string $name The variable name to use in the template
	 *
	 * @param string $value The content you assign to $name
	 *
	 * @access public
	 *
	 * @return void
	 *
	 * @see getVars, $variables
	 *
	 */
	public function __set($name, $value)
	{
		$this->variables[$name] = $value;
	}
	
	public function getLayout()
	{
		return $this->layout_file;	
	}

	public function setLayout($layout_file)
	{
		$this->layout_file = $layout_file;	
	}
	

	/**
	 * @Returns names of all the added variables
	 *
	 * Returns a numeral array containing the names of all
	 * added variables.
	 *
	 * @access public
	 *
	 * @return array
	 *
	 * @see addVar, $variables
	 *
	 */
	public function getVars()
	{
		 $variables = array_keys($this->variables);
		 return !empty($variables) ? $variables : false;
	}

	/**
	 *
	 * Outputs the final template output
	 *
	 * Fetches the final template output, and echoes it to the browser.
	 *
	 * @param string $file Filename (with path) to the template you want to output
	 *
	 * @param string $id The cache identification number/string of the template you want to fetch
	 *
	 * @access public
	 *
	 * @return void
	 *
	 * @see fetch
	 *
	 */
	public function parser($file)
	{
		return $this->fetch($file);
	}
	
	public function renderAction($controller,$action,$vars = array())
	{
		$query_str = "";
		foreach ($vars as $key=>$value) $query_str .=  "/$key/$value";
		$url = site_url("$controller/$action" . $query_str);
		$url = str_replace(" ","%20",$url); 
		return file_get_contents($url);
	}
	
	public function loadLayout($name = null) 
	{
		$path = null;		
		if (!empty($this->layout_dir))
		{
			if(is_null($name))
				$path = $this->layout_dir . '/' . $this->layout_file . '.phtml';
			else 
				$path = $this->layout_dir . '/' . $name . '.phtml';
		}
			
		if (file_exists($path) == false)
		{
			throw new Exception('Layout not found in '. $path);
			return false;
		}
		
		if($this->_disableLayout == true)
			return $this->variables['content'];
		
		$output = $this->getOutput($path);
		return isset($output) ? $output : false;		
	}		

	/**
	 * Fetch the final template output and returns it
	 *
	 * @param string $template_file Filename (with path) to the template you want to fetch
	 *
	 * @param string $id The cache identification number/string of the template you want to fetch
	 *
	 * @access private
	 *
	 * @return string Returns a string on success, FALSE on failure
	 *
	 * @see display
	 *
	 */
	public function fetch($template_file)
	{
		/*** if the template_dir property is set, add it to the filename ***/
//		if (!empty($this->template_dir))
//		{
//			$template_file = realpath($this->template_dir) . '/' . $template_file . '.phtml';
//		}
		
		$r = explode('/',$template_file);
		
		$template_file = realpath(__APP_PATH . "/{$r[0]}/views") . "/{$r[1]}/{$r[2]}.phtml";
		
//		$template_file = realpath(__APP_PATH . $r[0]) . '/' . $template_file . '.phtml';
		
		$output = $this->getOutput($template_file);
		return isset($output) ? $output : false;
	}

	/**
	 *
	 * Fetch the template output, and return it
	 *
	 * @param string $template_file Filename (with path) to the template to be processed
	 *
	 * @return string Returns a string on success, and FALSE on failure
	 *
	 * @access private
	 *
	 * @see fetch, display
	 *
	 */
	private function getOutput( $template_file )
	{
		if(!isset($this->variables['_view']))
			 $this->variables['_view'] = $this;
		
		/*** extract all the variables ***/
		extract( $this->variables );
	
		if (file_exists($template_file))
		{
			ob_start();
			include($template_file);
			$output = ob_get_contents();
			ob_end_clean();
		}
		else
		{
			throw new Exception("The template file '$template_file' does not exist");
		}
		return !empty($output) ? $output : false;
	}

	/**
	 *
	 * Sets the template directory
	 *
	 * @param string $dir Path to the template dir you want to use
	 *
	 * @access public
	 *
	 * @return void
	 *
	 */
	public function setTemplateDir($dir)
	{
		$template_dir = realpath($dir);
		if (is_dir($template_dir))
		{
			$this->template_dir = $template_dir;
		}
		else
		{
			throw new MvcException("The template directory '$dir' does not exist", 200);
		}
	}
	
	public function setLayoutDir($dir)
	{
		$layout_dir = realpath($dir);
		if (is_dir($layout_dir))
		{
			$this->layout_dir = $layout_dir;
		}
		else
		{
			throw new MvcException("The layout directory '$dir' does not exist", 200);
		}
	}	



} /*** end of class ***/

?>
