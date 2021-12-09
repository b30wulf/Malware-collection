<?php

$dir = str_replace('/scripts/set', '', str_replace('\\', '/', realpath('.'))) . '/';

include_once($dir . 'includes/functions.first.php');
include_once($dir . 'includes/functions.get_config.php');
include_once($dir . 'includes/functions.prefix.php');

if(file_exists($dir . 'cache/config.json')) $config = json_decode(file_get_contents($dir . 'cache/config.json'), 1);

if(empty($_POST['t'])) print_data('NOT TYPE', true);

switch($_POST['t']){	case '1':
    	if(!empty($_POST['hash']) && !empty($_POST['comment'])){
    		$cfg_db = get_config();
			require_once($dir . 'classes/mysqli.class.lite.php');
			$mysqli = new mysqli_db();
            $mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
            if(count($mysqli->errors) > 0) print_data('DB_ERROR!', true);
            unset($cfg_db);

			$com = $mysqli->query('SELECT id, comment FROM bf_comments WHERE (prefix = \''.$_POST['prefix'].'\') AND (uid = \''.$_POST['uid'].'\') AND (uniq = \''.$_POST['hash'].'\') AND (type = \'9\') LIMIT 1', null, null, false);
			if(count($com)){				foreach($com as $c){
					if(strpos($c->comment, '{') != false){                        $mysqli->query('update bf_comments set comment = \' '. preg_replace('~([ ]+)~is', ' ', preg_replace('~ {(.*)} ~isU', ' {' . $_POST['comment'] . '} ', $c->comment)) . ' \' WHERE (id = \''.$c->id.'\')');
					}else{
						$mysqli->query('update bf_comments set comment = \'' . $c->comment . ' {' . $_POST['comment'] . '} \' WHERE (id = \''.$c->id.'\')');
					}
				}
			}else{				$mysqli->query('INSERT DELAYED INTO bf_comments (prefix, uid, type, uniq, comment) VALUES (\''.$_POST['prefix'].'\', \''.$_POST['uid'].'\', \'9\', \''.$_POST['hash'].'\', \' {'.$_POST['comment'].'} \')');
			}
    	}
	break;

	case '2':
    	if(!empty($_POST['type']) && !empty($_POST['comment'])){
    		$cfg_db = get_config();
			require_once($dir . 'classes/mysqli.class.lite.php');
			$mysqli = new mysqli_db();
            $mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
            if(count($mysqli->errors) > 0) print_data('DB_ERROR!', true);
            unset($cfg_db);

            $com = $mysqli->query('SELECT id, comment FROM bf_comments WHERE (prefix = \''.$_POST['prefix'].'\') AND (uid = \''.$_POST['uid'].'\') AND (type = \''.$_POST['type'].'\') LIMIT 1', null, null, false);
			if(count($com)){
				foreach($com as $c){
					if(strpos($c->comment, '{') != false){
                        $mysqli->query('update bf_comments set comment = \' '. preg_replace('~([ ]+)~is', ' ', preg_replace('~ {(.*)} ~isU', ' {' . $_POST['comment'] . '} ', $c->comment)) . ' \' WHERE (id = \''.$c->id.'\')');
					}else{
						$mysqli->query('update bf_comments set comment = \'' . $c->comment . ' {' . $_POST['comment'] . '} \' WHERE (id = \''.$c->id.'\')');
					}
				}
			}else{
				$mysqli->query('INSERT DELAYED INTO bf_comments (prefix, uid, type, comment) VALUES (\''.$_POST['prefix'].'\', \''.$_POST['uid'].'\', \''.$_POST['type'].'\',\' {'.$_POST['comment'].'} \')');
			}
    	}
	break;

	default:
		exit;
	break;
}

?>