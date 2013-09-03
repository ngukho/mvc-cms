function add_file_extra(){
	var tr = '<tr><td><input type="text" name="file_extra[name][]" /></td><td><input name="file_extra[code][]" type="text" id="file_extra[code][]" value="" size="5" /></td><td><input type="text" name="file_extra[type][]" /></td><td><input type="text" name="file_extra[note][]" /><a href="#" onclick="delete_row(this);return false;"> x </a></td></tr>';
	$('#file_extra').append(tr);
}

function add_fields_extra(){
	var tr = '<tr><td><input type="text" name="fields_extra[name][]" /></td><td><input name="fields_extra[code][]" type="text" id="fields_extra[code][]" value="" size="5" /></td><td><input type="text" name="fields_extra[type][]" /></td><td><input type="text" name="fields_extra[note][]" /><a href="#" onclick="delete_row(this);return false;"> x </a></td></tr>';
	$('#fields_extra').append(tr);
}
function add_ln_fields_extra(){
	var tr = '<tr><td><input type="text" name="ln_fields_extra[name][]" /></td><td><input name="ln_fields_extra[code][]" type="text" id="ln_fields_extra[code][]" value="" size="5" /></td><td><input type="text" name="ln_fields_extra[type][]" /></td><td><input type="text" name="ln_fields_extra[note][]" /><a href="#" onclick="delete_row(this);return false;"> x </a></td></tr>';
	$('#ln_fields_extra').append(tr);
}


function add_status_fields(){
	var tr ='<tr><td><input name="status_fields[name][]" type="text"  value="" /></td><td><input name="status_fields[code][]" type="text"  value="" size="5" /></td><td><input type="text" size="5" value="" id="status_fields[default][]" name="status_fields[default][]"></td><td><input name="status_fields[note][]" type="text"  value="" /><a href="#" onclick="delete_row(this);return false;"> x </a></td></tr>';	
	$('#status_fields').append(tr);
}

function delete_row(o){
	$(o).parent().parent().remove();
}
function newConfigure(mod){
	var name = window.prompt('Input name: ');
	if(name) location.href='?mod=configure&act=module&module='+mod+'&name='+name+'&do=new';
}

function copyConfigure(mod,typeid){
	var from = window.prompt('Input Module Id');
	if(from) location.href='?mod=configure&act=module&module='+mod+'&typeid='+typeid+'&from='+from+'&do=copy';
}

function newHTMLCfg(typeid){
	var c = window.confirm('Are you sure?');
	if(c) location.href='?mod=configure&act=module&typeid='+typeid+'&do=newhtml';
}

function data_attr(sel){
	$('#attr_data,#main_data').hide();
	$(sel).show();
}
function content_empty(type){
	var i = 1;
	$('tr.clear_category').each(function(){
		var obj = this;
		$.post('?mod=category&act=delete&ajax=1&c=1&id='+this.id,{},function(){
			$(obj).remove();	
			i++;
		});
	});
	$('tr.clear_product').each(function(){
		var obj = this;
		$.post('?mod=content&act=delete&ajax=1&c=1&id='+this.id,{},function(){
			$(obj).remove();																 
		});
		i++;
		//alert(i);
	});
}

function options_empty(type){
	var i = 1;
	$('tr.clear_product').each(function(){
		var obj = this;
		$.post('?mod=options&act=delete&ajax=1&c=1&id='+this.id,{},function(){
			$(obj).remove();																 
		});
		i++;
		//alert(i);
	});
}
function add_buttons(){
	$('#add_button').toggle();	
}

function fields_status(obj){
	$('p.info').hide();	
	switch(obj.value){
		case 'status':
			$(obj).parent().find('p.info_'+obj.value).show();
		break;
		case 'selectbox':
		case 'radio':
			$(obj).parent().find('p.info_'+obj.value).css({"position":"absolute","padding-left":"90px"}).show();
		break;
	}	
}