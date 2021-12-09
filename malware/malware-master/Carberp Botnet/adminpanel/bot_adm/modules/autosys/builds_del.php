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

$check = check_pid();

if($check != true){
    if(!empty($Cur['id'])){
        $item = $mysqli->query('SELECT * FROM bf_builds WHERE (id<>\'0\') AND (id=\''.$Cur['id'].'\') LIMIT 1');
        
        if($item->id == $Cur['id']){
            @unlink('cfg/' . $item->file_orig);
            @unlink('cache/originals/' . $item->file_orig);
            @unlink('cfg/' . $item->file_orig);
            @unlink('cfg/' . $item->file_crypt);
            
            if(file_exists('cache/cryptor/' . $item->id . '/')){
                $fl = scandir('cache/cryptor/' . $item->id . '/');
                unset($fl[0], $fl[1]);
                
                if(count($fl) > 0){
                    foreach($fl as $f){
                        @unlink('cache/cryptor/' . $item->id . '/' . $f);                        
                    }
                }

                rmdir('cache/cryptor/' . $item->id . '/');
            }
            
            $mysqli->query('delete from bf_builds WHERE (id = \''.$Cur['id'].'\') LIMIT 1');
        }        
    }
}

header('Location: /autosys/builds.html?ajax=1');
exit;

?>