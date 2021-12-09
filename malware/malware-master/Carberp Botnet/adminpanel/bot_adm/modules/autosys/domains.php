<?php

function to_br($str){
    return str_replace('|', '<br />', $str);
}

function check_pid(){
    if(file_exists('cache/domains.pid')){
        $pid = file_get_contents('cache/domains.pid');
        if(stripos(exec('ps -p '.$pid), $pid) === false){
	    return false;
        }else{
            return true;
        }
    }else{
        return false;
    }
}

if($Cur['str'] == 'view_av' && !empty($Cur['id'])){
    $row = $mysqli->query('SELECT * FROM bf_domains WHERE (id = \''.$Cur['id'].'\') LIMIT 1');
    if($row->id == $Cur['id']){
        $text = 'Domain: ' . $row->host . "\r\n";
        $text .= "AV Detect List: \r\n " . str_replace('|', "\r\n ", $row->avt);
        $smarty->assign('text', $text);
        echo ' ';
        $smarty->display('modules/autosys/builds_av.tpl');
    }
    exit;
}

if($Cur['str'] == 'start_builds'){
    file_put_contents('/tmp/domains.sh', '#!/bin/sh' . "\n");
    file_put_contents('/tmp/domains.sh', 'cd ' . realpath('crons/scripts/') . "/\n", FILE_APPEND);
    file_put_contents('/tmp/domains.sh', '/usr/bin/env php ' . realpath('crons/scripts/') . '/domains.php > /dev/null &', FILE_APPEND);
    chmod('/tmp/domains.sh', 0777);
    @system('/tmp/domains.sh');
    unlink('/tmp/domains.sh');
    sleep(3);
    header('Location: /autosys/domains.html');
    exit;
}

if($Cur['str'] == 'set_comment'){
    $mysqli->query('update bf_domains set comment = \''.str_replace("\n", '<br />', str_replace("'", '', $_POST['text'])).'\' WHERE (id=\''.$Cur['id'].'\') LIMIT 1');
    if(empty($_POST['text'])) $_POST['text'] = ' ';
    print($_POST['text']);
    exit;
}

$domains = $mysqli->query('SELECT * FROM bf_domains WHERE (answer = \'0\')', null, null, false);
$smarty->assign('domains', $domains);

$_domains = $mysqli->query('SELECT * FROM bf_domains WHERE (answer = \'1\')', null, null, false);
$smarty->assign('_domains', $_domains);

$smarty->assign('check_pid', check_pid());

?>