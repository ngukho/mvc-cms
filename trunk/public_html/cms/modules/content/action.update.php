<?php

defined('_ROOT') or die(__FILE__);

$fields = $oMaster->fields('content');
$ln_fields = $oMaster->fields('content_ln');
if($_POST){
	// update main
	//print_r($_POST);exit();
	
	$error = NULL;
	$warning  = NULL;
	if(in_array('image',$show_actions)){ // use image
		if($cfg_type['main_icon']['chose']){
			$_POST['icon'] = $_POST['current_icon'];
			if(intval($_POST['delete_icon'])>0){				
				@unlink(_UPLOAD.$_POST['current_icon']);
				$_POST['current_icon'] = "";
				$_POST['icon'] = "";
				$icon = "";
			}			
			$icon = upload('icon',_UPLOAD,time().'-',$image_exts);
			if ($_POST['delete_icon'] || (!empty($icon) && trim($_POST['current_icon']) != "")){
				@unlink(_UPLOAD.$_POST['current_icon']);
				$_POST['icon'] = "";				
			}
			if(is_array($icon)){
				$error .= $icon['msg'].'<br />';
			}
			if($icon){
				$_POST['icon'] = $icon;
				$oImg = new image(_UPLOAD.$icon);
				$oImg->resize($cfg_type['main_icon']['w'],$cfg_type['main_icon']['h'],_UPLOAD.$icon);
			}
			
		}
		
		
		if($cfg_type['main_img']['chose']){
			$_POST['image'] = $_POST['current_image'];
			if(intval($_POST['delete_image'])>0){				
				@unlink(_UPLOAD.$_POST['current_image']);
				@unlink(_UPLOAD.'thumb/'.$_POST['current_image']);
				$_POST['delete_image'] = "";
				$_POST['image'] = "";
				$image = "";
			}
			$image = upload('image',_UPLOAD,time().'-',$image_exts);
			if ($_POST['delete_image'] || (!empty($image) && trim($_POST['current_image']) != "")){
				@unlink(_UPLOAD.$_POST['current_image']);
				@unlink(_UPLOAD.'thumb/'.$_POST['current_image']);	
				$_POST['image'] = "";			
			}
			if(is_array($image)){
				$error .= $image['msg'].'<br />';
			}
			if($image){
				$_POST['image'] = $image;
				$oImg = new image(_UPLOAD.$image);
				$oImg->resize($cfg_type['main_img']['w'],$cfg_type['main_img']['h'],_UPLOAD.$image);
				if($cfg_type['main_thumb']['chose']) 
					$oImg->resize($cfg_type['main_thumb']['w'],$cfg_type['main_thumb']['h'],_UPLOAD.'thumb/'.$image);
			}			
		}
	}
	// file extra
	$file_extra = array();
	if($_POST['file_extra_type']) foreach($_POST['file_extra_type'] as $k=>$ext){
		$file_extra_allow = $ext?explode(',',$ext):NULL;
		$f = upload('file_extra'.$k,_UPLOAD,time().'-',$file_extra_allow);
		if(!is_array($f)){
			if ($_POST['delete_file_extra'.$k] || (!empty($f) && trim($_POST['current_file_extra'.$k]) != "")){
				@unlink(_UPLOAD.$_POST['current_file_extra'].$k);
				if($_POST['delete_file_extra'.$k]) $_POST['current_file_extra'.$k] = "";
			}
			$file_extra[$k] = empty($f)?$_POST['current_file_extra'.$k]:$f;
		}else{ $error .= $f['msg'].'<br />'; }
	}
	
	
	if(!$error){	
		$arr = array(
			/*'code'=>$_POST['code'],*/
		);
		
		
		foreach($cfg_type['main_fields'] as $code=>$info) if($info['chose'] && in_array($code,$fields)){
			if ($info["type"]=="checkbox"){
				$arr[$code] = isset($_POST[$code])?$_POST[$code]:0;
			}
			else if (!empty($_POST[$code]))	$arr[$code] = $_POST[$code];
			
		}
		/// No modify
		$arr['icon'] = $_POST['icon'];
		$arr['image'] = $_POST['image'];
		$arr['date'] = $_POST['date'];
		$arr['file_extra'] = serialize($file_extra);
		$arr['fields_extra'] = serialize($_POST['fields_extra']);
		$arr['related_contentid'] = intval($_POST["related_contentid"]);
		if (is_array($_POST["extra_categoryid"]) && $cfg_type["extra_category"]){
			$arr["extra_catid"] = ",".implode(",",$_POST["extra_categoryid"]).",";
		}
		$arr["access"] = $_POST["assin_userid"]!=""?",".implode(",",$_POST["assin_userid"]).",":",0,";		
		if($request['do']=='new'){
			$arr["active"] = isset($acl_user_act["approved"])?intval($acl_user_act["approved"]):1;
			$arr['catid'] = $request['parentid'];
			$arr['type'] = $request['type'];
			$arr['userid'] = intval($_SESSION['admin_login']['id']);
			$lastid = $oClass->insert($arr);
		}else{
			$oClass->update($request['id'],$arr);
			$lastid = $request['id'];
		}
		
		// update options
		if(count($_POST['options'])) foreach($_POST['options'] as $options_type=>$options_array){
			$oClass->deleteOpt($lastid,$options_type);
			foreach($options_array as $options_id){
				$opts = array(
					'content_id'=>intval($lastid),
					'options_type'=>$options_type,
					'options_id'=>$options_id,
					'page'=>'content'
				);
				$oClass->insertOpt($opts);
			}
		}
		// update _ln
		$ln_req = $cfg_type['required_fields']?explode(',',$cfg_type['required_fields']):array('name');
		
		if(!$cfg_type['required_fields'] || $_POST[$ln_req[0]]) foreach($_POST[$ln_req[0]] as $ln=>$val){
			if(!$cfg_type['required_fields'] || $val){
				$arr_ln  = array(
/*					'name'=>$_POST['name'][$ln],
					'intro'=>$_POST['intro'][$ln],
					'content'=>$_POST['content'][$ln],
					'short_intro'=>$_POST['short_intro'][$ln],
*/				);

				foreach($cfg_type['ln_fields'] as $code=>$info) if($info['chose'] && in_array($code,$ln_fields)){
					if ($code=="name"){
						 $arr_ln["name_url"] = $_POST[$code."_url"][$ln]!=""?$_POST[$code."_url"][$ln]:str2url($_POST[$code][$ln]);
					}
					$arr_ln[$code] = $_POST[$code][$ln];
				}
				
				if(in_array('ln_image',$show_actions)){ // use image
					if($cfg_type['ln_main_icon']['chose']){
						if(intval($_POST['delete_ln_icon'][$ln])>0){				
							@unlink(_UPLOAD.$_POST['current_ln_icon'][$ln]);
							$_POST['current_ln_icon'][$ln] = "";
							$ln_icon = "";
						}	
						$ln_icon = upload('ln_icon',_UPLOAD,time().'-',$image_exts,$ln);
						if ($_POST['delete_ln_icon'][$ln] || (!empty($icon) && trim($_POST['current_ln_icon'][$ln]) != "")){
							@unlink(_UPLOAD.$_POST['current_ln_icon'][$ln]);							
						}
						if(is_array($ln_icon)){
							$error .= $ln_icon['msg'].'<br />';
						}
						if($ln_icon){
							$oImg = new image(_UPLOAD.$ln_icon);
							$oImg->resize($cfg_type['ln_main_icon']['w'],$cfg_type['ln_main_icon']['h'],_UPLOAD.$ln_icon);
						}
						$_POST['ln_icon'][$ln] = empty($ln_icon)?$_POST['current_ln_icon'][$ln]:$ln_icon;
					}
					
					
					
					if($cfg_type['ln_main_img']['chose']){
						$_POST['ln_image'][$ln] = $_POST['current_ln_image'][$ln];
						if(intval($_POST['delete_ln_image'][$ln])>0){				
							@unlink(_UPLOAD.$_POST['current_ln_image'][$ln]);
							@unlink(_UPLOAD.'thumb/'.$_POST['current_ln_image'][$ln]);
							$_POST['delete_ln_image'][$ln] = "";
							$_POST['ln_image'][$ln] = "";
							$ln_image = "";
						}	
						
						$ln_image = upload('ln_image',_UPLOAD,time().'-',$image_exts,$ln);
						if ($_POST['delete_ln_image'][$ln] || (!empty($ln_image) && trim($_POST['current_ln_image'][$ln]) != "")){
							@unlink(_UPLOAD.$_POST['current_ln_image'][$ln]);
							@unlink(_UPLOAD.'thumb/'.$_POST['current_ln_image'][$ln]);	
							$_POST['ln_image'][$ln] = "";						
						}
						if(is_array($ln_image)){
							$error .= $image['msg'].'<br />';
						}
						if($ln_image){
							$_POST['ln_image'][$ln] = $ln_image;
							$oImg = new image(_UPLOAD.$ln_image);
							$oImg->resize($cfg_type['ln_main_img']['w'],$cfg_type['ln_main_img']['h'],_UPLOAD.$ln_image);
							if($cfg_type['ln_main_thumb']['chose']) 
								$oImg->resize($cfg_type['ln_main_thumb']['w'],$cfg_type['ln_main_thumb']['h'],_UPLOAD.'thumb/'.$ln_image);
						}
						
					}
				}
				
				$arr_ln['ln_icon'] = $_POST['ln_icon'][$ln];
				$arr_ln['ln_image'] = $_POST['ln_image'][$ln];
				$arr_ln['ln_fields_extra'] = serialize($_POST['ln_fields_extra'][$ln]);
				if(!$error) 	$oClass->update_ln($lastid,$ln,$arr_ln);
			}
		}
		
	
		// gallery 
		
		
		if(in_array('gallery',$show_actions)) foreach($_FILES as $key=>$a){
			if(substr($key,0,13)=='image_gallery'){
				
				if($cfg_type['gallery_icon']['chose']){
					$icon = upload(str_replace('image_','icon_',$key),_UPLOAD,time().'-');
					if($icon){
						$oImg = new image(_UPLOAD.$icon);
						$oImg->resize($cfg_type['gallery_icon']['w'],$cfg_type['gallery_icon']['h'],_UPLOAD.$icon);
					}
				}
				
				if($cfg_type['gallery_img']['chose']){
					$img = upload($key,_UPLOAD,time().'-');
					if($img){
						$oImg = new image(_UPLOAD.$img);
						$oImg->resize($cfg_type['gallery_img']['w'],$cfg_type['gallery_img']['h'],_UPLOAD.$img);
						if($cfg_type['gallery_thumb']['chose'])
							$oImg->resize($cfg_type['gallery_thumb']['w'],$cfg_type['gallery_thumb']['h'],_UPLOAD.'thumb/'.$img);
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
			$query_string = $_SERVER['QUERY_STRING'];
			parse_str($query_string,$result);
		
			if($_POST['back']){
				unset($result['act'],$result['id'],$result['do']);
			}else{
				$_SESSION['updated'] = true;
			}
			if($warning) $result['msg'] = $warning;
			$hook->redirect('?'.http_build_query($result,NULL,'&'));
		}

	}
}


$tpl->setfile(array(
	'body'=>$cfg_type['tpl_update']?$cfg_type['tpl_update']:'content.update.tpl',
));

$request['error'] = $error;
if($_SESSION['updated']){
	$request['back_list_0'] = 'selected';
	$request['msg'] = $languages['data_updated'];
	unset($_SESSION['updated']);
}
$content = array();
if($request['do']=='new'){
	$cat_ln = array();
	$breadcrumb->assign("","New");
	$request['access_action'] = 'access_new';
	$request['date'] = date('Y-m-d');
	$file_extra = array();
	foreach($cfg_type['main_fields'] as $code=>$info) if($info['chose'] && $info['type'] == 'status'){
		$content[$code] = $info['status_default'];
	}
}else{
	$result = $oClass->get($request['id']);
	$content = $result->fetch();
	if ($content['userid']!=intval($_SESSION['admin_login']['id']) && intval($_SESSION['admin_login']['is_admin'])!=1){	
		unset($_GET["act"],$_GET["do"]);
		$hook->redirect("?act=accessdenie&".http_build_query($_GET));
	}
	$request["action_update_check"] = 'checked';
	$cat_ln = $oClass->get_ln($_GET['id']);	
	$file_extra = $content['file_extra']?unserialize($content['file_extra']):array();
	$fields_extra = $content['fields_extra']?unserialize($content['fields_extra']):array();
	$tpl->assign($hook->format($content));
	$breadcrumb->assign("","Edit",$request['bread']);
	$request['display_update'] = 'show';
	$request['access_action'] = 'access_edit';
	//if(!$request['date']) $request['date'] = date('Y-m-d');
}

$required_fields = array();
$str_main_fields = '';
foreach($cfg_type['main_fields'] as $code=>$info){
	
	if($info['chose'] && $info['type'] != 'status' && in_array($code,$fields)){
		$style = "";
		if ($info['widthxheight']!=""){
			$info['widthxheight'] = str_replace("px","",$info['widthxheight']);
			$arrWH = explode("x",$info['widthxheight']);
			$style = 'style="';
			$style .= $arrWH[0]>0?"width:".$arrWH[0]."px; ":"";
			$style .= $arrWH[1]>0?" height:".$arrWH[1]."px; ":"";
			$style .= '"';	
			
		}
		$str_main_fields .= '<tr>
	  <td class="textLabel">'.$info['name'].' '.$arr['ln_alias'].'</td>
	  <td>'.html_input($info['type'],$code,$content[$code],' title="'.$info['require_msg'].'" '.$style.'',$info['options']).'
		<span class="description">'.$info['description'].'</span>
	  </td>
	</tr>';
		if($info['require']) $required_fields[] = $code;
	}
}


$request['main_fields'] = $str_main_fields;
$lang_cond = "active = 1";
$lang_cond .= $cfg_type['languages']?" AND is_default=1":"";
$lang = $oLanguage->view($lang_cond);
if(!$lang->num_rows()){
	$request['msg'] = $languages['require_least_language_enable'];
	$request['hide_no_languages'] = 'hide';
}



while($rs = $lang->fetch()){
	$arr = array_merge($rs,$cat_ln[$rs['ln']]?$cat_ln[$rs['ln']]:array());
	
	
	$ln_fields_extra = unserialize($arr['ln_fields_extra']);
	$arr = $hook->format($arr);
	$str_ln_fields = '';
	$required_ln_fields = array();
	foreach($cfg_type['ln_fields'] as $code=>$info){
		if($info['chose'] && in_array($code,$ln_fields)){
			$style = "";
			if ($info['widthxheight']!=""){
				$info['widthxheight'] = str_replace("px","",$info['widthxheight']);
				$arrWH = explode("x",$info['widthxheight']);
				$style = 'style="';
				$style .= $arrWH[0]>0?"width:".$arrWH[0]."px; ":"";
				$style .= $arrWH[1]>0?" height:".$arrWH[1]."px; ":"";
				$style .= '"';			
			}
			$str_ln_fields .= '<tr>
      <td class="textLabel">'.$info['name'].' '.$arr['ln_alias'].'</td>
      <td>'.html_input($info['type'],$code.'['.$arr['ln'].']',$arr[$code],' title="'.$info['require_msg'].'" '.$style.'').'
	  	<span class="description">'.$info['description'].'</span>
	  </td>
    </tr>';
			if($info['require']) { $required_ln_fields[] = $code; }
		
		}
	}
	$arr['ln_fields'] = $str_ln_fields;
	//$arr['ln_fields_extra'] = sprintf($ln_fields_extra,$arr['ln_alias'], $arr['ln'],'');
	$arr['default_ln'] = $cfg['language_tab'] == 'tab'?($arr['is_default']?'active':'hide'):'';
	$arr['tab_default'] = $arr['is_default']?'class="active"':'';
	$str = '';
	if($cfg_type['ln_fields_extra']['name']){
		foreach($cfg_type['ln_fields_extra']['name'] as $k=>$name) if($name){
			$str .= '<tr>
				<td class="textLabel">'.$name.' '.$arr['ln_alias'].'</td>
				<td>'.html_input($cfg_type['ln_fields_extra']['type'][$k],'ln_fields_extra['.$arr['ln'].']['.$cfg_type['ln_fields_extra']['code'][$k].']',$ln_fields_extra[$cfg_type['ln_fields_extra']['code'][$k]]).'</td>
			</tr>';
			
		}
	}
	
	$arr['ln_fields_extra'] = $str;
	
	$tpl->assign($arr,'language');
}

// load gallery
$result = $oGallery->get($system->module,$request['id']);
while($rs = $result->fetch()){
	$rs['name'] = $rs['name']?$rs['name'].'<br />':'';
	$tpl->assign($rs,'gallery');
}
$request['breadcrumb'] = $breadcrumb->parse();
$request['size_icon'] = demension_size($cfg_type['main_icon']['w'],$cfg_type['main_icon']['h']);
$request['size_icon'] = $request['size_icon']!=""?$request['size_icon']:$cfg_type['main_icon']['desc'];
$request['field_icon'] = $cfg_type['main_icon']['name']?$cfg_type['main_icon']['name']:'Icon';

$request['size_image'] = demension_size($cfg_type['main_img']['w'],$cfg_type['main_img']['h']);
$request['field_image'] = $cfg_type['main_img']['name']?$cfg_type['main_img']['name']:'Image';
$request['size_image'] = $request['size_image']!=""?$request['size_image']:$cfg_type['main_img']['desc'];

$request['size_ln_icon'] = demension_size($cfg_type['ln_main_icon']['w'],$cfg_type['ln_main_icon']['h']);
$request['size_ln_icon'] = $request['size_ln_icon']!=""?$request['size_ln_icon']:$cfg_type['ln_main_icon']['desc'];
$request['ln_field_icon'] = $cfg_type['ln_main_icon']['name']?$cfg_type['ln_main_icon']['name']:'Icon';

$request['size_ln_image'] = demension_size($cfg_type['ln_main_img']['w'],$cfg_type['ln_main_img']['h']);
$request['size_ln_image'] = $request['size_ln_image']!=""?$request['size_ln_image']:$cfg_type['ln_main_img']['desc'];
$request['ln_field_image'] = $cfg_type['ln_main_img']['name']?$cfg_type['ln_main_img']['name']:'Image';


$request['size_gallery_icon'] = demension_size($cfg_type['gallery_icon']['w'],$cfg_type['gallery_icon']['h']);
$request['size_gallery_icon'] = $request['size_gallery_icon']!=""?$request['size_gallery_icon']:$cfg_type['gallery_icon']['desc'];

$request['size_gallery_image'] = demension_size($cfg_type['gallery_img']['w'],$cfg_type['gallery_img']['h']);
$request['size_gallery_image'] = $request['size_gallery_image']!=""?$request['size_gallery_image']:$cfg_type['gallery_img']['desc'];

$request['field_gallery_name'] = $cfg_type['gallery_name']?$cfg_type['gallery_name']:'Gallery';

$request['required_fields'] = count($required_fields)?"'".implode("','",$required_fields)."'":'';
$request['required_ln_fields'] = count($required_ln_fields)?"'".implode("','",$required_ln_fields)."'":'';
if(in_array('drapdrop_gallery',$show_actions))  $tpl->box('drapdrop_gallery');
if(in_array('enable_editor',$cfg_type['act'])) $tpl->box('enable_editor');

$request['msg'] = $error;
$show = array();
if($show_fields) foreach($show_fields as $field){
	//$a = explode(':',$field);
	$show['display_'.$field] = 'show';
	$show['is_'.$field] = 1;
}
$tpl->assign($show);
$action = array();
foreach($show_actions as $act) $action['action_'.$act] = 'show';
$tpl->assign($action);


// Load options
$attributes = array();
if($request['do'] != 'new'){
	$result = $oContent->options($request['id']);
	while($rs = $result->fetch()){
		$attributes[$rs['options_type']][] = $rs['options_id'];
	}
}


if(count($cfg_type['options'])){
	$opt_type = $oConfigure->getMod("`module` = 'options' AND typeid IN(".implode(',',$cfg_type['options']).")");
	while($rs = $opt_type->fetch()){
		$result = $oOption->view($rs['typeid']);
		if($cfg_type['options_type'][$rs['typeid']] == 2) $aOpt = array(0=>'--');
		else $aOpt = array();
		while($opt = $result->fetch()){
			$aOpt[$opt['id']] = $opt['name'];
		}		
		switch($cfg_type['options_type'][$rs['typeid']]){
			case '1':
				$rs['values'] = '<div class="option_checkbox listopts'.$rs['typeid'].'" id="opt'.$rs['typeid'].'" >'.options_checkbox($aOpt,$rs['typeid'],$attributes[$rs['typeid']]).'</div>';
				$rs['check_all'] = "<input type=\"checkbox\" name=\"all\" value=\"".$rs['typeid']."\" class=\"checkallopts\" id=\"checkallopts".$rs['typeid']."\" /> <label for=\"checkallopts".$rs['typeid']."\">Check All</label>";
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
		$tpl->assign($rs,'options');
	}
}
// file extra
if($cfg_type['file_extra']['name']){
	foreach($cfg_type['file_extra']['name'] as $k=>$name) if($name){
		$rs = array();
		$rs['stt'] = $k;
		$rs['name'] = $name;
		$rs['code'] = $cfg_type['file_extra']['code'][$k];
		$rs['file'] = $file_extra[$rs['code']];
		$rs['type'] = $cfg_type['file_extra']['type'][$k];
		$rs['note'] = $cfg_type['file_extra']['note'][$k];
		$tpl->assign($rs,'file_extra');
		
	}
}

foreach($cfg_type['main_fields'] as $code=>$info) if($info['chose'] && $info['type'] == 'status'){
	$rs = array();
	$rs['name'] = $info['name'];
	$rs['code'] = $code;
	$rs['note'] = $info['description'];
	$rs['value'] = html_status($code,$content[$code]);
	$tpl->assign($rs,'status_fields');
}

// fields extra
if($cfg_type['fields_extra']['name']){
	foreach($cfg_type['fields_extra']['name'] as $k=>$name) if($name){
		$rs = array();
		$rs['stt'] = $k;
		$rs['name'] = $name;
		$rs['value'] = html_input($cfg_type['fields_extra']['type'][$k],'fields_extra['.$cfg_type['fields_extra']['code'][$k].']',$fields_extra[$cfg_type['fields_extra']['code'][$k]]);
		$tpl->assign($rs,'fields_extra');
		
	}
}
/*relatedcontent*/
if (strlen($cfg_type["relatedcontent"])>0 && $cfg_type["relatedcontent"]!="")
{	
	$request['relatedcontent_name'] = $cfg_type['relatedcontent_name']?$cfg_type['relatedcontent_name']:'Related Content';
	$opt_content = $oConfigure->getMod("module = 'content' AND typeid IN (".$cfg_type["relatedcontent"].")");
	while($val = $opt_content->fetch()){
		$arrtype[$val["typeid"]]  = $val["name"];
	}	
	$relatedcontent = $oClass->viewall($cfg_type["relatedcontent"]);
	$isgroup = false;
	while($rs = $relatedcontent->fetch()){
		$rs = $hook->format($rs);	
		$rs["groupname"] ="";
		if ($rs["type"]!=$isgroup && $rs["groupname"]==""){
			$isgroup  = $rs["type"];
			$rs["groupname"] = '<option value="0" disabled="disabled">'.$arrtype[intval($rs["type"])].'--------</option>';
		}				
		$rs["selected"] = $rs["id"]==intval($content["related_contentid"])?'selected="selected"':'';
		$tpl->assign($rs,'listcontent');
	}
	$tpl->assign(array("display_related_contentid"=>"show"));
	
}
if (strlen($cfg_type["extra_category"])>0 && $cfg_type["extra_category"]!="")
{	
	$request["extra_category_mode"] = $cfg_type["extra_category_mode"]>=1?'multiple="multiple"':'';
	$request['display_extra_category'] = "show";
	$request['ln_extra_category_name'] = $cfg_type['extra_category_name']?$cfg_type['extra_category_name']:'Extra Category';
	
	$tree = $oCategory->tree($cfg_type["extra_category"],0,'&nbsp;',1);
	$content['extra_catid'] = rtrim(ltrim($content['extra_catid'],","),",");
	$arr_extracatid = $content['extra_catid']!=""?explode(",",$content['extra_catid']):array();
	foreach($tree as $rs){
		$rs['prefix'] = $rs['prefix'].'|&mdash;';
		$rs['selected'] = in_array($rs['id'],$arr_extracatid)?'selected':'';
		$tpl->assign($rs,'extra_category_list');
	}

}	
if (strlen($cfg_type["assin_userid_name"])>0 && $cfg_type["assin_userid_name"]!=""){
	$assinUList = $cfg_type['assin_userid_access']!=""?explode(",",$cfg_type['assin_userid_access']):array();
	$uid = $_SESSION['admin_login']['id'];
	if (in_array($uid,$assinUList) || $_SESSION['admin_login']['is_admin']){	
		$content['access'] = rtrim(ltrim($content['access'],","),",");
		$arrUserList = $content['access']!=""?explode(",",$content['access']):array();	
		$request['display_assin_userid_name'] = "show";
		$request['assin_userid_name'] = $cfg_type["assin_userid_name"];	
		$objuser = $oUser->view();
		while($rs = $objuser->fetch()){	
			$rs['selected'] = in_array($rs['id'],$arrUserList)?'selected':'';
			$tpl->assign($rs,'user_access');
		}
	}
}

if($request['parentid']) $tpl->box('breadcrumb_cat');
$tpl->assign($request);

?>