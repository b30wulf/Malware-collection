#!/usr/bin/env php
<?php
error_reporting(-1);

function real_path($p){
	return str_replace('//', '/', str_replace('\\', '/', realpath($p)) . '/');
}

$dir = array();
$dir['script'] = pathinfo(__FILE__, PATHINFO_DIRNAME);
$dir['site'] = real_path($dir['script'] . '/../../') . '/';
$dir['logs'] = real_path($dir['site'] . '/logs/') . '/';

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

$files = scandir($dir['site'] . 'logs/unnecessary/fgr1/');

foreach($files as $f){
	$z = $mysqli->query('SELECT * FROM bf_unnecessary WHERE (md5=\''.$f.'\') LIMIT 1');
	if($z->md5 == $f){
		$z->host_pre = substr($z->host, 0, 2);
		@mkdir($dir['site'] . 'logs/unnecessary/fgr/' . $z->host_pre . '/');
		rename($dir['site'] . 'logs/unnecessary/fgr1/' . $f, $dir['site'] . 'logs/unnecessary/fgr/' . $z->host_pre . '/' . $f);
		//echo $dir['site'] . 'logs/unnecessary/fgr1/' . $f . "\n";
		//echo $dir['site'] . 'logs/unnecessary/fgr/' . $z->host_pre . '/' . $f . "\n\n";
	}else{
		echo "\n" . 'Not Found: ' . $f . "\n";
		file_put_contents('1.txt', $f . "\r\n", FILE_APPEND);
	}
}

?>