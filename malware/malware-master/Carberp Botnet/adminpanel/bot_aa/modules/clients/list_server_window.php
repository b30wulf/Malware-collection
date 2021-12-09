<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

function servers_build($row){
	global $servers;
	$servers[$row->id] = $row;
}

function domains_build($row){
	global $servers;
	$servers[$row->server_id]->domains[$row->id] = $row;
}

if(!empty($Cur['id'])){	$client = $mysqli->query('select * from bf_clients where (id = \''.$Cur['id'].'\')');

	if($client->id == $Cur['id']){		$smarty->assign("client", $client);

		$servers = array();
		$mysqli->query('select * from bf_servers where (client_id = \''.$client->id.'\')', null, 'servers_build');
		$mysqli->query('select * from bf_domains where (client_id = \''.$client->id.'\')', null, 'domains_build');

		$smarty->assign("servers", $servers);
	}
}

?>