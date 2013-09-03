<div class="top_callFunc_member" style="width: 100%;">
<div class="table_list toolbar fleft">
<!--	<a href="?mod=contact&act=export{contact_url}" class="btn l" style="margin-right:5px;">Export Excel</a>-->
    <br  />
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
  	<th class="th-checkbox firstColumn" align="center" width="4%" valign="middle">      
        <input type="checkbox" class="no_width" style="{display_checkall}" name="checkall" value="1" onclick="checkAll('#table-list',this,'pro')" />
    </th>   
    <th class="th-name">Name</th>  
    <th class="th-name">Email</th>   
    <th class="th-name">Cell Phone</th> 
    <th class="th-name">Customers</th>   
    <th class="th-name">Datetime</th>
    <th class="th-status">Status</th>
    <th align="center"  class="th-action lastColumn">Action</th>
  </tr>
  <!--BASIC contact-->
  <tr id="rows{contact.id}">
  	<td class="th-checkbox firstColumn"><input type="checkbox" name="pro[]" value="{contact.id}" class="no_width" /></td>  
    
    <td class="th-name"><a href="{url_link}&act=detail&id={contact.id}&sk=listteam" class="mbajax">{contact.fullname}</a></td>
    <td class="th-name">{contact.email}</td>
    <td class="th-name">{contact.cellphone}</td>    
    <td class="th-name">{contact.customers}</td>    
    <td class="th-name">{contact.datetime}</td>    
     <td class="th-status"><img src="images/icons_default/status{contact.active}.gif" /></td>    
     <td class="th-status lastColumn">
     <a href="{url_link}&act=detail&id={contact.id}" title="Detail" class="mbajax"><img src="images/icons_default/view.gif" width="16" height="16" /></a>&nbsp;&nbsp;     
   	 <a href="{url_link}&act=delete&id={contact.id}" title="Delete" onClick="deleteConfirm(this); return false;"><img src="images/icons_default/delete{ucp.delete}.png" width="16" height="16" /></a>
    </td>
  </tr>
  <!--BASIC contact-->
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
  <script type="text/javascript">
	$('a.divbox').divbox({ caption: false});
	function setActive(id){
		$.post('?mod=member&act=active&id='+id,{},function(){});
	}	
  </script>
