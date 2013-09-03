$(document).ready(function(){

	
/// Overall ////////////////////////////////////////////////	
/*	$('div').each( function(){
		if ($(this).html() == '') $(this).hide();
	});
*/	
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
	
	
/// Left Menu //////////////////////////////////////////////
	$('.side .menu h2').toggle(function(){
		$(this).parent().find('a').stop(true, true).animate({
			height: 'toggle'
		},250);							   
	},function(){
		$(this).parent().find('a').stop(true, true).animate({
			height: 'toggle'
		},250);	
	})
/*	$('.menu h2.menu-expanded, .menu h2.menu-collapsed').hover(
		function(){
			$(this).css({backgroundPosition:'150px center'})
		},
		function(){
			$(this).css({backgroundPosition:'-150px center'})
		}
	);
	
	$('.menu-des, .menu-desSub').click(function(){
		$('.side').animate({ marginLeft:'-400px', opacity:'0'}, 200);
		$('.ct').animate({ marginLeft:'15px'}, 200);
		$('.btn_expandMenu').addClass('btn_expandMenu_active');
	});
	
	$('.btn_expandMenu_active').live('mouseover',
		function(){
			$(this).css({ backgroundColor:'#0084ff', cursor:'e-resize' });
		}
	);
	$('.btn_expandMenu_active').live('mouseout',
		function(){
			$(this).css({ backgroundColor:'transparent' });
		}
	);
	$('.btn_expandMenu_active').live('click', function(){
		$('.side').animate({ marginLeft:'-200px', opacity:'1'}, 200);
		$('.ct').animate({ marginLeft:'200px'}, 200);
		$('.btn_expandMenu').removeClass('btn_expandMenu_active').css({ backgroundColor:'transparent', cursor:'default' });
	});
	*/
	
	
})

