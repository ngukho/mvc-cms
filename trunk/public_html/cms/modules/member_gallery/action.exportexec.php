<?php
/**
 * @Name: CaoBox v1.0
 * @author LinhNMT <w2ajax@gmail.com>
 * @link http://code.google.com/p/caobox/
 * @copyright Copyright &copy; 2009 phpbasic
 */
 
defined('_ROOT') or die(__FILE__);
	$tpl->reset();
	$tpl->setfile(array(
		'body'=>'member.exportexcel.tpl',
	));
	$cond = "m.city='Hồ Chí Minh'";
	$list[] = array("fullname"=>"Hồ Chí Minh");
	$result = $oClass->db->query("select m.* from maybeaio__member_gallery m where m.active=1 and m.blocked=0 and $cond order by numvote desc limit 10");
	while($rs = $result->fetch()){	
		$rs = $hook->format($rs);
		//$dir = _UPLOAD."/gallery-top200-final/";		
		//$rs["name_pic"] = str2url($rs["fullname"])."-".$rs["id"].substr($rs["contest_pic"],-4);
		$rs["facebook_profiles"] = strlen($rs["username"])>3?"http://www.facebook.com/profile.php?id=".$rs["username"]:"";
		///copy(_UPLOAD."".$rs["contest_pic"],$dir.$rs["name_pic"]);
		//$rs["contest_pic"] = $rs["name_pic"];
		//$tpl->assign($rs,'user');
		$list[] = $rs;
	}
	$list[] = array("fullname"=>"Hà Nội");
	$cond = "m.city='Hà Nội'";
	$result = $oClass->db->query("select m.* from maybeaio__member_gallery m where m.active=1 and m.blocked=0 and $cond order by numvote desc limit 10");
	while($rs = $result->fetch()){	
		$rs = $hook->format($rs);
		//$dir = _UPLOAD."/gallery-top200-final/";		
		//$rs["name_pic"] = str2url($rs["fullname"])."-".$rs["id"].substr($rs["contest_pic"],-4);
		$rs["facebook_profiles"] = strlen($rs["username"])>3?"http://www.facebook.com/profile.php?id=".$rs["username"]:"";
		///copy(_UPLOAD."".$rs["contest_pic"],$dir.$rs["name_pic"]);
		//$rs["contest_pic"] = $rs["name_pic"];
		//$tpl->assign($rs,'user');
		$list[] = $rs;
	}
	$list[] = array("fullname"=>"Đà Nẵng");
	$cond = "m.city='Đà Nẵng'";
	$result = $oClass->db->query("select m.* from maybeaio__member_gallery m where m.active=1 and m.blocked=0 and $cond order by numvote desc limit 10");
	while($rs = $result->fetch()){
		$rs = $hook->format($rs);	
		//$dir = _UPLOAD."/gallery-top200-final/";		
		//$rs["name_pic"] = str2url($rs["fullname"])."-".$rs["id"].substr($rs["contest_pic"],-4);
		$rs["facebook_profiles"] = strlen($rs["username"])>3?"http://www.facebook.com/profile.php?id=".$rs["username"]:"";
		///copy(_UPLOAD."".$rs["contest_pic"],$dir.$rs["name_pic"]);
		//$rs["contest_pic"] = $rs["name_pic"];
		//$tpl->assign($rs,'user');
		$list[] = $rs;
	}
	$list[] = array("fullname"=>"Cần Thơ");
	$cond = "m.city='Cần Thơ'";
	$result = $oClass->db->query("select m.* from maybeaio__member_gallery m where m.active=1 and m.blocked=0 and $cond order by numvote desc limit 10");
	while($rs = $result->fetch()){	
		$rs = $hook->format($rs);
		//$dir = _UPLOAD."/gallery-top200-final/";		
		//$rs["name_pic"] = str2url($rs["fullname"])."-".$rs["id"].substr($rs["contest_pic"],-4);
		$rs["facebook_profiles"] = strlen($rs["username"])>3?"http://www.facebook.com/profile.php?id=".$rs["username"]:"";
		///copy(_UPLOAD."".$rs["contest_pic"],$dir.$rs["name_pic"]);
		//$rs["contest_pic"] = $rs["name_pic"];
		//$tpl->assign($rs,'user');
		$list[] = $rs;
	}
	foreach($list as $val){
		$tpl->assign($val,'user');
	}
	header('Content-type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename=gallery_all_'.date('Ymd').'.xls');
	//die("DONE");

?>