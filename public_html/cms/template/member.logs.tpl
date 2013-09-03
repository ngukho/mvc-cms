<div class="top_callFunc_member">
<div class="table_list toolbar fleft">
	<span class="btn l" style="margin-right:5px;">{username} đã chia sẻ {numshare} Bài Viết</span>&nbsp;&nbsp;
    <a href="?mod=member&act=export_logs&weekid={weekid}&username={username}" class="btn l" style="margin-right:5px;">Export Excel</a>   
    <br  />
    <br  />
</div>
</div>

<div class="error">{msg}</div>
<!--BOX listitemssshow-->
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="table_list list marginTop5" id="table-list">
  <tr>
  	<th class="th-checkbox firstColumn">STT</th>
    <th class="th-name">Bài viết</th>
    <th class="th-name">Tác giả</th>   
    <th class="th-name">Ảnh</th>   
    <th class="th-date">Ngày Gửi</th> 
  </tr>
  <!--BASIC user-->
  <tr id="rows{user.id}">
  	<td class="th-checkbox firstColumn">{user.stt}</td>
    <td class="th-name">{user.fullname} {user.surname}</td>
    <td class="th-name"><a class="mbajax" href="?mod=member&act=detail&id={user.user_created}">{user.username}</a></td>
    <td class="th-name">{user.contest_pic}</td>    
    <td class="th-date">{user.dateline}</td>    
  </tr>
  <!--BASIC user-->
</table>   
<!--BOX listitemssshow--> 
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
  
  
