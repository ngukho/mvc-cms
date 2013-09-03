<div class="top_callFunc">
<!--BOX breadcrumb_cat-->
	<div class="hide breadcrumb_cat">{xpath}</div><!--BOX breadcrumb_cat-->
	<a href="{http_referer}" class="btn btn_back r">&lt; {lang.back}</a>
</div>
<div class="form"><form action="" method="post" enctype="multipart/form-data" name="form1" id="fcontent">
<!--BOX language_tab-->
<div class="language_tab"><!--BASIC language-->
<a href="#" onclick="showLangTab('{language.ln}',this);" {language.tab_default}>{language.ln_name}</a> <!--BASIC language--></div>
<!--BOX language_tab-->
<div class="error">{msg}</div>
  <table width="100%" border="0" cellspacing="0" cellpadding="1" class="table-Form1">
  <!--BASIC language-->
<tbody class="{language.default_ln} tab_language" id="tab_{language.ln}">
    <tr>
		<td class="td1">&nbsp;</td>
		<td class="td2">&nbsp;</td>
	</tr>
	{language.ln_fields}
    <tr class="hide {display_ln_icon}">
      <td width="16%">Icon {language.ln_alias} </td>
      <td width="84%"><input name="ln_icon[{language.ln}]" type="file" id="ln_icon[{language.ln}]" class="no_width">        {size_ln_icon}<br />
		<span class="hide"><a href="{_UPLOAD}{language.ln_icon}" class="mb">{language.ln_icon}</a> 
	    <input type="checkbox" name="delete_ln_icon[{language.ln}]" class="no_width" value="1" /> 
		<input name="current_ln_icon[{language.ln}]" type="hidden" title="{language.ln_icon}" value="{language.ln_icon}" class="box_delete_file" />
	    {lang.delete}		</span></td>
    </tr>
    <tr class="hide {display_ln_image}">
      <td width="16%">Image {language.ln_alias}</td>
      <td width="84%"><input name="ln_image[{language.ln}]" type="file" id="ln_image[{language.ln}]" class="no_width">
       
        {size_ln_image}<br />
		<span class="hide"><a href="{_UPLOAD}{language.ln_image}" class="mb">{language.ln_image}</a> 
		<input name="current_ln_image[{language.ln}]" type="hidden" title="{language.ln_image}" value="{language.ln_image}"  class="box_delete_file" />
	    <input type="checkbox" name="delete_ln_image[{language.ln}]" class="no_width" value="1" /> 
	    {lang.delete}		</span></td>
    </tr>
   <tr class="sep">
      <td></td>
      <td></td>
    </tr>
	</tbody>
<!--BASIC language-->
  </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="1" class="table-Form1 {hide_no_languages}">
    <tr>
		<td class="td1">&nbsp;</td>
		<td class="td2">&nbsp;</td>
	</tr>
    <!--BASIC options_cat-->	
    <tr class="rows_option">
      <td  valign="top" class="textLabel">{options_cat.name}</td>
      <td>{options_cat.values}
       <a class="addnew_opt" href="#" onclick="return addOptions({options_cat.typeid},'<li>{options_cat.js_string}</li>',this);">New</a></td>
    </tr>
	<!--BASIC options_cat-->

	{main_catfields}
	
	 <tr class="hide {display_file}">
      <td>Brochure (.PDF)</td>
      <td><input name="file" type="file" id="file" class="no_width">
        
		<br />
		<span class="hide"><a href="{_UPLOAD}{file}" class="mb">{file}</a> 
		<input name="current_file" type="hidden" title="{file}" value="{file}" class="box_delete_file" />
	    <input type="checkbox" name="delete_icon" class="no_width" value="1" /> 
	    {lang.delete}		
		</span></td>
    </tr>
	
    <tr class="hide {display_icon}">
      <td>{lang.icon}</td>
      <td><input name="icon" type="file" id="icon" class="no_width">
        
        {size_icon}
		<br />
		<span class="hide"><a href="{_UPLOAD}{icon}" class="mb">{icon}</a> 
		<input name="current_icon" type="hidden" title="{icon}" value="{icon}" class="box_delete_file" />
	    <input type="checkbox" name="delete_icon" class="no_width" value="1" /> 
	    {lang.delete}		
		</span></td>
    </tr>
    <tr class="hide {display_image}">
      <td>{lang.image}</td>
      <td><input name="image" type="file" id="image" class="no_width">
        {size_image}
		<br /><span class="hide"><a href="{_UPLOAD}{image}" class="mb">{image}</a> 
		  <input type="checkbox" name="delete_image" class="no_width" value="1" /> 
		  <input name="current_image" type="hidden" title="{image}" value="{image}" class="box_delete_file" />
		  {lang.delete} </span></td>
    </tr>
    <tr class="hide {display_enable_catdate}">
      <td>{lang.date}</td>
      <td><script type="text/javascript">DateInput('date', true, 'YYYY-MM-DD','{date}');</script></td>
    </tr>
   	<tr class="hide {display_gallerycat}">
        <td class="textLabel">{field_gallery_name} <br /> {size_gallery}</td>
    	<td>
              <table width="75%" border="0" cellspacing="2" id="table-gallery" cellpadding="2" class="table_list list table-Form1 marginTop5">
               <tr>
                  <th class="th-checkbox firstColumn"><input title="Select all" type="checkbox" name="checkbox" onclick="checkAll('tbody.gallery',this,'del_gallery');" value="checkbox" /></th>
                  <th width="76%" class="th-name">{lang.name}</th>
                  <th width="9%" align="center" class="th-status">{lang.status}</th>
                  <th width="11%" align="center" class="th-status lastColumn">{lang.actions}</th>
                </tr>
                <tbody class="gallery">
                 <!--BASIC gallery-->
                <tr id="{gallery.id}">
                  <td class="th-checkbox"><input type="checkbox" name="del_gallery[]" value="{gallery.id}" /></td>
                  <td> {gallery.name}<a href="{_UPLOAD}{gallery.image}" class="mb">{gallery.image}</a></td>
                  <td  align="center"><a href="?mod=gallery&act=active&p={module}&pid={id}&parentid={parentid}&type={type}&id={gallery.id}"><img src="images/icons_default/status{gallery.active}.gif" alt="Status"  /></a></td>
                  <td align="center"><a href="?mod=gallery&act=update&p={module}&pid={id}&parentid={parentid}&type={type}&id={gallery.id}"><img src="images/icons_default/edit{ucp.new}.png" border="0" /></a> <a href="?mod=gallery&act=delete&p={module}&pid={id}&parentid={parentid}&type={type}&id={gallery.id}" onclick="deleteConfirm(this); return false;"><img src="images/icons_default/delete{ucp.delete}.png" border="0" /></a></td>
                </tr>
                <!--BASIC gallery-->
            </tbody>
              </table>
            <div >
              <input type="button" name="Submit2" value="{lang.delete_selected_image}" class="btn" onclick="deleteGallery('#table-gallery',{type})" />
            </div>
            <p>&nbsp;</p>
            <p class="link-addMore"><a href="#" onclick="add_gallery('#add_gallery','{is_gallery_name}','{is_gallery_icon}','{size_gallery_icon}','{size_gallery_image}');return false">{lang.insert_more}</a></p>
                <div class="gallery-inputFile">
                    <p class="hide {display_gallery_name}">
                        <label>{lang.title}</label>
                        <input name="name_gallery0" type="text" value="" title="" />						
                    </p>
                    <p class="hide {display_gallery_icon}">
                        <label>{lang.icon}</label>
                        <input type="file" name="icon_gallery0" size="25" />
                        {size_gallery_icon}
                    </p>
                    <p>
                        <label>Upload Image</label>
                        <input type="file" name="image_gallery0" size="25" />
                        {size_gallery_image}
                        <!--multiple-->
                    </p>
                </div>
                  <p id="add_gallery"></p>
                  
                  <!--<p>
                    <span class="hide" {display_gallery_name}>Title <input name="name_gallery0" type="text" value="" title="" /><br /></span><span class="hide" {display_gallery_icon}>Icon <input type="file" name="icon_gallery0" class="no_width" /> </span>Image <input type="file" name="image_gallery0" class="no_width" /></p>
                  
            <a href="#" onclick="add_gallery('#add_gallery','{is_gallery_name}','{is_gallery_icon}');return false"><img src="images/plus.jpg" align="absmiddle" /> Insert more</a>-->

		
		</td>
    </tr>
  </table>
  <div class="table_list {hide_no_languages}" align="right">
	<input type="submit" class="btn" name="Submit" value="Save">
	<input type="button" class="btn btn_cancel" name="" value="Cancel" onclick="location.href='{http_referer}';">
  </div>
</form></div>
<script type="text/javascript">
	var opt = {
		required: [{category_required}],
		required: [{required_ln_fields}],
		lang: '{language}'
	};
	if({access_action} == '') opt['disabled'] = '{lang.user_no_access}';
	$('form#fcontent').validate(opt);

</script>
