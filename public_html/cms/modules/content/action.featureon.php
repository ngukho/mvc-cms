<?php

 
defined('_ROOT') or die(__FILE__);
$request['catid'] = intval($request['catid']);
$result = $oClass->get($request['id']);
$pro = $result->fetch();
//$request['catid'] = intval($pro['catid']);

if($request['do']){
	if($request['do']=='new'){
		$featuredon = $request['pid'];
		if($pro['featuredon']){
			$tmp = explode(',',$pro['featuredon']);
			$tmp[] = $request['pid'];
			$featuredon = implode(',',$tmp);
			
		}
	}
	if($request['do']=='del'){
		$featuredon = NULL;
		if($pro['featuredon']){
			$tmp = explode(',',$pro['featuredon']);
			$a = array_flip($tmp);
			unset($a[$request['pid']]);
			$a = array_flip($a);
			$featuredon = implode(',',$a);
			
		}
	}
	$oClass->update($request['id'],array('featuredon'=>$featuredon));
	clear_sql_cache();
	$result = $_GET;
	unset($result['do'],$result['pid']);
	$hook->redirect('?'.http_build_query($result,NULL,'&'));
}
	
$tpl->setfile(array(
	'body'=>'content.featureon.tpl',
));
$breadcrumb->assign("","Featured on");

$tree = $oCategory->tree($request['type'],0,'&nbsp;',1);
$catids = array(0);
foreach($tree as $rs){
	$rs['prefix'] = $rs['prefix'].'|&mdash;';
	$rs['selected'] = $rs['id']==$request['catid']?'selected':'';
	$catids[] = $rs['id'];
	$tpl->assign($rs,'category');
}

/*if($request['catid']){
	$catids = array($request['catid']);
	$tree = $oCategory->tree($request['type'],$current_catid,'&nbsp;');
	foreach($tree as $rs) $catids[] = $rs['id'];
	
	
}
*/
$limit = 30;
$start = $limit * intval($request['page']);

$featuredon = $request['id'];
if($pro['featuredon']){
	$tmp = explode(',',$pro['featuredon']);
	$tmp[] = $request['id'];
	$featuredon = implode(',',$tmp);
}
$cond = " c.catid = ".$request['catid']." AND c.id NOT IN(".$featuredon.")";
$result = $oClass->view($request['type'],$request['catid'],NULL,$cond);
$total = $result->num_rows();
$result = $oClass->view($request['type'],$request['catid'],NULL,$cond,$start,$limit);
while($rs = $result->fetch()){
	$rs = $hook->format($rs);
	$tpl->assign($rs,'product');
}

$dP = new paging($_SERVER['QUERY_STRING'],$total,$limit);
$request['divpage'] = $dP->simple(5);
$featuredon = 0;
if($pro['featuredon']){
	$tmp = explode(',',$pro['featuredon']);
	$featuredon = implode(',',$tmp);
}
$result = $oClass->view($request['type'],-1,NULL," c.id IN(".$featuredon.")");
while($rs = $result->fetch()){
	$rs = $hook->format($rs);
	$tpl->assign($rs,'current');
}


$request['breadcrumb'] = $breadcrumb->parse();


$tpl->assign($request);
?>