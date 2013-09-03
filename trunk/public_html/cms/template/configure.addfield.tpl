<form name="form1" method="post" action="" onsubmit="return checkForm(this);">
  <table width="100%" border="0" cellspacing="1" cellpadding="1" class="table-Form1">
    <tr>
      <td width="12%">Field</td>
      <td width="10%">Type </td>
      <td width="8%">Length</td>
      <td width="70%">Default</td>
    </tr>
    <tr>
      <td><input name="field" type="text" id="field" size="19"></td>
      <td><select name="type" id="type">
        <option value="VARCHAR">VARCHAR</option>
        <option value="TEXT">TEXT</option>
        <option value="INT">INT</option>
      </select>      </td>
      <td><input name="length" type="text" id="length" size="2"></td>
      <td><input name="default" type="text" id="default" size="10">
      <input type="submit" class="btn" name="Submit" value="Save"></td>
    </tr>
  </table>
</form>
<script type="text/javascript">
function checkForm(doc){
	if(doc.field.value==''){ alert('Please enter Field'); doc.field.focus();return false; }
	return true;
}
</script>
