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

$to = '';

if(!empty($Cur['id'])){
    $item = $mysqli->query('SELECT id, enable, prio from bf_servers WHERE (id = \''.$Cur['id'].'\') LIMIT 1');

    if($item->id == $Cur['id']){
        switch($Cur['str']){
            case 'enable':
                if($item->enable == '1'){
                    $mysqli->query('update bf_servers set enable = \'0\' WHERE (id = \''.$item->id.'\') LIMIT 1');
                }else{
                    $mysqli->query('update bf_servers set enable = \'1\' WHERE (id = \''.$item->id.'\') LIMIT 1');
                }
            break;
            
            case 'up':
                $sitem = $mysqli->query('SELECT id, prio, enable from bf_servers WHERE (prio < \''.$item->prio.'\') ORDER by prio DESC LIMIT 1');

                if(!empty($sitem->id) && !empty($sitem->prio)){
                    $mysqli->query('update bf_servers set prio = \''.$sitem->prio.'\' WHERE (id = \''.$item->id.'\') LIMIT 1');
                    $mysqli->query('update bf_servers set prio = \''.$item->prio.'\' WHERE (id = \''.$sitem->id.'\') LIMIT 1');
                    /*
                    $item->iprule = 10000+$sitem->prio;
                    $item->tun = 1000+$item->id;
                    $sitem->iprule = 10000+$item->prio;
                    $sitem->tun = 1000+$sitem->id;
                    
                    system_to('/sbin/ip rule del prio ' . $item->iprule);
                    system_to('/sbin/ip rule del prio ' . $sitem->iprule);
                    
                    if($item->enable == 1) system_to('/sbin/ip rule add from 10.10.101.0/24 prio ' . $item->iprule . ' table ' . $item->tun);
                    if($sitem->enable == 1) system_to('/sbin/ip rule add from 10.10.101.0/24 prio ' . $sitem->iprule . ' table ' . $sitem->tun);
                    
                    suexec(true);
                    */
                }
            break;
            
            case 'down':
                $sitem = $mysqli->query('SELECT id, prio, enable from bf_servers WHERE (prio > \''.$item->prio.'\') ORDER by prio ASC LIMIT 1');
                if(!empty($sitem->id) && !empty($sitem->prio)){
                    $mysqli->query('update bf_servers set prio = \''.$sitem->prio.'\' WHERE (id = \''.$item->id.'\') LIMIT 1');
                    $mysqli->query('update bf_servers set prio = \''.$item->prio.'\' WHERE (id = \''.$sitem->id.'\') LIMIT 1');
                    /*
                    $item->iprule = 10000+$sitem->prio;
                    $item->tun = 1000+$item->id;
                    $sitem->iprule = 10000+$item->prio;
                    $sitem->tun = 1000+$sitem->id;
                    
                    system_to('/sbin/ip rule del prio ' . $item->iprule);
                    system_to('/sbin/ip rule del prio ' . $sitem->iprule);
                    
                    if($item->enable == 1) system_to('/sbin/ip rule add from 10.10.101.0/24 prio ' . $item->iprule . ' table ' . $item->tun);
                    if($sitem->enable == 1) system_to('/sbin/ip rule add from 10.10.101.0/24 prio ' . $sitem->iprule . ' table ' . $sitem->tun);
                    
                    suexec(true);
                    */
                }
            break;
        }
    }
}

header('Location: /servers/?ajax=1');

?>