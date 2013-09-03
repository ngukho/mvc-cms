<div class="top_callFunc">
	<a href="{http_referer}" class="btn r">&lt; {lang.back}</a>
</div>
<div class="form"><form action="" method="post" id="fcontent" enctype="multipart/form-data" name="fcontent">

<!--BOX language_tab-->
	<div class="language_tab">
	<!--BASIC language-->
		<a href="#" onclick="showLangTab('{language.ln}',this);" {language.tab_default}>{language.ln_name}</a>
	<!--BASIC language-->
	</div>
<!--BOX language_tab-->

<div class="error">{msg}</div>
  
<table width="100%" border="0" cellspacing="0" cellpadding="1" class="table-Form1">
<!--BASIC language-->
<tbody class="{language.default_ln} tab_language" id="tab_{language.ln}">
    <tr>
      <td class="textLabel">Name</td>
      <td><input type="text" name="name[{language.ln}]" value="{language.name}" title="Vui long nhap Name" ></td>
    </tr>
    <tr>
      <td class="textLabel">Module name(url) <span class="description">{language.ln_alias}</span></td>
      <td><input name="module_name[{language.ln}]" type="text" id="module_name[{language.ln}]" title="Vui long nhap Name" value="{language.module_name}" ></td>
    </tr>
    <tr>
      <td class="textLabel">Meta title {language.ln_alias}</td>
      <td><input name="web_title[{language.ln}]" type="text" id="web_title[{language.ln}]" title="Vui long nhap Name" value="{language.web_title}" ></td>
    </tr>
    <tr>
      <td class="textLabel">Meta keywords  {language.ln_alias}</td>
      <td><textarea name="web_keyword[{language.ln}]" rows="5" id="web_keyword[{language.ln}]" title="Vui long nhap Intro">{language.web_keyword}</textarea></td>
    </tr>
    <tr>
      <td class="textLabel">Meta description  {language.ln_alias} </td>
      <td><textarea name="web_desc[{language.ln}]" rows="5" id="web_desc[{language.ln}]">{language.web_desc}</textarea></td>
    </tr>
    <tr>
      <td class="textLabel">Meta extra {language.ln_alias} </td>
      <td><textarea name="meta_extra[{language.ln}]" rows="5" id="meta_extra[{language.ln}]">{language.meta_extra}</textarea></td>
    </tr>
    <tr>
      <td class="textLabel">Action Translate  {language.ln_alias} </td>
      <td><textarea name="module_actions[{language.ln}]" rows="5" id="module_actions[{language.ln}]">{language.module_actions}</textarea></td>
    </tr>
    
	</tbody>
<!--BASIC language-->
  </table>

<hr />

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table-Form1 {hide_no_language}">
    <tr>
      <td class="textLabel">Module Folder </td>
      <td><input name="module" type="text" id="module" value="{module}" /></td>
    </tr>
	<tr>
      <td class="textLabel">TypeID or Module ID</td>
      <td><input name="typeid" type="text" id="typeid" value="{typeid}" /></td>
    </tr>
	
	<tr>
      <td class="textLabel">&nbsp;</td>
    	<td align="right">
			<input type="submit" class="btn" name="Submit" value="{lang.save}">
			<input type="button" class="btn btn_cancel" name="" value="{lang.cancel}" onClick="location.href='{http_referer}';">		</td>
    </tr>
  </table>

  <!--<div class="table-Form1 {hide_no_language}">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="79%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr class="hide" {display_gallery}>
            <td align="right"><span class="hide"  {display_update}><select name="back" id="back">
        <option value="1" {back_list_1}>Go back to list</option>
        <option value="0" {back_list_0}>Keep me here</option>
        </select></span></td>
          </tr>
        </table></td>
        <td width="21%" align="right"><input type="submit" class="btn" name="Submit" value="&nbsp;"> <input type="button" class="btn_cancel" name="" value="&nbsp;" onClick="location.href='{http_referer}';"></td>
      </tr>
    </table>
    
  </div>-->
  
</form></div>
<script type="text/javascript">
	var opt = {
		required: ['module'],
		lang: '{language}'
	};
	$('form#fcontent').validate(opt);

</script>