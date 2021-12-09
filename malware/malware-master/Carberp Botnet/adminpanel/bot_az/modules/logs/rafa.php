<?php

// cberfiz

get_function('html_pages');
$page['count_page'] = 100;

$gen = true;

if($_SESSION['user']->config['infoacc'] == '1'){
    if($_SESSION['user']->config['systems']['rafa'] != true){
        $gen = false;
        header('Location: /logs/');
        exit;
    }
}

if($gen == true){
    if(!empty($_SESSION['user']->config['userid'])){
        $count_users = $mysqli->query_name('SELECT COUNT(distinct(concat(prefix, uid))) count FROM bf_hidden WHERE (userid = \''.$_SESSION['user']->config['userid'].'\') AND (system = \'rafa\')');
        $smarty->assign("list", $mysqli->query('SELECT prefix, uid, summ, post_date FROM bf_hidden WHERE (userid = \''.$_SESSION['user']->config['userid'].'\') AND (system = \'rafa\') GROUP by prefix, uid ORDER by id DESC LIMIT '.($Cur['page'] == 0 ? 0 : $Cur['page']*$page['count_page']).','.$page['count_page'], null, null, false));
    }else{
        $count_users = $mysqli->query_name('SELECT COUNT(distinct(concat(prefix, uid))) count FROM bf_hidden WHERE (system = \'rafa\')');
        $smarty->assign("list", $mysqli->query('SELECT prefix, uid, summ, post_date FROM bf_hidden WHERE (system = \'rafa\') GROUP by prefix, uid ORDER by id DESC LIMIT '.($Cur['page'] == 0 ? 0 : $Cur['page']*$page['count_page']).','.$page['count_page'], null, null, false));
    }
    
    $smarty->assign('pages', html_pages('/logs/rafa.html?', $count_users, $page['count_page']));
    $smarty->assign('count_users', $count_users);
}

$smarty->assign('title', $lang['logs']);

?>