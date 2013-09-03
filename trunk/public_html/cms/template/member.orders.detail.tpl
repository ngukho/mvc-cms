<div class="top_callFunc"> 

<a href="{user.back}" class="btn r">< Back</a> 
<!--<a href="{user.back}" class="btn r">Print</a> -->

</div>
<style type="text/css">
.readonly{ 	
	background: buttonface;
	
 }
</style>
	<h2>Đơn đặt hàng {user.clienttype}</h2>
    <hr  />
	<table width="100%" border="0" cellspacing="0" cellpadding="1" class="table-Form1">
    	<tr>
			<td class="textLabel" style="width:400px;">Mã đơn hàng</td>
			<td><strong>{user.orderID}</strong></td>
		</tr>	
		<tr>
			<td class="textLabel">Ngày đặt hàng</td>
			<td>{user.timestamp}</td>
		</tr>		
		<tr>
			<td class="textLabel">Ngày giao hàng</td>
			<td>{user.shippingdate}</td>
		</tr>	
         <tr>
            <td class="textLabel">Số tiền tạm ứng</td>
            <td>{user.sotiendatamung}</td>
        </tr>
        <tr>
            <td class="textLabel">Số tiền còn lại</td>
            <td>{user.sotienconlai}</td>
        </tr>
        
    </table>
     <br  />
    <frameset>
    	<legend><h2>Thông tin người Nhận Hàng</h2></legend>
        <br  />
        <table width="100%" border="0" cellspacing="0" cellpadding="1" class="table-Form1" style="padding-left:10px;">		
            <tr>
                <td class="textLabel">Tên người nhận</td>
                <td>{user.fullname}</td>
            </tr>
            <tr>
                <td class="textLabel">Số điện thoại</td>
                <td>{user.tel}</td>
            </tr>
            <tr>
                <td class="textLabel">Địa chỉ giao hàng.</td>
                <td>{user.address}, {user.district}, {user.city}</td>
            </tr>           
            <tr>
                <td class="textLabel">Hình thức thanh toán</td>
                <td>{user.paymenttype}</td>
            </tr>	            
            <tr>
                <td class="textLabel">Ghi Chú</td>
                <td>{user.message}</td>
            </tr>	
  		</table>		
    </frameset>  
     <br  /> 
    <frameset>
    	<legend><h2>Thông tin người đặt Hàng</h2></legend>
        <br  />
         <table width="100%" border="0" cellspacing="0" cellpadding="1" class="table-Form1" style="padding-left:10px;">		
            <tr>
                <td class="textLabel">Họ Tên</td>
                <td>{user.nguoidathang}</td>
            </tr>
            <tr>
                <td class="textLabel">Số điện thoại</td>
                <td>{user.tel_regis}</td>
            </tr>
            <tr>
                <td class="textLabel">Email</td>
                <td>{user.email}</td>
            </tr>
            <tr>
                <td class="textLabel">Địa chỉ đăng ký.</td>
                <td>{user.address_regis}, {user.district_regis}, {user.city_regis}</td>
            </tr>
             <tr>
                <td class="textLabel">Mã số thuế</td>
                <td>{user.taxcode}</label></td>
            </tr>          
  		</table>	
   </frameset> 
   <form method="post" action="#" enctype="multipart/form-data" id="postdata" onsubmit="return checkformconfirm(this);">  
  <table  width="100%" border="0" cellspacing="0" cellpadding="1" class="table-Form1">			
        <tr>
        	<td colspan="2">&nbsp;</td>
        </tr>
        <tr>
        	<td class="textLabel">Trạng thái</td>
        	<td>            	              	
	            	<select name="payment_status" id="payment_status" style="float:left; margin-top:7px;" onchange="changestatus(this);">
                    	<option value="0" {user.active0}>Đang chờ xác nhận.</option>
                        <option value="1" {user.active1}>Đang chờ thanh toán.</option>
                        <option value="2" {user.active2}>Đang chờ giao hàng.</option>
                        <option value="3" {user.active3}>Đã được giao hàng.</option>
                        <option value="4" {user.active3}>Đã Tạm ứng.</option>
                        
    	            </select>
                   <div id="showtientamung" style="float:left; margin-top:7px; padding-left:10px; display:none">Số Tiền Tạm ứng: <input type="text" name="sotientamung" id="sotientamung" /></div>                   

                  
            </td>
        </tr>    
        <tr>
        	<td class="textLabel">Ngày xác nhận</td>
            <td>
            	 <script type="text/javascript">DateInput('date', true, 'YYYY-MM-DD','{datenow}');</script>
                 <div class="sending" style="font-weight:bold; color:#0033FF; display:none">Thông tin đang được gửi đi. Vui lòng chờ trong giây lát</div>
                 <input type="submit" name="submit" value="Xác nhận" class="btn" />                                  
            </td>
        </tr>    		            
	</table>
    </form>
   <table width="100%" border="0" cellspacing="0" cellpadding="1" class="table-Form1">
   		<tr>
        	 <td><strong>Giỏ quà</strong></td>
             <td><strong>Số Lượng</strong></td>
             <td><strong>Giá (đ)</strong></td>
             <td><strong>Thành tiền (đ)</strong></td>
        </tr>
        <tr>
        	<td colspan="4"><hr  /></td>
        </tr>        
   		 <!--BASIC listorder-->
   		<tr>
        	<td><strong>{listorder.name}</strong></td>
            <td><strong>{listorder.quantity}</strong></td>
            <td><strong>{listorder.price}</strong></td>
            <td><strong>{listorder.total}</strong></td>
        </tr>
        <!--BASIC listorder.sub-->
        <tr>
            <td style="padding-left:10px;">{listorder.sub.name}</td>
            <td>{listorder.sub.quantity}</td>
            <td>{listorder.sub.price}</td>
            <td>{listorder.sub.total}</td>
        </tr>
        <!--BASIC listorder.sub-->
      <!--BASIC listorder-->
       <tr>
        	<td colspan="4"><hr  /></td>
        </tr>  
       <tr>
       	 <td colspan="3"><strong>Tổng cộng (đã gồm VAT)</strong></td>        
         <td><strong>{sub_total}</strong></td>
       </tr>     
       <tr>
       	 <td colspan="3"><strong>Chiết khấu {rate}%</strong></td>        
         <td><strong>{discount}</strong></td>
       </tr> 
       <tr>
       	 <td colspan="3"><strong>Tổng giá trị đơn hàng (đã gồm VAT)</strong></td>        
         <td><strong>{total}</strong></td>
       </tr> 
   </table>
    <script type="text/javascript">
		function changestatus(obj)
		{
			var value = $(obj).val();
			if (value==4){
				$("#showtientamung").show();
				$("#sotientamung").focus();
			}
			else
			{
				$("#showtientamung").hide();
			}
		}
		function checkformconfirm(form){	
			if ($(form.payment_status).val()==4 && $(form.sotientamung).val()<=0){
				alert("Vui lòng nhập số tiển tạm ứng");
				$(form.sotientamung).focus();
				return false;
			}
			else if ($(form.payment_status).val()==4 && !isNaN($(form.phone_cn).val())){
				alert("Số tiển tạm ứng bạn nhập không hợp lệ.");
				$(form.sotientamung).focus();
				return false;
			}			
			if ($(form.payment_status).val()==""){
				alert("Vui lòng nhập trạng thái các nhận");
				$(form.codeinput).focus();
				return false;
			}
			var param = $(form).serialize();
			$(form.submit).hide();
			$(".sending").show();
			$.post("?mod={module}&act=updatestatus&id={user.id}",param, function(result){
				alert(result.message);	
				$(".sending").hide();	
				$(form.submit).show();		
			}, "json");
			return false;
		}
	</script>
