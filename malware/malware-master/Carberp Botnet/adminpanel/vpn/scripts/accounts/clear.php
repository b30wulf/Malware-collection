<?php

include_once('includes/functions.get_config.php');

$cfg_db = get_config();
require_once("classes/mysqli.class.php");
$mysqli = new mysqli_db();

$mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
if(count($mysqli->errors) > 0){
	print('ERROR_DB');
	exit;
}

$mysqli->query('TRUNCATE `bf_filters_files`');
$mysqli->query('TRUNCATE `bf_filter_ep`');
$mysqli->query('TRUNCATE `bf_filter_ft`');
$mysqli->query('TRUNCATE `bf_filter_me`');
$mysqli->query('TRUNCATE `bf_filter_rd`');

echo 'OK';

?>