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

$pid_file = $dir['cache'] . 'management.pid';

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
include_once($dir['site'] . 'includes/functions.ip_rule.php');
include_once($dir['site'] . 'includes/functions.rc.php');

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

//require_once($dir['site'] . 'classes/manager.class.php');
//$m = new manager();

//$m->start = array();
//$m->start[] = 'verb 0';
//$m->start[] = 'log 0';

function parse_status($str){
	 $str = explode("\r\n", $str);
	 $list = array();

	 foreach($str as $f){
	 	$f = explode("\t", $f);
	 	switch($f[0]){
	 		case 'TITLE':
	 			$list['title'] = $f[1];
	 		break;
		
	 		case 'TIME':
	 			$list['time'] = array($f[1], $f[2]);
	 		break;
		
	 		case 'CLIENT_LIST':
	 			$tmp = array();
	 			$tmp['name'] = $f[1];
	 			$tmp['ip'] = $f[2];
	 			$tmp['nip'] = $f[3];
	 			$tmp['received'] = $f[4];
	 			$tmp['sent'] = $f[5];
	 			$tmp['time'] = $f[6];
	 			$tmp['timec'] = $f[7];
	 			$list['list'][$f[3]]  = $tmp;
	 			unset($tmp);
	 		break;
	 	}
	 }
	 return $list;
}

function network($str){
	global $socket;
	socket_write($socket, $str . "\r\n", strlen($str. "\r\n"));
	usleep(10000);
	socket_recv($socket, $buf, 65536, MSG_WAITALL);
	return $buf;
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
    @system('sudo ' . $file . ' > /dev/null');
    unlink($file);
    $to = '';
}

$time = time();

do{
	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	$result = socket_connect($socket, '127.0.0.1', '689');
	socket_set_nonblock($socket);
	network('verb 0');
	network('log off');
	unset($buf);
	
	$buf = network('status 3');
	$vpn = parse_status($buf);

	if(is_array($vpn)){
		if(isset($vpn['list']) && is_array($vpn['list'])){
			foreach($vpn['list'] as $item){
				//$item['ip'] = preg_replace('~(.*)\.(.*)\.(.*)\.(.*)~is', 'xx.xx.xx.xx', $item['ip']);
				$item['ip'] = '-';

				$client = $mysqli->query('SELECT id, name, inip, server, status, autocheck, enable, post_date FROM `bf_clients` WHERE (name = \''.$item['name'].'\') LIMIT 1');
				if(isset($client->name) && $client->name == $item['name']){
					$kill = false;
					
					$client->prio = (1000+$client->id);
					$client->table = (1000+$client->server);
					
					if($client->enable != 1){
						$kill = true;
						system_to('/sbin/ip rule del prio ' . $client->prio);
						suexec();
						network('kill ' . $client->name);
					}elseif(!empty($client->server) && $client->server != 0){
						$ip_rule = iprule_decode();
						$ip_status = iprule_search($client->prio, $client->inip . '/32', $client->table);
						
						if($ip_status != $client->prio){
							system_to('/sbin/ip rule del prio ' . $client->prio);
							system_to('/sbin/ip rule add prio ' . $client->prio . ' from ' . $client->inip . '/32' . ' table ' . $client->table);
							suexec();
						}
					}
					
					if($kill != true){
						if($client->server != 0 && $client->autocheck == 1){
							$server = $mysqli->query('SELECT id, status FROM `bf_servers` WHERE (status != \'2\') (id = \''.$client->server.'\') LIMIT 1');
							if(isset($server->id) && $server->id == $client->server){
								$kill = true;
								network('kill ' . $client->name);
							}
						}
					}
					
					if($kill != true){
						switch($client->status){
							case '0':
							case '1':
								$mysqli->query('update bf_clients set `status` = \'2\', `inip` = \''.$item['nip'].'\', last_date = NOW() WHERE (id = \''.$client->id.'\')');
							break;
							
							case '2':
								$mysqli->query('update bf_clients set `inip` = \''.$item['nip'].'\', last_date = NOW() WHERE (id = \''.$client->id.'\')');
							break;
						}
						
						$key = md5($client->id . $client->name . $client->post_date . 'WMOARQog7wEGdTRH2ipnmAn71ptAfM9g');
						$log = openssl_encrypt(json_encode($item), 'AES-256-CBC', sha1($key), false, substr($key, 0, 16));
						$mysqli->query("INSERT INTO bf_logs set log = '".$log."', client_id = '".$client->id."', last_date = '".date('Y-m-d H:i:s', $item['timec'])."', post_date = CURRENT_TIMESTAMP() on duplicate key update log = '".$log."'");
					}
				}
			}
		}
	}
	
	@socket_shutdown($socket, 2);
	@socket_close($socket);

	if((time() - $time) > 60){
		$mysqli->query('update bf_clients set `status` = \'0\', last_date = NOW() WHERE (status = \'2\') AND (last_date < DATE_SUB(NOW(), INTERVAL 30 SECOND))');
		$time = time();
	}
	
	sleep(30);
}while(true);

?>