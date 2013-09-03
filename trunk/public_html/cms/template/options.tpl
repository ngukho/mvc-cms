<div class="top_callFunc">
<!--BOX search_function-->
	<div class="searchForm table-Form1">
		<form id="form1" name="form1" method="get" action="">
			<input name="mod" type="hidden" id="mod" value="{module}" />
			<input name="type" type="hidden" id="type" value="{type}" />
			<input name="parentid" type="hidden" id="parentid" value="{parentid}" />
			<input name="q" type="text" class="txf-normal" id="q" value="{q}" onfocus="if(this.value==this.defaultValue) this.value='';" onblur="if(this.value=='') this.value = this.defaultValue;" />
			<input name="cmd" type="submit" class="btn" id="cmd" value="{lang.search}" />
			{search_result}
		</form>
	</div><!--BOX search_function-->
<!--<img src="images/new_record{ucp.new}.png" width="16" height="16" align="absmiddle" /> <a href="?mod={module}&amp;act=update&amp;type={type}&amp;do=new" title="New">New</a>
--><div class="l" >
		<a href="?mod={module}&amp;act=update&amp;parentid={parentid}&amp;type={type}&amp;do=new" title="" class="addNew btn">{button.new_item}</a>
		<!--style="background-image:url(../images/new_record{ucp.new}.png)" -->
	</div>
</div>
<!--<div class="breadcrumb_cat">{xpath}</div>
-->

<div class="pagination paging-top">
	<span class="r"><a href="./?mod={module}&amp;parentid={parentid}&type={type}&amp;page={page}">{lang.sorted_default}</a></span>{divpage}
</div>

<div class="form">
<form id="form2" name="form2" method="post" action="" onSubmit="return checkSubmitAction(this)">
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="table_list list table-Form1" id="table-list">
  <tr>
    <th width="3%" class="th-checkbox firstColumn"><input type="checkbox" name="checkall" {display_checkall} value="1" onClick="checkAll('#table-list',this,'pro')" /></th>
    
    <th width="70%" class="th-name"><a href="./?mod={module}&amp;parentid={parentid}&type={type}&amp;page={page}&amp;by=name&amp;order={switchorder}">{button.header_name}</a> {orderby.name}</th>
    <th width="16%" class="th-action {class_status_fields}" >{status_fields} </th>
    <th width="5%" class="th-order"><a href="./?mod={module}&amp;parentid={parentid}&type={type}&amp;page={page}&amp;by=order_id&amp;order={switchorder}">{button.header_order}</a> {orderby.order_id}</th>
    <th width="5%" class="th-status"><a href="./?mod={module}&amp;parentid={parentid}&type={type}&amp;page={page}&amp;by=active&amp;order={switchorder}">{button.header_status}</a> {orderby.active}</th>
    <th width="18%" class="th-status lastColumn">{lang.actions}</th>
  </tr>
  <tbody class="pro">
  <!--BASIC product-->
  <tr id="{product.id}pro">
    <td class="th-checkbox firstColumn">
      <input type="checkbox" name="pro[]" value="{product.id}" /></td>
    <td class="th-name">{product.thumb_field}{product.list_field}</td>
    <td class="th-action {class_status_fields}">{product.status_fields}</td>
    <td class="th-order"><span class="hide {icon_updown}"><a class="{product.classup}" href="?mod={module}&amp;act=orderbtn&amp;do=up&amp;id={product.id}&amp;page={page}&amp;parentid={parentid}&amp;type={type}"><img src="images/icons_default/up.png" /></a> <a class="{product.classdown}" href="?mod={module}&amp;act=orderbtn&amp;do=down&amp;id={product.id}&amp;page={page}&amp;parentid={parentid}&amp;type={type}"><img src="images/icons_default/down.png" /></a></span><span class="order_id">{product.order_id}</span></td>
    <td class="th-status"><a href="?mod={module}&amp;act=active&amp;id={product.id}&amp;parentid={parentid}&amp;type={type}" title="{lang.update_status}"><img src="images/icons_default/status{product.active}.gif" /></a></td>
    <td class="th-status lastColumn"><a href="?mod={module}&amp;act=update&amp;id={product.id}&amp;parentid={parentid}&amp;type={type}" title="{lang.edit}"><img src="images/icons_default/edit{ucp.edit}.png" width="16" height="16" border="0" /></a> <a {product.nodel} href="?mod={module}&amp;act=delete&amp;id={product.id}&amp;parentid={parentid}&amp;type={type}" onClick="deleteConfirm(this); return false;" title="{lang.delete}"><img src="images/icons_default/delete{ucp.delete}.png" width="16" height="16" /></a></td>
  </tr>
  <!--BASIC product-->
  </tbody>
</table>
		<div class="pagination paging-bottom">
			{divpage}
		</div>
<div class="bottom_callAction" style="{display_checkall}">			
			<strong>{button.tools_copy}:</strong><br /> <select name="content_action">
    <option value="">{lang.choose_action}</option>
	<option value="delete">{button.delete_item}</option>
	<option value="active">{button.active_item}</option>
	<option value="inactive">{button.inactive_item}</option>
  </select>
  <input name="cmd" type="submit" class="btn" id="cmd" value="{lang.go}" />
</div>
</form></div>
<!--BOX drapdrop_content-->	

<script type="text/javascript">
	var begin = null;
	$('#table-list tbody.pro').tableDnD({
		onDragClass: "ondrag-list",
		onDragStart: function(table, row) {
			begin = $.tableDnD.serialize();
		},
		onDrop: function(table, row) {
			var after = $.tableDnD.serialize();
			if(begin != after){
				$.post('?mod={module}&act=order', {'data':after,'start':{start}},function(data){
					var rows = after.replace(/\[\]=/ig,'').split('&');
					for(var i in rows) $('tr#'+rows[i]+' td.th-order span.order_id').html(parseInt(i) + parseInt({start}));

				})
			}
		}
    });
	
</script>
<!--BOX drapdrop_content-->	

