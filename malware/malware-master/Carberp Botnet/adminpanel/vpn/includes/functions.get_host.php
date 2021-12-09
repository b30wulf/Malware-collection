<?php

/*
function get_host($url){
	$url = str_replace('www.', '', $url);
	$base = @parse_url($url, PHP_URL_HOST); // PHP 5.2.1 or leter
	if(preg_match('~([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})~', $base)){
		return $base;
	}else{
		$parse = explode('.', $base);
		if(count($parse) == 2){
			return $base;
		}else{
			$num = count($parse)-2;
			if(strlen($parse[$num]) <= 3){
				return $parse[$num-1] . '.' . $parse[$num] . '.' . $parse[$num+1];
			}else{
				return $parse[$num] . '.' . $parse[$num+1];
			}
		}
	}
}
*/

function get_host($url){
	return @parse_url(str_replace('www.', '', strtolower($url)), PHP_URL_HOST); // PHP 5.2.1 or leter
}

?>