<div class="top_callFunc">	
	<a href="{http_referer}" class="btn r">< Back</a>
</div>
<style type="text/css">
	.template a{ color:#CCCCCC;}
	.template .active{ font-weight:bold; color:#0000CC; padding:0px 10px}
</style>
<!--BOX sented-->
<form action="" method="post" enctype="multipart/form-data" name="form1">
  <table width="100%" border="0" cellspacing="0" cellpadding="1" class="table-Form1">
  	<tr>
    	<td class="textLabel"></td>
        <td><strong>{err.message}</strong></td>
    </tr>
  	<tr>
    	<td class="textLabel">Template email</td>
        <td class="template">
       	 <!--BASIC listtemplate-->    	
         <a  {listtemplate.active} href="?mod=newsletter&act=sendnewsletter&id={listtemplate.id}">{listtemplate.name}</a> | 
          <!--BASIC listtemplate--> 
        </td>
      <td>
    </tr>
    <tr>
      <td class="textLabel">Customer</td>
      <td>
      <select id="customers_email_address" name="customers_email_address" onchange="listemail_newsletter(this)">
      		<option selected="selected" value="">Select Customer</option>
            <option value="1">All Customers</option>
            <!--BASIC listobj-->
            <option value="{listobj.id}">{listobj.name}</option>
            <!--BASIC listobj-->
            <!--BASIC listemail-->                                    
            <option value="{listemail.email}">{listemail.name} ({listemail.email})</option>
            <!--BASIC listemail-->            
		</select>
    </td>
    </tr>
    <tr class="tolistemail h">
      <td class="textLabel">To</td>
      <td> <select id="customers_email_address_to" name="customers_email_address_to[]" multiple="multiple" size="7">      	
      </select></td>
    </tr>  
    <tr>
      <td class="textLabel">From</td>
      <td><input name="from" type="text" id="username" value="{from}"></td>
    </tr>  
     <tr>
      <td class="textLabel">Subject</td>
      <td><input name="subject" value="Bellavita - Message from a site Bellavita" type="text" id="username" value="{subject}"></td>
    </tr>  
    <tr>
      <td class="textLabel">Message</td>
      <td>
      	<textarea name="messages" id="messages" rows="5" class="tinymce">{detail.content}</textarea>
        <p>Note: [fullname], [name],</blockquote> </p>
      </td>
    </tr>   
	<tr>
      <td class="textLabel">&nbsp;</td>
      <td><br />
	  	<input type="submit" class="btn" name="Submit" value="Submit">
		<input type="button" class="btn btn_cancel" name="" value="Cancel" onclick="location.href='{http_referer}';">
      </td>
    </tr>
  </table>
</form>
<!--BOX sented-->