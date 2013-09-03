function ga_login(){
	$('#analytic').html('Login....');
	$.post('?mod=analytic&act=login',{},function(data){
		if(data=='1'){
			$('#analytic').html('Loading...');
			$.post('?mod=analytic&act=info',{info: true},function(data){
				$('#analytic').html(data);
			});
			
		}else{
			$('#analytic').html('We cannt login with your account');
		}
	});
}
function ga_account(){
	$.post('?mod=analytic&act=account',{info: true},function(data){
		$('#analytic').html(data);
	});
}
