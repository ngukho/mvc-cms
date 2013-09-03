$(document).ready(function(){

	
/// Overall ////////////////////////////////////////////////	
	
	/* Show call action Edit/Delete */
	$('.callAction').hover(
		function(){
			$(this).find('.callAction_hide').show();
		},
		function(){
			$(this).find('.callAction_hide').hide();
		}
	);
	
	
	/* Show call actions in List */
	$('.table_list tr').hover(
		function(){
			$(this).addClass('tr_hover').find('.th-actions').show();
		},
		function(){
			$(this).removeClass('tr_hover').find('.th-actions').hide();
		}
	);
	
	// side menu
	$('.side .menu > a').hover(function(){
		$('.side .submenu').hide();	
		$(this).parent().find('.submenu').show();	
		$('.side .menu > a').removeClass('active');
		$(this).addClass('active');
	});
	$('.main').hover(function(){
		$('.side .submenu').hide();						  
	});

	
	
	
	
})

