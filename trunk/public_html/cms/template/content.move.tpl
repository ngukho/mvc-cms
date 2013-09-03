<div class="breadcrumb_cat hide">{xpath}</div>
<div class="top_callFunc"><a class="btn r" href="{http_referer}">Back</a></div>
<div class="form"><form name="form1" method="post" action="{form_action}" id="fsubmit">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table-Form1">
      <tr>
        <td width="20%">{lang.choose_category} </td>
        <td width="80%"><input name="pro" type="hidden" id="pro" value="{products}" />
        <select name="catid" id="catid">
		 <option value="0">Root</option>
          <!--BASIC category-->
		  <option value="{category.id}" {category.selected}>{category.prefix}{category.name}</option>
		  <!--BASIC category-->
        </select>
		<p>&nbsp;</p>
        <input type="submit" name="Submit" class="btn" value="{lang.save}" /> 
        <input onclick="location.href='{http_referer}';" type="button" name="Button" class="btn btn_cancel" value="{lang.cancel}" /></td>
      </tr>
    </table>
</form></div>
<script type="text/javascript">
var opt = {
		required: [],
		'{system.lang}'
	};
	$('form#fsubmit').validate(opt);
</script>
    