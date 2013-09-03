<div class="table_list toolbar"><a href="{http_referer}">&lt; {lang.back}</a></div>
<div class="form"><form action="" method="post" enctype="multipart/form-data" name="form1" id="fpermission">
  <table width="100%" border="0" cellspacing="0" cellpadding="1" class="table_list table-Form1" id="table-list">
<!--     <tr>
      <td colspan="2"><h2>Data permission </h2></td>
      </tr>
    
     <tr>
      <td><input name="all_content" type="radio" id="permission_data[]" value="0" {all_content.0_checked} /></td>
      <td>Just content of this user </td>
    </tr>
     <tr>
       <td><input name="all_content" type="radio" id="radio" value="1" {all_content.1_checked} /></td>
       <td>All content </td>
     </tr>
-->   <tr>
      <td colspan="2"><h2>{lang.permission_action}</h2></td>
      </tr>
    <tr>
      <td><input name="action[new]" type="checkbox" id="action[]" {action.new} value="checked" /></td>
      <td colspan="2">{lang.new}</td>
    </tr>
    <tr>
      <td><input name="action[edit]" type="checkbox" id="action[]" {action.edit} value="checked" /></td>
      <td colspan="2">{lang.edit}</td>
    </tr>
    <tr>
      <td><input name="action[delete]" type="checkbox" id="action[]" {action.delete} value="checked" /></td>
      <td colspan="2">{lang.delete}</td>
    </tr>
    <tr>
      <td><input name="checkbox" type="checkbox" disabled="disabled" value="checkbox" checked="checked" /></td>
      <td colspan="2">{lang.view}</td>
    </tr>
    <tr>
      <td colspan="2"><h2>{lang.permission_module}</h2></td>
      <td><h2>{lang.permission_action}</h2></td>
      </tr>
  <!--BASIC permission-->
    <tr>
      <td width="2%"><input id="{permission.section}_{permission.index}" type="checkbox" {permission.checked} name="act[{permission.index}][check]" value="true" onclick="checkAll('#table-list',this,'act[{permission.index}]');"></td>
      <td width="20%"><label for="{permission.section}_{permission.index}">&nbsp;{permission.section}</label></td>
      <td>      	
      	{lang.new}&nbsp;&nbsp;<input name="module_act[{permission.index}][new]" type="checkbox" {permission.new} value="checked" />&nbsp;&nbsp;
        {lang.edit}&nbsp;&nbsp;<input name="module_act[{permission.index}][edit]" type="checkbox" {permission.edit} value="checked" />&nbsp;&nbsp;
        {lang.delete}&nbsp;&nbsp;<input name="module_act[{permission.index}][delete]" type="checkbox" {permission.delete} value="checked" />&nbsp;&nbsp;
        {lang.view}&nbsp;&nbsp;<input name="module_act[{permission.index}][view]" type="checkbox" disabled="disabled" value="checkbox" checked="checked" />&nbsp;&nbsp;     
        Filter User&nbsp;&nbsp;<input name="module_act[{permission.index}][filteruser]" type="checkbox" {permission.filteruser} value="checked" />&nbsp;&nbsp;                
         Administration Review&nbsp;&nbsp;<input name="module_act[{permission.index}][approved]" type="checkbox" {permission.approved} value="0" />&nbsp;&nbsp;                
        
        
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">{permission.actions}</td>
    </tr>
  <!--BASIC permission-->	
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <div class="table_list" align="right">
    <input type="submit" class="btn" name="Submit" value="{lang.save}"> 
    <input type="button" class="btn btn_cancel" value="{lang.cancel}" onclick="location.href='{http_referer}';">
  </div>
</form></div>

<script type="text/javascript">
var opt = {
		required: [],
		'{system.lang}'
	};
	$('form#fpermission').validate(opt);
</script>
