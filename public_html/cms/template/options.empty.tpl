<div class="content_empty" style="background: #fff;">
  <div style="height: 200px; overflow:auto;">
	<table width="100%" border="0" cellspacing="1" cellpadding="1" class="table_list list marginTop5">
		   <!--BASIC product-->
          <tr class="clear_product tr_content2" id="{product.id}pro">
            <td width="3%"><img src="images/icons_default/file.gif" alt="" width="16" height="16" /></td>
            <td width="97%">{product.name}</td>
          </tr><!--BASIC product-->
         {empty_data}
        </table>
	</div>
	<p align="center">Are you sure you want empty all data for this module?<br />
	<input type="button" value="Yes" onclick="options_empty({type});" />&nbsp;
	<input type="button" value="No" class="btn_closed" />
	</p>
</div>