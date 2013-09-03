<div class="table_list toolbar"><a href="{BACK}"> &lt; {lang.back}</a></div>
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="table_list table-Form1">
  <tr>
    <th width="1%" class="th-checkbox firstColumn">&nbsp;</th>
	<th width="62%" class="th-name">{lang.name}</th>
    <th width="11%" class="th-name">{lang.author}</th>
    <th width="15%" class="th-date">{lang.date}</th>
	<th width="5%" class="th-name">{lang.status}</th>
    <th width="6%" class="th-name lastColumn">{lang.actions}</th>
  </tr>
  <!--BASIC comment-->
  <tr>
  	<td>&nbsp;</td>
    <td>{comment.content}</td>
    <td>{comment.mem}</td>
    <td>{comment.timestamp}</td>
	<td align="center"><a href="{LINK}&act=active&id={comment.id}"><img src="images/status{comment.active}.gif" width="10" height="10" border="0"></a></td>
    <td align="center"><span class="hide {action_delete}"><a href="{LINK}&act=delete&id={comment.id}" onClick="deleteConfirm(this); return false;"><img src="images/icons_default/delete.png" width="16" height="16" /></a></span></td>
  </tr>
  <!--BASIC comment-->
</table>
