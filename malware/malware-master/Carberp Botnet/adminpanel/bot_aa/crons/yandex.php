#!/usr/bin/env php
<?php

set_time_limit(0);
ini_set('max_execution_time', 0);

function real_path($p){
	return str_replace('//', '/', str_replace('\\', '/', realpath($p)) . '/');
}

$dir = array();
$dir['script'] = pathinfo(__FILE__, PATHINFO_DIRNAME);
$dir['site'] = real_path($dir['script'] . '/../') . '/';
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

function start($row){
	global $mysqli, $dir, $count, $ci;
	$ci++;
	echo $ci . ' iz ' . $count . "\r";
	$log = $mysqli->query('SELECT v1, v2 FROM bf_filter_22 WHERE (prefix = \''.$row->prefix.'\') AND (uid = \''.$row->uid.'\')');
	//$cl = count($log);
	if(isset($log[0])){
		foreach($log as $item){
			//$i++;
			//echo $i . ' iz ' . $cl . "\r";
			if(!empty($item->v1) && !empty($item->v2)){
				file_put_contents($dir['site'] . 'cache/yandex.ru.txt', $item->v1 . ';' . $item->v2 . "\r\n", FILE_APPEND);
				file_put_contents($dir['site'] . 'cache/yandex.ru_uid.txt', $item->v1 . ';' . $item->v2 . ';' . $row->prefix . $row->uid . "\r\n", FILE_APPEND);
				/*
				if(!empty($row->v1)){
					file_put_contents($dir['site'] . 'cache/yandex.ru_parol.txt', $item->v1 . ';' . $item->v2 . ';' . $row->v1 . ';' . $row->prefix . $row->uid . "\r\n", FILE_APPEND);
					file_put_contents($dir['site'] . 'cache/yandex.ru_parol_uid.txt', $item->v1 . ';' . $item->v2 . ';' . $row->v1 . ';' . $row->prefix . $row->uid . "\r\n", FILE_APPEND);
				}
				*/
			}
		}
	}
	unset($log);
	
	$log = $mysqli->query('SELECT v1, v2 FROM bf_filter_193 WHERE (prefix = \''.$row->prefix.'\') AND (uid = \''.$row->uid.'\')');
	//$cl = count($log);
	if(isset($log[0])){
		foreach($log as $item){
			//$i++;
			//echo $i . ' iz ' . $cl . "\r";
			if(!empty($item->v1) && !empty($item->v2)){
				file_put_contents($dir['site'] . 'cache/yandex.ru.txt', $item->v1 . ';' . $item->v2 . "\r\n", FILE_APPEND);
				file_put_contents($dir['site'] . 'cache/yandex.ru_uid.txt', $item->v1 . ';' . $item->v2 . ';' . $row->prefix . $row->uid . "\r\n", FILE_APPEND);
				/*
				if(!empty($row->v1)){
					file_put_contents($dir['site'] . 'cache/yandex.ru_parol.txt', $item->v1 . ';' . $item->v2 . ';' . $row->v1 . ';' . $row->prefix . $row->uid . "\r\n", FILE_APPEND);
					file_put_contents($dir['site'] . 'cache/yandex.ru_parol_uid.txt', $item->v1 . ';' . $item->v2 . ';' . $row->v1 . ';' . $row->prefix . $row->uid . "\r\n", FILE_APPEND);
				}
				*/
			}
		}
	}
	unset($log);
}

$count = $mysqli->query_name('SELECT COUNT(id) count FROM bf_filter_205');
$ci = 0;
$mysqli->query('SELECT id, prefix, uid, v1 FROM bf_filter_205', null, 'start');

$count = $mysqli->query_name('SELECT COUNT(id) count FROM bf_filter_206');
$mysqli->query('SELECT id, prefix, uid, v1 FROM bf_filter_206', null, 'start');


?>