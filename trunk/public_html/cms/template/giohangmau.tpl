<div class="top_callFunc">
	<a href="{http_referer}" class="btn r">< Back</a>
</div>
{strMSG}
<form action="{theurl}" method="post" enctype="multipart/form-data" name="form1">
 <table width="100%" border="0" cellspacing="0" cellpadding="1" class="table-Form1"> 
    <tr>
      <td width="5%" align="center">STT</td>	
      <td width="80%" colspan="2">Sản Phẩm</td>
       <td width="15%" colspan="2">Cửa Hàng</td>
    </tr>
	<!--BASIC list-->
    <tr>
      <td align="center">{list.stt}</td>	
      <td><input type="checkbox" name="giohang[]" value="{list.id}" {list.checked} /></td>
      <td>&nbsp;{list.name}</td>
      <td>&nbsp;{list.location}</td>
    </tr>
	<!--BASIC list-->
    <tr>
      <td colspan="3"><input type="submit" name="Submit" value="Submit" class="btn"  />      </td>
    </tr>
  </table>
</form>
