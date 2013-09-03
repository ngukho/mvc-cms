<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}
class bTemplate extends CaoBox{
	var $attribute = 'src|href|background|value|url';
	var $folder = 'format|media|css|images|flash|js';
	var $files=array();
	var $mytpl;
	var $_data;
	var $tpl_dir;
	var $cache;
	var $aLang = array();
	var $return_data = array();
	var $box = array();
	var $merge = array();
	var $cachefile; // private
	var $root_dir = "";
	function __construct($tpl_dir){ 
		$this->mytpl = NULL;
		$this->_data = array();
		$this->tpl_dir = rtrim($tpl_dir,'/').'/';
	
	}

	function box($name){
		$this->box[$name] = true;
	}
	
	function setstring($array_files,$dir = NULL,$justify_path = true ){
		$this->setfile($array_files,$dir,$justify_path,true);
	}
	
	function setfile($array_files,$dir = NULL,$justify_path = true,$file_string = false){
		if(!$array_files) return false;
		$dir = $dir ? rtrim($dir,'/').'/' : $this->tpl_dir;
		if(!is_array($array_files)){ $this->files['__master__'] = $dir.$array_files;return false;} // load template master
		foreach($array_files as $block=>$file) {
			$this->files[$block] = array('dir'=>$dir, 'file'=>$file,'file_string'=>$file_string, 'justify_path'=>$justify_path);
		}
	}
	
	function attribute($attribute){
		$this->attribute .= '|'.$attribute;
	}
	
	function folder($folder){
		$this->folder .= '|'.$folder;
	}
	
	function _load($file){
		if(file_exists($this->root_dir.$file)) return file_get_contents($this->root_dir.$file);
		$this->getError('File :'.$this->root_dir.$file.' isn\'t exists');
	}
	
	function merge($array,$prefix = 'lang'){
		if(!$prefix) return false;		
		$this->merge[$prefix] = is_array($array)?$array:array();
	}
	
	function assign($vararray,$block = NULL){
		if($vararray && !is_array($vararray) && $block) $this->_data['.'][0][$block] = $vararray;
		if(!$vararray || !is_array($vararray)) return false;
		if($block){
			if(strpos($block,'.')){
				$blocks = explode('.',$block);
				$this->_data["$blocks[0]."][(count($this->_data["$blocks[0]."])-1)]["$blocks[1]."][] = 
					$vararray;
			}else{
				$this->_data["$block."][] = $vararray;
			}		
		}else{
			if(!isset($this->_data['.'][0])) $this->_data['.'][0] = array();
			$this->_data['.'][0] = array_merge($this->_data['.'][0],$vararray);
		}
	}
	
	function compile_tpl($mytpl){
		$mytpl = str_replace('\\', '\\\\', $mytpl); 
		$mytpl = str_replace('\\\{', ':ldelim:', $mytpl);
		$mytpl = str_replace('\\\}', ':rdelim:', $mytpl);
		$mytpl = str_replace('\'', '\\\'', $mytpl);
		return $mytpl;
	}
	
	function getTemplate($tpl_dir,$currentLang){
		$mytpl = '';
		if(count($this->files)) foreach($this->files as $block=>$array){
			$content = $array['file_string']?$array['file']:$this->_load($array['dir'].$array['file']);
			if($array['justify_path']){
				$content = $this->modify_url($content,$array['dir']?$array['dir']:$tpl_dir,$currentLang);
			}
			if($mytpl==''){
				$mytpl = $content;
			}else{
				$mytpl = str_replace('{include:tpl='.$block.'}',"\n<!--start:$block-->\n".$content."\n<!--end:$block-->\n",$mytpl);
			}
		}
		$mytpl = $this->compile_tpl($mytpl);
		$mytpl = $this->compile_data($mytpl,$tpl_dir,$currentLang);
		
		return $mytpl;
	}
	
	function compile_block($mytpl){
		preg_match_all('#\{(([a-z0-9\-_]+?\.)+?)([a-z0-9\-_]+?)\}#is', $mytpl, $vars);

		if(count($vars[0])){
			$keyblock = array_unique($vars[0]);
		 	foreach($keyblock as $i=>$val){
				$tmp = explode('.',substr($val,1,-1));
				$cTmp = count($tmp);
				if($cTmp ==2){
					$mytpl = str_replace($val,'\'.(isset($this->_data["'.$tmp[0].'."][$i]["'.$tmp[1].'"])?$this->_data["'.$tmp[0].'."][$i]["'.$tmp[1].'"]:\'\').\'',$mytpl);
				}elseif($cTmp==3){
					$mytpl = str_replace($val,'\'.(isset($this->_data["'.$tmp[0].'."][$i]["'.$tmp[1].'."][$j]["'.$tmp[2].'"])?$this->_data["'.$tmp[0].'."][$i]["'.$tmp[1].'."][$j]["'.$tmp[2].'"]:\'\').\'',$mytpl);
				}
			}
		}
		// compile boxes		
		$mytpl = preg_replace('#<!--BOX ([^\.<>]+?)-->(.*?)<!--BOX \\1-->#is','\'; if(isset($this->box[\'\\1\'])){ $_tpl.= \'\\2\';}$_tpl .= \'',$mytpl);
		
		//block level 1
		preg_match_all('#<!--BASIC ([^\.<>]+?)-->(.*?)<!--BASIC \\1-->#is',$mytpl,$blocks);
		$block = $blocks[1];
		for($i=0;$i<count($block);$i++){
			$str = "';if(isset(\$this->_data['".$block[$i].".'])){";
			$str .= 'for($i=0;$i< count($this->_data[\''.$block[$i].'.\']);$i++){';
			$str .= "\$_tpl .= '".$blocks[2][$i]."';";
			$str .= '}}'."\$_tpl .= '";
			$mytpl = str_replace($blocks[0][$i],$str,$mytpl);
		}
		
		//block level 2
		preg_match_all('#<!--BASIC ([a-z0-9^\.]+?)\.([a-z0-9^\.]+?)-->(.*?)<!--BASIC \\1\.\\2-->#is',$mytpl,$subblocks);
		$block = $subblocks[1];
		$subblock = $subblocks[2];
		for($j=0;$j<count($block);$j++){
			$str = "';\nif(isset(\$this->_data['$block[$j].'][\$i]['".$subblock[$j].".'])){";
			$str .= '	for($j=0;$j<count($this->_data[\''.$block[$j].'.\'][$i][\''.$subblock[$j].'.\']);$j++){';
			$str .= "		\$_tpl .= '".$subblocks[3][$j]."';";
			$str .= '	}}'."\n\$_tpl .= '";
			$mytpl = str_replace($subblocks[0][$j],$str,$mytpl);
		}
		return $mytpl;
	}
	
	function compile_data($mytpl,$tpl_dir,$currentLang){
		$mytpl = preg_replace('/\{([a-z0-9\-_]+?)\}/i','\'.(isset($this->_data["."][0][\'\\1\'])?$this->_data["."][0][\'\\1\']:\'\').\'',$mytpl);
		$mytpl = preg_replace('/\{([a-z0-9\-_]+?)\}/i','\'.(isset($this->_data["."][0][\'\\1\'])?$this->_data["."][0][\'\\1\']:\'\').\'',$mytpl);
		$mytpl = $this->modify_url($mytpl,$tpl_dir,$currentLang);
		if(count($this->merge)) foreach($this->merge as $prefix=>$arr){
			$mytpl =  preg_replace('/\{'.$prefix.'\.([a-z0-9\-_]*?)\}/i','\'.(isset($this->merge[\''.$prefix.'\'][\'\\1\'])?$this->merge[\''.$prefix.'\'][\'\\1\']:\'\').\'',$mytpl);
		}
		return $mytpl;
	}
	
	function modify_url($mytpl,$tpl_dir,$currentLang){
		$mytpl = preg_replace('/('.$this->attribute.')="('.$this->folder.')\/lang_([a-z]{2})\//i','\\1="'.$tpl_dir.'\\2/lang_'.$currentLang.'/',$mytpl);
		$mytpl = preg_replace('/('.$this->attribute.')="('.$this->folder.')\//i','\\1="'.$tpl_dir.'\\2/',$mytpl);				
		return $mytpl;
	}
		
	function reset(){
		$this->mytpl = NULL;
		$this->files = array();
	}
	
	function parse($mimeType = 'text/html'){
		$file = '';
		$max_time = 0;
		foreach($this->files as $arr){
			$file .= $arr['dir'].$arr['file'];
			if($t = filemtime($arr['dir'].$arr['file']) > $max_time) $max_time = $t;
		}
		$this->cachefile = 'tpl_'.md5($file).'.php';
		if(file_exists($this->cache.$this->cachefile) && filemtime($this->cache.$this->cachefile) > $max_time){
			include $this->cache.$this->cachefile;
			if($merge_block == count($this->merge)){ return $_tpl; }
		}
		
		if(file_exists($this->cache.$this->cachefile)) @unlink($this->cache.$this->cachefile);
		$mytpl = $this->getTemplate($this->tpl_dir,$this->aLang);
		$mytpl = $this->compile_block($mytpl);
		$mytpl = preg_replace('/\{([a-z0-9\._\-\:=]+?)\}/i','',$mytpl);
		$mytpl = str_replace(array(':ldelim:',':rdelim:'),array('{','}'),$mytpl);
		$mytpl = preg_replace('/url\((.*?)\)/i','url('.$this->tpl_dir.'\\1)',$mytpl);		
		
		$bug = 1;
		$_tpl = NULL;
		@eval("\$_tpl = '$mytpl';\$bug = 0;");
		if($bug){
			$msg = 'An error appear when template parsed <br />';
			$msg .= highlight_string("<?php \$_tpl= '$mytpl';\n?>",1);
			$this->getError($msg,1);
		}
		$this->mytpl = $mytpl;
		return $_tpl;
	}
	
	function cache(){
		if($this->cachefile && !file_exists($this->cache. $this->cachefile)){
			$str = "<?php defined('_ROOT') or die('Not Allowed');\n";
			$str .= "\$merge_block = ".count($this->merge).";\n";
			$str .= "\$_tpl = '".$this->mytpl."'; ?>";
			$fp = @fopen($this->cache. $this->cachefile,'w');
			if($fp){ fwrite($fp,$str);fclose($fp);}
		}
	}

}
?>