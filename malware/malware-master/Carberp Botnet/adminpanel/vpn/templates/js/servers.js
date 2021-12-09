
function gsp(id, str){
	get_hax({
		url: '/servers/edit-'+id+'.html?str='+str+'&ajax=1',
		method: 'get',
		id: 'content',
		indicator: '<div align="center"><img src="/images/indicator.gif" alt="'+lang['load']+'" /></div>',
	});
}

function update_prio(){
	get_hax({
		url: '/servers/index.html?ajax=1&str=update_prio',
		method: 'get',
		id: 'content',
		indicator: '<div align="center"><img src="/images/indicator.gif" alt="'+lang['load']+'" /></div>',
	});
}