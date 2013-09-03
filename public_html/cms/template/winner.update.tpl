<div class="top_callFunc">
	<a href="{http_referer}" class="btn r">< Back</a>
</div>
{strMSG}
<form action="#" method="post" enctype="multipart/form-data" name="form1">
  <table width="100%" border="0" cellspacing="0" cellpadding="1" class="table-Form1">   
    <tr>
      <td class="textLabel">UID</td>
      <td><input type="text"  value="{detail.uid}" name="detail[uid]" style="width:500px">&nbsp;</td>
    </tr>  
     <tr>
      <td class="textLabel">Username</td>
       <td><input type="text"  value="{detail.username}" name="detail[username]" style="width:500px">&nbsp;</td>
    </tr>  
    <tr>
      <td class="textLabel">Contest Name</td>
       <td><input type="text"  value="{detail.contest_name}" name="detail[contest_name]" style="width:500px">&nbsp;</td>
    </tr>  
    <tr>
      <td class="textLabel">contest_desc</td>
       <td>
       <textarea rows="5" name="detail[contest_desc]" style="width:500px">{detail.contest_desc}</textarea></td>       
    </tr>
    <tr>
      <td class="textLabel">contest_like</td>
       <td><input type="text"  value="{detail.contest_like}" name="detail[contest_like]" style="width:500px">&nbsp;</td>
    </tr>
    <tr>
      <td class="textLabel">contest_prize:</td>
	  <td><input type="text"  value="{detail.contest_prize}" name="detail[contest_prize]" style="width:500px">&nbsp;</td>
    </tr>  
    <tr>
      <td class="textLabel">contest_id:</td>
	  <td><input type="text"  value="{detail.contest_id}" name="detail[contest_id]" style="width:500px">&nbsp;</td>
    </tr>  
    <tr>
      <td class="textLabel">prize_week</td>
      <td><input type="text"  value="{detail.prize_week}" name="detail[prize_week]" style="width:500px">&nbsp;</td>
    </tr>
    <tr>
      <td class="textLabel">Week Video</td>
	  <td>
      <select name="detail[obj_id]" class="submit_action">
      <!--BASIC listvideoweek-->      	
        	<option value="{listvideoweek.id}" {listvideoweek.active}>{listvideoweek.name}</option>       
       <!--BASIC listvideoweek-->
       </select>      
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
			<input type="submit" class="btn" name="Submit" value="{lang.save}">
			<input type="button" class="btn btn_cancel" id="reset" onclick="location.href='{http_referer}';" value="{lang.cancel}">
			
		</td>
      </tr>
    </table>
</form>

