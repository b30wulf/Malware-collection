#!/usr/bin/env php
<?php

function generatePassword ($length = 8){
	$password = '';
	$possible = "0123456789aAbBcCdDfFgGhHjJkKmMnNpPqQrRsStTvVwWxXyYzZ";
	$i = 0;
	while ($i < $length){
		$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
		if (!strstr($password, $char)) {
			$password .= $char;
			$i++;
		}
	}
	$password = str_replace('BJB', 'JBJ', $password);
	return $password;
}

$cfg_db['host'] = 'localhost';
$cfg_db['user'] = 'ftpuser';
$cfg_db['pass'] = 'w46UTzpf';
$cfg_db['db'] = 'pureftpd';

$mysqli = new mysqli($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
if(mysqli_connect_errno()){
	print('not mysql');
	exit;
}

$mysqli->real_query("SET NAMES utf8");

$dir = array();

$dir['ftp_scandir'] = '/home/vnc1/ftp/';

$ftp['uid'] = '500';
$ftp['gid'] = '502';

$ud = scandir($dir['ftp_scandir']);
unset($ud[0], $ud[1]);

foreach($ud as $user){
	$pass = generatePassword(8);

    $user_orig = $user;
	$user = str_replace(' ', '_', $user);

	$mysqli->real_query("INSERT INTO `users` (`User`, `Password`, `Uid`, `Gid`, `Dir`) VALUES ('".$user."', MD5('".$pass."'), '".$ftp['uid']."', '".$ftp['gid']."', '".$dir['ftp_scandir'] . $user_orig . "/') ON DUPLICATE KEY UPDATE `Password` = MD5('".$pass."'), `Uid` = '".$ftp['uid']."', `Gid` = '".$ftp['gid']."', `Dir` = '".$dir['ftp_scandir'] . $user_orig . "/';");

    $txt = '';
    $txt .= 'FTP User: ' . $user . "\r\n";
    $txt .= 'FTP Pass: ' . $pass . "\r\n";
    $txt .= "\r\n";

    file_put_contents('ftp_users.txt', $txt, FILE_APPEND);
}

?>