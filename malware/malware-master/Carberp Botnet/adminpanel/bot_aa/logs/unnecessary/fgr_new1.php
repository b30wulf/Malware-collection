#!/usr/bin/env php
<?php
error_reporting(-1);

function real_path($p){
	return str_replace('//', '/', str_replace('\\', '/', realpath($p)) . '/');
}

$dir = array();
$dir['script'] = pathinfo(__FILE__, PATHINFO_DIRNAME);
$dir['site'] = real_path($dir['script'] . '/../../');
$dir['logs'] = real_path($dir['site'] . '/logs/');

include_once($dir['site'] . 'includes/config.php');
include_once($dir['site'] . 'includes/functions.php');
require_once($dir['site'] . 'classes/mysqli.class.lite.php');
$mysqli = new mysqli_db();

$mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
$mysqli->settings["ping"] = true;

unset($cfg_db);
if(count($mysqli->errors) > 0){
	error_log('DB_ERROR: ' . print_r($mysqli->errors, true),4);
	exit;
}

$dir['logs'] = $dir['site'] . 'logs/unnecessary/fgr/';

$files = scandir($dir['logs']);

$ml = ini_get('memory_limit');
$ml = str_replace('M', '', $ml);
$ml = ($ml * 1024 * 1024);

$cb = 0;

$cf = count($files);
foreach($files as $kz => $d){
	if($d == '.') continue;
	if($d == '..') continue;

	//echo 'Dir: ' . $dir['logs'] . $d . '/' . "\n";
    //file_put_contents('2.txt', 'Dir: ' . $dir['logs'] . $d . '/' . "\r\n", FILE_APPEND);
	$dir['logs1'] = $dir['logs'] . $d . '/';

	$di = scandir($dir['logs'] . $d . '/');
	$cdi = count($di);
	foreach($di as $k => $f){
		if($f == '.') continue;
		if($f == '..') continue;


		$fs = filesize($dir['logs1'] . $f);

        //file_put_contents('2.txt', 'File: ' . $dir['logs1'] . $f . "\r\n", FILE_APPEND);

		if($fs > ($ml / 4)){
			$block_size = $ml / 8;
			$fs_max = ($fs + $block_size);
     		$cb = $h = 0;
     		$separator = "[~]\r\n\r\n";

     		$log = '';

     		do{
    			$log .= file_get_contents($dir['logs1'] . $f, false, null, $cb, $block_size);
     			$h = strrpos($log, $separator);

     			if($h !== false){
     				$h += strlen($separator);

                    include($dir['site'] . 'logs/unnecessary/fgr_u.php');

     				$cb += $h;
     				$log = '';
     			}else{
     				$cb += $block_size;
     			}
     		}while($cb < $fs_max);
        }else{
        	$cb = $fs;
        	$log = file_get_contents($dir['logs1'] . $f);
        	include($dir['site'] . 'logs/unnecessary/fgr_u.php');
     	}
        echo '   File (' . $dir['logs1'] = $dir['logs'] . $d . '/' . $f . '): ' . $k . ' iz ' .$cdi . "\n";
        unlink($dir['logs'] . $d . '/' . $f);
	}
	echo 'Dir: (' . $dir['logs1'] = $dir['logs'] . $d . '/' . '): ' . $kz . ' iz ' .$cf . "\n";
	rmdir($dir['logs'] . $d . '/');
}

?>