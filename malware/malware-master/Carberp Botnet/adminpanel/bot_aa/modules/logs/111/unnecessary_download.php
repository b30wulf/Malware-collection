<?php
header("Content-Type:text/plain");
if(empty($Cur['id'])) exit;

$item = $mysqli->query('SELECT * FROM bf_unnecessary WHERE (id=\''.$Cur['id'].'\') LIMIT 1');

if($item->id == $Cur['id']){	$item->host_pre = mb_substr($item->host, 0, 2, 'utf8');
	if($item->type == '6'){
		if(file_exists('logs/unnecessary/gra/' . $item->host_pre . '/' . $item->md5)){
			if($Cur['type'] == '1') header( "Content-Disposition: attachment; filename=\"" . $item->host . '.txt"' );
			if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header( 'X-LIGHTTPD-send-file: ' . realpath('logs/unnecessary/gra/' . $item->host_pre . '/' . $item->md5));
			}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header('X-Sendfile: ' . realpath('logs/unnecessary/gra/' . $item->host_pre . '/' . $item->md5));
			}
		}
        /*
		if(file_exists('logs/unnecessary/gra/' . $item->md5)){			if($Cur['type'] == '1') header( "Content-Disposition: attachment; filename=\"" . $item->host . '.txt"' );
			if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){				header( 'X-LIGHTTPD-send-file: ' . realpath('logs/unnecessary/gra/' . $item->md5));
			}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){				header('X-Sendfile: ' . realpath('logs/unnecessary/gra/' . $item->md5));
			}
		}
		*/
	}elseif($item->type == '5'){
		if(file_exists('logs/unnecessary/fgr/' . $item->host_pre . '/' . $item->md5)){
			if($Cur['type'] == '1') header( "Content-Disposition: attachment; filename=\"" . $item->host . '.txt"' );
			if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header( 'X-LIGHTTPD-send-file: ' . realpath('logs/unnecessary/fgr/' . $item->host_pre . '/' . $item->md5));
			}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header('X-Sendfile: ' . realpath('logs/unnecessary/fgr/' . $item->host_pre . '/' . $item->md5));
			}
		}
	}else{		exit;
	}
}

exit;

?>