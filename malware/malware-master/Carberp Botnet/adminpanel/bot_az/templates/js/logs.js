
function gbwc(id){
	get_window('/logs/cberfiz-'+id+'.html?window=1', {name:'bot'+id, widht: 800, height: 600});
}

function gbwr(id){
	get_window('/logs/rafa-'+id+'.html?window=1', {name:'bot'+id, widht: 800, height: 600});
}

function gbwcc(id){
	get_window('/logs/cc-'+id+'.html?window=1', {name:'bot'+id, widht: 800, height: 600});
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
