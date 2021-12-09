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

$i = 0;

if ($dh = opendir($dir . '/logs/unnecessary/fgr/')) {
	while (($file = readdir($dh)) !== false) {
		if($file != '.' && $file != '..'){
			$i++;
			$f = $mysqli->query('SELECT md5 FROM bf_unnecessary WHERE (md5 = \''.$file.'\') LIMIT 1');
			if($f->md5 != $file){
				echo "delete: /fgr/$file \n";
				unlink($dir . '/logs/unnecessary/fgr/' . $file);
			}
		}
	}
	closedir($dh);
}

if ($dh = opendir($dir . '/logs/unnecessary/gra/')) {
	while (($file = readdir($dh)) !== false) {
		if($file != '.' && $file != '..'){
			$i++;
			$f = $mysqli->query('SELECT md5 FROM bf_unnecessary WHERE (md5 = \''.$file.'\') LIMIT 1');
			if($f->md5 != $file){
				echo "delete: /gra/$file \n";
				unlink($dir . '/logs/unnecessary/gra/' . $file);
			}
		}
	}
	closedir($dh);
}

echo $i;

?>