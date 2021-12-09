<?php

get_function('html_pages');
$page['count_page'] = 100;

$gen = true;

if($_SESSION['user']->config['systems']['bss'] != true) $gen = false;

if($gen != true){
    $mysqli->query('SET TIME_ZONE = \'+03:00\'');

    if($Cur['str'] == 'delete'){
        if(!empty($Cur['id'])){
            $man = $mysqli->query('SELECT id FROM bf_manuals WHERE (id = \''.$Cur['id'].'\') LIMIT 1');
            if($man->id == $Cur['id']){
                $mysqli->query('delete from bf_manuals where (id = \''.$man->id.'\') LIMIT 1');
            }
        }
        header('Location: /transfers/manual.html');
        exit;
    }

    if($Cur['str'] == 'create_link'){
        if(!empty($Cur['id'])){
            $man = $mysqli->query('SELECT id, system, blocks FROM bf_manuals WHERE (id = \''.$Cur['id'].'\') LIMIT 1');
            if($man->id == $Cur['id']){
                $tmp = file_get_contents('templates/modules/transfers/manual/'.strtolower($man->system).'.txt');
                
                $man->blocks = json_decode(base64_decode($man->blocks), true);
                
                if(count($man->blocks[1]) > 0){
                    foreach($man->blocks[1] as $k_1 => $v_1){
                        $man->blocks[1][$k_1] = '["'.$v_1['acc'].'", "'.$v_1['summ'].'"]';
                    }
                    $man->blocks[1] = implode(',', $man->blocks[1]);
                    $tmp = str_replace('{array_1}', $man->blocks[1], $tmp);
                }
                
                if(count($man->blocks[2]) > 0){
                    foreach($man->blocks[2] as $k_2 => $v_2){
                        $man->blocks[2][$k_2] = '["'.$v_2['acc'].'", "'.$v_2['num'].'", "'.$v_2['date'].'", "'.$v_2['summ'].'"]';
                    }
                    $man->blocks[2] = implode(',', $man->blocks[2]);
                    $tmp = str_replace('{array_2}', $man->blocks[2], $tmp);
                }
                
                $rand_name = md5(mt_rand() . time());

                file_put_contents('cache/str2bin/' . $rand_name . '.txt', $tmp);
                system('cd '.realpath('cache/str2bin/').'; /usr/bin/wine ' . realpath('cache/str2bin/MakeBinConfig2.exe') . ' ' . $rand_name . '.txt ' . $rand_name . '.bin');
                unlink('cache/str2bin/' . $rand_name . '.txt');

                if(file_exists('cache/str2bin/'  . $rand_name . '.bin')){
                    $man->bin = file_get_contents('cache/str2bin/'  . $rand_name . '.bin');
                    unlink('cache/str2bin/' . $rand_name . '.bin');
                    $mysqli->query('update bf_manuals set bin = \''.base64_encode($man->bin).'\', expiry_date = DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 HOUR), rand = \''.md5(mt_rand(111111111, 999999999)).'\' WHERE (id = \''.$man->id.'\') LIMIT 1');
                    $ex = array('psd', 'tiff', 'bmp');
                    $man = $mysqli->query('SELECT rand, expiry_date FROM bf_manuals WHERE (id = \''.$Cur['id'].'\') LIMIT 1');
                    print('<a href="/man/'.$man->rand.'.'.$ex[mt_rand(0,2)].'" target="_blank">'.$man->expiry_date.'</a>');
                }else{
                    echo 'Error!';
                }
            }
        }
        echo ' ';
        exit;
    }

    if(isset($_POST['update'])){
        @array_walk($_POST, "sql_inject");
        @array_walk($_POST, 'real_escape_string');
        unset($_POST['update']);

        foreach($_POST as $key => $value){
            if($key != 'cmd' && $key != 'link'){
                $_SESSION['search']['manual'][$key] = trim($value);
            }
        }
    }

    $filter = '';

    if(count($_SESSION['search']['manual'])){
        foreach($_SESSION['search']['manual'] as $key => $value){
            if(!empty($value)){
                switch($key){
                    case 'acc':
                        $filter .= ' AND (acc = \''.$value.'\') ';
                    break;

                    case 'sys':
                        if($value != 'ALL') $filter .= ' AND (system = \''.strtolower($value).'\') ';
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

    function get_list($row){
        global $list;
        if($row->diff_date > 0){
            $ex = array('psd', 'tiff', 'bmp');
            $row->link = '<a href="/man/'. $row->rand .'.'.$ex[mt_rand(0,2)].'" target="_blank">'.$row->expiry_date.'</a>';
        }else{
            $row->expiry_date = '0000-00-00 00:00:00';
        }
        $list[] = $row;
    }

    function get_system($row){
        global $sys;
        $sys[$row->nid] = $row->name;
    }

    $date = array();
    $sys = array();

    $mysqli->query('SELECT distinct(DATE(post_date)) date FROM bf_manuals ORDER by post_date DESC', null, 'get_date');
    if($_SESSION['user']->access['logs']['cc'] != 'on'){
        $mysqli->query('SELECT distinct(nid) nid, name FROM bf_systems WHERE (nid != \'cc\')', null, 'get_system');
    }else{
        $mysqli->query('SELECT distinct(nid) nid, name FROM bf_systems', null, 'get_system');
    }

    $smarty->assign('date', $date);
    $smarty->assign('sys', $sys);

    $count_users = $mysqli->query_name('SELECT COUNT(id) count FROM bf_manuals'. (!empty($filter) ? ' WHERE ' . ltrim($filter, ' AND ') : '' ));
    $mysqli->query('SELECT id, system, post_date, expiry_date, rand, TIMESTAMPDIFF(second, CURRENT_TIMESTAMP(), expiry_date) diff_date FROM bf_manuals '. (!empty($filter) ? ' WHERE ' . ltrim($filter, ' AND ') : '' ) .' ORDER by post_date DESC LIMIT '.($Cur['page'] == 0 ? 0 : $Cur['page']*$page['count_page']).','.$page['count_page'], null, 'get_list', false);
    $smarty->assign("list", $list);
    $smarty->assign('pages', html_pages('/transfers/manual.html?', $count_users, $page['count_page']));
    $smarty->assign('count_users', $count_users);

    $smarty->assign('title', $lang['manual']);
}

?>