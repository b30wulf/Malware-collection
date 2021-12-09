<?php

$dir = str_replace('scripts' . DIRECTORY_SEPARATOR . 'set', '', realpath('.'));

$url = file_get_contents($dir . 'includes/url.cfg');

error_reporting(0);
set_time_limit(120);
ini_set('max_execution_time', 120);

header('Content-Type: text/html; charset=utf-8');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url . 'set/plugs.html');
curl_setopt($ch, CURLOPT_FAILONERROR, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect: '));
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents("php://input") . '&remote_ip=' . $_SERVER['REMOTE_ADDR']);
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

print($result);

?>