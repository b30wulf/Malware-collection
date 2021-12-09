<?php

$dir = str_replace('/scripts/set', '', str_replace('\\', '/', realpath('.'))) . '/';

error_reporting(0);
ini_set('error_reporting', 0);
header("Pragma: no-cache");
header("Expires: 0");

function no_found(){	global $dir;
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	print(file_get_contents($dir . '404.html'));
	exit;
}

if(!empty($_POST['remote_ip'])){
	$_SERVER['REMOTE_ADDR'] = $_POST['remote_ip'];
	unset($_POST['remote_ip']);
}elseif(!empty($_SERVER["HTTP_X_REAL_IP"])){
	$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_X_REAL_IP"];
}

$config = file_exists($dir . 'cache/config.json') ? json_decode(file_get_contents($dir . 'cache/config.json'), 1) : '';

if(!empty($_GET['p'])){	$l = strlen($_GET['p']);
	unset($_GET['p']);
	switch(true){		case (($l >= 1 && $l <= 6) || ($l >= 18 && $l <= 24)):
        	include_once('task.php');
        break;

        case (($l > 6 && $l <= 12) || ($l > 24 && $l <= 30)):
        	include_once('first.php');
        break;

        case (($l > 12 && $l < 18) || ($l > 30 && $l <= 36)):
        	include_once('plugs.php');
        break;

        case (($l > 36 && $l <= 48)):
        	include_once('comment.php');
        break;

        case (($l > 48 && $l <= 56)):
        	include_once('hunter.php');
        break;

        case (($l > 56 && $l <= 64)):
        	include_once('cfgs.php');
        break;

        default:
        	no_found();
        break;
    }
}else{	no_found();
}

?>