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

get_function('iptables');
get_function('ip_rule');

$to = '';

if(!empty($Cur['id'])){
    $item = $mysqli->query('SELECT id, enable, server, autocheck, inip from bf_clients WHERE (id = \''.$Cur['id'].'\') LIMIT 1');

    if($item->id == $Cur['id']){
        switch($Cur['str']){
            case 'enable':
                if($item->enable == '1'){
                    $mysqli->query('update bf_clients set enable = \'0\' WHERE (id = \''.$item->id.'\') LIMIT 1');
                    
                    if($item->autocheck == 1){
                        /*
                        $ipt = '-A drops -t mangle -s '.$item->inip.'/32 -o ! tun'.$item->server.' -j DROP';
                        if(iptables_search('mangle', 'drops', $ipt) == false){
                            system_to('/sbin/iptables ' . $ipt);
                            suexec();
                        }
                        */
                        $ipt = '-A drops -t mangle -s '.$item->inip.'/32 -o ! tun'.$item->server.' -j DROP';
                        if(iptables_search('mangle', 'drops', $ipt) == false){
                            $ipt = '-A drops -t mangle -s '.$item->inip.'/255.255.255.255 -o ! tun'.$item->server.' -j DROP';
                            if(iptables_search('mangle', 'drops', $ipt) == false){
                                $ipt = '-A drops -t mangle -s '.$item->inip.' -o ! tun'.$item->server.' -j DROP';
                                if(iptables_search('mangle', 'drops', $ipt) == false){
                                    system_to('/sbin/iptables ' . $ipt);
                                    suexec();
                                }
                            }
                        }
                    }
                    
                    $ip_rule = iprule_decode();
                    
                    $item->prio = (1000+$item->id);
                    $item->table = (1000+$item->server);
                    $ip_status = iprule_search($item->prio, $item->inip . '/32', $item->table);
                    
                    if($ip_status != $item->prio){
                        system_to('/sbin/ip rule del prio ' . $item->prio);
                        system_to('/sbin/ip rule add prio ' . $item->prio . ' from ' . $item->inip . '/32' . ' table ' . $item->table);
                        suexec();
                    }
                }else{
                    $mysqli->query('update bf_clients set enable = \'1\' WHERE (id = \''.$item->id.'\') LIMIT 1');
                    
                    iptables_decode();
                    $ret = iptables_match($item->inip . '/32');
                    if($ret['count'] > 0){
                        foreach($ret['tables'] as $tk => $ti){
                            foreach($ti as $ak => $ai){
                                krsort($ai, SORT_NUMERIC);
                                foreach($ai as $zk => $zi){
                                    system_to('/sbin/iptables -t mangle -D drops  ' . ($zk+1));
                                    }
                                }
                            }
                        suexec();
                    }
                    /*
                    $ipt = '-A drops -t mangle -s '.$item->inip.'/32 -j DROP';
                    if(iptables_search('mangle', 'drops', $ipt) == false){
                        system_to('/sbin/iptables ' . $ipt);
                        suexec();
                    }
                    */
                    
                    $ipt = '-A drops -t mangle -s '.$item->inip.'/32 -o ! tun'.$item->server.' -j DROP';
                    if(iptables_search('mangle', 'drops', $ipt) == false){
                        $ipt = '-A drops -t mangle -s '.$item->inip.'/255.255.255.255 -o ! tun'.$item->server.' -j DROP';
                        if(iptables_search('mangle', 'drops', $ipt) == false){
                            $ipt = '-A drops -t mangle -s '.$item->inip.' -o ! tun'.$item->server.' -j DROP';
                            if(iptables_search('mangle', 'drops', $ipt) == false){
                                system_to('/sbin/iptables ' . $ipt);
                                suexec();
                            }
                        }
                    }
                }
            break;
        }
    }
}

header('Location: /clients/?ajax=1');

?>