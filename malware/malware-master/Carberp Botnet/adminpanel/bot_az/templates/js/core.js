var comment = new Array();
var note = new Array();

function banner_mouse(mode) {
	var banner= document.getElementById('banner_line');
	if(mode == true){
		banner.style.filter = 'alpha(opacity=100)';
		banner.style.opacity = '1';
	}else{
		banner.style.filter = 'alpha(opacity=50)';
		banner.style.opacity = '0.5';
	}
}

function get_array_json(obj, sub){
	var return_var = '';
	var return_sub = '';
	var value = '';
	var first = false;
	for (var key in obj) {
    	switch (typeof obj[key]) {
    		case 'string':
            	value = obj[key].replace('*','');
            	if(key != '' && value != ''){
            		if(return_var != '') if (!return_var.match('~(,|{)$~')) return_var += ',';
            		return_var += '"'+key+'":'+'"'+escape(value)+'"';
            	}
    		break;

    		case 'boolean':
            	if(key != '' && obj[key] != ''){
            		if(return_var != '') if (!return_var.match('~(,|{)$~')) return_var += ',';
            		return_var += '"'+key+'":'+'"'+ escape(obj[key])+'"';
            	}
    		break;

    		case 'object':
				if(sub <= 0){
					return_sub = '';
	           		return_sub = get_array_json(obj[key], sub+1);
	           		if(return_sub != ''){
	           			if(return_var != '') if (!return_var.match('~(,|{)$~')) return_var += ',';
	           			return_var += '"' + key + '":' + return_sub;
	           		}
	           		first = false;
            	}
    		break;
    	}
    }
    if(return_var != '') return_var = '{' + return_var + '}';
    return return_var;
}

function numbersonly(e){
	var key;
  	var keychar;

	if (window.event) key = window.event.keyCode; else if (e) key = e.which; else return true;
	keychar = String.fromCharCode(key);

	if (key == 8 || key == 0) return true; else
	if ((("0123456789").indexOf(keychar) > -1)) return true; else return false;
}

function get_hax (options) {
	if (!options) options = {};
	if(options.nohistory == null) options.nohistory = true;
	if(options.destroy == null) options.destroy = true;
	if(options.nocache == null) options.nocache = true;
	if(options.rc == null) options.rc = true;
	if(options.method == null) options.method = 'get';
	if(options.id == null) options.id = 'content';
	hax(options);
	if(options.id){
		if(options.indicator == null) options.indicator = '<br /><br /><div align="center"><img src="/images/indicator.gif" alt="'+lang['load']+'" /></div><br /><br />';
		document.getElementById(options.id).innerHTML = options.indicator;
	}
	options = false;
	return false;
}

function checklen(t, max_length) {
	if (t.value.length>max_length) {
		alert(lang['ndps'] + ': ' + max_length);
		return(false);
	}else{
		return(true);
	}
}

function edit_comment(td, form_name, type){
	var id = td.id.replace('cg_', '');
	if(!comment[id]){
		var text = td.innerHTML;
		text = text.replace(/<br>/g, '\n');
		text = text.replace(/<\/?[^>]+>/g, '');
		td.innerHTML = '<textarea name="text_'+id+'" cols="1" rows="1" style="width:99%; height: 60px;" onBlur="save_comment(this, '+form_name+', \''+type+'\');">'+text+'</textarea>';
    	document.forms[form_name].elements['text_' + id].focus();
		comment[id] = true;
    }
}

function save_comment(object, form_name, type){
    var id = object.name.replace('text_', '');
	object.parentNode.style.cursor = '';
	object.name = 'text';
	get_hax({
		url: '/bots/save_comment.html?str='+id+'&x='+type+'&ajax=1',
		method: 'post',
		form: form_name,
		id: 'cg_' + id,
		onload: function(){comment[id] = false;},
		indicator: '<div align="center"><img src="/images/loading.gif" alt="'+lang['load']+'" /></div>',
	});
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

function setinfo(type, id, rand, uid){
	if(document.getElementById('bot' + id + '_content')){
		var sid = 'bot' + id + '_content';
	}else if(document.getElementById('bot' + uid + '_content')){
		var sid = 'bot' + uid + '_content';
	}
	
	get_hax({
		url: '/bots/bot-'+id+'.html?x='+type+'&window=1&y=' + rand,
		method: 'get',
		id: sid,
		indicator: '<div align="center"><img src="/images/loading.gif" alt="'+lang['load']+'" /></div>',
	});
}

function edit_note(td, form_name){
	var id=td.id.split('_');
	if(!note[id[1]]){
		var text = td.innerHTML;
		text = text.replace(/<br>/g, '\n');
		text = text.replace(/<\/?[^>]+>/g, '');
		td.innerHTML = '<textarea name="text_'+id[1]+'" cols="1" rows="1" style="width:99%; height: 60px;" onBlur="save_note(this, '+form_name+');">'+text+'</textarea>';
    	document.forms[form_name].elements['text_' + id[1]].focus();
		note[id] = true;
    }
}

function save_note(object, form_name){
	var id=object.name.split('_');
	object.name = 'text';
	get_hax({
		url: '/bots/bot.html?id='+id[1]+'&x=save_note&ajax=1',
		method: 'post',
		form: form_name,
		id: 'cn_' + id[1],
		onload: function(str){note[id[1]] = false; if(str['html'][0] == ' ') document.getElementById(str['id']).innerHTML = ''; },
		indicator: '<div align="center"><img src="/images/loading.gif" alt="'+lang['load']+'" /></div>',
	});
}

function edit_percent(bot, id){
	get_hax({
		url: '/bots/bot.html?str='+bot+'&x=edit_percent&z='+id+'&ajax=1',
		method: 'get',
		id: id,
		indicator: '<div align="center"><img src="/images/loading.gif" alt="'+lang['load']+'" /></div>',
	});
}

function edit_percent_save(bot, id){
	get_hax({
		url: '/bots/bot.html?str='+bot+'&x=edit_percent&ajax=1',
		method: 'post',
		//form: 'groups' + id,
		params: 'system_percent=' + document.forms['groups'+id].elements['epi'+id].value,
		id: 'ep' + id,
		indicator: '<div align="center"><img src="/images/loading.gif" alt="'+lang['load']+'" /></div>',
	});
}



function gbw(id){
	get_window('/bots/bot-'+id+'.html?window=1', {name:'bot'+id, widht: 800, height: 600});
}

function print_r(arr, level) {
    var print_red_text = "";
    if(!level) level = 0;
    var level_padding = "";
    for(var j=0; j<level+1; j++) level_padding += "    ";
    if(typeof(arr) == 'object') {
        for(var item in arr) {
            var value = arr[item];
            if(typeof(value) == 'object') {
                print_red_text += level_padding + "'" + item + "' :\n";
                print_red_text += print_r(value,level+1);
		} 
            else 
                print_red_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
        }
    } 

    else  print_red_text = "===>"+arr+"<===("+typeof(arr)+")";
    return print_red_text;
}
