<?php

get_function('iptables');
get_function('ip_rule');

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
    @system('sudo ' . $file . ' > /dev/null');
    unlink($file);
    $to = '';
}

if(!empty($Cur['id'])){
    $item = $mysqli->query('SELECT id, name, server, inip from bf_clients WHERE id = '.$Cur['id'].' LIMIT 1');
    
    if($item->id == $Cur['id']){
        $item->prio = (1000+$item->id);
        $item->table = (1000+$item->server);
        
        system_to('sudo /sbin/ip rule del prio ' . $item->prio);
        suexec();
        
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
        
        system_to('cd  ' . $config['esa'] . ';');
        system_to('./vars');
        system_to('sourche ./vars');
        system_to('./revoke-full ' . $item->name);
        system_to('cp ' . $config['esa'] . '/keys/tun/crl.pem /etc/openvpn/tun/crl.pem');
        suexec();
        
        $mysqli->query('delete from bf_clients where (id = \''.$item->id.'\')');
    }
}

?>