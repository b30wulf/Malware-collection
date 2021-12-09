#!/usr/bin/env php
<?php

$dir = '/srv/www/vhosts/adm.plaro.in/';

//$dir = "/srv/www/vhosts/adm.plaro.in/logs/unnecessary/fgr/";

include_once($dir . 'includes/config.php');
require_once($dir . 'classes/mysqli.class.php');
$mysqli = new mysqli_db();

$mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
unset($cfg_db);
if(count($mysqli->errors) > 0){
	error_log('DB_ERROR: ' . print_r($mysqli->errors, true),4);
	exit;
}

$files = $mysqli->query('SELECT id,file FROM bf_files WHERE (TIMESTAMPDIFF(DAY, post_date, NOW()) >= 90) AND (status = \'1\') AND (import = \'1\')');

$i = 0;

foreach($files as $f){
	$i++;
	$fi = $dir . 'logs' . $f->file;
	if(file_exists($fi)){
		unlink($fi);
		$mysqli->query('delete from bf_files where (id = \''.$f->id.'\')');
	}
	echo $i;
}

?>