<?php

error_reporting(0);
set_time_limit(120);
ini_set('max_execution_time', 120);

$dir = str_replace('scripts', '', realpath('.'));
$url = file_get_contents($dir . 'includes/url.cfg');
$url = trim($url, "\r");
$url = trim($url, "\n");

$post = array();
if(count($_POST) > 0){	foreach($_POST as $k => $p){		$post[$k] = $p;
	}
}

$df = array();
if(count($_FILES) > 0){	foreach($_FILES as $k => $p){
		$rf = $dir . 'cache/' .  $_FILES[$k]['name'];
		if(move_uploaded_file($_FILES[$k]['tmp_name'], $rf)){			$post[$k] = '@' . $rf;
			$df[] = $rf;
		}
	}
}

$post['remote_ip'] = $_SERVER['REMOTE_ADDR'];

header('Content-Type: text/html; charset=utf-8');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url . substr($_SERVER["REQUEST_URI"], 1, strlen($_SERVER["REQUEST_URI"])));
curl_setopt($ch, CURLOPT_FAILONERROR, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect: '));
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
$result = curl_exec($ch);
$code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
curl_close($ch);

if($code == '403'){
	header("Status: 403 Forbidden");
	header("HTTP/1.1 403 Forbidden");
}elseif($code == '404'){
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
}

if(!empty($result)){	print($result);
}else{	print($result);
}

foreach($df as $f){
	if(file_exists($f)) unlink($f);
}

?>