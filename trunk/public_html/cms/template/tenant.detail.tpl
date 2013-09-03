<div class="top_callFunc"> <a href="{user.back}" class="btn r">< Back</a> </div>
<style type="text/css">
.readonly{ 	
	background: buttonface;
 }
</style>
	<table width="100%" border="0" cellspacing="0" cellpadding="1" class="table-Form1">
		<tr>
			<td class="textLabel">Name</td>
			<td><label class="borders" style="width:500px; display:block">{user.name}</label>
				&nbsp;</td>
		</tr>		
		<tr>
			<td class="textLabel">Phone</td>
			<td><label class="borders" style="width:500px; display:block">{user.phone}</label>&nbsp;</td>
		</tr>		
		<tr>
			<td class="textLabel">Suite</td>
			<td><label class="borders" style="width:500px; display:block">{user.suite}</label>&nbsp;</td>
		</tr>
        <tr>
			<td class="textLabel">Building</td>
			<td><label class="borders" style="width:500px; display:block">{user.building}</label>&nbsp;</td>
		</tr>
        <tr>
			<td class="textLabel">Issue</td>
			<td><label class="borders" style="width:500px; display:block">{user.issue}</label>&nbsp;</td>
		</tr>
        <tr>
			<td class="textLabel">Message</td>
			<td><label class="borders" style="width:500px; display:block">{user.content}</label>&nbsp;</td>
		</tr>		     	
        <tr>
        	<td class="textLabel">Status</td>
            <td valign="middle">
            	{user.status}               
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
