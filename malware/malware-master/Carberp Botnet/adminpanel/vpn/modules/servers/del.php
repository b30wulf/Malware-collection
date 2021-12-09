<?php

function system_to($cmd){
    global $to;
    $to .= $cmd . "\n\n";
}

function suexec($deamon = false){
    global $to;
    $file = '/tmp/phpexec_'.mt_rand().'.sh';
    file_put_contents($file, '#!/bin/sh' . "\n");
    file_put_contents($file, $to . "\n", FILE_APPEND);
    @system('sudo /bin/chmod 777 ' . $file);
    @system('sudo ' . $file . ' > /dev/null');
    unlink($file);
    $to = '';
}

if(!empty($Cur['id'])){	$item = $mysqli->query('SELECT * from bf_servers WHERE id = '.$Cur['id'].' LIMIT 1');

	if($item->id == $Cur['id']){
		$mysqli->query('DELETE FROM bf_servers WHERE (id = \''.$item->id.'\') LIMIT 1');
		
		if(file_exists('cfg/' . $item->id . '/vpn.pid')){
			system_to('/bin/kill ' . file_get_contents('cfg/' . $item->id . '/vpn.pid'));
			suexec();
		}
		
		$sdf = scandir('cfg/' . $item->id . '/');
		unset($sdf[0], $sdf[1]);
		foreach($sdf as $f){
			@unlink('cfg/' . $item->id . '/' . $f);
		}
		
		rmdir('cfg/' . $item->id . '/');
		
		system_to('/bin/ip rule del prio ' . (10000+$item->prio));
		system_to('/bin/ip rule del table ' . (1000+$item->id));
		suexec();
	}
}

header('Location: /servers/index.html');
exit;

?>