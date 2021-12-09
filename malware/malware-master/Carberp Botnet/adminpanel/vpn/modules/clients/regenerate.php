<?php

set_time_limit(0);
ini_set('max_execution_time', 0);

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

$to = '';

system_to('');
system_to('cd  ' . $config['esa'] . ';');
system_to('./vars');
system_to('source ./vars');
system_to('./clean-all');

system_to('/bin/chmod -R 777 ' . $config['esa'] . '*.*');

system_to('echo "unique_subject = no'."\n".'" > ' . $config['esa'] . 'keys/index.txt.attr');

system_to('/bin/chmod -R 777 ' . $config['esa'] . '*.*');

system_to('export EASY_RSA="'.$config['esa'].'";');
system_to('"$EASY_RSA/pkitool" --initca ca;');
system_to('"$EASY_RSA/pkitool" --server server;');
system_to('$OPENSSL dhparam -out '.$config['esa'].'keys/dh2048.pem 2048;');
system_to('/usr/sbin/openvpn --genkey --secret /etc/openvpn/tun/ta.key');

system_to('echo "unique_subject = no'."\n".'" > ' . $config['esa'] . 'keys/index.txt.attr');

$users = $mysqli->query('SELECT * from bf_clients', null, null, false);

foreach($users as $user){
     system_to('export KEY_CN="' . $user->name . '"');
     system_to('export KEY_NAME="' . $user->name . '"');
     system_to('export KEY_OU="' . $user->name . '"');
     system_to('"$EASY_RSA/pkitool" --batch ' . $user->name . ';');
}

system_to('/bin/chmod -R 777 /etc/openvpn/*.*');
system_to('/bin/chmod -R 777 ' . $config['esa'] . '*');

system_to('/sbin/service openvpn restart');

suexec();

copy($config['esa'] . 'keys/ca.crt', '/etc/openvpn/tun/ca.crt');
copy($config['esa'] . 'keys/ca.key', '/etc/openvpn/tun/ca.key');
copy($config['esa'] . 'keys/server.crt', '/etc/openvpn/tun/server.crt');
copy($config['esa'] . 'keys/server.key', '/etc/openvpn/tun/server.key');
copy($config['esa'] . 'keys/dh2048.pem', '/etc/openvpn/tun/dh2048.pem');

system_to('/bin/chmod -R 777 /etc/openvpn/*.*');
system_to('/bin/chmod -R 777 ' . $config['esa'] . '*.*');
suexec();

foreach($users as $user){
    $crt = file_get_contents($config['esa'] . 'keys/' . $user->name . '.crt');
    $key = file_get_contents($config['esa'] . 'keys/' . $user->name . '.key');
    $expiry_date = '';

    preg_match_all('~Not After : (.*) GMT~is', $crt, $out);
    if(!empty($out[1][0])){
        $expiry_date = date('Y-m-d H:i:s', strtotime($out[1][0] . ' GMT'));
        unset($out);
    }else{
        $expiry_date = '0000-00-00 00:00:00';
    }

    $mysqli->query('update bf_clients set `crt` = \''.$crt.'\', `key` = \''.$key.'\', `expiry_date` = \''.$expiry_date.'\' WHERE (id = \''.$user->id.'\')');
}

system_to('/sbin/service openvpn stop');
system_to('/usr/bin/killall openvpn');
sleep(3);
system_to('/sbin/service openvpn start');
sleep(3);
system_to('/sbin/service openvpn restart');
sleep(1);

header('Location: /clients/');
exit;

?>