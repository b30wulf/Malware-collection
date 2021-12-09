#!/usr/bin/env php
<?php

$dir = pathinfo(__FILE__, PATHINFO_DIRNAME) . '/';
$dir = realpath('../') . '/';

include_once($dir . 'includes/functions.get_config.php');

$cfg_db = get_config();

$mysqli = new mysqli($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
if(mysqli_connect_errno()) exit;

$mysqli->real_query("SET NAMES utf8");
/*
$mysqli->real_query('delete from bf_plugin_history where (post_date >= DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL -30 MINUTE))');

$result = $mysqli->query('SELECT * FROM bf_plugins WHERE (status = \'-1\')');

while($row = $result->fetch_object()){
	$r = $mysqli->query('SELECT count(id) count FROM bf_plugin_history WHERE plugin_id = \''.$row->id.'\' AND b.post_date >= DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL -30 MINUTE)');
	$c = $r->fetch_object();

	if($c->count == '0'){
		$mysqli->real_query('delete from bf_plugins where (id >=  \''.$row->id.'\')');
	}
}

//$mysqli->real_query('OPTIMIZE TABLE bf_plugins, bf_plugin_history, bf_bots, bf_bots_ip, bf_process, bf_process_stats, bf_cabs, bf_filters, bf_filters_files, bf_filters_save, bf_filters_unnecessary');
*/
exit;

?>