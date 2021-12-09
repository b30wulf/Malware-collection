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
file_put_contents($dir['site'] . 'cache/dirs_downloads.json', json_encode($dir));

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
$thread->settings["file_proc"] = $dir['site'] . 'crons/downloads_file.php';
$thread->settings["error_file"] = $dir['site'] . 'cache/error_downloads.txt';
$thread->settings["pid_file"] = $dir['site'] . 'cache/pid_downloads.txt';
$thread->settings["memory_limit"] = '256M';

$thread->clear();
$thread->set_pid();

function load_task($row){
	global $mysqli, $thread;

	$mysqli->query("INSERT INTO bf_threads (type, size, post_id, script) VALUES ('".$row->type."', '".$row->size."', '".$row->id."', '".$thread->settings["uniq"]."')");
}

function del_files($row){
	global $mysqli;
	$mysqli->query('delete from bf_files where (status = \'0\') AND (post_id = \''.$row->id.'\')');
}

$sdf = scandir($dir['site'] . 'cache/sdf/');
unset($sdf[0], $sdf[1]);

foreach($sdf as $f) @unlink($dir['site'] . 'cache/sdf/' . $f);

$mysqli->query('SELECT id FROM `bf_admins` WHERE datediff(NOW(), update_date) >= 1', null, 'del_files');

//$mysqli->query('SELECT * FROM bf_files WHERE (status=\'0\')', null, 'load_task');
$mysqli->query('SELECT a.* FROM bf_files a, bf_admins b WHERE (a.status=\'0\') AND (a.size != \'0\') AND (b.id = a.post_id) AND (DATEDIFF( NOW( ) , b.update_date ) = 0)', null, 'load_task');

$thread->start();

$mysqli->query('UPDATE bf_files SET file = REPLACE(file, \'/srv/www/vhosts/adm.piqa.in/logs//\', \'\')');

;

?>