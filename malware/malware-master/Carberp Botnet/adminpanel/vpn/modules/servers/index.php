<?php

function system_to($cmd){
    global $to;
    $to .= $cmd . "\n\n";
}

function suexec($deamon = false){
    global $to;
    $file = '/tmp/phpexec_'.mt_rand().'.sh';
    file_put_contents($file, '#!/bin/sh' . "\n");
    file_put_contents($file, $to . "\n", FILE_APPEND);
    @system('sudo /bin/chmod 777 ' . $file);
    
    if($deamon == true){
        @system('sudo ' . $file . ' > /dev/null &');
    }else{
        @system('sudo ' . $file . ' > /dev/null');
    }
    
    unlink($file);
    $to = '';
}

function update_prio($row){
   system_to('/sbin/ip rule add from 10.10.101.0/24 prio ' . (10000+$row->prio) . ' table ' . (1000+$row->id));
}

if($Cur['str'] == 'update_prio'){
    $to = '';
    $cs = $mysqli->query_name('SELECT COUNT(id) count FROM bf_servers');
    for($i = 0; $i < $cs; $i++){
        system_to('/sbin/ip rule del from 10.10.101.0/24');
    }

    $mysqli->query('SELECT id, prio FROM bf_servers WHERE (enable = \'1\') ORDER by prio ASC', null, 'update_prio', false);
    
    suexec();
}

get_function('html_pages');
$page['count_page'] = 100;

$smarty->assign('prio', $mysqli->query('SELECT MAX(prio) max, MIN(prio) min FROM bf_servers'));

$count_users = $mysqli->table_rows('bf_servers');

if($_SESSION['user']->config['infoacc'] == '1'){    
    $sql = '';
    
    foreach($_SESSION['user']->config['servers'] as $key => $item){
        $sql .= ' OR (id = \''.$key.'\')';
    }
    $sql = preg_replace('~^ OR ~', '', $sql);
    
    if(!empty($sql)){
        $smarty->assign("list", $mysqli->query('SELECT * FROM bf_servers WHERE '.$sql.' ORDER by prio ASC LIMIT '.($Cur['page'] == 0 ? 0 : $Cur['page']*$page['count_page']).','.$page['count_page'], null, null, false));
    }
}else{
    $smarty->assign("list", $mysqli->query('SELECT * FROM bf_servers ORDER by prio ASC LIMIT '.($Cur['page'] == 0 ? 0 : $Cur['page']*$page['count_page']).','.$page['count_page'], null, null, false));
}

$smarty->assign('pages', html_pages('/servers/?', $count_users, $page['count_page']));
$smarty->assign('count_users', $count_users);

$smarty->assign('title', $lang['servers']);

?>