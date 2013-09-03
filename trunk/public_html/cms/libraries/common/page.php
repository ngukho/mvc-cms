<?php
class paging{
	var $label = array('First','Prev','Next','Last');
	var $current = '<strong>%d</strong>';
	var $first_current = '<strong class="first active">%d</strong>';
	var $last_current = '<strong class="last active">%d</strong>';
	var $first_params = ' class="first"';
	var $last_params = ' class="last"';
	var $sep = '  ';
	var $info = array();
	
	function paging($url = NULL, $total = 0, $limit = 0){
		if(!$total || !$limit || $total <= $limit) return false;
		$this->info['pnum'] = ceil($total/$limit);
		if($this->info['pnum'] <=1) return false;
		$this->info['url'] = $url;
		if(isset($_GET['page']) && intval($_GET['page'])){
			$this->info['page'] = intval($_GET['page']);
		}
	}
	
	function build_link($page = 0,$param = NULL,$label  =  NULL){
		$url =  '<a href="'.$this->info['url'];
		if($page) $url .= strpos($this->info['url'],'?') === false?'?page='.$page:'&page='.$page;
		$url .= '" '.($param?str_replace('%d',$page,$param):'').'>'.($label?$label:$page+1).'</a>';
		return $url;
	}	

	function first($label=NULL,$param=NULL){
		if($this->info['page']) return $this->build_link(0,$this->first_params?$this->first_params:$param,$this->label[0]).$this->sep;
		return NULL;
	}
	
	function prev($label=NULL,$param=NULL){
		if($this->info['page']) return $this->build_link($this->info['page']-1,$param,$this->label[1]).$this->sep;
		return NULL;
	}
	
	function next($label = NULL, $param = NULL){
		if($this->info['pnum'] > 1 && $this->info['page'] < $this->info['pnum'] - 1) return $this->sep.$this->build_link($this->info['page']+1,$param,$this->label[2]);	
		return NULL;
	}
	
	function last($label = NULL, $param = NULL){
		if($this->info['pnum'] > 1 && $this->info['page'] < $this->info['pnum'] - 1) return $this->sep.$this->build_link($this->info['pnum']-1,$this->last_params?$this->last_params:$param,$this->label[3]);
		return NULL;	
	}
	function parse($div = 0,$param = NULL){
		$begin = 0;
		$end = $this->info['pnum'];
		if($div && $div < $end){
			$begin =  max(0, $this->info['page'] - floor($div/2));
			if($begin==0) $end = $div;
			else $end = min($this->info['page'] + ceil($div/2),$this->info['pnum']);
		}
		$aP = array();
		for($i=$begin;$i<$end;$i++){
			if($i==$this->info['page']){
				$pr = $this->current;
				if($i==$begin) $pr = $this->first_current;
				if($i==$end-1) $pr = $this->last_current;
				$aP[] =  str_replace('%d',$i+1,$pr);
			}else{
				$pr = $param;
				//if($i==$begin) $pr = $this->first_params;
				//if($i==$end-1) $pr = $this->last_params;
				$aP[] = $this->build_link($i,$pr);
			}
		}
		$p = implode($this->sep,$aP);
		return $p;
	}
	
	//// paging formats
	function simple($div = 0,$param = NULL){
		$p =  $this->first(NULL,$param);
		$p .= $this->prev(NULL,$param);
		$p .= $this->parse($div,$param);
		$p .= $this->next(NULL,$param);
		$p .= $this->last(NULL,$param);
		return $p;
	}
}
?>