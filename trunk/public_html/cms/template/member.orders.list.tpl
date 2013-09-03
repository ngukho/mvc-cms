 <form id="form1" name="form1" method="post" action="" onsubmit="return checkSubmitAction(this)">
<div class="top_callFunc_member">
<div class="table_list toolbar fleft">
	<div class="l" style="padding:10px;">   
        <strong>Từ Ngày:</strong>
         <input name="fromdate" type="text" class="txf-normal" id="fromdate" value="{fromdate}" />  ex: YYYY-MM-DD
        <strong>&nbsp;&nbsp;Đến Ngày:</strong>
          <input name="todate" type="text" class="txf-normal" id="todate" value="{todate}" />  
        <strong>Tỉnh/Thành Phố&nbsp;&nbsp;</strong>
        <select onchange="loadcontent(this);" name="location">
            <option value="0">Tất cả</option>
            <!--BASIC locationlist-->
            <option value="{locationlist.id}" {locationlist.active}>{locationlist.name}</option>
            <!--BASIC locationlist-->
        </select>
         &nbsp;&nbsp;<input name="btnExec" type="submit" class="btn" id="cmd" value="Xem" />&nbsp;&nbsp;      
          &nbsp;&nbsp;<input name="btnExport" type="submit" class="btn" id="cmd" value="Export Execel" />&nbsp;&nbsp;          
          <input type="hidden" name="status" value="{status}" />
    </div>
    
	
</div>
<!--<div class="searchForm table-Form1 fleft">
<form id="form1" name="form1" method="post" action="">
    <input name="q" type="text" class="txf-normal" id="q" value="{search_text}"/>
    <input name="btnSearch" type="submit" class="btn" id="cmd" value="{lang.search}" />   
</form>
</div>-->
</div>

<div class="error">{msg}</div>
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="table_list list marginTop5" id="table-list">
  <tr>
  	<th class="th-checkbox firstColumn" align="center" width="4%" valign="middle">      
        <input type="checkbox" class="no_width" style="{display_checkall}" name="checkall" value="1" onclick="checkAll('#table-list',this,'pro')" />
    </th>
    <th class="th-name">OrderId </th>      
    <th class="th-name">Người đặt hàng</th>       
    <th class="th-name">Người Nhận Hàng</th>       
    <th class="th-name">Điện Thoại</th>   
    <th class="th-name">Công ty </th>   
    <th class="th-name">Ngày đặt hàng</th>
    <th class="th-status">Trạng thái</th>
    <th align="center"  class="th-action lastColumn">Chức Năng</th>
  </tr>
  <!--BASIC user-->
  <tr id="rows{user.id}">
  	<td class="th-checkbox firstColumn"><input type="checkbox" name="pro[]" value="{user.id}" class="no_width" /></td>  
     <td class="th-name">{user.orderid}</td>
    <td class="th-name"><a class="mbajax" href="?mod=member&act=detail&id={user.memberid}">{user.nguoidathang}</a></td>
    <td class="th-name">{user.fullname}</td>   
    <td class="th-name">{user.tel}</td>       
     <td class="th-name">{user.company}</td>    
     <td class="th-name">{user.timestamp}</td>
     <td class="th-status">{user.paystatus}</td>    
     <td class="th-status lastColumn">
     <a href="{url_link}&act=detail&id={user.id}" title="Detail"><img src="images/icons_default/view.gif" width="16" height="16" /></a>&nbsp;&nbsp;
   	 <a href="{url_link}&act=delete&id={user.id}" title="Delete" onClick="deleteConfirm(this); return false;"><img src="images/icons_default/delete{ucp.delete}.png" width="16" height="16" /></a>
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
    </div>
</form>
  <script type="text/javascript">
	$('a.divbox').divbox({ caption: false});
	function setActive(id){
		$.post('?mod=member&act=active&id='+id,{},function(){});
	}	
	function loadcontent(obj){
		var listid = $("input[name='pro[]']").serialize();		
		var location = $(obj).val()>0?$(obj).val():0;
		var url = '?mod={module}&status={status}&location='+location;
		/*if ($("#fromdate").val()!=""){
			url += "&fromdate="+$("#fromdate").val();
		}	
		if ($("#todate").val()!=""){
			url += "&todate="+$("#todate").val();
		}*/		
		window.location = url;
	}
  </script>
