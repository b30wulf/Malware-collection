#!/usr/bin/env php
<?php

/*
/media/sf_www/crons/scripts/openvpn_down.php tun1 1500 1558 10.50.11.18 10.50.11.17 init

ip link set dev tun1 up mtu 1500
ip addr add dev tun1 local 10.50.11.18 peer 10.50.11.17
ip route add 46.166.155.118/32 via 10.0.2.2
ip route add 10.50.11.1/32 via 10.50.11.17

ip route add 0.0.0.0/1 via 10.50.11.17
ip route add 128.0.0.0/1 via 10.50.11.17
*/

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

include_once($dir['site'] . 'includes/functions.iptables.php');
include_once($dir['site'] . 'includes/functions.ip_rule.php');

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

    if($deamon == true){
        @system('sudo ' . $file . ' > /dev/null &');
    }else{
        @system('sudo ' . $file . ' > /dev/null');
    }

    //unlink($file);
    $to = '';
}

switch($_SERVER['argv'][6]){
	case 'init':
		$num = str_replace('tun', '', $_SERVER['argv'][1]);
		$tn = (1000+$num);

		$main_ip = explode('.', $_SERVER['argv'][4], 4);
		$main_ip[3] = 0;
		$main_net = implode('.', $main_ip);
		$main_ip[3] = 1;
		$main_ip = implode('.', $main_ip);

		$server = $mysqli->query('select id, status, prio from bf_servers where (id = \''.$num.'\') limit 1');

		file_put_contents('1', print_r($server, true) . "\n---------------------------------\n", FILE_APPEND);

		if($server->id == $num){
			if($server->status != 2){
				$to = '';

				system_to('/sbin/ip ro flush table ' . $tn);

				system_to('/sbin/ip ru del fwmark ' . $tn);

				system_to('/sbin/ip route flush table ' . $tn);

				system_to('/sbin/ip link set dev '.$_SERVER['argv'][1].' up mtu ' . $_SERVER['argv'][2]);

				system_to('/sbin/ip addr add dev '.$_SERVER['argv'][1].' local '.$_SERVER['argv'][4].' peer '.$_SERVER['argv'][5].'');

				system_to('/sbin/ip route add '.$main_ip.'/32 via '.$_SERVER['argv'][5]);

				system_to('/sbin/ip route add 10.10.200.0/24 via 10.10.200.2 dev tap0 table ' . $tn);

				system_to('/sbin/ip route add 0.0.0.0/1 via '.$_SERVER['argv'][5].' table ' . $tn);

				system_to('/sbin/ip route add 128.0.0.0/1 via '.$_SERVER['argv'][5].' table ' . $tn);

				$ip_rule = iprule_decode();
				$ip_status = iprule_search((10000+$server->prio), '10.10.101.0/24', $tn);

				if($ip_status != (10000+$server->prio)){
					system_to('/sbin/ip rule add prio '.(10000+$server->prio).' from 10.10.101.0/24 table ' . $tn);
				}

				system_to('/sbin/ip route flush cache');

				for($i = 0; $i < 10; $i++){
					iptables_decode();
					if(!empty($iptables)){
						break;
					}else{
						sleep(1);
					}
				}

				if(!empty($iptables)){
					$ipt = '-A POSTROUTING -t nat -o '.$_SERVER['argv'][1].' -j MASQUERADE';
					if(iptables_search('nat', 'POSTROUTING', $ipt) == false) system_to('/sbin/iptables ' . $ipt);

					$ipt = '-A POSTROUTING -t nat -s '.$main_net.'/24 -o eth0 -j MASQUERADE';
					if(iptables_search('nat', 'POSTROUTING', $ipt) == false){
						$ipt = '-A POSTROUTING -t nat -s '.$main_net.'/255.255.255.0 -o eth0 -j MASQUERADE';
						if(iptables_search('nat', 'POSTROUTING', $ipt) == false){
							system_to('/sbin/iptables ' . $ipt);
						}
					}
				}

				system_to('/sbin/iptables-save > /etc/sysconfig/iptables');

				suexec();
				/*
				file_put_contents('/tmp/r'.$_SERVER['argv'][1].'.sh', '#!/bin/sh' . "\n");
				file_put_contents('/tmp/r'.$_SERVER['argv'][1].'.sh', $to . "\n", FILE_APPEND);
				chmod('/tmp/r'.$_SERVER['argv'][1].'.sh', 0777);
				@system('/tmp/r'.$_SERVER['argv'][1].'.sh');
				unlink('/tmp/r'.$_SERVER['argv'][1].'.sh');
				*/
			}
		}

		$mysqli->query('update bf_servers set `inip` = \''.$main_net.'\', `status` = \'2\' WHERE (id = \''.$num.'\')');

		file_put_contents('1', print_r($iptables, true) . "\n---------------------------------\n" . $to . "\n\n\n", FILE_APPEND);
	break;
}

?>