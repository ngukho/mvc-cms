<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}
$fields = $oMaster->fields('category');
$ln_fields = $oMaster->fields('category_ln');

$error = NULL;
if($_POST){
	// update main
	if(in_array('catimg',$show_actions)){ // use image
		if($cfg_type['catimg_icon']['chose']){
			$icon = upload('icon',_UPLOAD,time().'-',$image_exts);
			if(!is_array($icon)){
				if ($_POST['delete_icon'] || (!empty($icon) && trim($_POST['current_icon']) != "")){
					@unlink(_UPLOAD.$_POST['current_icon']);
					if($_POST['delete_icon']) $_POST['current_icon'] = "";
				}
			
			
				$oImg = new image(_UPLOAD.$icon);
				$oImg->resize($cfg_type['catimg_icon']['w'],$cfg_type['catimg_icon']['h'],_UPLOAD.$icon);
				$_POST['icon'] = empty($icon)?$_POST['current_icon']:$icon;
			}else{
				$error .= $icon['msg'].'<br />';
			}
			
		}
		
		$file = upload('file',_UPLOAD,time().'-',array('.pdf'));
		if(!is_array($pdf)){
				if ($_POST['delete_file'] || (!empty($file) && trim($_POST['current_file']) != "")){
					@unlink(_UPLOAD.$_POST['current_file']);
					if($_POST['delete_file']) $_POST['current_file'] = "";
				}
			

				$_POST['file'] = empty($file)?$_POST['current_file']:$file;			
			}else{
			
				$error .= $image['msg'].'<br />';
			}
		
		if($cfg_type['catimg_img']['chose']){
			$image = upload('image',_UPLOAD,time().'-',$image_exts);
			if(!is_array($image)){
				if ($_POST['delete_image'] || (!empty($image) && trim($_POST['current_image']) != "")){
					@unlink(_UPLOAD.$_POST['current_image']);
					@unlink(_UPLOAD.'thumb/'.$_POST['current_image']);
					if($_POST['delete_image']) $_POST['current_image'] = "";
				}
			
				$oImg = new image(_UPLOAD.$image);
				$oImg->resize($cfg_type['catimg_img']['w'],$cfg_type['catimg_img']['h'],_UPLOAD.$image);
				if($cfg_type['catimg_thumb']['chose']) 
					$oImg->resize($cfg_type['catimg_thumb']['w'],$cfg_type['catimg_thumb']['h'],_UPLOAD.'thumb/'.$image);

				$_POST['image'] = empty($image)?$_POST['current_image']:$image;			
			}else{
			
				$error .= $image['msg'].'<br />';
			}
			
		}
	}
	
	if(!$error){
		$arr = array(
			//'icon'=>$_POST['icon'],
		);
		
		foreach($cfg_type['cat_fields'] as $code=>$info) if($info['chose'] && in_array($code,$fields)){			
			$arr[$code] = $_POST[$code];
		}
		
		$arr['icon'] = $_POST['icon'];
		$arr['image'] = $_POST['image'];
		$arr['date'] = $_POST['date'];	
		$arr['file'] = $_POST['file'];
		
		if($request['do'] == 'new'){
			$arr['parentid'] = $request['parentid'];
			$arr['type'] = $request['type'];
			$lastid = $oClass->insert($arr);
		}else{
			$oClass->update($_GET['id'],$arr);
			$lastid = $request['id'];
			
		}
	
	}

	// update _ln
	$ln_req = $cfg_type['required_fields']?explode(',',$cfg_type['required_fields']):array('name');
	if(!$cfg_type['required_fields'] || $_POST[$ln_req[0]]) foreach($_POST[$ln_req[0]] as $ln=>$val){
		if(!$cfg_type['required_fields'] || $val){
			$arr_ln  = array(
				//'name'=>$_POST['name'][$ln],
				
			);
			
			foreach($cfg_type['ln_catfields'] as $code=>$info) if($info['chose'] && in_array($code,$ln_fields)){
				if ($code=="name"){
					 $arr_ln["name_url"] = $_POST[$code."_url"][$ln]!=""?$_POST[$code."_url"][$ln]:str2url($_POST[$code][$ln]);
				}
				$arr_ln[$code] = $_POST[$code][$ln];
			}
			
			if(in_array('ln_catimg',$show_actions)){ // use image
				if($cfg_type['ln_catimg_icon']['chose']){
					$ln_icon = upload('ln_icon',_UPLOAD,time().'-',$image_exts,$ln);
					
					if(!is_array($ln_icon)){
						if ($_POST['delete_ln_icon'][$ln] || (!empty($icon) && trim($_POST['current_ln_icon'][$ln]) != "")){
							@unlink(_UPLOAD.$_POST['current_ln_icon'][$ln]);
							if($_POST['delete_ln_icon'][$ln]) $_POST['current_ln_icon'][$ln] = "";
						}
					
					
						$oImg = new image(_UPLOAD.$ln_icon);
						$oImg->resize($cfg_type['ln_catimg_icon']['w'],$cfg_type['ln_catimg_icon']['h'],_UPLOAD.$ln_icon);
						$_POST['ln_icon'][$ln] = empty($ln_icon)?$_POST['current_ln_icon'][$ln]:$ln_icon;
					}else{
						$error .= $ln_icon['msg'].'<br />';
					}
					
				}
				
				
				
				if($cfg_type['ln_catimg_img']['chose']){
					$ln_image = upload('ln_image',_UPLOAD,time().'-',$image_exts,$ln);
					
					if(!is_array($ln_image)){
						if ($_POST['delete_ln_image'][$ln] || (!empty($ln_image) && trim($_POST['current_ln_image'][$ln]) != "")){
							@unlink(_UPLOAD.$_POST['current_ln_image'][$ln]);
							@unlink(_UPLOAD.'thumb/'.$_POST['current_ln_image'][$ln]);
							if($_POST['delete_ln_image'][$ln]) $_POST['current_ln_image'][$ln] = "";
						}
					
						$oImg = new image(_UPLOAD.$ln_image);
						$oImg->resize($cfg_type['ln_catimg_img']['w'],$cfg_type['ln_catimg_img']['h'],_UPLOAD.$ln_image);
						if($cfg_type['ln_catimg_thumb']['chose']) 
							$oImg->resize($cfg_type['ln_catimg_thumb']['w'],$cfg_type['ln_catimg_thumb']['h'],_UPLOAD.'thumb/'.$ln_image);
						$_POST['ln_image'][$ln] = empty($ln_image)?$_POST['current_ln_image'][$ln]:$ln_image;
					}else{
						$error .= $ln_image['msg'].'<br />';
					}
					
				}
			}
			$arr_ln['ln_icon'] = $_POST['ln_icon'][$ln];
			$arr_ln['ln_image'] = $_POST['ln_image'][$ln];
			if(!$error) $oClass->update_ln($lastid,$ln,$arr_ln);
			
			//die('--update category--');
		}
	}
	// update options
	if(count($_POST['options'])) foreach($_POST['options'] as $options_type=>$options_array){
		$oClass->deleteOpt($lastid,$options_type);
		foreach($options_array as $options_id){
			$opts = array(
				'content_id'=>intval($lastid),
				'options_type'=>$options_type,
				'options_id'=>$options_id,
				'page'=>'category'
			);
			$oClass->insertOpt($opts);
		}
	}
	
	if(in_array('gallerycat',$show_actions)) foreach($_FILES as $key=>$a){
		if(substr($key,0,13)=='image_gallery'){
			
			if($cfg_type['gallerycat_icon']['chose']){
				$icon = upload(str_replace('image_','icon_',$key),_UPLOAD,time().'-');
				if($icon){
					$oImg = new image(_UPLOAD.$icon);
					$oImg->resize($cfg_type['gallerycat_icon']['w'],$cfg_type['gallerycat_icon']['h'],_UPLOAD.$icon);
				}
			}
			
			if($cfg_type['gallerycat_img']['chose']){
				$img = upload($key,_UPLOAD,time().'-');
				if($img){
					$oImg = new image(_UPLOAD.$img);
					$oImg->resize($cfg_type['gallerycat_img']['w'],$cfg_type['gallerycat_img']['h'],_UPLOAD.$img);
					if($cfg_type['gallery_thumb']['chose'])
						$oImg->resize($cfg_type['gallerycat_thumb']['w'],$cfg_type['gallerycat_thumb']['h'],_UPLOAD.'thumb/'.$img);
				}
			}
			
			
			$name = $_POST[str_replace('image_','name_',$key)];
			
			if($img){
				$data = array(
					'name'=>$name,
					'icon'=>$icon,
					'image'=>$img,
				);
				$oGallery->insert($system->module,$lastid,$data);
			}
		}
	}
	clear_sql_cache();
	
	// refresh
	
	if(!$error){
		clear_sql_cache();
		$query_string = $_SERVER['QUERY_STRING'];
		parse_str($query_string,$result);
		$mod = $result['p'];
		unset($result['mod'],$result['act'],$result['id'],$result['c'],$result['p'],$result['do']);
		$hook->redirect('?mod='.$mod.'&'.http_build_query($result,NULL,'&'));
	}
}


$tpl->setfile(array(
	'body'=>$cfg_type['cattpl_update']?$cfg_type['cattpl_update']:'category.update.tpl',
));


if($request['do']=='new'){
	$breadcrumb->assign("","New");
	$request['access_action'] = 'access_new';
	$request['date'] = date('Y-m-d');
	$cat_ln = array();
}else{
	$cat_ln = $oClass->get_ln($_GET['id']);
	$result = $oClass->get($_GET['id']);
	$cat = $result->fetch();
	$tpl->assign($hook->format($cat));
	$request['display_update'] = ' show';
	$breadcrumb->assign("","Edit");
	$request['access_action'] = 'access_edit';
}

$required_fields = array();
$str_catfields = '';
$arrlevel = array();
foreach($cfg_type['cat_fields'] as $code=>$info){
	$arrlevel = $info["category_level"]!=""?explode(",",$info["category_level"]):array();	
	if($info['chose'] && $info['type'] != 'status' &&   in_array($code,$fields) && in_array($level,$arrlevel) && $code!="file" && $info['type']!="file")
	{
		$str_cat_fields .= '<tr>
		  <td class="textLabel">'.$info['name'].' '.$arr['ln_alias'].'</td>
		  <td>'.html_input($info['type'],$code,$cat[$code],' title="'.$info['require_msg'].'"').'
			<span class="description">'.$info['description'].'</span>
		  </td>
		</tr>';
		if($info['require']) $required_fields[] = $code;
	}
	else if ($info['chose'] && ($code =="file" || $info['type']=="file") && in_array($level,$arrlevel))
	{
		$show_fields[] = $code;
	}
}
$request['main_catfields'] = $str_cat_fields;


$lang_cond = "active = 1";
$lang_cond .= $cfg_type['languages']?" AND is_default=1":"";
$lang = $oLanguage->view($lang_cond);
if(!$lang->num_rows()){
	$request['msg'] = $languages['require_least_language_enable'];
	$request['hide_no_languages'] = 'hide';
}

while($rs = $lang->fetch()){
	$arr = array_merge($rs,$cat_ln[$rs['ln']]?$cat_ln[$rs['ln']]:array());
	$arr = $hook->format($arr);
	$str_ln_catfields = '';
	$required_ln_catfields = array();
	$arrlevel = array();
	foreach($cfg_type['ln_catfields'] as $code=>$info){
		$arrlevel = $info["category_level"]?explode(",",$info["category_level"]):"";
		if ($info['chose'] && in_array($level,$arrlevel) && in_array($code,$ln_fields)){
			$str_ln_catfields .= '<tr>
			  <td class="textLabel">'.$info['name'].' '.$arr['ln_alias'].'</td>
			  <td>'.html_input($info['type'],$code.'['.$arr['ln'].']',$arr[$code],' title="'.$info['require_msg'].'"').'
				<span class="description">'.$info['description'].'</span>
			  </td>
			</tr>';
			if($info['require']) { $required_ln_catfields[] = $code; }
		}
		else if($info['chose'] && $info["category_level"]=="" && in_array($code,$ln_fields)){
			$str_ln_catfields .= '<tr>
			  <td class="textLabel">'.$info['name'].' '.$arr['ln_alias'].'</td>
			  <td>'.html_input($info['type'],$code.'['.$arr['ln'].']',$arr[$code],' title="'.$info['require_msg'].'"').'
				<span class="description">'.$info['description'].'</span>
			  </td>
			</tr>';
			if($info['require']) { $required_ln_catfields[] = $code; }
		
		}
	}
	$arr['ln_fields'] = $str_ln_catfields;
	$arr['default_ln'] = $cfg['language_tab'] == 'tab'?($arr['is_default']?'active':'hide'):'';
	$arr['tab_default'] = $rs['is_default']?'class="active"':'';
	$tpl->assign($arr,'language');
	
}
$optshow = false;
if (implode(',',$cfg_type['options_cat_level'])!=""){
	foreach($cfg_type['options_cat'] as $key=>$val){
		$arrTemp = explode(",",$cfg_type['options_cat_level'][$val]);
		if (!in_array($level,$arrTemp)){			
			unset($cfg_type['options_cat'][$key]);
		}
		else{
			$optshow = true;
		}
	}
}
else
{
	$optshow = true;
}
// Load options
$attributes = array();
if($request['do'] != 'new' && $optshow){
	$result = $oContent->options($request['id'],0,0,'category');
	while($rs = $result->fetch()){
		$attributes[$rs['options_type']][] = $rs['options_id'];
	}
}


if(count($cfg_type['options_cat']) && $optshow){
	$opt_type = $oConfigure->getMod("`module` = 'options' AND typeid IN(".implode(',',$cfg_type['options_cat']).")");
	while($rs = $opt_type->fetch()){
		$result = $oOption->view($rs['typeid']);
		if($cfg_type['options_type'][$rs['typeid']] == 2) $aOpt = array(0=>'--');
		else $aOpt = array();
		while($opt = $result->fetch()){
			$aOpt[$opt['id']] = $opt['name'];
		}
		switch($cfg_type['options_type'][$rs['typeid']]){
			case '1':
				$rs['values'] = '<div class="option_checkbox" id="opt'.$rs['typeid'].'" >'.options_checkbox($aOpt,$rs['typeid'],$attributes[$rs['typeid']]).'</div>';
				$rs['js_string'] = "<input type=\'checkbox\' name=\'options[".$rs['typeid']."][]\' value=\'%value\' />%label";
				break;
			case '2': 
				$rs['values'] = options_dropdown($aOpt,$rs['typeid'],$attributes[$rs['typeid']],' id="opt'.$rs['typeid'].'" ');
				$rs['js_string'] = "<option selected=\'selected\' name=\'options[".$rs['typeid']."][]\' value=\'%value\'>%label</option>";
				break;
			case '3':
				$rs['values'] = options_dropdown($aOpt,$rs['typeid'],$attributes[$rs['typeid']],'  id="opt'.$rs['typeid'].'"  size="5" multiple="multiple"');
				$rs['js_string'] = "<option selected=\'selected\' name=\'options[".$rs['typeid']."][]\' value=\'%value\'>%label</option>";
				break;
			default: //0
				$rs['values'] = '<div class="option_checkbox" id="opt'.$rs['typeid'].'" >'.options_radio($aOpt,$rs['typeid'],$attributes[$rs['typeid']]).'</div>';
				$rs['js_string'] = "<input type=\'radio\' name=\'options[".$rs['typeid']."][]\' value=\'%value\'  checked=\'checked\' />%label";
				break;
		}
		
		$tpl->assign($rs,'options_cat');
	}
}

// load gallery
$result = $oGallery->get($system->module,$request['id']);
while($rs = $result->fetch()){
	$rs['name'] = $rs['name']?$rs['name'].'<br />':'';
	$tpl->assign($rs,'gallery');
}

//$breadcrumb->reset();

//$breadcrumb->assign("./?mod=product","Manage Products");
if($request['parentid']) $tpl->box('breadcrumb_cat');

$request['breadcrumb'] = $breadcrumb->parse();
$request['size_icon'] = demension_size($cfg_type['catimg_icon']['w'],$cfg_type['catimg_icon']['h']);
$request['size_icon'] = $request['catimg_icon']!=""?$request['size_icon']:$cfg_type['catimg_icon']['desc'];
$request['size_image'] = demension_size($cfg_type['catimg_img']['w'],$cfg_type['catimg_img']['h']);
$request['size_image'] = $request['size_image']!=""?$request['size_image']:$cfg_type['catimg_img']['desc'];

$request['size_ln_icon'] = demension_size($cfg_type['ln_catimg_icon']['w'],$cfg_type['ln_catimg_icon']['h']);
$request['size_ln_image'] = demension_size($cfg_type['ln_catimg_img']['w'],$cfg_type['ln_catimg_img']['h']);
$request['category_required'] = $cfg_type['category_required']?"'".str_replace(',',"','",$cfg_type['category_required'])."'":'';
$request['required_ln_catfields'] = count($required_ln_catfields)?"'".implode("','",$required_ln_catfields)."'":'';
$request['required_catfields'] = count($required_catfields)?"'".implode("','",$required_catfields)."'":'';
if(in_array('enable_editor',$cfg_type['act'])) $tpl->box('enable_editor');
$request['msg'] = $error;
$tpl->assign($request);


$show = array();
if($show_fields) foreach($show_fields as $field) $show['display_'.$field] = 'show';
$tpl->assign($show);

?>