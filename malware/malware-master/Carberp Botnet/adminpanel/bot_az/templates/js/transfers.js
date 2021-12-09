
function gtw(id){
	get_window('/transfers/show-'+id+'.html?window=1', {name:'trasfer'+id, widht: 800, height: 600});
}

function gli(obj, id){
	var rand_id = Math.floor(Math.random()*6549);
	obj.parentNode.id = 'rid' + rand_id;
	get_hax({
		url: '/transfers/manual.html?ajax=1&str=create_link&id=' + id,
		method: 'get',
		id: obj.parentNode.id,
	});
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

function gldt(id){
	var dip = id.parentNode.id.split('_');
	var pip = id.href.split('?&page=');
	get_hax({
		url: '/bots/bot.html?id=' + dip[1] + '&x=logs_tech&page=' + pip[1] + '&z=' + dip[2],
		method: 'get',
		id: 'logs_tech' + dip[2],
		indicator: '<div align="center"><img src="/images/loading.gif" /></div>',
	});
	return false;
}

function gldh(id){
	var dip = id.parentNode.id.split('_');
	var pip = id.href.split('?&page=');
	get_hax({
		url: '/bots/bot.html?id=' + dip[1] + '&x=logs_history&page=' + pip[1] + '&z=' + dip[2],
		method: 'get',
		id: 'logs_history' + dip[2],
		indicator: '<div align="center"><img src="/images/loading.gif" /></div>',
	});
	return false;
}

function gltl(id){
	var dip = id.parentNode.id.split('_');
	get_hax({
		url: '/bots/bot.html?id=' + dip[1] + '&x=tlogs_clear&' + '&z=' + dip[2],
		method: 'get',
		id: 'logs_tech' + dip[2],
		indicator: '<div align="center"><img src="/images/loading.gif" /></div>',
	});
	return false;
}

function gltlh(id){
	var dip = id.parentNode.id.split('_');
	get_hax({
		url: '/bots/bot.html?id=' + dip[1] + '&x=hlogs_clear&' + '&z=' + dip[2],
		method: 'get',
		id: 'logs_tech' + dip[2],
		indicator: '<div align="center"><img src="/images/loading.gif" /></div>',
	});
	return false;
}



