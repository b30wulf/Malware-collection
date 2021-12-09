<?php

function load_threads($r){
	global $threads, $mysqli;

	$r->count = array();

	$r->count['1'] = $mysqli->query_name('SELECT COUNT(id) count FROM bf_threads WHERE (script = \''.$r->script.'\') AND ((status = \'0\') OR (status = \'1\'))'); //start
	$r->count['2'] = $mysqli->query_name('SELECT COUNT(id) count FROM bf_threads WHERE (script = \''.$r->script.'\') AND (status = \'2\')'); //parsing
	$r->count['3'] = $mysqli->query_name('SELECT COUNT(id) count FROM bf_threads WHERE (script = \''.$r->script.'\') AND (status != \'0\') AND (status != \'1\') AND (status != \'2\') AND (status != \'255\')'); //error
	$r->count['255'] = $mysqli->query_name('SELECT COUNT(id) count FROM bf_threads WHERE (script = \''.$r->script.'\') AND (status = \'255\')'); //succes

	$r->count['all'] = array_sum($r->count); //all
	$r->count['obra'] = $r->count['255'] + $r->count['3'];
	$r->count['allp'] = number_format(($r->count['obra'] / $r->count['all'] * 100), 2);
	$r->count['ost'] = $r->count['1'] + $r->count['2'];
	$r->count['ostp'] = number_format(($r->count['ost'] / $r->count['all'] * 100), 2);
	$r->count['errp'] = number_format(($r->count['3'] / $r->count['all'] * 100), 2);

	$threads[$r->script] = $r;
}

$threads = array();
$mysqli->query('SELECT script FROM bf_threads GROUP by script', false, 'load_threads');
$smarty->assign('threads', $threads);

?>