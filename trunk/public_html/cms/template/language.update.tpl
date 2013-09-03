<div class="table_list toolbar">	
	<div class="breadcrumb">{xpath}</div>
	<a href="{http_referer}" class="btn r">&lt; {lang.back}</a>
</div>
<div class="form"><form action="" method="post" enctype="multipart/form-data" name="form1" id="flanguage">
  <table width="100%" border="0" cellspacing="0" cellpadding="1" class="table-Form1 marginTop5">
    <tr>
      <td width="20%" class="textLabel">{lang.code}</td>
      <td width="80%">{ln}&nbsp;</td>
    </tr>
    <tr>
      <td class="textLabel">{lang.name}</td>
      <td><input name="ln_name" type="text" id="ln_name" value="{ln_name}"></td>
    </tr>
    <tr>
      <td class="textLabel">{lang.alias}</td>
      <td><input name="ln_alias" type="text" id="ln_alias" value="{ln_alias}" /></td>
    </tr>
	 <tr>
      <td class="textLabel">&nbsp;</td>
      <td align="right">
        <input type="submit" class="btn" name="Submit" value="{lang.save}">
		<input type="button" class="btn btn_cancel" value="{lang.cancel}" onclick="location.href='./?mod=language';"></td>
    </tr>
  </table>
</form></div>
<script type="text/javascript">
var opt = {
		required: [],
		'{system.lang}'
	};
	$('form#flanguage').validate(opt);
</script>
