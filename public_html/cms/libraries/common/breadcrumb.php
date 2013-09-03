<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

class breadcrumb extends Controller{
	var $limit = 0;
	function breadcrumb(){
		if(!$_SESSION['brc']) $_SESSION['brc'] = array();
	}	
	
	
	function reset(){
		$_SESSION['brc'] = array();
	}
	// array(url, title)
	function assign($url,$title,$remove_last_item = false){
		if($title){
			if($remove_last_item){
				$key = end(array_keys($_SESSION['brc']));
				unset($_SESSION['brc'][$key]);
				if(!$this->limit) $this->limit = count($_SESSION['brc']);
			}
			$_SESSION['brc'][$url.'-'.$title] =  array('url'=>$url,'title'=>$title);
		}
	}
	
	
	function parse(){
		$content = '';
		$n = count($_SESSION['brc']);
		$k = 0;
		foreach($_SESSION['brc'] as $key=>$arr){
			if(!$k && !$arr['url']) $content .= '<span>'.$arr['title']["name"].'</span>';
			elseif($k==$n-1) $content .=  '<strong>'.$arr['title'].'</strong>';
			else $content .= '<span><a href="'.$arr['url'].'">'.$arr['title'].'</a></span>';
			if($this->limit && $k>$this->limit) unset($_SESSION['brc'][$key]);
			$k++;
		}
		return $content;
	}
}

?>