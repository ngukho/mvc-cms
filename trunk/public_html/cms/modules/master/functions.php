<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}
function demension_size($width = 0,$height = 0){
	if($width || $height){
		if($height==0) return 'Width: '.$width.'px';
		elseif($width==0) return 'Height: '.$height.'px';
		else return 'Size: '.$width.' x '.$height;
	}else{
		return false;
	}
}

function ie(){
	if (preg_match("/MSIE/i", $_SERVER['HTTP_USER_AGENT'])) return true;
	return false;
}

/*function skins($folder_include_skin){
	$fskin = $folder_include_skin.'skins.txt';
	$skin = array();
	if(file_exists($fskin)){
		$handle = fopen($fskin, "r");
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$skin[] = $data;
		}
	}
	return $skin;
}
*/
/*function clear_cache_configure(){
	@unlink(_CACHE.'configure_mod.php');
}
*/

function clear_sql_cache(){
	$sql_cache = _CACHE.'sql_cache.txt';
	if(file_exists($sql_cache)) @unlink($sql_cache);
}

function remove_dir($folder){
	$dir = dir($folder);
	while ($file = $dir->read()) {
		if($file != '.' && $file != '..'){
			if(filetype($dir->path.$file) == 'file')  @unlink($dir->path.$file);
			if(filetype($dir->path.$file) == 'dir') remove_dir($dir->path.$file.'/');
		}
	}
}


function html_input($inputtype = 'input', $name = 'textfield',$value = '',$params='', $options){
	$strinput = "";
	switch($inputtype){
		case "selectbox":
			$stropt = '';
			$arroptions = explode("\n", $options);
			if (is_array($arroptions)){			
				//$check = strstr($arroptions[0],"=");
				foreach($arroptions as $key=>$val){					
					if (!strstr($val,"="))
					{
						$checked = $value==$key?'selected="selected"':'';
						$stropt .='<option value="'.$val.'" '.$checked.'>'.$val.'</option>';
					}
					else{												
						$arrvalue = explode("=", $val);		
						$arrvalue[0] = strtolower(trim($arrvalue[0])); 				
						$checked = strcasecmp($value,$arrvalue[0])==0?'selected="selected"':'';
						$stropt .='<option value="'.trim($arrvalue[0]).'" '.$checked.'>'.trim($arrvalue[1]).'</option>';
					}
				}			
			}
			return '<select name="'.$name.'" '.$params.'>'.$stropt.'</select>';
		break;	
		case "radio":
			$stropt = '';
			$arroptions = explode("\n", $options);
			if (is_array($arroptions)){			
				//$check = strstr($arroptions[0],"=");
				foreach($arroptions as $key=>$val){					
					if (!strstr($val,"="))
					{
						$checked = $value==$key?'checked="checked"':'';
						$stropt .= '<li><input type="radio" name="'.$name.'" value="'.$val.'"  '.$checked.' />  '.$val."</li>";						
					}
					else{												
						$arrvalue = explode("=", $val);		
						$arrvalue[0] = strtolower(trim($arrvalue[0])); 				
						$checked = strcasecmp($value,$arrvalue[0])==0?'checked="checked"':'';
						//$stropt .='<option value="'.trim($arrvalue[0]).'" '.$checked.'>'.trim($arrvalue[1]).'</option>';
						$stropt .= '<li><input type="radio" name="'.$name.'" value="'.trim($arrvalue[0]).'"  '.$checked.' />  '.trim($arrvalue[1])."</li>";
						
					}
				}			
			}
			return '<ul id="rdoptionlist">'.$stropt.'</ul>';
		break;	
		case "checkbox":
			$checked = $value>0?'checked="checked"':'';
			return '<input type="checkbox" name="'.$name.'" value="1"  '.$checked." ".$params.' />';
		case 'textarea':
			return '<textarea name="'.$name.'" '.$params.'>'.$value.'</textarea>';
		case 'simplemce1':
		case 'simplemce2':
		case 'simplemce3':
		case 'simplemce':
		case 'tinymce':
			return '<textarea class="'.$inputtype.'" name="'.$name.'" '.$params.'>'.$value.'</textarea>';
			break;		
		default: 
			return '<input type="text" name="'.$name.'" value="'.$value.'"  '.$params.' />';
	}
}

function html_status($name = 'select',$default_value = '1'){
	return '<select name="'.$name.'">
		  <option value="0">No</option>
		  <option value="1"'.($default_value?'selected':'').'>Yes</option>
		  </select>';
}


?>