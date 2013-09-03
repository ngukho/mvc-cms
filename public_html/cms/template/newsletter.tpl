<div class="top_callFunc_member">
<div class="table_list toolbar fleft">
	<a href="?mod=newsletter&act=export" class="btn l">Export CSV</a>
    <br  />
</div>
<div class="searchForm table-Form1 fleft">
<form id="form1" name="form1" method="post" action="">
    <input name="q" type="text" class="txf-normal" id="q" value="{search_text}"/>
    <input name="btnSearch" type="submit" class="btn" id="cmd" value="{lang.search}" />   
</form>
</div>
</div>

<div class="error">{msg}</div>
<form id="form2" name="form2" method="post" action="" onsubmit="return checkSubmitAction(this)">
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="table_list list marginTop5" id="table-list">
  <tr>
    <th class="th-checkbox firstColumn">
        <input type="checkbox" class="no_width" style="{display_checkall}" name="checkall" value="1" onclick="checkAll('#table-list',this,'pro')" />
    </th>
    <th  class="th-name">Name </th>
    <th class="th-name">Email </th>
    <th class="th-name">Date </th>
    <th class="th-name">IP Address</th>
    <th class="th-status">Action</th>
  </tr>
  <!--BASIC user-->
  <tr id="rows{user.id}">
    <td class="th-checkbox firstColumn"><input type="checkbox" name="pro[]" value="{user.id}" class="no_width" /></td>
    <td class="th-name">{user.name} {user.surname}</td>
    <td class="th-name">{user.email}</td>
    <td class="th-name">{user.timestamp}</td>   
    <td class="th-name">{user.ip}</td>   
    <td class="th-status"><a href="?mod={module}&amp;act=active&amp;id={user.id}#rows{user.id}" title="Update status"><img src="images/icons_default/status{user.active}.gif" /></a></td>
  </tr>
  <!--BASIC user-->
</table>
    <div class="pagination paging-bottom">
        {divpage}
    </div>
    <div class="bottom_callAction">			
			<strong>Select action:</strong><br />
    <input name="act_delete" type="submit" class="btn" id="act_delete" value="Delete" />
    <input name="act_active" type="submit" class="btn" id="act_active" value="Activate" />
	<input name="act_inactive" type="submit" class="btn" id="act_inactive" value="Inactivate" />
    </div>
</form>
<p>&nbsp;</p>
<div align="left">{divpage}
</div>
  <script type="text/javascript">
	$('a.divbox').divbox({ caption: false});
	function setActive(id){
		$.post('?mod=newsletter&act=active&id='+id,{},function(){});
	}
  </script>
