#!/usr/bin/env php
<?php
error_reporting(-1);

function real_path($p){
	return str_replace('//', '/', str_replace('\\', '/', realpath($p)) . '/');
}

$dir = array();
$dir['script'] = pathinfo(__FILE__, PATHINFO_DIRNAME);
$dir['site'] = real_path($dir['script'] . '/../') . '/';
$dir['logs'] = real_path($dir['site'] . '/logs/') . '/';
file_put_contents($dir['site'] . 'cache/dirs_panels.json', json_encode($dir));

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

$thread->settings['mp'] = '30';
$thread->settings["uniq"] = basename(__FILE__);
$thread->settings["file_proc"] = $dir['site'] . 'crons/panels_file.php';
$thread->settings["error_file"] = $dir['site'] . 'cache/error_panels.txt';
$thread->settings["pid_file"] = $dir['site'] . 'cache/pid_panels.txt';
$thread->settings["memory_limit"] = '256M';

$thread->clear();
$thread->set_pid();

function load_task($row){
	global $mysqli, $thread;

	$mysqli->query("INSERT INTO bf_threads (post_id, script) VALUES ('".$row->id."', '".$thread->settings["uniq"]."')");
}

$mysqli->query('SELECT id FROM bf_filter_panels WHERE (import = \'0\') AND ((program = \'WHM\') OR (program = \'cPanel\'))', null, 'load_task');

$thread->start();

?>