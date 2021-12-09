/*
SRAX.Filter.add({
	url:['/main/', '/projects/', '/media/', '/phorum/', '/accounts/', '/pages/'],
	id:'content',
	changer:function(url, owner){
      return url + '?ajax=1';
    }
});

SRAX.Filter.add({
	url:['/accounts/registration.html', '/accounts/authorization.html'],
	id:'content',
	changer:function(url, owner){
      return url + '?ajax=1';
    }
});
*/
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

function right (id, formname) {
	var reg = new RegExp('^rights\\['+id+'\\]', 'gi');
	for(i=0; i < document.forms[formname].elements.length; i++){
		if(document.forms[formname].elements[i].type=="checkbox"){
			if(id != ''){
               	if(document.forms[formname].elements[i].name.match(reg)){
					if(document.forms[formname].elements[i].checked == true){
						document.forms[formname].elements[i].checked = false;
					}else{
						document.forms[formname].elements[i].checked = true;
					}
				}
			}else{
				if(document.forms[formname].elements[i].checked == true){
					document.forms[formname].elements[i].checked = false;
				}else{
					document.forms[formname].elements[i].checked = true;
				}
			}
		}
	}
}

function check(r){
	if (r.checked == true){
		r.checked = false;
	}else{
		r.checked = true;
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

function save_info(){
	navigator['screen'] = new Array ();
	navigator['screen']['w'] = '' + screen.width;
	navigator['screen']['h'] = '' + screen.height;
	navigator['screen']['c'] = '' + screen.colorDepth;

	return escape(get_array_json(navigator, 0));
}

function numbersonly(e){
	var key;
  	var keychar;

	if (window.event) key = window.event.keyCode; else if (e) key = e.which; else return true;
	keychar = String.fromCharCode(key);

	if (key == 8) return true; else
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
		if(options.indicator == null) options.indicator = '<br /><br /><div align="center"><img src="/images/indicator.gif" alt="Загрузка..." /></div><br /><br />';
		document.getElementById(options.id).innerHTML = options.indicator;
	}
	options = false;
	return false;
}

function put_system_cmd(id, cmd){
	//alert(id + ' - ' + cmd);
	switch(cmd){
		case 'cmd_delete':
			if(confirm('Вы действительно хотите удалить из системы данную админку?') != false){
				get_hax({
					url: '/admins/cmd_delete.html?ajax=1&id='+id,
					method: 'post',
					id: 'content',
					nohistory: true,
					nocache: true,
					destroy: true,
				});
			}
		break;
		
		case 'cmd_edit':
			get_window('/admins/edit.html?id=' + id + '&window=1', {name: 'control'+id, widht: '650', height: '295'});
		break;
		
		case 'cmd_info_uid':
			get_window('/admins/cmd_info_uid.html?id=' + id + '&window=1', {name: 'control'+id, widht: '650', height: '295'});
		break;
		
		case 'cmd_info_country':
			get_window('/admins/cmd_info_country.html?id=' + id + '&window=1', {name: 'control'+id, widht: '800', height: '400'});
		break;
		
		case 'cmd_info_prefix':
			get_window('/admins/cmd_info_prefix.html?id=' + id + '&window=1', {name: 'control'+id, widht: '800', height: '400'});
		break;
		
		case 'cmd_stats_cmd':
			get_window('/admins/cmd_stats_cmd.html?id=' + id + '&window=1', {name: 'control'+id, widht: '1000', height: '400'});
		break;
		
		case 'cmd_create_cmd':
			get_window('/admins/cmd_create_cmd.html?id=' + id + '&window=1', {name: 'control'+id, widht: '900', height: '600'});
		break;
		
		case 'cmd_create_link':
			get_window('/admins/cmd_create_link.html?id=' + id + '&window=1', {name: 'control'+id, widht: '600', height: '250'});
		break;
		
		case 'cmd_configs':
			get_window('/admins/cmd_configs.html?id=' + id + '&window=1', {name: 'control'+id, widht: '600', height: '250'});
		break;
		
		case 'cmd_list_links':
			get_window('/admins/cmd_list_links.html?id=' + id + '&window=1', {name: 'control'+id, widht: '900', height: '600'});
		break;
		
		case 'cmd_list_users':
			get_window('/admins/cmd_list_users.html?id=' + id + '&window=1', {name: 'control'+id, widht: '800', height: '400'});
		break;
		
		case 'cmd_import':
			get_window('/admins/cmd_import.html?id=' + id + '&window=1', {name: 'control'+id, widht: '800', height: '400'});
		break;
		
		case 'cmd_shever':
			get_window('/admins/cmd_shever.html?id=' + id + '&window=1', {name: 'control'+id, widht: '800', height: '400'});
		break;
		
		case 'cmd_shever_fgr':
			get_window('/admins/cmd_shever_fgr.html?id=' + id + '&window=1', {name: 'control'+id, widht: '800', height: '400'});
		break;
		
		default:
			get_window('/admins/' + cmd + '.html?id=' + id + '&window=1', {name: 'control'+id});
		break;
	}
}

function delete_cmd(id, cmd_id, window_id){
	if(confirm('Действительно удалить данное задание?') != false){
		get_hax({
			url: '/admins/cmd_stats_cmd.html?window=1&id='+id+'&x='+cmd_id,
			method: 'get',
			id: window_id + '_content',
			nohistory: true,
			nocache: true,
			destroy: false,
		});
	}
}

function delete_sh(id, cmd_id, window_id){
	if(confirm('Действительно удалить?') != false){
		get_hax({
			url: '/admins/cmd_shever.html?window=1&id='+id+'&x='+cmd_id,
			method: 'get',
			id: window_id + '_content',
			nohistory: true,
			nocache: true,
			destroy: false,
		});
	}
}

function delete_sh_fgr(id, cmd_id, window_id){
	if(confirm('Действительно удалить?') != false){
		get_hax({
			url: '/admins/cmd_shever_fgr.html?window=1&id='+id+'&y='+cmd_id,
			method: 'get',
			id: window_id + '_content',
			nohistory: true,
			nocache: true,
			destroy: false,
		});
	}
}

function stat_sh(id, window_id){
	get_hax({
		url: '/admins/cmd_shever.html?window=1&id='+id+'&z=1',
		method: 'get',
		id: window_id + '_content',
		nohistory: true,
		nocache: true,
		destroy: false,
	});
}

function add_sh(id, sname, stype, window_id){
	get_hax({
		url: '/admins/cmd_shever.html?window=1&id='+id+'&y='+sname+'&z='+stype,
		method: 'get',
		id: window_id + '_content',
		nohistory: true,
		nocache: true,
		destroy: false,
	});
}

function add_sh_fgr(id, sname, window_id){
	get_hax({
		url: '/admins/cmd_shever_fgr.html?window=1&id='+id+'&x='+sname,
		method: 'get',
		id: window_id + '_content',
		nohistory: true,
		nocache: true,
		destroy: false,
	});
}

function add_sh_fgr_d(id, sname, window_id){
	get_hax({
		url: '/admins/cmd_shever_fgr.html?window=1&id='+id+'&z='+sname,
		method: 'get',
		id: window_id + '_content',
		nohistory: true,
		nocache: true,
		destroy: false,
	});
}

function delete_link(id, cmd_id, window_id){
	if(confirm('Действительно удалить ссылку?') != false){
		get_hax({
			url: '/admins/cmd_list_links.html?window=1&id='+id+'&x='+cmd_id,
			method: 'get',
			id: window_id + '_content',
			nohistory: true,
			nocache: true,
			destroy: false,
		});
	}
}

function add_search(){
	get_hax({
		url:'/files/search.html?ajax=1',
		method:'post',
		form:'search_fg',
	});
}

function get_result_window(id){
	get_window('/files/result-'+id+'.html?window=1', {name:'result'+id, widht: 800, height: 600});
	return false;
}

function start_search(){
	get_hax({
		url:'/files/search.html?ajax=1&type=1',
		method:'get',
	});
}

function start_filters(){
	get_hax({
		url:'/files/filters.html?ajax=1&type=1',
		method:'get',
	});
}

function search_lp(obj){
	get_hax({
		url: obj.href,
		id: obj.parentNode.parentNode.id,
		method:'get',
	});

	return false;
}

function check_date(obj){
	if(obj.checked == true){
		obj.style.display = 'none';
		document.getElementById('date_search_set').innerHTML = '';
	}else{
		obj.checked = true;
	}
}

function add_filter(){
	get_hax({
		url:'/files/filters.html?ajax=1',
		method:'post',
		form:'search_fg',
	});
}

function put_system(id, cmd){
	//alert(id + ' - ' + cmd);
	switch(cmd){
		case 'delete':
			if(confirm('Вы действительно хотите удалить из системы данного клиента?') != false){
				get_hax({
					url: '/clients/delete.html?ajax=1&id='+id,
					method: 'post',
					id: 'content',
					nohistory: true,
					nocache: true,
					destroy: true,
				});
			}
		break;
		
		case 'edit':
			get_window('/clients/edit.html?id=' + id + '&window=1', {name: 'control'+id, widht: '650', height: '295'});
		break;
		
		case 'add_server':
			get_window('/clients/add_server.html?id=' + id + '&window=1', {name: 'control'+id, widht: '650', height: '295'});
		break;
		
		case 'list_server':
			get_window('/clients/list_server.html?id=' + id + '&window=1', {name: 'control'+id, widht: '800', height: '400'});
		break;
		
		case 'add_domain':
			get_window('/clients/add_domain.html?x=' + id + '&window=1', {name: 'control'+id, widht: '800', height: '400'});
		break;
		
		case 'add_domain_fs':
			get_window('/clients/add_domain.html?id=' + id + '&window=1', {name: 'control'+id+''+cmd, widht: '800', height: '400'});
		break;
		
		case 'list_domain':
			get_window('/clients/list_domain.html?id=' + id + '&window=1', {name: 'control'+id, widht: '1000', height: '400'});
		break;
	}
}

function put_system_a(id, cmd, obj){
	//alert(id + ' - ' + cmd + ' - ' + obj);
	switch(cmd){
		case 'delete':
			if(confirm('Вы действительно хотите удалить из системы данного клиента?') != false){
				get_hax({
					url: '/clients/delete.html?ajax=1&id='+id,
					method: 'post',
					id: 'content',
					nohistory: true,
					nocache: true,
					destroy: true,
				});
			}
		break;
		
		case 'edit':
			get_window('/clients/edit.html?id=' + id + '&window=1', {name: 'control'+id, widht: '650', height: '295'});
		break;
		
		case 'add_domain_fs':
			get_hax({
				url:'/clients/add_domain.html?id=' + id + '&window=1&y='+obj,
				method:'get',
				id: obj + '_content',
				nohistory: true,
				nocache: true,
				destroy: true,
			});
			//get_window('/clients/add_domain.html?id=' + id + '&window=1', {name: 'control'+id+''+cmd, widht: '800', height: '400'});
		break;
	}
}

var tau = 0;

function update(){
	clearTimeout(tau);
	clearInterval(tau);
	get_hax({url: '/files/index.html?ajax=1'});
	tau=setTimeout("update();",60000);
}

function loadun (host, id) {
	get_window ('/unnecessary/show.html?str=' + host + '&id=' + id + '&window=1', {name: 'log'+host+id, widht: 800, height: 500});
}