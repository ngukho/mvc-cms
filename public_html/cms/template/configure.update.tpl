<div class="top_callFunc"><a class="btn r" href="{http_referer}">&lt; {lang.back}</a></div>
<div class="form"><form action="" method="post" enctype="multipart/form-data" name="fcontent" id="fcontent">
  <table width="100%" border="0" cellspacing="0" cellpadding="1" class="table-Form1">
    <tr>
      <td width="84%"><strong>{name}</strong>&nbsp;<br>
      <em>{note}</em></td>
    </tr>
    <tr>
      <td>{value}</td>
    </tr>
  </table>
  <div class="table_list" align="right">
    <input type="submit" class="btn" name="Submit" value="{lang.save}">
	<input name="back" type="button" class="btn btn_cancel" id="back" value="{lang.cancel}" onclick="location.href='{http_referer}';">
  </div>
</form></div>
<script type="text/javascript">
	var opt = {
		required: [],
		'{system.lang}'
	};
	$('form#fcontent').validate(opt);

</script>