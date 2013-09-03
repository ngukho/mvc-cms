<div class="top_callFunc_member">
<div class="table_list toolbar fleft">
	<div class="l" style="padding:10px;">
    <strong>Chủ Đề&nbsp;&nbsp;</strong>
    <select onchange="loadcontent(this);" style="width:200px">
    	<option value="0">----</option>
        <!--BASIC listvideoweek-->
        <option value="{listvideoweek.id}" {listvideoweek.active}>{listvideoweek.name} {listvideoweek.intro}</option>
        <!--BASIC listvideoweek-->
    </select>
    </div>
	<a href="?mod=member&act=export_member_share&weekid={weekid}" class="btn l" style="margin-right:5px;">Export Excel</a>    
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
    <th class="th-name">Họ Tên </th>   
    <th class="th-name">Email </th>
    <th class="th-name">Tên Đăng Nhập </th>
    <th class="th-name">Di động</th>    
    <th class="th-name">Chia sẻ</th>    
  </tr>
  <!--BASIC user-->
  <tr id="rows{user.id}">
  	<td class="th-checkbox firstColumn"><input type="checkbox" name="pro[]" value="{user.id}" class="no_width" /></td>  
    
    <td class="th-name"><a href="?mod=member&act=logs&byid={user.id}&username={user.username}">{user.fullname} {user.surname}</a></td>
    <td class="th-name">{user.email}</td>
    <td class="th-name">{user.username}</td>
    <td class="th-name">{user.tel}</td>    
    <td class="th-name"><a href="?mod=member&act=logs&byid={user.id}&username={user.username}&weekid={weekid}">{user.numshare}</a></td>    
    
    <!--<td class="th-status"><a href="{url_link}&act=active&id={user.id}{url_current}#rows{user.id}" title="Update status"><img src="images/icons_default/status{user.active}.gif" /></a></td>    
     <td class="th-status lastColumn">
     <a href="{url_link}&act=detail&id={user.id}" title="Detail" class="mbajax"><img src="images/icons_default/view.gif" width="16" height="16" /></a>&nbsp;&nbsp;
      <a href="{url_link}&act=update&id={user.id}" title="Detail"><img src="images/icons_default/edit.png" width="16" height="16" /></a>&nbsp;&nbsp;
   	 <a href="{url_link}&act=delete&id={user.id}" title="Delete" onClick="deleteConfirm(this); return false;"><img src="images/icons_default/delete{ucp.delete}.png" width="16" height="16" /></a>
    </td>-->
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
  <script type="text/javascript">
	$('a.divbox').divbox({ caption: false});
	function setActive(id){
		$.post('?mod=member&act=active&id='+id,{},function(){});
	}	
	function loadcontent(obj){
		window.location = '?mod=member&act=share&weekid='+$(obj).val();
	}
  </script>
  
