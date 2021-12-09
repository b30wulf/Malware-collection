<?php

get_function('html_pages');
$page['count_page'] = 100;

if(isset($_POST['update'])){
    @array_walk($_POST, "sql_inject");
    @array_walk($_POST, 'real_escape_string');
    unset($_POST['update']);
    
    foreach($_POST as $key => $value){
        if($key != 'cmd' && $key != 'link'){
            $_SESSION['search']['trans'][$key] = trim($value);
        }
    }
}

$filter = '';

if(count($_SESSION['search']['trans'])){
    foreach($_SESSION['search']['trans'] as $key => $value){
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
                            $_SESSION['search']['trans'][$key] = '';
                        }
                    }else{
                        $_SESSION['search']['trans'][$key] = '';
                    }
                break;
                
                case 'sys':
                    if($value != 'ALL') $filter .= ' AND (system = \''.strtolower($value).'\') ';
                break;
                
                case 'date':
                    if($value != 'ALL') $filter .= ' AND (DATE(post_date) = \''.$value.'\') ';
                break;
            
                case 'status':
                    if($value != 'ALL') $filter .= ' AND (status = \''.$value.'\') ';
                break;
            }
        }
    }
}

function get_date($row){
    global $date;
    $date[$row->date] = true;
}
/*
function get_sys($row){
    global $sys;
    $sys[strtoupper($row->system)] = true;
}
*/
function get_system($row){
    global $sys;
    $sys[$row->nid] = $row->name;
}

$date = array();
$sys = array();

if($_SESSION['user']->config['infoacc'] == '1'){
    $sql = array();
    $sql['logs'] = '';
    $sql['sys'] = '';
    $sql['prefix'] = '';
    
    foreach($_SESSION['user']->config['systems'] as $key => $item){
        $sql['logs'] .= ' OR (system = \''.$key.'\')';
        $sql['sys'] .= ' OR (nid = \''.$key.'\')';
    }
    
    if(!empty($_SESSION['user']->config['userid'])){
        $sql['userid'] = 'userid = \''.$_SESSION['user']->config['userid'].'\'';
    }
    
    foreach($sql as $sk => $si){
        $sql[$sk] = preg_replace('~^ OR ~', '', $sql[$sk]);
        $sql[$sk] = '('.$sql[$sk].') AND ';
    }
    
    $mysqli->query('SELECT distinct(DATE(post_date)) date FROM bf_transfers WHERE '.preg_replace('~ AND $~', '', $sql['logs'].$sql['userid']).' ORDER by post_date DESC', null, 'get_date');
        
    if($_SESSION['user']->access['logs']['cc'] != 'on'){
        $mysqli->query('SELECT distinct(nid) nid, name FROM bf_systems WHERE '.$sql['sys'].' AND (nid != \'cc\')', null, 'get_system');
    }else{
        $mysqli->query('SELECT distinct(nid) nid, name FROM bf_systems WHERE '.preg_replace('~ AND $~', '', $sql['sys']), null, 'get_system');
    }
    
    if(!empty($filter)){
        $filter = preg_replace('~ AND $~', '', $sql['userid'] . $sql['logs']) . $filter;
    }else{
        $filter = preg_replace('~ AND $~', '', $sql['userid'] . $sql['logs']);
    }
    
    $count_users = $mysqli->query_name('SELECT COUNT(id) count FROM bf_transfers'. (!empty($filter) ? ' WHERE ' . ltrim($filter, ' AND ') : '' ));
    $smarty->assign("list", $mysqli->query('SELECT prefix, uid, post_date, balance, num, system FROM bf_transfers '. (!empty($filter) ? ' WHERE ' . ltrim($filter, ' AND ') : '' ) .' ORDER by id DESC LIMIT '.($Cur['page'] == 0 ? 0 : $Cur['page']*$page['count_page']).','.$page['count_page'], null, null, false));
}else{
    if(!empty($_SESSION['user']->config['userid'])){
        $mysqli->query('SELECT distinct(DATE(post_date)) date FROM bf_transfers WHERE (userid = \''.$_SESSION['user']->config['userid'].'\') ORDER by post_date DESC', null, 'get_date');
        
        if($_SESSION['user']->access['logs']['cc'] != 'on'){
            $mysqli->query('SELECT distinct(nid) nid, name FROM bf_systems WHERE (nid != \'cc\')', null, 'get_system');
        }else{
            $mysqli->query('SELECT distinct(nid) nid, name FROM bf_systems', null, 'get_system');
        }
        
        $sql = array();
        $sql['userid'] = '';
        
        if(!empty($_SESSION['user']->config['userid'])){
            $sql['userid'] = 'userid = \''.$_SESSION['user']->config['userid'].'\'';
        }
        
        foreach($sql as $sk => $si){
            $sql[$sk] = preg_replace('~^ OR ~', '', $sql[$sk]);
            $sql[$sk] = '('.$sql[$sk].') AND ';
        }
        
        if(!empty($filter)){
            $filter = preg_replace('~ AND $~', '', $sql['userid']) . $filter;
        }else{
            $filter = preg_replace('~ AND $~', '', $sql['userid']);
        }
        
        $count_users = $mysqli->query_name('SELECT COUNT(id) count FROM bf_transfers'. (!empty($filter) ? ' WHERE ' . ltrim($filter, ' AND ') : '' ));
        $smarty->assign("list", $mysqli->query('SELECT prefix, uid, post_date, num, system FROM bf_transfers '. (!empty($filter) ? ' WHERE ' . ltrim($filter, ' AND ') : '' ) .' ORDER by id DESC LIMIT '.($Cur['page'] == 0 ? 0 : $Cur['page']*$page['count_page']).','.$page['count_page'], null, null, false));
    }else{
        $mysqli->query('SELECT distinct(DATE(post_date)) date FROM bf_transfers ORDER by post_date DESC', null, 'get_date');
        
        if($_SESSION['user']->access['logs']['cc'] != 'on'){
            $mysqli->query('SELECT distinct(nid) nid, name FROM bf_systems WHERE (nid != \'cc\')', null, 'get_system');
        }else{
            $mysqli->query('SELECT distinct(nid) nid, name FROM bf_systems', null, 'get_system');
        }
        
        $count_users = $mysqli->query_name('SELECT COUNT(id) count FROM bf_transfers'. (!empty($filter) ? ' WHERE ' . ltrim($filter, ' AND ') : '' ));
        $smarty->assign("list", $mysqli->query('SELECT prefix, uid, post_date, num, system FROM bf_transfers '. (!empty($filter) ? ' WHERE ' . ltrim($filter, ' AND ') : '' ) .' ORDER by id DESC LIMIT '.($Cur['page'] == 0 ? 0 : $Cur['page']*$page['count_page']).','.$page['count_page'], null, null, false));
    }
}

$smarty->assign('date', $date);
$smarty->assign('sys', $sys);

$smarty->assign('pages', html_pages('/transfers/?', $count_users, $page['count_page']));
$smarty->assign('count_users', $count_users);

$smarty->assign('title', $lang['transfers']);

?>