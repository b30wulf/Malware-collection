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

$pid_file = $dir['cache'] . 'clients.pid';

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

function parse_rule($str){
    $str = explode("\r\n", $str);
    
}

function system_to($cmd){
    global $to;
    $to .= $cmd . "\n\n";
}


function suexec($deamon = false){
    global $to;
    $file = '/tmp/cphpexec_'.mt_rand().'.sh';

    file_put_contents($file, '#!/bin/sh' . "\n");
    file_put_contents($file, $to . "\n", FILE_APPEND);
    @system('sudo /bin/chmod 777 ' . $file);
    @system('sudo ' . $file . ' > /dev/null');
    unlink($file);

    $to = '';
}

function get_servers($row){
    global $servers;

    $servers[$row->id] = $row;
}

function get_clients($row){
    global $to, $servers;

    if(isset($servers[$row->server])){
	if(!empty($row->inip) && $row->inip != '10.10.101.0'){
		//$ipt = '-A drops -t mangle -s '.$row->inip.'/32 -o ! tun'.$row->server.' -j DROP';
		
		if($row->autocheck == 1){
			$ipt = '-A drops -t mangle -s '.$row->inip.'/32 -o ! tun'.$row->server.' -j DROP';
			iptables_decode();
			if(iptables_search('mangle', 'drops', $ipt) == false){
				$ipt = '-A drops -t mangle -s '.$row->inip.'/255.255.255.255 -o ! tun'.$row->server.' -j DROP';
				if(iptables_search('mangle', 'drops', $ipt) == false){
					$ipt = '-A drops -t mangle -s '.$row->inip.' -o ! tun'.$row->server.' -j DROP';
					if(iptables_search('mangle', 'drops', $ipt) == false){
						system_to('/sbin/iptables ' . $ipt);
						//suexec();
					}
				}
			}
		    /*
		    if(iptables_search('mangle', 'drops', $ipt) == false){
			$ipt = '-A drops -t mangle -s '.$row->inip.'/255.255.255.255 -o ! tun'.$row->server.' -j DROP';
			if(iptables_search('mangle', 'drops', $ipt) == false){
				$ipt = '-A drops -t mangle -s '.$row->inip.' -o ! tun'.$row->server.' -j DROP';
				if(iptables_search('mangle', 'drops', $ipt) == false){
					system_to('/sbin/iptables ' . $ipt);
					suexec();
				}
			}
		    }
		    */
		}else{
			iptables_decode();
			$iptse = iptables_search('mangle', 'drops', $ipt);
			if($iptse != false){
				$ipt = '-A drops -t mangle -s '.$row->inip.'/32 -o ! tun'.$row->server.' -j DROP';
				if(iptables_search('mangle', 'drops', $ipt) == false){
					$ipt = '-A drops -t mangle -s '.$row->inip.'/255.255.255.255 -o ! tun'.$row->server.' -j DROP';
					if(iptables_search('mangle', 'drops', $ipt) == false){
						$ipt = '-A drops -t mangle -s '.$row->inip.' -o ! tun'.$row->server.' -j DROP';
						if(iptables_search('mangle', 'drops', $ipt) == false){
							system_to('/sbin/iptables ' . $ipt);
							//suexec();
						}
					}
				}
				
				/*
				$ipt = '-A drops -t mangle -s '.$row->inip.'/255.255.255.255 -o ! tun'.$row->server.' -j DROP';
				if(iptables_search('mangle', 'drops', $ipt) == false){
					$ipt = '-A drops -t mangle -s '.$row->inip.'/ -o ! tun'.$row->server.' -j DROP';
					if(iptables_search('mangle', 'drops', $ipt) == false){
						system_to('/sbin/iptables -t mangle -D drops  ' . $iptse);
						suexec();
					}
				}
				*/
			}
		}
		
		if(!empty($row->server)){
			$ip_rule = iprule_decode();
			$row->prio = (1000+$row->id);
			$row->table = (1000+$servers[$row->server]->id);
			$ip_status = iprule_search($row->prio, $row->inip . '/32', $row->table);
			
			if($ip_status != $row->prio){
			    system_to('sudo /sbin/ip rule del prio ' . $row->prio);
			    system_to('sudo /sbin/ip rule add prio ' . $row->prio . ' from ' . $row->inip . '/32' . ' table ' . $row->table);
			    //suexec();
			}
		}
	}
    }
}
/*
function iptables_searcht($t, $a, $s){
	global $iptables;

	if(isset($iptables[$t][$a]['rule'])){
		foreach($iptables[$t][$a]['rule'] as $k => $i){
			if(strpos($s, '-t ' . $t) != false) $s = str_replace(' -t ' . $t, '', $s);
			print_r($i . "\r\n");
			print_r($s . "\r\n");
			print_r('-----------------------------------------' . "\r\n");
			if($i == $s) return ($k+1);
		}
	}

	return false;
}
*/
function get_block($row){
	//$ipt = '-A drops -t mangle -s '.$row->inip.'/255.255.255.255 -o ! tun'.$row->server.' -j DROP';
	global $to;
	
	/*
	iptables_decode();
	$ipt = '-A drops -t mangle -s '.$row->inip.'/32 -o ! tun'.$row->server.' -j DROP';
	if(iptables_search('mangle', 'drops', $ipt) == false){
		$ipt = '-A drops -t mangle -s '.$row->inip.'/255.255.255.255 -o ! tun'.$row->server.' -j DROP';
		if(iptables_search('mangle', 'drops', $ipt) == false){
			$ipt = '-A drops -t mangle -s '.$row->inip.' -o ! tun'.$row->server.' -j DROP';
			if(iptables_search('mangle', 'drops', $ipt) == false){
				system_to('/sbin/iptables ' . $ipt);
				suexec();
			}
		}
	}
	*/
	
	$search = false;
	$ipt = array();
	$ipt[] = '-A drops -t mangle -s '.$row->inip.'/32 -o ! tun'.$row->server.' -j DROP';
	$ipt[] = '-A drops -t mangle -s '.$row->inip.'/255.255.255.255 -o ! tun'.$row->server.' -j DROP';
	$ipt[] = '-A drops -t mangle -s '.$row->inip.' -o ! tun'.$row->server.' -j DROP';
	$ipt[] = '-A drops -s '.$row->inip.'/32 -o ! tun'.$row->server.' -j DROP';
	$ipt[] = '-A drops -s '.$row->inip.'/255.255.255.255 -o ! tun'.$row->server.' -j DROP';
	$ipt[] = '-A drops -s '.$row->inip.' -o ! tun'.$row->server.' -j DROP';
	
	foreach($ipt as $ips){
		if(iptables_search('mangle', 'drops', $ips) != false && strpos($to, $row->inip) != false){
			$search = true;
			break;
		}
	}

	if($search != true){
		system_to('/sbin/iptables -A drops -t mangle -s '.$row->inip.'/255.255.255.255 -o ! tun'.$row->server.' -j DROP');
		//suexec();
	}
}

$to = '';
$servers = array();

do{
    //iptables_decode();
    system_to('/sbin/iptables -F drops -t mangle');
    
    $mysqli->query('SELECT id, name, ip, inip, prio, status, enable FROM `bf_servers`', null, 'get_servers', false);
    $mysqli->query('SELECT id, name, inip, server, status, autocheck, post_date FROM `bf_clients` WHERE (enable = \'1\') AND (autocheck = \'1\') AND (server != \'0\') AND (inip != \'\') ', null, 'get_block', false);
    
    iptables_decode();
    
    //$mysqli->query('SELECT id, name, inip, server, status, autocheck, post_date FROM `bf_clients` WHERE (enable = \'1\') AND (status = \'2\') AND (server != \'0\') AND (inip != \'\')', null, 'get_clients', false);
    
    suexec();
    
    sleep(30);
}while(true);

?>