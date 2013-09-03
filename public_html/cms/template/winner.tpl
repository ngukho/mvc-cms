<div class="top_callFunc">
	<a href="?mod=winner&act=update&do=new" class="btn l">{lang.new}</a>
</div>

<div class="error hide">{msg}</div>
<div class="form">

		<table width="100%" border="0" cellspacing="0" cellpadding="2" class="table_list list marginTop5" id="table-list">
  <tr>
  	<th width="2%" class="th-name firstColumn">&nbsp;&nbsp;Week</th>
  	<th width="20%" class="th-name">Người gửi</th>
    <th width="47%" class="th-name">Bài Viết</th>    
    <th width="20%" class="th-name">Giải Thưởng</th> 
     <th width="20%" class="th-name">Chức Năng</th>  
  </tr>
  <!--BASIC listwinner-->
  <tr>
  	<td class="th-name firstColumn">{listwinner.prize_week}</td>
    <td class="th-name">&nbsp;&nbsp;{listwinner.username}</td>
    <td class="th-name"><a href="?mod=member_gallery&act=detail&id={listwinner.contest_id}&enabled=1">{listwinner.contest_name}</a></td>    
    <td class="th-name">{listwinner.contest_prize}</td>
     <td class="th-action lastColumn"><a href="?mod=winner&act=update&id={listwinner.id}"><img src="images/icons_default/edit{ucp.edit}.png" width="16" height="16" border="0" /></a></td>
  </tr>
  <!--BASIC listwinner-->
</table>
</div>