<?php

ini_set('max_execution_time', 0);
$dir = realpath('.');

$files = array();

function list_dir($s){
	global $files;
	$rp_s = realpath($s);
	$s_ = scandir($rp_s);
	unset($s_[0], $s_[1]);
	if(count($s_) > 0){
		foreach($s_ as $file){
			if(is_dir($rp_s . '/' . $file)){
				if($file != 'unnecessary'){
					//echo $rp_s . '/' . $file . '/' . '<br>';
					list_dir($rp_s . '/' . $file . '/');
				}
			}elseif(is_file($rp_s . '/' . $file)){
				if(basename($file) != '.htaccess'){
					//echo $rp_s . '/' . $file . '<br>';
					$files[] = $rp_s . '/' . $file;
				}
			}
		}
	}
}

list_dir($dir . '/logs');

foreach($files as $key => $file){	$fz = str_replace($dir . '/logs', '', $file);
	$f = $mysqli->query('SELECT id,file,size FROM bf_files WHERE (file = \''.$fz.'\') LIMIT 1');
	if($f->file == $fz){		unset($files[$key]);
	}else{		@unlink($file);
	}
}

?>