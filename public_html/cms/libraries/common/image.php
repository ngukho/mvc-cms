<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}

class image extends Controller{
	var $image;
	var $img;
	var $by_min_size  = true;
	var $store_file = true;
	function image($image){
		if(file_exists($image)) $this->image = $image;
	}	
	
	
	function info(){
		if($this->image){
			return @getimagesize($this->image);
		}
		return false;
	}
	
	function check(){
		if(substr($info['mime'],0,5)=='image') return true;
		return false;
	}
	
	
	function saveImg($info,$oImg,$nFile  = NULL){
		switch($info['mime']){
			case 'image/jpeg': 
				if(!$this->store_file){
					@header("Content-type: image/jpeg");@imagejpeg($oImg);
				}elseif($nFile) 	@imagejpeg($oImg,$nFile);
			break;
			case 'image/gif':
				if(!$this->store_file){
					@header("Content-type: image/gif");@imagegif($oImg);
				}elseif($nFile) 	@imagegif($oImg,$nFile);
			break;
			case 'image/png':
				if(!$this->store_file){
					@header("Content-type: image/png");@imagepng($oImg);
				}elseif($nFile) 	@imagejpeg($oImg,$nFile);
			break;
			default: return false;
		}
	}
	
	function createImg($info,$image){
		switch($info['mime']){
			case 'image/jpeg': return  @imagecreatefromjpeg($image);
			break;
			case 'image/gif': return @imagecreatefromgif($image);
			break;
			case 'image/png': return @imagecreatefrompng($image);
			break;
			default: return false;
		}

	}
	
	function free($oImg){
		@imagedestroy($oImg);
	}
	
	// public functions
	function resize($width = 0,$height = 0,$nFile = NULL){
		$info = $this->info();
		if(($width == 0&&$height == 0) || ($info[0] == $width && $info[1] == $height)){
			if(dirname($this->image) != dirname($nFile)) copy($this->image, $nFile);
			return false;
		}
		if($width <= 0){
			$width = round($info[0]*$height/$info[1]);
		}elseif($height<=0){
			$height = round($width*$info[1]/$info[0]);
		}else{ // width > 0 && height > 0
			if($this->by_min_size){
				if($info[0] <= $info[1]){
					$height = round($width*$info[1]/$info[0]);
				}else{
					$width = round($info[0]*$height/$info[1]);
				}
			}
		}
		$type_file = substr($info['mime'],0,5);
		if($type_file == 'image'){
			$img = $this->createImg($info,$this->image);
		}	
		$oImg=@imagecreatetruecolor($width,$height);
		@imagecopyresampled ( $oImg, $img, 0,0,0,0, $width, $height, $info[0],$info[1]);
		if($type_file == 'image'){
			$this->saveImg($info,$oImg,$nFile);
		}
		$this->free($oImg);
	}
	
	
	function watermark($wmImg = NULL,$wmText = NULL,$wmX = 0,$wmY = 0){
		if(!$wmImg && !$wmText) return false;
		if($wmImg && file_exists($wmImg)){
			$info = getimagesize($wmImg);
			$watermark = $this->createImg($info,$wmImg);  
			$watermark_width = $info[0];  
			$watermark_height = $info[1]; 
		} 
		$image = imagecreatetruecolor($watermark_width, $watermark_height);  
		$info = $this->info(); 
		$image = $this->createImg($info,$this->image);  
		$dest_x = $info[0] - $watermark_width - 5;  
		$dest_y = $info[1] - $watermark_height - 5;  
		imagecopymerge($image, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height, 100);  
		$this->saveImg($info,$image,$this->image);  
		$this->free($image);  
		$this->free($watermark); 
	}
	
	
	
}

?>