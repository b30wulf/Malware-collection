<?php

function generatePassword ($length = 8){
    $password = '';
    $possible = "0123456789aAbBcCdDfFgGhHjJkKmMnNpPqQrRsStTvVwWxXyYzZ";
    $i = 0;
    while ($i < $length){
        $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
        if (!strstr($password, $char)) {
            $password .= $char;
            $i++;
        }
    }
    $password = str_replace('BJB', 'JBJ', $password);
    return $password;
}

function count_user ($cid){
    global $mysqli;
    return $mysqli->query_name('SELECT COUNT(id) count FROM bf_users WHERE (userid = \''.$cid.'\')');
}

function getpref($cid){
    global $cplp;
    if(isset($cplp[$cid])){
        $tcp = array_keys($cplp[$cid]);
        return implode('<br />', $tcp);
    }else{
        return '';
    }
}

$cdb = 'cache/clients_list.json';
$cpdbp = 'cache/clients_pref.json';

if($Cur['str'] == 'cleint_add'){
    if(preg_match('~^([a-zA-Z0-9]+)$~is', $Cur['x'])){
        $Cur['x'] = strtolower($Cur['x']);
        if(file_exists($cdb)){
            $cl = @json_decode(@file_get_contents($cdb), true);
            $ncl = generatePassword(8);
            if(isset($cl[$ncl])) $ncl = generatePassword(8);
            
            $cl_n = array_flip($cl);
            if(!isset($cl_n[$Cur['x']])){
                $cl[$ncl] = $Cur['x'];
                file_put_contents($cdb, json_encode($cl));
            }
            
            header('Location: /accounts/clients.html?ajax=1');
            exit;
        }else{
            $cl = array();
            $ncl = generatePassword(8);
            if(isset($cl[$ncl])) $ncl = generatePassword(8);
            $cl[$ncl] = $Cur['x'];
            file_put_contents($cdb, json_encode($cl));
            
            header('Location: /accounts/clients.html?ajax=1');
            exit;
        }
    }
}

if(file_exists($cdb)){
    $cl = @json_decode(@file_get_contents($cdb), true);
    $smarty->assign('clist', $cl);
}

if(file_exists($cpdbp)){
    $cplp = @json_decode(@file_get_contents($cpdbp), true);
    $smarty->assign('cpref', $cplp);
}

?>