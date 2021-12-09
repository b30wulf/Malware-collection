#!/usr/bin/env php
<?php

set_time_limit(0);
error_reporting(-1);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '512M');

function real_path($p){
	return str_replace('//', '/', str_replace('\\', '/', realpath($p)) . '/');
}

if(file_exists('../cache/dirs_panels.json')) $dir = json_decode(file_get_contents('../cache/dirs_panels.json'), true);
if(empty($dir['script']) || empty($dir['site']) || empty($dir['logs'])){
	$dir = array();
	$dir['script'] = pathinfo(__FILE__, PATHINFO_DIRNAME);
	$dir['site'] = real_path($dir['script'] . '/../');
	$dir['logs'] = real_path($dir['site'] . '/logs/');
	file_put_contents($dir['site'] . 'cache/dirs_panels.json', json_encode($dir));
}

ini_set('error_log', $dir['site'] . 'cache/error_panels_file.txt');
function error_import_handler($code, $msg, $file, $line){
	global $dir;
	if($code != 8 && !strpos($file, 'geoip.inc')) file_put_contents($dir['site'] . 'cache/error_panels_file.txt', print_r(array('code' => $code, 'msg' => $msg, 'file' => $file, 'line' => $line), true) . "\r\n", FILE_APPEND);
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

$task = $mysqli->query('SELECT * FROM bf_filter_panels WHERE (id = \''.$thread->post_id.'\')');
if($task->id != $thread->post_id) {
	error_log('Admin Panel not found!',4);
	exit();
}

switch($task->program){
	case 'cPanel':
    	include_once($dir['script'] . '/modules/panels/cpanel.php');
    	$mysqli->query('update bf_filter_panels set import = \'1\' WHERE (id = \''.$task->id.'\')');
	break;

	case 'WHM':
    	include_once($dir['script'] . '/modules/panels/whm.php');
    	$mysqli->query('update bf_filter_panels set import = \'1\' WHERE (id = \''.$task->id.'\')');
	break;

	case 'DirectAdmin':
    	//include_once($dir['script'] . '/modules/panels/directadmin.php');
    	//exit;
	break;

	case 'ISPManager':
    	//include_once($dir['script'] . '/modules/panels/isp.php');
    	//exit;
	break;
}

sleep(1);

$mysqli->query('update bf_threads set status = \'255\', last_date = CURRENT_TIMESTAMP WHERE (id = \''.$thread->id.'\')');

exit;

?>