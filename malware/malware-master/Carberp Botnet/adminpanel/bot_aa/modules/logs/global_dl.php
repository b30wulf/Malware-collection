<?php

if(!empty($Cur['x']) && file_exists('cache/gdl/' . $Cur['x'])){
    header( 'Content-Disposition: attachment; filename="' . $Cur['x'] . '"');
    
    if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
        header( 'X-LIGHTTPD-send-file: ' . realpath('cache/gdl/' . $Cur['x']));
    }elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
        header('X-Sendfile: ' . realpath('cache/gdl/' . $Cur['x']));
    }
}else{
    exit;
}

?>