<div class="breadcrumb_cat hide">{xpath}</div>
<div class="top_callFunc"><a class="btn r" href="?mod={module}&parentid={catid}&type={type}">&lt; {lang.back}</a></div>
<div class="table-Form1">
<h2>{lang.featured_on}</h2>
  <table width="100%" border="0" cellspacing="1" cellpadding="1">
  <!--BASIC current-->
    <tr>
      <td width="2%"><a href="?mod=content&amp;act=featureon&amp;id={id}&amp;parentid={parentid}&amp;type={type}&amp;do=del&amp;pid={current.id}&catid={catid}"><img src="images/icons_default/delete.png" alt="" width="16" height="16" /></a></td>
      <td>{current.name}</td>
    </tr>
<!--BASIC current-->
  </table>
  
  <p>&nbsp;</p>
<form name="form1" method="post" action="{form_action}">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="10%">Category</td>
        <td width="90%"><select name="catid" id="catid" onchange="changeCat(this.value)">
		 <option value="0">Root</option>
          <!--BASIC category-->
		  <option value="{category.id}" {category.selected}>{category.prefix}{category.name}</option>
		  <!--BASIC category-->
        </select></td>
      </tr>
    </table>
</form>
<p>&nbsp;</p>
  <table width="100%" border="0" cellspacing="1" cellpadding="1">
  <!--BASIC product-->
    <tr>
      <td width="2%" align="center"><a href="?mod=content&amp;act=featureon&amp;id={id}&amp;parentid={parentid}&amp;type={type}&amp;do=new&amp;pid={product.id}&catid={catid}"><img src="images/icons_default/plus.jpg" alt="" width="16" height="16" /></a></td>
      <td>{product.name}</td>
    </tr>
<!--BASIC product-->
  </table>
  <div>{divpage}</div>
</div>
<script type="text/javascript">
function changeCat(id){
	location.href = '?mod={module}&act={action}&id={id}&parentid={parentid}&catid='+id+'&type={type}';
}
</script>
    