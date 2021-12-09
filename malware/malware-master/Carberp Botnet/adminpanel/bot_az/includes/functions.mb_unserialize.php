<?php

function mb_unserialize($serial_str){
    $serial_str = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str);
    $serial_str = str_replace("\r", "", $serial_str);
    //$out = unserialize($serial_str);
    
    /*
    if(count($out['params']) > 0){
        foreach($out['params'] as $k => $i){
            $i = convert('windows-1251', 'UTF-8//IGNORE', $i);
            $i = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $i);
            $out['params'][$k] = unserialize($i);
        }
    }
    */
    
    //return $out;
    return unserialize($serial_str);
}

?>