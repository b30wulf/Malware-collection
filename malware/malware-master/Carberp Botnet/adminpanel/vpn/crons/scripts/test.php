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

include_once($dir['site'] . 'includes/functions.iptables.php');
include_once($dir['site'] . 'includes/functions.ip_rule.php');
include_once($dir['site'] . 'includes/functions.rc.php');

include_once($dir['site'] . 'includes/config.php');
require_once($dir['site'] . 'classes/mysqli.class.lite.php');

$mysqli = new mysqli_db();

$mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
$mysqli->settings["ping"] = true;

unset($cfg_db);
if(count($mysqli->errors) > 0){
	error_log('DB_ERROR: ' . print_r($mysqli->errors, true),4);
	exit;
}

function parse_rule($str){
    $str = explode("\r\n", $str);
    
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

function get_servers($row){
    global $servers;

    $servers[$row->id] = $row;
}



$ipt = '-A drops -t mangle -s '.$row->inip.'/32 -o ! tun'.$row->server.' -j DROP';
	
iptables_decode();
	
print_r($iptables);
		


?>