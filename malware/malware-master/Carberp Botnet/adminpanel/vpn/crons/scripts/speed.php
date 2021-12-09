#!/usr/bin/env php
<?php

set_time_limit(0);
ini_set('max_execution_time', 0);

function real_path($p){
	return str_replace('//', '/', str_replace('\\', '/', realpath($p)) . '/');
}

$dir = array();
$dir['script'] = pathinfo(__FILE__, PATHINFO_DIRNAME);
$dir['site'] = real_path($dir['script'] . '/../../');
$dir['logs'] = real_path($dir['site'] . '/logs/');

$pid_file = $dir['site'] . 'cache/pid_speed.txt';

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

$s = array();
$s['rx'] = array();
$s['tx'] = array();

do{
	$s['rx'][] = file_get_contents('/sys/class/net/eth0/statistics/rx_bytes');
	$s['tx'][] = file_get_contents('/sys/class/net/eth0/statistics/tx_bytes');

	usleep(950000);

	if(isset($s['rx']['10'])){
		$s['time'] = time();
		file_put_contents($dir['site'] . 'cache/current_speed.txt', json_encode($s));
		unset($s);
		$s = array();
		$s['rx'] = array();
		$s['tx'] = array();
	}
}while(true);

?>