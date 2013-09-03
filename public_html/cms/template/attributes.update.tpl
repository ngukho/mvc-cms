<div class="form attributes_form">  
    <!--BOX language_tab-->
    <div class="language_tab">
      <!--BASIC language-->
      <a href="#" onclick="showLangTab('{language.ln}',this);" {language.tab_default}>{language.ln_name}</a>
      <!--BASIC language-->
    </div>
    <!--BOX language_tab-->
    <div class="error">{msg}</div>
    <table width="100%" border="0" cellspacing="0" cellpadding="1" class="table-Form1 tbl_repeat">
      <tr>
        <th align="left" width="16%">Name</th>
        <th align="left">Description</th>        
        <th align="left">Size</th>
        <th align="left">Room Type</th>
        <th align="left">Level</th>
        <th>Action</th>
      </tr>
      <form action="" method="post" id="fcontent" enctype="multipart/form-data" name="fcontent">
      <tr valign="baseline">
        <td colspan="2" width="40%"><table width="100%" cellpadding="0" cellspacing="0">
            <!--BASIC language-->
            <tbody class="{language.default_ln} tab_language" id="tab_{language.ln}">
              <tr>
                <td width="40%"><input type="text" name="name[{language.ln}]" value="{language.name}" title="Vui long nhap Name" ></td>
                <td><textarea rows="1" cols="50" style="height:15px;" name="content[{language.ln}]">{language.content}</textarea></td>
              </tr>
            </tbody>
            <!--BASIC language-->
          </table></td>
        <td><input type="text" name="size" value="{detail.size}" title="Vui long nhap Name" ></td>
        <td><select name="room_type">
        	 <option value="">Please Choose...</option>
        	<!--BASIC listroom-->
            <option value="{listroom.id}" {listroom.active}>{listroom.name}</option>
            <!--BASIC listroom-->           
          </select>
        </td>
        <td><select name="level">
        	<option value="">Please Choose...</option>
        	<!--BASIC listlevel-->
            <option value="{listlevel.id}" {listlevel.active}>{listlevel.name}</option>
            <!--BASIC listlevel-->           
          </select>
        </td>
        <td>
        	<input type="submit" class="btn" name="Submit" value="{lang.save}">
            <a class="btn" href="?mod={module}&act=update&do=new&obj_id={obj_id}">Reset</a>
        </td>
      </tr>
      	<input type="hidden" value="{obj_id}" name="obj_id"  />   
       	<input type="hidden" value="{obj_type}" name="obj_type"  />   
       <input type="hidden" value="{detail.id}" name="updateid"  />   
      </form>
      <tr>
      <tr>
      	<td colspan="6">
        	<hr  />
        </td>
      </tr>	
       <tbody>
      	 <!--BASIC product-->               
      	<tr id="order_{product.id}">
        	<td width="16%">{product.name}</td>
            <td >{product.content}</td>
            <td>{product.size}</td>
            <td>{product.room}</td>
            <td>{product.level}</td>
            <td align="center" class="th-status lastColumn">
            	<a href="?mod={module}&amp;act=active&amp;id={product.id}&amp;obj_id={product.obj_id}&amp;obj_type={product.obj_type}" title="{lang.update_status}"><img src="images/icons_default/status{product.active}.gif" /></a>&nbsp;&nbsp;
                <a href="?mod={module}&amp;act=update&amp;id={product.id}&amp;obj_id={product.obj_id}&amp;obj_type={product.obj_type}" title="{lang.edit}"><img src="images/icons_default/edit{ucp.edit}.png"  width="16" height="16" border="0" /></a>
            </td>    
        </tr>
      <!--BASIC product-->
      </tbody>
    </table>  
</div>
<style type="text/css">
	.attributes_form .btn{ margin-top:0px;}
</style>
<script type="text/javascript">
$(function() {
	$(".tbl_repeat tbody").tableDnD({
		onDrop: function(table, row) {
			var orders = $.tableDnD.serialize();
			$.post('?mod={module}&act=order&obj_id={obj_id}&obj_type={obj_type}', { orders : orders });						
		}
	});

});
</script>
