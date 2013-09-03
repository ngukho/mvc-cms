<div class="top_callFunc">

	<span class="hide {action_modify}"><a href="?mod={module}&amp;act=update&amp;type={type}&amp;do=new" title="New" class="btn l">New</a></span>
</div>

<div class="form">

<form id="form2" name="form2" method="post" action="" onSubmit="return checkSubmitAction(this)">
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="table_list list marginTop5" id="table-list">
  <tr>
    <th width="3%" class="th-checkbox firstColumn"><input type="checkbox" name="checkall" {display_checkall} value="1" onClick="checkAll('#table-list',this,'pro')" />    </th>
    <th width="60%" class="th-name">{lang.name}</th>
    <th width="20%" class="th-name">Module Folder</th>
    <th width="5%" class="th-status">{lang.status}</th>
    <th width="11%" class="th-status lastColumn">{lang.actions}</th>
    <!--<th class="th-action">Edit</th>-->
  </tr>
  <tbody class="pro">
  <!--BASIC product-->
  <tr id="{product.id}pro">
    <td class="th-checkbox firstColumn"><input type="checkbox" name="pro[]" value="{product.id}" /></td>
    <td class="th-name">
		{product.name}	</td>
  <td class="th-name">
		{product.module}	</td>        
    <td align="center" class="th-status lastColumn"><a href="?mod={module}&amp;act=active&amp;id={product.id}&amp;parentid={parentid}&amp;type={type}" title="{lang.update_status}"><img src="images/icons_default/status{product.active}.gif" /></a></td>    
	
    <td class="th-status lastColumn"><a href="?mod={module}&amp;act=update&amp;id={product.id}&amp;parentid={parentid}&amp;type={type}" title="{lang.edit}"><img src="images/icons_default/edit{ucp.edit}.png"  width="16" height="16" border="0" /></a>
			<a class="hide  {action_modify}" href="?mod={module}&amp;act=delete&amp;id={product.id}&amp;parentid={parentid}&amp;type={type}" onClick="deleteConfirm(this); return false;" title="{lang.delete}"><img src="images/icons_default/delete{ucp.delete}.png" width="16" height="16" /></a></td>
  </tr>
  <!--BASIC product-->
  </tbody>
</table>

	<div class="bottom_callAction">			
		
	</div>

</form>
</div>
