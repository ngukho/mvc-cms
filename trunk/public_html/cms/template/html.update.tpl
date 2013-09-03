<div class="table_list toolbar"></div>
<div class="form">
	<form action="" method="post" enctype="multipart/form-data" name="form1" id="fcontent">
	
		<!--BOX language_tab-->
		<div class="language_tab {hide_no_html_record}">
			<!--BASIC language-->
			<a href="#" onclick="showLangTab('{language.ln}',this);" {language.tab_default}>{language.ln_name}</a>
			<!--BASIC language-->
		</div>
		<!--BOX language_tab-->
		<div class="error">{msg}</div>
		
		
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table-Form1 {hide_no_html_record}">
			<!--BASIC language-->
			<tbody class="{language.default_ln} tab_language" id="tab_{language.ln}">
				<tr>
				  <td class="t1">&nbsp;</td>
				  <td class="t2">&nbsp;</td>
			 	</tr>
				{language.ln_fields}
				{language.ln_fields_extra}
				
				<tr class="hide {display_ln_icon}" >
					<td class="textLabel"><label>{ln_field_icon} {language.ln_alias}</label></td>
					<td>						
						<input name="ln_icon[{language.ln}]" type="file" id="ln_icon" class="no_width" /> {size_ln_icon}
						<p class="hide">
							<a href="{_UPLOAD}{language.ln_icon}" class="mb">{language.ln_icon}</a>
							<input type="checkbox" name="delete_ln_icon[{language.ln}]" class="no_width" value="1" />
							<input name="current_ln_icon[{language.ln}]" type="hidden" value="{language.ln_icon}" title="{language.ln_icon}"  class="no_width box_delete_file" />
							{lang.delete}					  	</p>					</td>
				</tr>
				<tr class="hide {display_ln_image}" >
					<td class="textLabel"><label>{ln_field_image} {language.ln_alias}</label></td>
					<td>						
						
							
						<input name="ln_image[{language.ln}]" type="file" id="ln_image" class="no_width" /> {size_ln_image}
						<p class="hide">
							<a href="{_UPLOAD}{language.ln_image}" class="mb">{language.ln_image}</a>
							
							<input type="checkbox" name="delete_ln_image[{language.ln}]" class="no_width" value="1" />
							<input name="current_ln_image[{language.ln}]" type="hidden" value="{language.ln_image}" title="{language.ln_image}" class="no_width box_delete_file" />
							{lang.delete}					  </p>					</td>
				</tr>
				<tr class="sep">
					<td colspan="2"><p></p></td>
				</tr>
			</tbody>
			<!--BASIC language-->
		</table>
		
		
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table-Form1 {hide_no_languages} {hide_no_html_record}">
			<tr >
			  <td class="t1">&nbsp;</td>
			  <td class="t2">&nbsp;</td>
		  </tr>
		  {main_fields}
			<tr class="hide {display_enable_date}" >
				<td class="textLabel"><label>{lang.date}</label></td>
				<td class="input_date"><script type="text/javascript">DateInput('date', true, 'YYYY-MM-DD','{date}');</script></td>
			</tr>
			<tr class="hide {display_icon}" >
				<td class="textLabel"><label>{field_icon}</label></td>
				<td>
					
					<input name="icon" type="file" id="icon" class="no_width"> {size_icon}
					<p class="hide">
						<a href="{_UPLOAD}{icon}" class="mb">{icon}</a>
						<input type="checkbox" name="delete_icon" class="no_width" value="1" /> 
						<input name="current_icon" type="hidden" title="{icon}" value="{icon}" class="box_delete_file" />
						{lang.delete}				  	</p>				</td>
			</tr>
			<tr class="hide {display_image}" >
				<td class="textLabel"><label>{field_image}</label></td>
				<td>
				  
					<input name="image" type="file" id="image" class="no_width">
					
					{size_image} 
					<p class="hide">
						<a href="{_UPLOAD}{image}" class="mb">{image}</a>
					
					<input type="checkbox" name="delete_image" class="no_width" value="1" />
						{lang.delete}
					<input name="current_image" type="hidden" title="{image}" value="{image}" class="box_delete_file" />
					</p>				</td>
			</tr>
			<!--BASIC file_extra-->
			<tr>
				<td class="textLabel"><label>{file_extra.name}</label></td>
				<td>
				  
					<input name="file_extra{file_extra.stt}" type="file" id="image" class="no_width">
					<input name="file_extra_type[{file_extra.stt}]" type="hidden" value="{file_extra.type}" />
					<input name="file_extra_code[{file_extra.stt}]" type="hidden" value="{file_extra.code}" />
					{file_extra.note}
					<p class="hide">
						<a href="{_UPLOAD}{file_extra.file}" class="mb">{file_extra.file}</a>
						<input type="checkbox" name="delete_file_extra{file_extra.stt}" class="no_width" value="1" />
						<input name="current_file_extra{file_extra.stt}" type="hidden" title="{file_extra.file}" value="{file_extra.file}" class="box_delete_file" />
						{lang.delete}					</p>				</td>
			</tr><!--BASIC file_extra-->
			<tr class="hide {action_seo}" >
				<td class="textLabel"><label>Meta Keywords</label></td>
				<td><textarea name="web_keyword" rows="5" id="web_keyword">{web_keyword}</textarea></td>
			</tr>
			<tr class="hide {action_seo}" >
				<td class="textLabel"><label>Meta Description</label></td>
				<td><textarea name="web_desc" rows="5" id="web_desc">{web_desc}</textarea></td>
			</tr>
	<!--BASIC fields_extra-->
	<tr>
		<td class="textLabel"><label>{fields_extra.name}</label></td>
		<td>{fields_extra.value}</td>
	</tr><!--BASIC fields_extra-->
			
			<tr class="hide {display_gallery}" >
				<td class="textLabel">
					<label>{field_gallery_name}</label>
					<p class="label-description">{size_gallery}</p>				</td>
				<td>
					
					<table width="75%" border="0" cellspacing="2" id="table-gallery" cellpadding="2" class="table_list list marginTop5">
	   <tr>
	     <th width="4%"   class="th-checkbox firstColumn"><input title="Select all" type="checkbox" name="checkbox" onclick="checkAll('tbody.gallery',this,'del_gallery');" value="checkbox" /></th>
          <th width="76%"   class="th-name">{lang.name}</th>
          <th width="9%" align="center" class="th-status">{lang.status}</th>
          <th width="11%" align="center" class="th-status lastColumn">{lang.actions}</th>
        </tr>
	  <tbody class="gallery">
	 
       
		 <!--BASIC gallery-->
        <tr id="{gallery.id}">
          <td class="th-checkbox"><input type="checkbox" name="del_gallery[]" value="{gallery.id}" /></td>
          <td> {gallery.name}<a href="{_UPLOAD}{gallery.image}" class="mb">{gallery.image}</a></td>
          <td  align="center"><a title="{lang.update_status}" href="?mod=gallery&act=active&p={module}&pid={id}&parentid={parentid}&type={type}&id={gallery.id}"><img src="images/icons_default/status{gallery.active}.gif" alt="Status"  /></a></td>
          <td align="center"><a title="{lang.edit}" href="?mod=gallery&act=update&p={module}&pid={id}&parentid={parentid}&type={type}&id={gallery.id}"><img src="images/icons_default/edit{ucp.new}.png" border="0" /></a> <a title="{lang.delete}" href="?mod=gallery&act=delete&p={module}&pid={id}&parentid={parentid}&type={type}&id={gallery.id}" onclick="deleteConfirm(this); return false;"><img src="images/icons_default/delete{ucp.delete}.png" border="0" /></a></td>
        </tr>
		<!--BASIC gallery-->
	</tbody>
      </table>
 <div>
  <input type="button" name="Submit2" value="{lang.delete_selected_image}" class="btn" onclick="deleteGallery('#table-gallery',{type})" />
</div>
<p>&nbsp;</p>
					
					
					<div class="gallery-inputFile">
						<p class="hide {display_gallery_name}" >
							<label>Title</label>
							<input name="name_gallery0" type="text" value="" title="" />						
						</p>
						<p class="hide {display_gallery_icon}" >
							<label>Upload Icon</label>
							<input type="file" name="icon_gallery0" size="25" />
                            {size_gallery_icon}
						</p>
						<p>
							<label>Upload Image</label>
							<input type="file" name="image_gallery0" size="25" />
                            {size_gallery_image}
						</p>
					</div>
					<p id="add_gallery"></p>
					<p class="link-addMore"><a href="#" onclick="add_gallery('#add_gallery','{is_gallery_name}','{is_gallery_icon}','{size_gallery_icon}','{size_gallery_image}');return false">Insert more</a></p>				</td>
			</tr>
		</table>
		
		
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="{hide_no_languages} {hide_no_html_record} table-Form1">
				<tr>
					<td class="textLabel"><label>&nbsp;</label></td>
				  <td><input type="submit" class="btn" name="Submit" value="Save"></td>
				</tr>
		</table>
	</form>
</div>
<!--BOX drapdrop_gallery-->
<script type="text/javascript">
	var begin = null;
	$('#table-gallery tbody.gallery').tableDnD({
		onDragClass: "ondrag-list",
		onDragStart: function(table, row) {
			begin = $.tableDnD.serialize();
		},
		onDrop: function(table, row) {
			var after = $.tableDnD.serialize();
			if(begin != after){
				$.post('?mod=gallery&act=order&p=html',{'data': after});
			}
		}
    });
</script>
<!--BOX drapdrop_gallery-->
<script type="text/javascript">
	var opt = {
		required: [{required_fields}],
		required_lang: [{required_ln_fields}],
		lang: '{language}'
	};
	if(access_edit == '') opt['disabled'] = '{lang.user_no_access}';
	$('form#fcontent').validate(opt);

</script>
