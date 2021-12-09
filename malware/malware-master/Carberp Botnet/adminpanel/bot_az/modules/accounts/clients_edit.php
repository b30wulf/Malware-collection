<?php

$cdb = 'cache/clients_list.json';
$cpdb = 'cache/clients.json';
$cpdbp = 'cache/clients_pref.json';

if(file_exists($cdb)){
    $cl = @json_decode(@file_get_contents($cdb), true);
}else{
    header('Location: /accounts/clients.html');
    exit;
}

if(file_exists($cpdb)){
    $cpl = @json_decode(@file_get_contents($cpdb), true);
}else{
    $cpl = array();
}

if(file_exists($cpdbp)){
    $cplp = @json_decode(@file_get_contents($cpdbp), true);
}else{
    $cplp = array();
}

if(isset($_POST['edit_submit'])){    
    if($cl[$Cur['str']] != $_POST['name'] && preg_match('~^([a-zA-Z0-9]+)$~is', $_POST['name'])){
        $cl[$Cur['str']] = $_POST['name'];
        file_put_contents($cdb, json_encode($cl));
    }
    
    if(!empty($_POST['prefixs'])){
        $prefixs = explode(',', $_POST['prefixs']);
        if(count($prefixs) > 0){
            foreach($cpl as $kp => $pl){
                if($pl == $Cur['str']) unset($cpl[$kp]);
            }
            unset($cplp[$Cur['str']]);
            
            foreach($prefixs as $p){
                $p = strtoupper($p);
                if(!isset($cpl[$p])){
                    $cpl[$p] = $Cur['str'];
                    $cplp[$Cur['str']][$p] = 1;
                }
            }
            
            file_put_contents($cpdb, json_encode($cpl));
            file_put_contents($cpdbp, json_encode($cplp));
        }
    }else{
        foreach($cpl as $kp => $pl){
            if($pl == $Cur['str']) unset($cpl[$kp]);
        }
        unset($cplp[$Cur['str']]);
        file_put_contents($cpdb, json_encode($cpl));
        file_put_contents($cpdbp, json_encode($cplp));
    }

    header('Location: /accounts/clients.html');
    exit;
}

$_POST['name'] = $cl[$Cur['str']];

if(count($cpl) > 0){
    $cpn = array();
    foreach($cpl as $key => $cp){
        if($cp == $Cur['str']) $cpn[] = $key;
    }
    
    $_POST['prefixs'] = implode(',', $cpn);
}
?>