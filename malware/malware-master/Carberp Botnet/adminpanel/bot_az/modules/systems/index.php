<?php

if($Cur['str'] == 'format_info'){	$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));
	$smarty->display('modules/systems/format_info.tpl');
	exit;
}

get_function('html_pages');
$page['count_page'] = 100;

if($_SESSION['user']->config['infoacc'] == '1'){
	$sql = '';
	
	foreach($_SESSION['user']->config['systems'] as $key => $item){
		$sql .= ' OR (nid = \''.$key.'\')';
	}
	$sql = preg_replace('~^ OR ~', '', $sql);
	
	if($_SESSION['user']->access['logs']['cc'] != 'on'){
		//$counts = $mysqli->table_rows('bf_systems');
		$counts = $mysqli->query_name('SELECT COUNT(nid) count FROM bf_systems WHERE ('.$sql.') AND (nid != \'cc\')');
		$smarty->assign("list", $mysqli->query('SELECT * FROM bf_systems WHERE ('.$sql.') ORDER by id DESC LIMIT '.($Cur['page'] == 0 ? 0 : $Cur['page']*$page['count_page']).','.$page['count_page'], null, null, false));
	}else{
		$counts = $mysqli->query_name('SELECT COUNT(nid) count FROM bf_systems WHERE ('.$sql.')');
		$smarty->assign("list", $mysqli->query('SELECT * FROM bf_systems WHERE ('.$sql.') ORDER by id DESC LIMIT '.($Cur['page'] == 0 ? 0 : $Cur['page']*$page['count_page']).','.$page['count_page'], null, null, false));
	}
}else{
	if($_SESSION['user']->access['logs']['cc'] != 'on'){
		$counts = $mysqli->query_name('SELECT COUNT(nid) count FROM bf_systems WHERE (nid != \'cc\')');
		$smarty->assign("list", $mysqli->query('SELECT * FROM bf_systems ORDER by id DESC LIMIT '.($Cur['page'] == 0 ? 0 : $Cur['page']*$page['count_page']).','.$page['count_page'], null, null, false));
	}else{
		$counts = $mysqli->table_rows('bf_systems');
		$smarty->assign("list", $mysqli->query('SELECT * FROM bf_systems ORDER by id DESC LIMIT '.($Cur['page'] == 0 ? 0 : $Cur['page']*$page['count_page']).','.$page['count_page'], null, null, false));
	}
}



$smarty->assign('pages', html_pages('/systems/?', $counts, $page['count_page']));
$smarty->assign('count_users', $counts);

$smarty->assign('title', $lang['systems']);

?>