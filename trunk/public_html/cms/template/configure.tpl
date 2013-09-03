<!--BOX root_admin-->

<h3>Database</h3>
<div class="top_callFunc">
	<a href="?mod=configure&act=clear_data" onclick="return window.confirm('Are you sure you want to clear all data ?');" class="btn l">Clear all data</a>
	<a href="?mod=configure&act=clear_configure" onclick="return window.confirm('Are you sure you want to clear all configure ?');" class="btn l">Clear all configure</a>
	<a href="?mod=configure&act=clear_file" onclick="return window.confirm('Are you sure you want to clear all upload files ?');" class="btn l">Clear upload files</a>
</div>

<p class="sep">&nbsp;</p>

<div class="top_callFunc">
	<h3>Configure Content</h3>
	<a href="#" onclick="newConfigure('content');return false;" title="New" class="btn l">New</a>
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="table_list marginTop5">
  <tr>
    <th class="th-action firstColumn">Type Id </th>
    <th class="th-name">Name</th>
    <th class="th-action lastColumn">Actions</th>
  </tr>
  <!--BASIC cfg_module-->
  <tr>
    <td class="th-action firstColumn"> #{cfg_module.typeid}</td>
    <td class="th-name">{cfg_module.attr}{cfg_module.name}</td>
    <td class="th-action lastColumn">
		<a href="#" onclick="copyConfigure('{cfg_module.module}',{cfg_module.typeid});return false;"><img src="images/icons_default/copy.gif" title="Copy configure" alt="" /></a> 
		<a href="?mod=content&amp;act=empty&amp;type={cfg_module.typeid}" class="divbox"><img src="images/icons_default/clear.gif" title="Clear data" alt="" /></a> 
		<a href="?mod={module}&amp;act=module&amp;module={cfg_module.module}&amp;typeid={cfg_module.typeid}"><img src="images/icons_default/edit.png" width="16" height="16" border="0" /></a>
	</td>
  </tr>
  <!--BASIC cfg_module-->
</table>

<p class="sep">&nbsp;</p>

<div class="top_callFunc">
	<h3>Configure HTML </h3>
	<a href="#" onclick="newConfigure('html');return false;" title="New" class="btn l">New</a>
</div>

<table width="100%" border="0" cellspacing="0" cellpadding="2" class="table_list marginTop5">
  <tr>
    <th class="th-action firstColumn">Page Id </th>
    <th class="th-name">Name</th>
    <th class="th-action lastColumn">Actions</th>
  </tr>
  <!--BASIC cfg_html-->
  <tr>
    <td class="th-action firstColumn"> #{cfg_html.typeid}</td>
    <td class="th-name">{cfg_html.name}</td>
    <td class="th-action lastColumn">
		<a href="#" onclick="copyConfigure('{cfg_html.module}',{cfg_html.typeid});return false;"><img src="images/icons_default/copy.gif" title="Copy configure" alt="" /></a> 
		<a href="?mod=html&amp;act=empty&amp;id={options.typeid}" onclick="deleteConfirm(this); return false;" ><img src="images/icons_default/clear.gif" title="Clear data" alt="" /></a> 
		<a href="?mod={module}&amp;act=module&amp;module={cfg_html.module}&amp;typeid={cfg_html.typeid}"><img src="images/icons_default/edit.png" width="16" height="16" border="0" /></a>{cfg_html.plus}</td>
  </tr>
  <!--BASIC cfg_html-->
</table>

<p class="sep">&nbsp;</p>

<div class="top_callFunc">
	<h3>Configure Options </h3>
	<a href="#" onclick="newConfigure('options');return false;" title="New" class="btn l">New</a>
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="table_list marginTop5">
  <tr>
    <th class="th-action firstColumn">Options Id </th>
    <th class="th-name">Name</th>
    <th class="th-action lastColumn">Actions</th>
  </tr>
  <!--BASIC options-->
  <tr>
    <td class="th-action firstColumn">#{options.typeid}</td>
    <td class="th-name">{options.name}</td>
    <td class="th-action lastColumn">
	<a href="#" onclick="copyConfigure('{options.module}',{options.typeid});return false;"><img src="images/icons_default/copy.gif" title="Copy configure" alt="" /></a> 
		<a href="?mod=options&amp;act=empty&amp;type={options.typeid}" class="divbox"><img src="images/icons_default/clear.gif" title="Clear data" alt="" /></a> 
		<a href="?mod={module}&amp;act=module&amp;module={options.module}&amp;typeid={options.typeid}"><img src="images/icons_default/edit.png" width="16" height="16" border="0" /></a> 
	</td>
  </tr>
  <!--BASIC options-->
</table>

<p class="sep">&nbsp;</p>

<!--BOX root_admin-->
	
 <h3> Configure by: <select name="group" onchange="location.href=this.value">
	  <!--BASIC cfg_group-->
	  <option {cfg_group.selected} value="?mod={module}&amp;gid={cfg_group.id}">{cfg_group.name}</option><!--BASIC cfg_group-->
	  </select></h3>
	 <!--BOX root_admin--> 
<div class="top_callFunc">
	<a href="?mod=configure&amp;act=new" title="New" class="btn l">New</a>
</div>
	 <!--BOX root_admin-->
	 <p>&nbsp;</p>
<div class="form">
<form method="post" name="formconfigure">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table_list marginTop5">
    <tr>
      <th class="th-name firstColumn" width="30%">{lang.name}</th>
	  <th class="th-name" width="70%">{lang.value}</th>
    </tr>
    <!--BASIC cfg_common-->
    <tr>
      <td class="th-name ">{cfg_common.name}</td>
      <td class="th-name">{cfg_common.value}</td>
    </tr>
    <!--BASIC cfg_common-->
    <tr>
    	<td colspan="2"><input type="submit" class="btn r" name="updateconfirgure" value="Update" /></td>
    </tr>
  </table>
 </form>
</div>
<script type="text/javascript">
	$('a.divbox').divbox({type: 'ajax',width: 600,height: 300, caption: false,btn_closed:'#divbox_frame .closed,#divbox_frame .btn_closed'});
</script>