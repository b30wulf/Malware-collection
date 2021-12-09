<?php

$dir = str_replace('/scripts/set', '', str_replace('\\', '/', realpath('.'))) . '/';

error_reporting(0);
ini_set('error_reporting', 0);
header("Pragma: no-cache");
header("Expires: 0");

$config = file_exists($dir . 'cache/config.json') ? json_decode(file_get_contents($dir . 'cache/config.json'), 1) : '';

include_once($dir . 'includes/functions.get_config.php');

$cfg_db = get_config();

require_once($dir . 'classes/mysqli.class.lite.php');
$mysqli = new mysqli_db();

$mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
unset($cfg_db);
if(count($mysqli->errors) > 0) print_data('DB_ERROR!', true);

$mysqli->query('SET TIME_ZONE = \'+02:00\'');

$_GET['p'] = preg_match_all('~^(.*)\.~is', $_GET['p'], $out, PREG_SET_ORDER);
$_GET['p'] = $out[0][1];

$man = $mysqli->query('SELECT * FROM bf_manuals WHERE (rand = \''.$_GET['p'].'\') AND (expiry_date != \'0000-00-00 00:00:00\') AND (TIMESTAMPDIFF(second, CURRENT_TIMESTAMP(), expiry_date) > 0) LIMIT 1');

if($man->rand == $_GET['p'] && file_exists($dir . 'templates/modules/transfers/manual/'.strtolower($man->system).'.txt')){
	include_once($dir . 'includes/functions.rc.php');
	
	$man->key = @rc_decode($man->key, 'AUvS8jou0Z9K7Bf9');
	
	if(!empty($man->bin)){
		header( 'Content-Disposition: attachment;');
		print(rc_encode(base64_decode($man->bin), $man->key));
	}else{
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
	}
}

?>