// JavaScript Document

	window.onload = function(){
		document.form1.username.focus();
	}


function checkLogin(doc){
	if(doc.username.value==''){alert('Please enter Username');doc.username.focus();return false;}
	if(doc.password.value==''){alert('Please enter Password');doc.password.focus();return false;}
}