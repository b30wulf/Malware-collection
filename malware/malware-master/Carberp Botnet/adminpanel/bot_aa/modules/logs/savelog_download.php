<?php
header("Content-Type:text/plain");
if(empty($Cur['id'])) exit;

$item = $mysqli->query('SELECT * FROM bf_save_ilog WHERE (id=\''.$Cur['id'].'\') LIMIT 1');

if($item->id == $Cur['id']){	if($item->type == '6'){
		$item->type = 'gra';
	}elseif($item->type == '5'){		$item->type = 'fgr';
	}else{		exit;
	}

	if(file_exists('logs/save_logs/' . $item->type . '/' . $item->md5)){
		if($Cur['type'] == '1') header( "Content-Disposition: attachment; filename=\"" . $item->host . '.txt"' );
		if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
			header( 'X-LIGHTTPD-send-file: ' . realpath('logs/save_logs/' . $item->type . '/' . $item->md5));
		}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
			header('X-Sendfile: ' . realpath('logs/save_logs/' . $item->type . '/' . $item->md5));
		}
	}
}

exit;

?>