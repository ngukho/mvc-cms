<div class="top_callFunc">
	<a href="?mod=user&act=update&do=new" class="btn l">{lang.new}</a>
</div>

<div class="error hide">{msg}</div>
<div class="form">

<table width="100%" border="0" cellspacing="0" cellpadding="2" class="table_list marginTop5">
  <tr>
    <th width="1%" class="th-name firstColumn">&nbsp;</th>
    <th width="47%" class="th-name">{lang.name}</th>
    <th width="38%" class="th-name">{lang.username}</th>
    <th width="14%" class="th-action lastColumn">{lang.actions}</th>
  </tr>
  <!--BASIC user-->
  <tr>
    <td class="th-name firstColumn">&nbsp;</td>
    <td class="th-name">{user.fullname}</td>
    <td class="th-name">{user.username}</td>
    <td class="th-action lastColumn">
    <span class="hide" {user.delete}>
        <a href="?mod=user&act=reset&id={user.id}" title="{lang.reset_password}">
        <img src="images/icons_default/pswd.png" width="16" height="16" /></a>
    </span> 
     <span><a href="?mod=user&act=update&id={user.id}" title="{lang.edit}">
    	<img src="images/icons_default/edit.png" width="16" height="16" /></a>
     </span>
     <span class="hide" {user.delete}>
         <a href="?mod=user&act=delete&id={user.id}" title="{lang.delete}" onClick="deleteConfirm(this); return false;">
         <img src="images/icons_default/delete.png" width="16" height="16" /></a>
     </span>
     </td>
  </tr>
  <!--BASIC user-->
</table>
</div>