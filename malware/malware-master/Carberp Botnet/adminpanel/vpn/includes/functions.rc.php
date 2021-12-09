<?php

function generatePassword ($length = 8){	$password = '';
	$possible = "0123456789aAbBcCdDfFgGhHjJkKmMnNpPqQrRsStTvVwWxXyYzZ";
	$i = 0;
	while ($i < $length){		$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
		if (!strstr($password, $char)) {			$password .= $char;
			$i++;
		}
	}
	$password = str_replace('BJB', 'JBJ', $password);
	return $password;
}

function rc_encode($str, $key = ''){
	global $rc, $no_base64;
	//$str = urldecode($str);
	if(empty($str)) return '';
	if(empty($key)) $key = $rc['key'];
    if(!isset($no_base64)) $no_base64 = false;
	$iv = generatePassword(8);
	$data = openssl_encrypt($str, 'RC2-CBC', $key, $no_base64, $iv);
	if(strpos($data, '==') !== false){
		return substr($iv, 0, 4) . substr($data, 0, strlen($data)-2) . substr($iv, 4, 8) . '==';
	}elseif(strpos($data, '=') !== false){
		return substr($iv, 0, 4) . substr($data, 0, strlen($data)-1) . substr($iv, 4, 8) . '=';
	}else{
		return substr($iv, 0, 4) . $data . substr($iv, 4, 8);
	}
	//return openssl_encrypt($str, 'RC2-CBC', $key, false, $iv);
}

function rc_decode($str, $key = ''){
	global $rc, $no_base64;
	$str = urldecode($str);
	if(empty($str)) return '';
	if(empty($key)) $key = $rc['key'];
	if(!isset($no_base64)) $no_base64 = false;
	$str = str_replace(' ', '+', $str);
	if(strpos($str, '==') !== false){
		$iv = substr($str, 0, 4) . str_replace('==', '', substr($str, strlen($str)-6, strlen($str)-4));
		return openssl_decrypt(substr($str, 4, strlen($str)-10) . '==', 'RC2-CBC', $key, $no_base64, $iv);
	}elseif(strpos($str, '=') !== false){
		$iv = substr($str, 0, 4) . str_replace('=', '', substr($str, strlen($str)-5, strlen($str)-3));
		return openssl_decrypt(substr($str, 4, strlen($str)-9) . '=', 'RC2-CBC', $key, $no_base64, $iv);
	}else{
		$iv = substr($str, 0, 4) . substr($str, strlen($str)-4, strlen($str));
		return openssl_decrypt(substr($str, 4, strlen($str)-8), 'RC2-CBC', $key, $no_base64, $iv);
	}
	//return openssl_decrypt($str, 'RC2-CBC', $key, false, $iv);
}

function rc_encode_aes($str, $key = ''){
	global $rc, $no_base64;
	if(empty($key)) $key = $rc['key'];
	if(!isset($no_base64)) $no_base64 = false;
	$iv = generatePassword(16);
	$data = openssl_encrypt($str, 'AES-256-CBC', $key, $no_base64, $iv);
	if(strpos($data, '==') !== false){
		return substr($iv, 0, 8) . substr($data, 0, strlen($data)-2) . substr($iv, 8, 16) . '==';
	}elseif(strpos($data, '=') !== false){
		return substr($iv, 0, 8) . substr($data, 0, strlen($data)-1) . substr($iv, 8, 16) . '=';
	}else{
		return substr($iv, 0, 8) . $data . substr($iv, 8, 16);
	}
	//return openssl_encrypt($str, 'RC2-CBC', $key, false, $iv);
}

function rc_decode_aes($str, $key = ''){
	global $rc, $no_base64;
	if(empty($key)) $key = $rc['key'];
	if(!isset($no_base64)) $no_base64 = false;
	$str = str_replace(' ', '+', $str);
	if(strpos($str, '==') !== false){
		$iv = substr($str, 0, 8) . str_replace('==', '', substr($str, strlen($str)-10, strlen($str)-8));
		//echo strlen($iv);
		return openssl_decrypt(substr($str, 8, strlen($str)-18) . '==', 'AES-256-CBC', $key, $no_base64, $iv);
	}elseif(strpos($str, '=') !== false){
		$iv = substr($str, 0, 8) . str_replace('=', '', substr($str, strlen($str)-9, strlen($str)-7));
		return openssl_decrypt(substr($str, 8, strlen($str)-17) . '=', 'AES-256-CBC', $key, $no_base64, $iv);
	}else{
		$iv = substr($str, 0, 8) . substr($str, strlen($str)-8, strlen($str));
		return openssl_decrypt(substr($str, 8, strlen($str)-16), 'AES-256-CBC', $key, $no_base64, $iv);
	}
}

?>