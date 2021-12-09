


function delete_domain(id){
	if(confirm(lang['dely']))
	get_hax({
			url: '/autosys/domains_del.html?ajax=1&id='+id,
			method: 'get',
	});
}

function delete_build(id){
	if(confirm(lang['dely']))
	get_hax({
			url: '/autosys/builds_del.html?ajax=1&id='+id,
			method: 'get',
	});
}

function edit_build(id){
	if(document.forms['upl'].elements['type'].value != '0'){
		document.getElementById('bt'+document.forms['upl'].elements['type'].value).style.background = '';
		document.forms['upl'].elements['type'].value = '0';
	}
	
	document.getElementById('nfo').innerHTML = 'Edit #' + id;
	document.forms['upl'].elements['type'].value = id;
	document.getElementById('bt'+id).style.background = 'url(/images/body-bg.gif)';
	
	return false;;
}

function ecd(td, form_name){
	var id = td.id.replace('cg_', '');
	if(!comment[id]){
		var text = td.innerHTML;
		text = text.replace(/<br>/g, '\n');
		text = text.replace(/<\/?[^>]+>/g, '');
		td.innerHTML = '<textarea name="text_'+id+'" cols="1" rows="1" style="width:99%; height: 60px;" onBlur="scd(this, '+form_name+');">'+text+'</textarea>';
    	document.forms[form_name].elements['text_' + id].focus();
		comment[id] = true;
    }
}

function scd(object, form_name){
    var id = object.name.replace('text_', '');
	object.parentNode.style.cursor = '';
	object.name = 'text';
	get_hax({
		url: '/autosys/domains.html?str=set_comment&id='+id+'&ajax=1',
		method: 'post',
		form: form_name,
		id: 'cg_' + id,
		onload: function(){comment[id] = false;},
		indicator: '<div align="center"><img src="/images/loading.gif" alt="'+lang['load']+'" /></div>',
	});
}