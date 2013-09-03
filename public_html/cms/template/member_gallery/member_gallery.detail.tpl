<div class="top_callFunc"> <a href="{user.back}" class="btn r">< Back</a> </div>
<style type="text/css">
.readonly{ 	
	background: buttonface;
 }
</style>
	<table width="100%" border="0" cellspacing="0" cellpadding="1" class="table-Form1">
		<tr>
			<td class="textLabel">Họ Tên </td>
			<td><label class="borders" style="width:500px; display:block">{user.fullname}</label>
				&nbsp;</td>
		</tr>		
		<!--<tr>
			<td class="textLabel">Ngày Sinh</td>
			<td><label class="borders" style="width:500px; display:block">{user.dob}</label>&nbsp;</td>
		</tr>		
		<tr>
			<td class="textLabel">CMND</td>
			<td><label class="borders" style="width:500px; display:block">{user.idno}</label>&nbsp;</td>
		</tr>
		<tr>
			<td class="textLabel">Email</td>
			<td><input type="text" class="readonly"  value="{user.email}" style="width:500px">
				&nbsp;</td>
		</tr>
        <tr>
			<td class="textLabel">Điện Thoại.</td>
			<td><label class="borders" style="width:500px; display:block">{user.tel}</label>&nbsp
				&nbsp;</td>
		</tr>
		<tr>
			<td class="textLabel">Địa chỉ</td>
			<td><input type="text" class="readonly"  value="{user.address}" style="width:500px">
				&nbsp;</td>
		</tr>		
		<tr>
			<td class="textLabel">Tỉnh/Thành Phố</td>
			<td><input type="text" class="readonly"  value="{user.city}" style="width:500px">
				&nbsp;</td>
		</tr>			
		<tr>
			<td class="textLabel">Hình ảnh đại diện</td>
			<td valign="middle"> {user.avatar} </td>
		</tr>-->
        <tr>
			<td class="textLabel">Nội dung.</td>
			<td><label class="borders" style="width:500px; display:block">{user.message}</label>&nbsp;</td>
		</tr>
        <tr>
			<td class="textLabel">Hình ảnh</td>
			<td valign="middle"> {user.contest_pic} </td>
		</tr>
        <tr>
        	<td colspan="2">&nbsp;</td>
        </tr>        
		<!--<tr>
			<td class="textLabel">Nhận quà</td>
			<td valign="middle" >
            {user.worked}          
            <!--BOX nhanqualocation-->
            <form method="post" action="?mod={module}&act=active&id={user.id}&enabled=1&menu=0.0" enctype="multipart/form-data" id="postdata" onsubmit="return checkformconfirm(this);">
            	<select name="location" id="location">
                <!--BASIC location-->
                	<option value="{location.id}">{location.name}</option>
                <!--BASIC location-->                  
                </select>
                &nbsp;&nbsp;<strong>Mã code</strong>: <input type="text" name="codeinput"  value="{user.purchased}">&nbsp;&nbsp;
                <input type="submit" name="submit" value="Xác nhận" class="btn" />
            </form> 
             <!--BOX nhanqualocation-->          
		</tr>
         <!--BOX nhanquaroi-->
        <tr>
			<td class="textLabel">Mã đã nhận</td>
			<td valign="middle" >{user.purchased} </td>
        </tr>
          <!--BOX nhanquaroi-->-->
        <tr>
        	<td class="textLabel">Báo Vi phạm</td>
            <td valign="middle">
            	<a href="?mod={module}&amp;act=blocked&blocked=1&enabled={user.active}&amp;id={user.id}&menu=0.2" title="Báo vi phạm"><img src="images/icons_default/status{user.blocked}.gif" /></a>
            </td>
        </tr>
	</table>
    <script type="text/javascript">
		function checkformconfirm(form){			
			if ($(form.codeinput).val()==""){
				alert("Vui lòng nhập mã code");
				$(form.codeinput).focus();
				return false;
			}
			var param = $(form).serialize();
			$.post("?mod={module}&act=active&id={user.id}&enabled=1&menu=0.0",param, function(result){
				if (result=="-1"){
					alert("Mã code này đã nhận Sản Phẩm. Vui lòng chọn mã khác");
				}
				else{
					window.location = result;
				}
			});
			return false;
		}
	</script>
