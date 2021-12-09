<?php

ini_set('error_reporting', 0);
ini_set('memory_limit', '1024M');

set_time_limit(0);

header("Content-Type: text/plain");
header("Pragma: no-cache");
header("Expires: 0");

switch($Cur['x']){
	case 'count_domain':
		$text = file_get_contents(realpath('cache/cc/' . $Cur['str']));
		preg_match_all('~http:\/\/(.*)\/~isU', $text, $text);
		unset($text[0]);
		
		$domain = array();
		foreach($text[1] as $item){
			if(preg_match('~^([a-zA-Z0-9-_.]+)$~is', $item)){
				@$domain[$item] += 1;
			}
		}
		arsort($domain);
		
		print_r($domain);	
	break;

	case 'count_domain_files':
		$files = scandir(realpath('cache/cc/'));
		unset($files[0], $files[1]);
		
		if(file_exists('cache/cc_files.txt')){
			$cc_files = json_decode(file_get_contents('cache/cc_files.txt'), true);
		}else{
			$cc_files = array();
		}
		
		if(file_exists('cache/cc_domian.txt')){
			$domain = json_decode(file_get_contents('cache/cc_domian.txt'), true);
		}else{
			$domain = array();
		}
		
		foreach($files as $file){
			if(!isset($cc_files[$file])){
				$text = file_get_contents(realpath('cache/cc/' . $file));
				preg_match_all('~http:\/\/(.*)\/~isU', $text, $text);
				unset($text[0]);
				
				foreach($text[1] as $item){
					if(preg_match('~^([a-zA-Z0-9-_.]+)$~is', $item)){
						@$domain[$item] += 1;
					}
				}
				unset($text);
				$cc_files[$file] = true;
			}
		}
		
		arsort($domain);
		
		file_put_contents('cache/cc_files.txt', json_encode($cc_files));
		file_put_contents('cache/cc_domian.txt', json_encode($domain));
		
		print_r($domain);
	break;	
	
	case 'count_domain2_files':
		$files = scandir(realpath('cache/cc/'));
		unset($files[0], $files[1]);
		
		if(file_exists('cache/cc_files.txt')){
			$cc_files = json_decode(file_get_contents('cache/cc_files.txt'), true);
		}else{
			$cc_files = array();
		}
		
		if(file_exists('cache/cc_domian.txt')){
			$domain = json_decode(file_get_contents('cache/cc_domian.txt'), true);
		}else{
			$domain = array();
		}
		
		foreach($files as $file){
			if(!isset($cc_files[$file])){
				$text = file_get_contents(realpath('cache/cc/' . $file));
				preg_match_all('~http:\/\/(.*)\/~isU', $text, $text);
				unset($text[0]);
				
				foreach($text[1] as $item){
					if(preg_match('~^([a-zA-Z0-9-_.]+)$~is', $item)){
						@$domain[$item] += 1;
					}
				}
				unset($text);
				$cc_files[$file] = true;
			}
		}
		
		//arsort($domain);
		
		file_put_contents('cache/cc_files.txt', json_encode($cc_files));
		file_put_contents('cache/cc_domian.txt', json_encode($domain));
		
		$dom = array();
		foreach($domain as $k => $i){
		    $key = explode('.', $k);
		    if(!preg_match('~^[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+$~is', $k)){
			if(count($key) > 2){
			    $key = array_reverse($key);
			    @$dom[$key[1] . '.' . $key[0]] += $i;
			}else{
			    @$dom[$k] += $i;
			}
		    }else{
			@$dom[$k] = $i;
		    }
		}
		
		arsort($dom);
		
		print_r($dom);
	break;	
	
	default:
		if(empty($Cur['str'])) exit;
		if(file_exists('cache/cc/' . $Cur['str'])){
			if($Cur['type'] == '1') header( "Content-Disposition: attachment; filename=\"" . $Cur['str'] . '.txt"' );
			if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header( 'X-LIGHTTPD-send-file: ' . realpath('cache/cc/' . $Cur['str']));
			}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
				header('X-Sendfile: ' . realpath('cache/cc/' . $Cur['str']));
			}
		}
	break;
}

exit;

?>