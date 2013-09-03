<div class="table_list toolbar"><a href="{http_referer}">Back</a></div>
<div class="form"><form action="" id="fcontent" method="post" enctype="multipart/form-data" name="form1">
<div class="error">{msg}</div>
  <table width="100%" border="0" cellspacing="0" cellpadding="1" class="table-Form1">
    <tr class="hide {display_name}">
      <td>{lang.title}</td>
      <td><input name="name" type="text" id="title" value="{name}">&nbsp;</td>
    </tr>
    <tr class="hide {display_content}">
      <td>{lang.content}</td>
      <td><textarea name="content" rows="5" class="tinymce">{content}</textarea></td>
    </tr>
    <tr class="hide {display_icon}">
      <td width="16%">{lang.icon}</td>
      <td width="84%"><input name="icon" type="file" id="icon" class="no_width">
        
        {size_icon}
		<p class="hide"><a href="{_UPLOAD}{icon}" class="mb">{icon}</a> 
	    <input type="checkbox" name="delete_icon" class="no_width" value="1"   /> 
		<input name="current_icon" type="hidden" title="{icon}" value="{icon}" class="box_delete_file" />
	    {lang.delete}		</p></td>
    </tr>
    <tr>
      <td>{lang.image}</td>
      <td><input name="image" type="file" id="image" class="no_width">
        {size_image}
		<p class="hide"><a href="{_UPLOAD}{image}" class="mb">{image}</a> 
		<!--<input type="checkbox" name="delete_image" class="no_width" value="1"  /> {lang.delete}-->
		<input name="current_image" type="hidden" title="{image}" value="{image}" class="box_delete_file" />
		 </p></td>
    </tr>
  </table>
  <div class="table_list" align="right">
    <input type="submit" class="btn" name="Submit" value="{lang.save}">
    <input type="button" class="btn btn_cancel" name="Button" value="{lang.cancel}" onclick="location.href='{http_referer}';">
  </div>
</form></div>
<script type="text/javascript">
	var opt = {
		required: [{required_fields}],
		lang: '{language}'
	};
	if(access_edit == '') opt['disabled'] = '{lang.user_no_access}';
	$('form#fcontent').validate(opt);
</script>
