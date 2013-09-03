<div class="top_callFunc"><a class="btn r" href="{http_referer}">&lt; {lang.back}</a></div>
<div class="form"><form action="" method="post" id="fcontent" enctype="multipart/form-data" name="form1">
<!--BOX language_tab-->
<div class="language_tab"><!--BASIC language-->
<a href="#" onclick="showLangTab('{language.ln}',this);" {language.tab_default}>{language.ln_name}</a> <!--BASIC language--></div><!--BOX language_tab-->
  <div class="error">{msg}</div>
  <table width="100%" border="0" cellspacing="0" cellpadding="1" class="table-Form1">
<!--BASIC language-->
<tbody class="{language.default_ln} tab_language" id="tab_{language.ln}">
    <tr>
      <td width="16%">&nbsp;</td>
      <td width="84%">&nbsp;</td>
    </tr>
	{language.ln_fields}
    <tr class="hide {display_ln_icon}">
      <td>{ln_field_icon} {language.ln_alias} </td>
      <td><input name="ln_icon[{language.ln}]" class="no_width" type="file" id="ln_icon">
        
        {size_ln_icon}
		<p class="hide"><a href="{_UPLOAD}{language.ln_icon}" class="mb">{language.ln_icon}</a>
	    <input type="checkbox" name="delete_ln_icon[{language.ln}]" class="no_width" value="1" /> 
		<input name="current_ln_icon[{language.ln}]" type="hidden" value="{language.ln_icon}" title="{language.ln_icon}" class="box_delete_file" />
	    {lang.delete}		</p></td>
    </tr>
     <tr class="hide {display_ln_image}">
      <td>{ln_field_image} {language.ln_alias} </td>
      <td><input name="ln_image[{language.ln}]" class="no_width" type="file" id="ln_image">
        
        {size_ln_image}
		<p class="hide"><a href="{_UPLOAD}{language.ln_image}" class="mb">{language.ln_image}</a>
	    <input type="checkbox" name="delete_ln_image[{language.ln}]" class="no_width" value="1" />
		<input name="current_ln_image[{language.ln}]" type="hidden" value="{language.ln_image}" title="{language.ln_image}" class="box_delete_file" />
	    {lang.delete}</p></td>
    </tr>
   <tr class="sep">
      <td></td>
      <td></td>
    </tr></tbody>
<!--BASIC language-->
  </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="1" class="table-Form1 {hide_no_languages}">
    <tr>
      <td width="16%">&nbsp;</td>
      <td width="84%">&nbsp;</td>
	</tr>
	{main_fields}
    
    <!--<tr class="googlemap">
    	<td class="textLabel">Google Map<br  />
        	<a class="button" onclick="showAddress('fcontent');">Get Gmap!</a> 
        </td>
        <td>
	    	<div class="gmap" id="map_canvas" style="height: 400px; width:600px; border:1px solid #CCCCCC">Map is loading.....</div>
    	     <input type="hidden"  id="input_position" name="input_position" value="45.828799,-103.292969" />     
        </td>
    </tr>-->
    <tr class="hide {display_enable_date}">
      <td>{lang.date}</td>
      <td class="input_date"><script type="text/javascript">DateInput('date', true, 'YYYY-MM-DD','{date}');</script></td>
    </tr>
    <tr class="hide {display_icon}">
      <td>{field_icon}</td>
      <td><input name="icon" class="no_width" type="file" id="icon">
        
        {size_icon}
		<p class="hide"><a href="{_UPLOAD}{icon}" class="mb">{icon}</a>
	    <input type="checkbox" name="delete_icon" class="no_width" value="1" />
		<input name="current_icon" type="hidden" value="{icon}" title="{icon}" class="box_delete_file" />
	    {lang.delete}</p></td>
    </tr>
    <tr class="hide {display_image}">
      <td>{field_image}</td>
      <td><input name="image" type="file" id="image" class="no_width">
        {size_image}
		<p class="hide">
		<a href="{_UPLOAD}{image}" class="mb">{image}</a> 
		<input type="checkbox" name="delete_image" class="no_width" value="1"  />
		<input name="current_image" type="hidden" title="{image}" value="{image}" class="box_delete_file" />
		  {lang.delete}</p></td>
    </tr>
    <tr class="hide {display_gallery}">
      <td class="textLabel">{field_gallery_name} <br /> 
        {size_gallery}</td>
      <td><table width="75%" border="0" cellspacing="2" id="table-gallery" cellpadding="2" class="table_list list table-Form1 marginTop5">
	   <tr>
	     <th    class="th-checkbox firstColumn"><input title="Select all" type="checkbox" name="checkbox" onclick="checkAll('tbody.gallery',this,'del_gallery');" value="checkbox" /></th>
          <th width="76%"   class="th-name">{lang.name}</th>
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
  <p>&nbsp;</p>
  <div class="{hide_no_languages}">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="79%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr class="hide {display_gallery}">
            <td align="right"><span class="hide  {display_update}"><select name="back" id="back">
        <option value="1" {back_list_1}>{lang.back_to_list}</option>
        <option value="0" {back_list_0}>{lang.keep_me_here}</option>
        </select></span></td>
          </tr>
        </table></td>
        <td width="21%" align="right"><input type="submit" class="btn" name="Submit" value="{lang.save}"> <input type="button" class="btn btn_cancel" name="" value="{lang.cancel}" onClick="location.href='{http_referer}';"></td>
      </tr>
    </table>
    
  </div>
</form></div>
<script type="text/javascript">
	var opt = {
		required: [{required_fields}],
		required_lang: [{required_ln_fields}],
		lang: '{language}'
	};
	if({access_action} == '') opt['disabled'] = '{lang.user_no_access}';
	$('form#fcontent').validate(opt);

</script>
<!--<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
<script type="text/javascript">				
  google.maps.event.addDomListener(window, 'load', initialize);
</script>
-->