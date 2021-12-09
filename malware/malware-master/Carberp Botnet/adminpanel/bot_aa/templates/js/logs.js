$(document).ready(function(){
//	$("#cats").treeview({animated: "fast",collapsed: true,persist: "cookie",cookieId: "logs-treeview"});
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

function load_data_save (url) {
	get_hax({
			url: url,
			method: 'post',
			form: unnecessar_name_form,
			id: 'savelog_content',
			//nohistory: false,
			nocache: false,
			//destroy: false,
	});
	return false;
}

function ldd (url) {
	get_hax({
			url: url,
			method: 'get',
			id: 'digits_content',
			nocache: false,
	});
	return false;
}

function load_data_log (id, fid) {
	get_window ('/logs/show-' + id + '.html?str=' + fid + '&window=1', {name: 'log'+id+fid});
}

function update_logs(){
	clearTimeout(tau);
	clearInterval(tau);
	get_hax({url: '/logs/index.html?ajax=1'});
	tau=setTimeout("update_logs();",60000);
}

function load_uniq_bot(id, obj){
	obj.onclick = null;
	obj.style.textDecoration = '';
	obj.style.color = '#000';
	obj.style.cursor = '';
	get_hax({
		url: 'show.html?str=' + id,
		method: 'get',
		id: obj.id, 
		indicator: '<img src="/images/loading.gif" alt="Загрузка..." width="16" />',
		//nohistory: false,
		nocache: true,
		//destroy: false,
	});
	return false;
}