
function glc(id){
	get_window('/clients/logs-'+id+'.html?window=1', {name:'client'+id, widht: 800, height: 600});
}

function gsp(id, str){
	get_hax({
		url: '/clients/edit-'+id+'.html?str='+str+'&ajax=1',
		method: 'get',
		id: 'content',
		indicator: '<div align="center"><img src="/images/indicator.gif" alt="'+lang['load']+'" /></div>',
	});
}


function dl(id){
	get_window('/clients/download-'+id+'.html?window=1', {name:'download'+id, widht: 800, height: 300});
}

function dsr(id, str, rand_name){
	get_hax({
		url: '/clients/download-'+id+'.html?window=1&x=' + str,
		method: 'get',
		id: document.getElementById('dl' + rand_name).parentNode.id,
		indicator: '<div align="center"><img src="/images/indicator.gif" alt="'+lang['load']+'" /></div>',
	});
}

