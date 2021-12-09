
function enable(id){
	get_hax({
			url: '/drops/edit-'+id+'.html?ajax=1&str=enable',
			method: 'get',
			id: 'content',
			indicator: '<div align="center"><img src="/images/indicator.gif" alt="'+lang['load']+'" /></div>',
	});
	return false;
}

function gdw(id){
	get_window('/drops/show-'+id+'.html?window=1', {name:'drop'+id, widht: 800, height: 600});
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
