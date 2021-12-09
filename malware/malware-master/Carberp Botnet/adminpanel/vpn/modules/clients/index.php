<?php

get_function('html_pages');
$page['count_page'] = 100;

$count_users = $mysqli->table_rows('bf_clients');

function get_servers($row){
    global $servers;
    $servers[$row->id] = $row->name . ' ('.$row->ip.')';
}

$mysqli->query('SELECT id, ip, name FROM bf_servers', null, 'get_servers');

$smarty->assign("servers", $servers);


if($_SESSION['user']->config['infoacc'] == '1'){    
    $sql = '';
    
    foreach($_SESSION['user']->config['clients'] as $key => $item){
        $sql .= ' OR (id = \''.$key.'\')';
    }
    $sql = preg_replace('~^ OR ~', '', $sql);
    
    if(!empty($sql)){
        $smarty->assign("list", $mysqli->query('SELECT `id`, `name`, `desc`, inip, `server`, `status`, `autocheck`, `enable` FROM bf_clients WHERE '.$sql.' ORDER by id DESC LIMIT '.($Cur['page'] == 0 ? 0 : $Cur['page']*$page['count_page']).','.$page['count_page'], null, null, false));
    }
}else{
    $smarty->assign("list", $mysqli->query('SELECT `id`, `name`, `desc`, inip, `server`, `status`, `autocheck`, `enable` FROM bf_clients ORDER by id DESC LIMIT '.($Cur['page'] == 0 ? 0 : $Cur['page']*$page['count_page']).','.$page['count_page'], null, null, false));
}

$smarty->assign('pages', html_pages('/clients/?', $count_users, $page['count_page']));
$smarty->assign('count_users', $count_users);

$smarty->assign('title', $lang['clients']);

?>