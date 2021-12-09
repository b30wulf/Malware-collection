#!/usr/bin/env php
<?php

set_time_limit(0);
ini_set('max_execution_time', 0);

$dir = pathinfo(__FILE__, PATHINFO_DIRNAME);
$dir_site = realpath($dir . '/../../');

function error_handler($code, $msg, $file, $line){
	global $dir_site;
	if($code != 8) file_put_contents($dir['site'] . '/cache/jabber_errors_php.txt', print_r(array('code' => $code, 'msg' => $msg, 'file' => $file, 'line' => $line), true) . "\r\n", FILE_APPEND);
}
set_error_handler('error_handler');

function shutdown(){
    global $jabber;
    //$jabber->disconnect();
}
register_shutdown_function('shutdown');

$cfg = json_decode(file_get_contents($dir_site . '/cache/config.json'), true);
if(empty($cfg['jabber']['1']['uid']) || empty($cfg['jabber']['1']['pass']) || empty($cfg['jabber']['2']['uid']) || empty($cfg['jabber']['2']['pass'])) exit;

$PRINT_TEXT = false;
$SAVE_LOG = false;

if($PRINT_TEXT == true){
	error_reporting(-1);
}else{
	error_reporting(0);
}

$socket = @stream_socket_server("tcp://0.0.0.0:16818", $errno, $errstr);
if (!$socket) exit;

require_once($dir_site . '/classes/class.jabber2.php');

function generatePassword ($length = 8){
  $password = "";
  $possible = "0123456789bcdfghjkmnpqrstvwxyz";
  $i = 0;
  while ($i < $length){
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
    if (!strstr($password, $char)) {
      $password .= $char;
      $i++;
    }
  }
  return $password;
}

function getParam ($txt, $type = 1){
	switch($type){
		case 1:
			$return = array();
			if(stripos($txt, ' ') === false){
				$return['cmd'] = $txt;
			}else{
				$pos = stripos($txt, ' ');
				$return['cmd'] = substr($txt, 0, $pos);
				$return['txt'] = substr($txt, $pos);
			}
			return $return;
		break;

		case 2:
        	$return = array();
        	if(stripos($txt, ' ') === false){
				return false;
			}else{
				$pos = stripos($txt, ' ');
				$return['cmd'] = substr($txt, 0, $pos);
				$return['txt'] = substr($txt, $pos);
			}
			return $return;
		break;

		default: return false;
	}
}

$counts = array();

while(1){
	$cfg = json_decode(file_get_contents($dir_site . '/cache/config.json'), true);
	$jabber = new Jabber($cfg['jabber']['1']['uid'], $cfg['jabber']['1']['pass']);
    $jabber->resource = 'jBot';

	$jabber->login();

	if(!$jabber->connected()){
		unset($jabber);
		$jabber = new Jabber($cfg['jabber']['2']['uid'], $cfg['jabber']['2']['pass']);
		$jabber->login();
	}

	while($jabber->connected() != false){
        $jabber->get_messages();
        //file_put_contents('log.txt', print_r($jabber->session, true));
        if(file_exists($dir_site . '/cache/jabber_off')){
        	unlink($dir_site . '/cache/jabber_off');
        	exit;
        }
        $count_session = count($jabber->session['messages']);
		if($count_session > 0){
			foreach($jabber->session['messages'] as $key => $msg){
				//echo strpos($msg['from'], $cfg['jabber']['admin']);
				if(strpos($msg['from'], $cfg['jabber']['admin']) !== false){
					$param = getParam($msg['body']);

					switch($param['cmd']){
						case '!quit':
						case '!exit':
                        	$jabber->send_message($msg['from'], 'jBot is exit.', '', 'chat');
							//$jabber->disconnect();
							exit;
						break;

						case '!genpass':
						case '!gp':
							$jabber->send_message($msg['from'], generatePassword($param['txt']), '', 'chat');
						break;

						case '!sendmessage':
							$param['sub'] = getParam($param['txt'], 2);

							if(!empty($param['sub']['cmd']) && $param['sub']['txt']){
								$jabber->send_message($param['sub']['cmd'], $param['sub']['txt'], '', 'chat');
							}
						break;

						case '!statbot':
							//
						break;

						case '!statcmd':
							//
						break;
					}
                    unset($param);
				}
				unset($jabber->session['messages'][$key]);
				usleep(50000); // sleep 0.05 sec
			}
		}

		usleep(100000); // sleep 0.1 sec

		$files = scandir($dir_site . '/cache/jabber/', false);
		unset($files[0], $files[1]);

		if(count($files) > 0){
			foreach($files as $file){
				$cmd = explode('_', $file);

				switch($cmd[0]){
					case 'to':
						$txt = @file_get_contents($dir_site . '/cache/jabber/' . $file);
						if(!empty($txt)){
							$jabber->send_message($cmd[1], $txt, 'jBOT', '', 'chat');
							if($SAVE_LOG == true){
								file_put_contents('messages.txt', $txt . "\r\n\r\n", FILE_APPEND);
								file_put_contents('logs.txt', 'sendmessage: ' . $cmd[1] . "\r\n", FILE_APPEND);
							}
							@unlink($dir_site . '/cache/jabber/' . $file);
						}else{
							@unlink($dir_site . '/cache/jabber/' . $file);
						}
					break;
				}

				usleep(50000); // sleep 0.05 sec
			}
		}

		usleep(3000000); // sleep 3.0 sec
	}

    //$jabber->disconnect();
    unset($jabber);
}

?>