<?php
header("Content-Type: text/plain");

if($Cur['id'] != 6){	if(!preg_match('~^([a-zA-Z]+)$~is', $Cur['str'])){		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
		exit;
	}

	if(!preg_match('~^([a-zA-Z0-9._]+)\.txt$~is', $Cur['file'])){		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
		exit;
	}
}else{	if(!preg_match('~^([a-zA-Z0-9]+)$~is', $Cur['file'])){
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
		exit;
	}
}

switch($Cur['id']){
	case 1:
    	if(file_exists('logs/import/gra/' . $Cur['str'] . '/' . $Cur['file'])){
			if($Cur['type'] == 1) header( 'Content-Disposition: attachment; filename="' . $Cur['str'] . '_' . $Cur['file'] . '"');
			if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header( 'X-LIGHTTPD-send-file: ' . realpath('logs/import/gra/' . $Cur['str'] . '/' . $Cur['file']));
			}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header('X-Sendfile: ' . realpath('logs/import/gra/' . $Cur['str'] . '/' . $Cur['file']));
				header('X-Accel-Redirect: /logs/import/gra/' . $Cur['str'] . '/' . $Cur['file']);
			}
		}
	break;

	case 2:
		if(file_exists('logs/import/fgr/' . $Cur['str'] . '/' . $Cur['file'])){
			if($Cur['type'] == 1) header( 'Content-Disposition: attachment; filename="' . $Cur['str'] . '_' . $Cur['file'] . '"');
			if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header( 'X-LIGHTTPD-send-file: ' . realpath('logs/import/fgr/' . $Cur['str'] . '/' . $Cur['file']));
			}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header('X-Sendfile: ' . realpath('logs/import/fgr/' . $Cur['str'] . '/' . $Cur['file']));
				header('X-Accel-Redirect: /logs/import/fgr/' . $Cur['str'] . '/' . $Cur['file']);
			}
		}
	break;

	case 3:
    	if(file_exists('logs/import/sni/' . $Cur['str'] . '/' . $Cur['file'])){
			if($Cur['type'] == 1) header( 'Content-Disposition: attachment; filename="' . $Cur['str'] . '_' . $Cur['file'] . '"');
			if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header( 'X-LIGHTTPD-send-file: ' . realpath('logs/import/sni/' . $Cur['str'] . '/' . $Cur['file']));
			}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header('X-Sendfile: ' . realpath('logs/import/sni/' . $Cur['str'] . '/' . $Cur['file']));
				header('X-Accel-Redirect: /logs/import/sni/' . $Cur['str'] . '/' . $Cur['file']);
			}
		}
	break;

	case 4:
    	if(file_exists('logs/import/tra/' . $Cur['str'] . '/' . $Cur['file'])){
			if($Cur['type'] == 1) header( 'Content-Disposition: attachment; filename="' . $Cur['str'] . '_' . $Cur['file'] . '"');
			if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header( 'X-LIGHTTPD-send-file: ' . realpath('logs/import/tra/' . $Cur['str'] . '/' . $Cur['file']));
			}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header('X-Sendfile: ' . realpath('logs/import/tra/' . $Cur['str'] . '/' . $Cur['file']));
				header('X-Accel-Redirect: /logs/import/tra/' . $Cur['str'] . '/' . $Cur['file']);
			}
		}
	break;

	case 5:
       	if(file_exists('logs/bots/' . $Cur['str'] . '/' . $Cur['name'] . '/' . $Cur['file'])){
			if($Cur['type'] == 1) header( 'Content-Disposition: attachment; filename="' . $Cur['str'] . $Cur['name'] . '_' . $Cur['file'] . '"');
			if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header( 'X-LIGHTTPD-send-file: ' . realpath('logs/bots/' . $Cur['str'] . '/' . $Cur['name'] . '/' . $Cur['file']));
			}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header('X-Sendfile: ' . realpath('logs/bots/' . $Cur['str'] . '/' . $Cur['name'] . '/' . $Cur['file']));
				header('X-Accel-Redirect: /logs/bots/' . $Cur['str'] . '/' . $Cur['name'] . '/' . $Cur['file']);
			}
		}
	break;

	case 6:
       	if(file_exists('cache/search/' . $Cur['file'])){
			if($Cur['type'] == 1) header( 'Content-Disposition: attachment; filename="' . $Cur['file'] . '"');
			if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header( 'X-LIGHTTPD-send-file: ' . realpath('cache/search/' . $Cur['file']));
			}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header('X-Sendfile: ' . realpath('cache/search/' . $Cur['file']));
				header('X-Accel-Redirect: cache/search/' . $Cur['file']);
			}
		}
	break;
}

exit;

?>