$(function() {
	tinyMCE.init({		
		// General options
		mode : "specific_textareas",
		editor_selector : "simplemce",
		theme : "advanced",
		plugins : "safari,pagebreak,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount",
		theme_advanced_buttons1 : "bold,italic,underline,formatselect,undo,redo,link,unlink,image,forecolor,removeformat,cleanup,code,imgmanager,fullscreen",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_resizing : true,
		entity_encoding : "raw",
		content_css : "../data/tinymce/css/style.css",
		file_browser_callback : "ajaxfilemanager",
		add_unload_trigger : false,
		remove_linebreaks : false,
		inline_styles : false,
		convert_fonts_to_spans : false,		
		relative_urls : true,
		file_browser_callback: 'openKCFinder',
		// Replace values for the template plugin
	});
	tinyMCE.init({
		// General options
		mode : "specific_textareas",
		editor_selector : "tinymce",
		theme : "advanced",
		height: '330',
		plugins : "tabfocus,safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
		file_browser_callback : "openKCFinder",
		tab_focus : ':prev,:next',
		add_unload_trigger : false,
		relative_urls : true,
		// Example content CSS (should be your site CSS)
		content_css : "../data/tinymce/css/style.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "../data/tinymce/template_list.js",
		external_link_list_url : "../data/tinymce/link_list.js",
		external_image_list_url : "../data/tinymce/image_list.js",
		media_external_list_url : "../data/tinymce/media_list.js"
	});
});	
function openKCFinder(field_name, url, type, win) {	
	tinyMCE.activeEditor.windowManager.open({
		file: '../plugins/tinymce/kcfinder/browse.php?opener=tinymce&type=' + type,
		title: 'KCFinder',
		width: 800,
		height: 500,
		resizable: "yes",
		inline: true,
		close_previous: "no",
		popup_css: false
	}, {
		window: win,
		input: field_name
	});
	return false;
}