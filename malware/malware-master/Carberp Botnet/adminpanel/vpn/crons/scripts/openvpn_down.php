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

include_once($dir['site'] . 'includes/config.php');
require_once($dir['site'] . 'classes/mysqli.class.lite.php');
$mysqli = new mysqli_db();

$mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
$mysqli->settings["ping"] = true;

function system_to($cmd){
    global $to;
    $to .= $cmd . "\n\n";
}

$num = str_replace('tun', '', $_SERVER['argv'][1]);

$main_ip = explode('.', $_SERVER['argv'][4], 4);
$main_ip[3] = 0;
$main_net = implode('.', $main_ip);
$main_ip[3] = 1;
$main_ip = implode('.', $main_ip);

$mysqli->query('update bf_servers set `status` = \'0\' WHERE (id = \''.$num.'\')');

$server = $mysqli->query('select id, status bf_servers where (id = \''.$num.'\') limit 1');

if($server->id == $num){	//dfG
}

/*
$to = '';

system_to('/sbin/ip ro flush table tun' . $num);

system_to('/sbin/ip ru del fwmark ' . $num);

system_to('/sbin/ip route flush table tun' . $num);

system_to('/sbin/ip route flush cache');

file_put_contents('/tmp/r'.$_SERVER['argv'][1].'.sh', '#!/bin/sh' . "\n");
file_put_contents('/tmp/r'.$_SERVER['argv'][1].'.sh', $to . "\n", FILE_APPEND);
chmod('/tmp/r'.$_SERVER['argv'][1].'.sh', 0777);
@system('/tmp/r'.$_SERVER['argv'][1].'.sh');
unlink('/tmp/r'.$_SERVER['argv'][1].'.sh');
*/


?>