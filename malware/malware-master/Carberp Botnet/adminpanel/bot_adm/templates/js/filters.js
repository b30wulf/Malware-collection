var disable_window = '';

$(document).ready(function(){
	$("#cats").treeview({animated: "fast",collapsed: true,persist: "cookie",cookieId: "filters-treeview"});
	$("#catse").treeview({animated: "fast",collapsed: true,persist: "cookie",cookieId: "filters-treeview-edit"});
});

function load_pages_logs (obj) {
	get_hax({
			url: obj.href + '&ajax=1',
			id: 'child_content',
			//nohistory: false,
			nocache: false,
			//destroy: false,
	});
	return false;
}

function load_data_logs (url) {
	get_hax({
			url: url,
			method: 'post',
			form: 'filters',
			id: 'child_content',
			//nohistory: false,
			nocache: false,
			//destroy: false,
	});
	return false;
}

function load_data_unnecessary (url) {
	get_hax({
			url: url,
			method: 'post',
			form: unnecessar_name_form,
			id: 'unnecessary_content',
			//nohistory: false,
			nocache: false,
			//destroy: false,
	});
	return false;
}

function load_data_fgr (url) {
	return get_hax({
			url: url.replace("#", ""),
			method: 'get',
			id: 'fgr_fields_content',
			nocache: false,
	});
}

function load_data_log (id, fid) {
	get_window ('/filters/show-' + id + '.html?str=' + fid + '&window=1', {name: 'log'+id+fid, widht: 800});
}

function get_item(id) {
	if(disable_window != '') window_close(disable_window);
	get_hax({url: '/filters/logs-'+id+'.html?ajax=1', id: 'child_content'});
}

function get_item_static(id) {
	if(disable_window != '') window_close(disable_window);
	get_hax({url: '/filters/logs_static.html?str='+id+'&ajax=1', id: 'child_content'});
}

function add_fgr(obj) {
	if(document.forms['filters']['fgr_fields'].value.indexOf(obj.name + ',', 0) == -1){
		document.forms['filters']['fgr_fields'].value += obj.name + ',';
	}else{
		document.forms['filters']['fgr_fields'].value = document.forms['filters']['fgr_fields'].value.replace(obj.name + ',', "");
	}
}

function add_fgr_val(form, name) {
	if(document.forms[form][name].value != ''){
		if(document.forms['filters']['fgr_fields'].value.indexOf(document.forms[form][name].value + ',', 0) == -1){
			document.forms['filters']['fgr_fields'].value += document.forms[form][name].value + ',';
			document.forms[form][name].value = '';
		}else{
			document.forms[form][name].value = '';
			alert(lang['yjeest'])
		}
	}
}

function work_edit (){
	$(document).ready(function(){
		if($("span a").css('display') == 'none'){
			$("span a").attr("style", "display:inline-block");
		}else{
			$("span a").attr("style", "display:none");
		}
	});
}