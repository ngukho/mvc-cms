<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}
$error = NULL;
if($_POST){
	if(in_array('gallery',$show_actions)){ // use image
		if($cfg_type['gallery_icon']['chose']){
			$icon = upload('icon',_UPLOAD,time().'-',$image_exts);
			if ($_POST['delete_icon'] || (!empty($icon) && trim($_POST['current_icon']) != "")){
				@unlink(_UPLOAD.$_POST['current_icon']);
				if($_POST['delete_icon']) $_POST['current_icon'] = "";
			}
			if(!is_array($icon)){
				
				if (!empty($icon) && trim($_POST['current_icon']) != "" && $icon != $_POST['current_icon']){
					@unlink(_UPLOAD.$_POST['current_icon']);
				}
			
			
				$oImg = new image(_UPLOAD.$icon);
				$oImg->resize($cfg_type['gallery_icon']['w'],$cfg_type['gallery_icon']['h'],_UPLOAD.$icon);
				
				$_POST['icon'] = empty($icon)?$_POST['current_icon']:$icon;
			}else{
				$error .= $icon['msg'].'<br />';
			}
			
		}
		
		
		
		if($cfg_type['gallery_img']['chose']){
			$image = upload('image',_UPLOAD,time().'-',$image_exts);
			if(!is_array($image)){
				if (!empty($image) && trim($_POST['current_image']) != "" && $image != $_POST['current_image']){
					@unlink(_UPLOAD.$_POST['current_image']);
					@unlink(_UPLOAD.'thumb/'.$_POST['current_image']);
				}
			
				$oImg = new image(_UPLOAD.$image);
				$oImg->resize($cfg_type['gallery_img']['w'],$cfg_type['gallery_img']['h'],_UPLOAD.$image);
				if($cfg_type['gallery_thumb']['chose']) 
					$oImg->resize($cfg_type['gallery_thumb']['w'],$cfg_type['gallery_thumb']['h'],_UPLOAD.'thumb/'.$image);
				
				$_POST['image'] = empty($image)?$_POST['current_image']:$image;
			}else{
				$error .= $image['msg'].'<br />';
			}
			
		}
	}
	
	if(!$error){
		$arr = array(
			'name'=>$_POST['name'],
			'content'=>$_POST['content'],
			'icon'=>$_POST['icon'],
			'image'=>$_POST['image'],
		);
		
		if($do == 'new'){
			$lastid = $oGallery->insert($request['p'],$request['pid'],$arr);
		}else{
			$oGallery->update($request['id'],$arr);
			$lastid = $request['id'];
		}
	
		clear_sql_cache();
		// refresh
		$query_string = $_SERVER['QUERY_STRING'];
		parse_str($query_string,$result);
		
		if($request['pid']){
			if($request['p'] != 'html') unset($result['act']);
			unset($result['mod'],$result['id'],$result['c'],$result['p'],$result['pid'],$result['do']);
			$hook->redirect('?mod='.$request['p'].'&id='.$request['pid'].'&'.http_build_query($result,NULL,'&'));
		}else{
			unset($result['act'],$result['do']);
			$hook->redirect('?'.http_build_query($result,NULL,'&'));
			
		}
	}
}


$tpl->setfile(array(
	'body'=>'gallery.update.tpl',
));


if($do=='new'){
	$breadcrumb->assign("","New");
	$cat_ln = array();
}else{
	$cat = $oClass->get($_GET['id']);
	$tpl->assign($cat->fetch());
	$request['display_update'] = 'style="display: block;"';
	if($request['mod'] != 'gallery' && $request['p'])	$breadcrumb->assign("?mod=$p&act=update&id=$pid&parentid=$parentid&type=$type&bread=1","Gallery",true);
	$breadcrumb->assign("","Edit");
}



//$breadcrumb->reset();

//$breadcrumb->assign("./?mod=product","Manage Products");

$request['breadcrumb'] = $breadcrumb->parse();
$request['size_icon'] = demension_size($cfg_type['main_icon']['w'],$cfg_type['main_icon']['h']);
$request['size_icon'] = $request['size_icon']!=""?$request['size_icon']:$cfg_type['main_icon']['desc'];
$request['size_image'] = demension_size($cfg_type['main_img']['w'],$cfg_type['main_img']['h']);
$request['size_image'] = $request['size_image']!=""?$request['size_image']:$cfg_type['main_img']['desc'];
$request['msg'] = $error;
$tpl->assign($request);

//$show_fields = array('title','content','icon','image'); 

$show = array();
if($show_fields) foreach($show_fields as $field) $show['display_'.$field] = 'show';
$tpl->assign($show);

?>