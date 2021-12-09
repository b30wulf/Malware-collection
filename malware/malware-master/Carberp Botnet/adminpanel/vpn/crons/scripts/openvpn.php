#!/usr/bin/env php
<?php

error_reporting(-1);
set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '128M');

function real_path($p){
	return str_replace('//', '/', str_replace('\\', '/', realpath($p)) . '/');
}

$dir = array();
$dir['script'] = pathinfo(__FILE__, PATHINFO_DIRNAME);
$dir['site'] = real_path($dir['script'] . '/../../');
$dir['cache'] = real_path($dir['site'] . '/cache/');
$dir['cfg'] = real_path($dir['site'] . '/cfg/');

$pid_file = $dir['cache'] . 'openvpn.pid';

if(file_exists($pid_file)){
	$pid = file_get_contents($pid_file);
	if(stripos(exec('ps -p '.$pid), $pid) === false){
		file_put_contents($pid_file, getmypid());
	}else{
		exit;
	}
}else{
	file_put_contents($pid_file, getmypid());
}

include_once($dir['site'] . 'includes/functions.iptables.php');

include_once($dir['site'] . 'includes/functions.ping.php');

include_once($dir['site'] . 'includes/config.php');
require_once($dir['site'] . 'classes/mysqli.class.lite.php');
$mysqli = new mysqli_db();

$mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
unset($cfg_db);
if(count($mysqli->errors) > 0){
	error_log('DB_ERROR: ' . print_r($mysqli->errors, true),4);
	exit;
}
$mysqli->settings["ping"] = true;

function openvpn_start($id){
	global $dir, $mysqli;
	// /usr/sbin/openvpn --daemon --writepid /var/run/openvpn/server.pid --config vpn.conf --cd /etc/openvpn --script-security 2
	$mysqli->query('update bf_servers set `status` = \'1\' WHERE (id = \''.$id.'\')');

	prt('openvpn_start - ' . $id);
	system_to('sudo /usr/sbin/openvpn --daemon --writepid '.$dir['cfg'] . $id.'/vpn.pid --config '.$dir['cfg'].$id.'/vpn.conf --cd '.$dir['cfg'] . $id.' --script-security 2');
	suexec(true);
	/*
	file_put_contents('/tmp/vpn-'.$id.'.sh', '#!/bin/sh' . "\n");
	file_put_contents('/tmp/vpn-'.$id.'.sh', 'sudo /usr/sbin/openvpn --daemon --writepid '.$dir['cfg'] . $id.'/vpn.pid --config '.$dir['cfg'].$id.'/vpn.conf --cd '.$dir['cfg'] . $id.' --script-security 2', FILE_APPEND);
	chmod('/tmp/vpn-'.$id.'.sh', 0777);
	@system('/tmp/vpn-'.$id.'.sh');
	unlink('/tmp/vpn-'.$id.'.sh');
	*/
}

function prt($str){
	echo $str . "\n";
	echo '-------------------' . "\n";
}

function get_pid($file){
	if(file_exists($file)){
		$pid = file_get_contents($file);
		$pid = trim($pid, "\n");
	}else{
		$pid = false;
	}
	return $pid;
}

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
    /*
    if($deamon == true){
        @system('sudo ' . $file . ' > /dev/null &');
    }else{
        @system('sudo ' . $file . ' > /dev/null');
    }
    */
    @system('sudo ' . $file . ' > /dev/null');
    unlink($file);
    $to = '';
}

function host_ping($ip, $try = 5, $timeout = 10){
	for($i = 1; $i <= $try; $i++){
		$ping = ping($ip, $timeout);
		if(!empty($ping)){
			break;
		}
	}
	return $ping;
}


$to = '';

function vpn($row){
    global $dir, $mysqli;
    
    prt($row->id . ' - start item');
    
    $pid = get_pid($dir['site'] . 'cfg/' . $row->id . '/vpn.pid');

    if($pid != false){
    	if($row->enable == 1){
    		if(stripos(exec('ps -p '.$pid), $pid) === false){
    			prt($row->id . ' - no pid - ' . exec('ps -p '.$pid));
    			openvpn_start($row->id);
    		}else{
    			switch($row->status){
				case 1:
				case 0:
					prt($row->id . ' - server off');
					$mysqli->query('update bf_servers set `status` = \'0\' WHERE (id = \''.$row->id.'\')');
					system_to('/bin/kill -s SIGABRT ' . $pid);
					system_to('/sbin/ip rule del prio ' . (10000+$row->id));
					suexec(true);
					unlink($dir['cfg'] . $row->id.'/vpn.pid');
				break;
			
				case 2:
					//$ping = exec('/bin/ping -c 3 -q -i 0 -s 1 ' . preg_replace('~\.0$~', '.1', $row->inip));
					//$sinip = host_ping(preg_replace('~\.0$~', '.1', $row->inip));
					//$ping = host_ping($sinip);
					/*
					if(empty($ping)){
						prt($row->id . ' - no ping ' . $sinip);
						$mysqli->query('update bf_servers set `status` = \'0\' WHERE (id = \''.$row->id.'\')');
						system_to('/bin/kill -s SIGABRT ' . $pid);
						system_to('/sbin/ip rule del prio ' . (10000+$row->id));
						suexec(true);
						unlink($dir['cfg'] . $row->id.'/vpn.pid');
						openvpn_start($row->id);
					}else{
					*/
						system_to('/sbin/ip route del 173.194.69.106');
						system_to('/sbin/ip route add 173.194.69.106 dev tun' . $row->id);
						suexec(true);
						
						$ping = host_ping('173.194.69.106');
						
						if(empty($ping)){						
							prt($row->id . ' - no ping 173.194.69.106');
							$mysqli->query('update bf_servers set `status` = \'0\' WHERE (id = \''.$row->id.'\')');
							system_to('/bin/kill -s SIGABRT ' . $pid);
							system_to('/sbin/ip rule del prio ' . (10000+$row->id));
							suexec(true);
							unlink($dir['cfg'] . $row->id.'/vpn.pid');
							openvpn_start($row->id);
						}
					//}
				break;
			}
    		}
    	}else{
    		prt($row->id . ' - server off');
		$mysqli->query('update bf_servers set `status` = \'0\' WHERE (id = \''.$row->id.'\')');
    		system_to('/bin/kill -s SIGABRT ' . $pid);
		system_to('/sbin/ip rule del prio ' . (10000+$row->id));
		suexec(true);
    		unlink($dir['cfg'] . $row->id.'/vpn.pid');
    	}
    }else{
        prt($row->id . ' - no proccess');
    	$mysqli->query('update bf_servers set `status` = \'0\' WHERE (id = \''.$row->id.'\')');
    	if($row->enable == 1){
    		openvpn_start($row->id);
    	}
    }
}

do{
	prt('start while');
	$mysqli->query('SELECT id, ip, inip, port, status, enable FROM `bf_servers`', null, 'vpn');
	prt('sleep - 20 sec');
	sleep(20);
}while(true);


?>