<?php

error_reporting(0);
set_time_limit(0);
ini_set('max_execution_time', 0);

header('Content-Type: text/html; charset=utf-8');

function donwload_file($link, $file){	$file_put = fopen($file, 'wb');
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $link);
	curl_setopt($ch, CURLOPT_FILE, $file_put);
	curl_setopt($ch, CURLOPT_FAILONERROR, false);
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect: '));
	curl_exec($ch);
	curl_close($ch);
	fclose($file_put);
}

$url = file_get_contents('includes/url.cfg');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url . 'get/cfg.html');
curl_setopt($ch, CURLOPT_FAILONERROR, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect: '));
$result = curl_exec($ch);
curl_close($ch);

$f = json_decode($result, true);

$cfg = scandir('cfg/', false);
unset($cfg[0], $cfg[1]);
foreach($cfg as $file){
	if($file != '.htaccess' && $file != '.' && $file != '..'){
		@unlink('cfg/' . $file);
	}
}

foreach($f as $z){
	donwload_file($url . 'cfg/' . $z, 'cfg/' . $z);
}

?>