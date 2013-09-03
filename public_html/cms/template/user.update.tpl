<div class="top_callFunc">
	<a href="{http_referer}" class="btn r">&lt; {lang.back}</a>
</div>

<form action="" method="post" enctype="multipart/form-data" name="form1" id="fuser">
  <table width="100%" border="0" cellspacing="0" cellpadding="1" class="table-Form1">
    <tr>
      <td class="textLabel">{lang.name}</td>
      <td><input name="fullname" type="text" id="title" value="{fullname}" style="width: 189px;">&nbsp;</td>
    </tr>
    <tr>
      <td class="textLabel">{lang.username}</td>
      <td><input name="username" type="text" class="tinymce" id="username" value="{username}" style="width: 189px;"></td>
    </tr>    
    <tr>
      <td class="textLabel">{lang.skin}</td>
      <td><select name="skin" id="skin" style="width: 200px;">
	  <!--BASIC skin-->
        <option value="{skin.value}" {skin.selected}>{skin.name}</option><!--BASIC skin-->
      </select>      </td>
    </tr>
	<tr>
	  <td class="textLabel">{lang.icon}</td>
	  <td><select name="icon" id="icon" style="width: 200px;">
        <!--BASIC icon-->
        <option value="{icon.value}" {icon.selected}>{icon.name}</option>
        <!--BASIC icon-->
      </select></td>
    </tr>
     <tr>
      <td class="textLabel">{lang.group}</td>
      <td>
      <ul>      	
         <!--BASIC listGroup-->
         <li style="padding: 5px 5px 5px 0px;">
        <input type="checkbox" name="namegroup[]"  {listGroup.checked} value="{listGroup.id}" /><span>{listGroup.groupname}</span>
        </li>
        <!--BASIC listGroup-->     
      </ul>
         
      </td>
    </tr>
	<tr>
      <td class="textLabel">&nbsp;</td>
      <td align="right"><br />
	  	<input type="submit" class="btn" name="Submit" value="{lang.save}">
	  <input type="button" class="btn btn_cancel" value="{lang.cancel}" onclick="location.href='{http_referer}';">      </td>
    </tr>
  </table>
</form>
<script type="text/javascript">
var opt = {
		required: [],
		'{system.lang}'
	};
	$('form#fuser').validate(opt);
</script>