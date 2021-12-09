<?php

@unlink('cache/check_download.txt');

if(!is_writable('cache/')) @chmod('cache/', '777');
file_put_contents('cache/check_download.txt', md5(time()));

if(!is_writable('cache/check_download.txt')){	file_put_contents('cache/check_download.txt', md5(time()));
	@chmod('cache/check_download.txt', '777');
}

if(file_exists(realpath('cache/check_download.txt'))){	if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){		header('X-LIGHTTPD-send-file: ' . realpath('cache/check_download.txt'));
	}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){		header('X-Sendfile: ' . realpath('cache/check_download.txt'));
	}
}

exit;
?>