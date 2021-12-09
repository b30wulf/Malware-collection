<?php
get_function('html_pages');
$page['count_page'] = 100;

if($_SESSION['user']->access['accounts']['list'] != 'on'){
	$count_users = 1;
	$smarty->assign("users", $mysqli->query('SELECT * FROM bf_users WHERE (id = \''.$_SESSION['user']->id.'\')', null, null, false));
	$smarty->assign('pages', html_pages('/accounts/?', $count_users, $page['count_page']));
	$smarty->assign('count_users', $count_users);
}else{	$count_users = $mysqli->table_rows('bf_users');
	$smarty->assign("users", $mysqli->query('SELECT * FROM bf_users WHERE (id<>0) ORDER by id ASC LIMIT '.($Cur['page'] == 0 ? 0 : $Cur['page']*$page['count_page']).','.$page['count_page'], null, null, false));
	$smarty->assign('pages', html_pages('/accounts/?', $count_users, $page['count_page']));
	$smarty->assign('count_users', $count_users);
}

$smarty->assign('title', $lang['accounts']);

?>