<div class="table_list toolbar">
	<a href="?mod=tenant" class="btn">All</a>&nbsp;&nbsp;<a href="?mod=tenant&enabled=0" class="btn">Pending</a>&nbsp;&nbsp;<a href="?mod=tenant&enabled=1" class="btn">Solved</a>
    <br  />
</div>

<div class="error">{msg}</div>
<form id="form2" name="form2" method="post" action="" onsubmit="return checkSubmitAction(this)">
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="table_list list marginTop5" id="table-list">
  <tr>
  	<th class="th-checkbox firstColumn">      
        <input type="checkbox" class="no_width" style="{display_checkall}" name="checkall" value="1" onclick="checkAll('#table-list',this,'pro')" />
    </th>   
    <th class="th-name">Name</th>
    <th class="th-name">Phone</th>
    <th class="th-name">Suite </th>
    <th class="th-name">Building </th>  
    <th class="th-name">Issue</th>    
    <th class="th-name">Date Submit</th>
    <th class="th-status">Status</th>
    <th align="center"  class="th-action lastColumn">Action</th>
  </tr>
  <!--BASIC user-->
  <tr id="rows{user.id}">
  	<td class="th-checkbox firstColumn"><input type="checkbox" name="pro[]" value="{user.id}" class="no_width" /></td>   
    <td class="th-name"><a href="?mod=member&act=detail&id={user.userid}" title="">{user.name}</a></td>
    <td class="th-name">{user.phone}</td>
    <td class="th-name">{user.suite}</td>
    <td class="th-name">{user.building}</td>    
    <td class="th-name">{user.issue}</td>    
    <td class="th-name">{user.timestamp}</td>
    <td class="th-status"><a class="status{user.active}" href="{url_link}&act=active&id={user.id}#rows{user.id}" title="Update status">{user.status}&nbsp;&nbsp;</a></td>    
    <td class="th-status lastColumn">
      <a href="{url_link}&act=detail&id={user.id}" title="Detail"><img src="images/icons_default/view.gif" width="16" height="16" /></a>
   	 &nbsp;&nbsp;<a href="{url_link}&act=delete&id={user.id}" title="Delete" onClick="deleteConfirm(this); return false;"><img src="images/icons_default/delete{ucp.delete}.png" width="16" height="16" /></a>     
    </td>
  </tr>
  <!--BASIC user-->
</table>
	<br />
	<div class="pagination paging-bottom">
        {divpage}
    </div>
    <div class="bottom_callAction">			
	<strong>Select action:</strong><br />
    <input name="act_delete" type="submit" class="btn" id="act_delete" value="Delete" />
    <input name="act_active" type="submit" class="btn" id="act_active" value="Solved" />
	<input name="act_inactive" type="submit" class="btn" id="act_inactive" value="Peding" />
    </div>
</form>
<p>&nbsp;</p>
  <script type="text/javascript">
	$('a.divbox').divbox({ caption: false});
	function setActive(id){
		$.post('?mod=member&act=active&id='+id,{},function(){});
	}	
  </script>
