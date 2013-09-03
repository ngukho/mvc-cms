<div class="top_callFunc_member" style="width:100%">
<div class="table_list toolbar fleft">
	 <div class="l" style="padding:10px;">
    <strong>Chủ Đề&nbsp;&nbsp;</strong>
    <select onchange="loadcontent(this);" style="width:200px">
    	<option value="0">Tất Cả</option>
        <!--BASIC listvideoweek-->
        <option value="{listvideoweek.id}" {listvideoweek.active}>{listvideoweek.name} {listvideoweek.intro}</option>
        <!--BASIC listvideoweek-->
    </select>
    </div>
	<a href="?mod={module}&act=export&weekid={weekid}" class="btn l">Export CSV</a>       
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
  	<th class="th-checkbox firstColumn" style="padding-left:3px;"><input type="checkbox" class="no_width" style="{display_checkall}" name="checkall" value="1" onclick="checkAll('#table-list',this,'pro')" /></th>   
    <th class="th-name">Bài viết</th>
    <th class="th-name">Tác giả</th>   
    <th class="th-name">Like</th>
    <th class="th-name">Chia sẻ</th>       
    <th class="th-name">Ảnh</th>   
    <th class="th-date">Ngày Gửi</th> 
    <th class="th-status"> Báo vi phạm</th>
    <th class="th-status"> Trạng Thái</th>
    <th align="center"  class="th-action lastColumn">Action</th>
  </tr>
  <!--BASIC user-->
  <tr id="rows{user.id}" style="padding-left:5px;">
  	<td class="th-checkbox firstColumn"><input type="checkbox" name="pro[]" value="{user.id}" class="no_width" /></td>   
    <td class="th-name">{user.title}</td>
    <td class="th-name"><a class="mbajax" href="?mod=member&act=detail&id={user.user_created}">{user.fullname}</a></td>
    <td class="th-name">{user.numvote}</td>
    <td class="th-name">{user.numshare}</td>
    <td class="th-name"><a class="mb" href="{_UPLOAD}{user.image}">Xem hình ảnh</a></td>    
    <td class="th-date">{user.timestamp}</td>    
    <td class="th-status"><a href="?mod={module}&amp;act=blocked&blocked={user.blocked}&enabled={user.active}&amp;id={user.id}{url_current}#rows{user.id}" title="Báo vi phạm"><img src="images/icons_default/status{user.blocked}.gif" /></a></td>    
    <td class="th-status"><a href="?mod={module}&amp;act=active&enabled={user.active}&amp;id={user.id}{url_current}#rows{user.id}" title="Cập nhận nhận quà"><img src="images/icons_default/status{user.active}.gif" /></a></td>    
    <td class="th-status lastColumn" style="width:55px">
      <a href="?mod=comment&amp;p=content&amp;pid={user.id}&amp;parentid={parentid}&amp;type={type}" title="Comment"><img src="images/icons_default/msg.png" width="16" height="16" border="0" /></a>	
      <a href="?mod={module}&act=detail&id={user.id}&enabled={user.active}{url_current}" title="Detail"><img src="images/icons_default/view.gif" width="16" height="16" /></a>   	
      <a href="?mod={module}&act=update&id={user.id}&enabled={user.active}{url_current}" title="Detail"><img src="images/icons_default/edit.png" width="16" height="16" /></a>   	
    </td>
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
  <script type="text/javascript">
	$('a.divbox').divbox({ caption: false});
	function setActive(id){
		$.post('?mod={module}&act=active&id='+id,{},function(){});
	}
	function loadcontent(obj){
		window.location = '?mod=member_gallery&enabled=1&weekid='+$(obj).val();
	}
		
  </script>
  
  
