<?php

$page['count_page'] = 100;
$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));
$count_users = $mysqli->query_name('SELECT count(id) count FROM bf_search_result where (sid = \''.$Cur['id'].'\')');
$smarty->assign("list", $mysqli->query('SELECT * FROM bf_search_task where (id = \''.$Cur['id'].'\') LIMIT 1'));
$smarty->assign("result", $mysqli->query('SELECT * FROM bf_search_result where (sid = \''.$Cur['id'].'\') ORDER by id DESC LIMIT '.($Cur['page'] == 0 ? 0 : $Cur['page']*$page['count_page']).','.$page['count_page'], null, null, false));
$smarty->assign('pages', html_pages('/files/result-'.$Cur['id'].'.html?window=1', $count_users, $page['count_page'], 1, 'search_lp'));
?>