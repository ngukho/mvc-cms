<div class="top_callFunc">
	<a href="?mod={module}&amp;act=backup&amp;do=new" title="New" class="btn l">{lang.new}</a>
</div>
<table width="100%" border="0" cellspacing="0" id="table-list" cellpadding="2" class="table_list marginTop5">
  <tr>
    <th class="th-name firstColumn">{lang.name}</th>
    <th class="th-date">{lang.date}</th>
    <th class="th-default">{lang.file}</th>
    <th class="th-action lastColumn">{lang.actions}</th>
  </tr>
  <tbody class="pro">
  <!--BASIC files-->
  <tr id="{simple.id}pro">
    <td class="th-name firstColumn">{files.name}</td>
    <td class="th-date">{files.date}</td>
    <td class="th-default">{files.size} KB </td>
    <td class="th-action lastColumn"><span><a href="?mod={module}&act={action}&do=download&file={files.name}" title="Download"><img src="images/icons_default/down.gif" width="16" height="16" /></a></span> <span><a href="?mod={module}&amp;act={action}&amp;do=delete&amp;file={files.name}&amp;type={type}" onClick="deleteConfirm(this); return false;"><img src="images/icons_default/delete.png" width="16" height="16" /></a></span></td>
  </tr>
  <!--BASIC files-->
  </tbody>
</table>
<br />
<p class="description">{lang.backup_folder}: {_BACKUP}</p>

