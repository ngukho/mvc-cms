<div class="top_callFunc_member">
<div class="p-left">
Table : <select name="table" id="table" onchange="addTable()">
<!--BASIC listTable-->
	<option value="{listTable.TABLE_NAME}">{listTable.TABLE_NAME}</option>
<!--BASIC listTable-->
</select>
</div>
<div class="p-right">
List Search :
<select name="strsql" id="strsql" onchange="appendsql()">
<option value="0">Select</option>
<!--BASIC listSql-->
	<option value="{listSql.sql}">{listSql.name}</option>
<!--BASIC listSql-->
</select>
</div>
</div><br />
<div class="loading"></div>
<div class="error">{msg}</div>
<form id="form2" name="form2" method="post" action="?mod=export&act=export">
    <textarea name="inputsql" id="inputsql" style="width:600px;height:130px;" ></textarea><br /><br />
    <input type="button" class="btn" name="submit" id="submit" onclick="Select();" style="height: 26px; width: 70px;" value="submit" />
    <input type="submit" class="btn" name="btnexport" id="btnexport" style="height: 26px; width: 70px;" value="Export" />
</form>
<br />
<div id="output"></div>


