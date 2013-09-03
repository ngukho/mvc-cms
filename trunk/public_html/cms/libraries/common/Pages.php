<?php
class Pages{
	var $Next = "Next";
    var $Prev = "Previous";
    var $First = "";//Label First
    var $Last = "";//Label Last
    var $maxpage = 10;
    var $params = "";
	var $current = "<strong>%page%</strong>";
	var $ClassItem="num";
	var $NextClass="next";
	var $PrevClass="prev";
	var $SeparatorLast = "<span>...</span>";
	function multipages($num, $perpage, $curpage, $mpurl) 
	{ 		
		$page = $this->maxpage;
		$multipage = "";        
		$realpages = 1;
		$from =0;
		$to= 1;        		
		if($num > $perpage) {
			$offset = 2;
			$realpages = @ceil($num / $perpage); //+ (($num % $perpage > 0) ? 1 : 0);
			$pages = $realpages;//maxpage<realpages ? maxpage : realpages;            			
			if($page > $pages) {
				$from = 1;
				$to = $pages;
			} else {
				$from = $curpage - $offset;
				$to = $from + $page - 1;
				if($from < 1) {
					$to = $curpage + 1 - $from;
					$from = 1;
					if($to - $from < $page) {
						$to = $page;
					}
				} else if($to > $pages) {
					$from = $pages - $page + 1;
					$to = $pages;
				}
			}
			
			$multipage = ($curpage - $offset > 1 && $pages > $page && $pages > $this->maxpage  && $this->First ?  "<a ".$this->replace_page($this->params, 1). " target=\"_self\" href=\"" . $this->replace_page($mpurl, 1) . "\" class=\"first\">" . $this->First . "</a>&nbsp;" : "") .
				(($curpage > 1 && $pages > $this->maxpage) ? "<a " . $this->replace_page($this->params, $curpage - 1) . " target=\"_self\"  href=\"" . $this->replace_page($mpurl, $curpage - 1) . "\" class=\"".$this->PrevClass."\">" . $this->Prev . "</a>&nbsp;" : "");
				
			$multipage .= ($curpage - $offset > 1 && $pages > $page && $pages > $this->maxpage ? '<a class="'.$this->ClassItem.'" '.$this->replace_page($this->params, 1).' href="'.$this->replace_page($mpurl, 1).'">1</a>'.$this->SeparatorLast: '');				
		   for($i = $from; $i <= $to; $i++){ 
				$multipage .= ($i == $curpage) ? $this->replace_page($this->current, $i)."&nbsp;" : "<a class=\"".$this->ClassItem."\" " . $this->replace_page($this->params, $i) . " target=\"_self\" href=\"" . $this->replace_page($mpurl, $i) . "\">" . $i . "</a>&nbsp;";
			}
			
			//$multipage .= ($curpage < $pages && $pages > $this->maxpage ? $this->SeparatorLast.'<a class="'.$this->ClassItem.'"  '.$this->replace_page($this->params, $pages).' href="'.$this->replace_page($mpurl, $pages).'" target="_self">'.$realpages.'</a>' : '');
										
			$multipage .= ($curpage < $pages && $pages > $this->maxpage ? "<a " . $this->replace_page($this->params, $curpage + 1) . " target=\"_self\" href=\"" . $this->replace_page($mpurl, $curpage + 1) . "\" class=\"".$this->NextClass."\">" . $this->Next . "</a>&nbsp;" : "") .
				(($to < $pages && $pages > $this->maxpage && $this->Last) ? "<a " . $this->replace_page($this->params, $pages) . " target=\"_self\" href=\"" . $this->replace_page($mpurl, $pages) . "\" class=\"last\">" . $this->Last . "</a>&nbsp;" : "");							         
		}
		$this->maxpage = $realpages;
		return $multipage;
	}
	function replace_page($url,$page)
    {
        return str_replace("%page%",$page,$url);
    }	
}
?>