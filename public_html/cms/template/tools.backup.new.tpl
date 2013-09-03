<div class="top_callFunc"><a class="btn r" href="{http_referer}">&lt; {lang.back}</a></div>
<div class="form"><form action="" method="post" enctype="multipart/form-data" name="form1" id="fcontent">
  <div class="error">{msg}</div>
  <table width="100%" border="0" cellspacing="0" cellpadding="1" class="table_list">
    <tr>
      <td width="2%"><input name="download_only" type="checkbox" id="name" value="1"></td>
      <td width="98%">{lang.download_file}</td>
    </tr>
  </table>
  <div class="table_list">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="77%">&nbsp;</td>
        <td width="23%" align="right"><input type="submit" class="btn" name="Submit" value=" {lang.save}"> 
        <input type="button" class="btn btn_cancel" name="Cancel" onClick="location.href='{http_referer}';" value=" {lang.cancel}"></td>
      </tr>
    </table>
    
  </div>
</form></div>
<script type="text/javascript">
var opt = {
		required: [],
		'{system.lang}'
	};
	$('form#fcontent').validate(opt);
</script>
    


