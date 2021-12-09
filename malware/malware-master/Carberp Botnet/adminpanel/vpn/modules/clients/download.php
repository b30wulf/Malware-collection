<?php

if(!empty($Cur['id'])){
    $item = $mysqli->query('SELECT * from bf_clients WHERE id = '.$Cur['id'].' LIMIT 1');

    if($item->id == $Cur['id']){
        switch($Cur['x']){
            case 'direct':
                header("Content-type: application/octet-stream"); 
                header('Content-Disposition: attachment; filename="' . ($item->name . '_' . $config['ip'] . '.zip') . '"');
                if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
                    //header( 'X-LIGHTTPD-send-file: ' . realpath('cache/download/direct/' . $item->name . '_' . $config['ip'] . '.zip'));
                    print(file_get_contents(realpath('cache/download/direct/' . $item->name . '_' . $config['ip'] . '.zip')));
                }elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
                    header('X-Sendfile: ' . realpath('cache/download/direct/' . $item->name . '_' . $config['ip'] . '.zip'));
                }
            break;
        }
    }else{
        exit;
    }
}else{
    exit;
}

?>