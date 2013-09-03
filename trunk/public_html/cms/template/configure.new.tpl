<div class="top_callFunc"><a class="btn r" href="{http_referer}">&lt; {lang.back}</a></div>
<div class="form"><form action="" method="post" enctype="multipart/form-data" name="fcontent" id="fcontent">
  <table width="100%" border="0" cellspacing="1" cellpadding="1" class="table-Form1">
    <tr>
      <td>&nbsp;</td>
      <td>{error}</td>
    </tr>
    <tr>
      <td width="13%">Code</td>
      <td width="87%"><input name="code" type="text" id="code" value="{code}"></td>
    </tr>
    <tr>
      <td>Name</td>
      <td><input name="name" type="text" id="name" value="{name}"></td>
    </tr>
    <tr>
      <td>Value</td>
      <td><input name="value" type="text" id="value" value="{value}"></td>
    </tr>
    <tr>
      <td>Notes</td>
      <td><textarea name="note" id="note">{note}</textarea></td>
    </tr>
  </table>
  <div class="table_list" align="right">
    <input type="submit" class="btn" name="Submit" value="{lang.save}">
	<input name="back" type="button" class="btn btn_cancel" id="back" value="{lang.cancel}" onClick="location.href='{http_referer}';">
  </div>
</form></div>
<script type="text/javascript">
	var opt = {
		required: [],
		lang: '{system.lang}'
	};
	$('form#fcontent').validate(opt);

</script>