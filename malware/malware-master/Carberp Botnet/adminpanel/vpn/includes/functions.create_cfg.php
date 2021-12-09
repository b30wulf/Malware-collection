<?php

function create_cfg($cfg){	if(!file_exists('cfg/' . $cfg['id'] . '/'))  mkdir('cfg/' . $cfg['id'] . '/');

	file_put_contents('cfg/' . $cfg['id'] . '/ca.crt', $cfg['ca']);
	file_put_contents('cfg/' . $cfg['id'] . '/client.crt', $cfg['crt']);
	file_put_contents('cfg/' . $cfg['id'] . '/client.key', $cfg['key']);
	file_put_contents('cfg/' . $cfg['id'] . '/ta.key', $cfg['ta']);

	$cfg['cfg'] = base64_decode($cfg['cfg']);

	$cfg['c'] = '';

	$cfg['c'] .= 'remote '.$cfg['ip'].' '.$cfg['port'].'' . "\r\n";
	$cfg['c'] .= 'proto '.$cfg['protocol'].'' . "\r\n";
	$cfg['c'] .= 'dev tun'.$cfg['id'].'' . "\r\n";
	$cfg['c'] .= 'nobind' . "\r\n";

	if(strpos($cfg['cfg'], 'tun-mtu') == false){
		$cfg['c'] .= 'tun-mtu 1500' . "\r\n";
	}

	if(strpos($cfg['cfg'], 'auth ') == false){
		$cfg['c'] .= 'auth SHA1' . "\r\n";
	}

	$cfg['c'] .= 'client' . "\r\n";
	$cfg['c'] .= 'persist-key' . "\r\n";
	$cfg['c'] .= 'persist-tun' . "\r\n";
    $cfg['c'] .= 'ns-cert-type server' . "\r\n";
	$cfg['c'] .= 'resolv-retry infinite' . "\r\n";

	if(!empty($cfg['ta'])){
		$cfg['c'] .= 'tls-client' . "\r\n";
		$cfg['c'] .= 'tls-timeout 120' . "\r\n";
		$cfg['c'] .= 'tls-auth "'.realpath('cfg/' . $cfg['id'] . '/ta.key').'" 1' . "\r\n";
	}

	$cfg['c'] .= 'ca "'.realpath('cfg/' . $cfg['id'] . '/ca.crt').'"' . "\r\n";
	$cfg['c'] .= 'cert "'.realpath('cfg/' . $cfg['id'] . '/client.crt').'"' . "\r\n";
	$cfg['c'] .= 'key "'.realpath('cfg/' . $cfg['id'] . '/client.key').'"' . "\r\n";

	if(strpos($cfg['cfg'], 'cipher') == false){
		$cfg['c'] .= 'cipher AES-256-CBC' . "\r\n";
	}

	$cfg['c'] .= 'route-noexec' . "\r\n";

	if(strpos($cfg['cfg'], 'comp-lzo') == false){
		$cfg['c'] .= 'comp-lzo' . "\r\n";
	}

	if(strpos($cfg['cfg'], 'redirect-gateway') == false){
		$cfg['c'] .= 'redirect-gateway' . "\r\n";
	}

	if(strpos($cfg['cfg'], 'ping-restart') == false){
		$cfg['c'] .= 'ping-restart 250' . "\r\n";
	}

	if(strpos($cfg['cfg'], 'ping ') == false){
		$cfg['c'] .= 'ping 150' . "\r\n";
	}

	$cfg['c'] .= 'verb 3' . "\r\n";
	$cfg['c'] .= 'mute 10' . "\r\n";
	$cfg['c'] .= 'fast-io' . "\r\n";
	$cfg['c'] .= 'multihome' . "\r\n";
	$cfg['c'] .= 'user root' . "\r\n";
	$cfg['c'] .= 'group root' . "\r\n";
	$cfg['c'] .= 'up '.realpath('crons/scripts/openvpn_up.php').'' . "\r\n";
	$cfg['c'] .= 'down '.realpath('crons/scripts/openvpn_down.php').'' . "\r\n";
	$cfg['c'] .= 'log '.realpath('cfg/' . $cfg['id'] . '/').'/openvpn.log' . "\r\n";
	$cfg['c'] .= 'log-append '.realpath('cfg/' . $cfg['id'] . '/').'/openvpn.log' . "\r\n";

	$cfg['c'] .= "\r\n";

	$cfg['c'] .= $cfg['cfg'];

	file_put_contents('cfg/' . $cfg['id'] . '/vpn.conf', $cfg['c']);
}

?>