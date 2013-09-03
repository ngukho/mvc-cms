<div class="top_callFunc">
	<a href="{http_referer}" class="btn r">< Back</a>
</div>

<form action="" method="post" enctype="multipart/form-data" name="form1">
  <table width="100%" border="0" cellspacing="0" cellpadding="1" class="table-Form1">  	 
    <tr>
      <td class="textLabel">Name </td>
      <td><input type="text"  value="{user.fullname}" name="detail[fullname]" style="width:500px">&nbsp;</td>
    </tr>     
    <!--
    <tr>
      <td class="textLabel">CODE</td>
      <td><input type="text"  value="{user.keys}" style="width:500px" name="detail[keys]">&nbsp;</td>
    </tr>
    <tr>
      <td class="textLabel">Birthday</td>
      <td><input type="text" value="{user.dob}" name="detail[dob]" style="width:500px">&nbsp;</td>
     </tr>  
    <tr>
      <td class="textLabel">Gender</td>
      <td>
      	<select name="detail[sex]">
        	<option value="0">Select--</option>
            <!--BASIC listsex--
            <option {listsex.selected} value="{listsex.id}">{listsex.name}</option>
             <!--BASIC listsex-
        </select>      
   </td>
   <tr>
      <td class="textLabel">Email</td>
      <td><input type="text"  value="{user.email}" name="detail[email]" style="width:500px">&nbsp;</td>
    </tr>   
     <tr>
      <td class="textLabel">Tel No.</td>
      <td><input type="text"  value="{user.tel}" name="detail[tel]" style="width:500px">&nbsp;</td>
    </tr> 
      <tr>
      <td class="textLabel">IDNO</td>
      <td>
      	<input type="text"  value="{user.idno}" name="detail[idno]" style="width:500px">&nbsp;
      </td>
    </tr>  
    <tr>
      <td class="textLabel">City</td>
      <td><input type="text"  value="{user.city}" style="width:500px" name="detail[city]">&nbsp;</td>
    </tr> -->
    <tr>
      <td class="textLabel">Message</td>
      <td><textarea name="detail[message]" rows="10">{user.message}</textarea></td>
    </tr> 
    <tr>
      <td class="textLabel">Active</td>
      <td><input type="checkbox" {user.active} value="1" name="detail[active]" /></td>
    </tr> 
    <tr>
        <td class="textLabel">Hình ảnh</td>
        <td>
        <input type="file" name="image"  />&nbsp;&nbsp; <input type="checkbox" value="1" name="delete_image" /> Delete<br  /><br  />		
        <p>{user.image_url} </p>
       </td>
    </tr>  
  </table>  
  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table-Form1">
      <tr>
        <td class="textLabel">&nbsp;</td>
		
		<td>		
			<div class="l">
				<span>	
					<select name="back" id="back" class="submit_action">
						<option value="1" {back_list_1}>{lang.back_to_list}</option>
						<option value="0" {back_list_0}>{lang.keep_me_here}</option>
					</select>
				</span>
			</div>
            <input type="hidden" name="current_contest_pic" value="{user.contest_pic}" />
			<input type="submit" class="btn" name="Submit" value="{lang.save}">
			<input type="button" class="btn btn_cancel" id="reset" onclick="location.href='{http_referer}';" value="{lang.cancel}">
			
		</td>
      </tr>
    </table>
</form>