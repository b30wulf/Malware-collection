#!/usr/bin/env php
<?php

$dir = pathinfo(__FILE__, PATHINFO_DIRNAME) . '/';
$dir = realpath('../') . '/';

$config = file_exists($dir . 'cache/config.json') ? json_decode(file_get_contents($dir . 'cache/config.json'), 1) : '';

/*
include_once($dir . 'includes/functions.get_config.php');

$cfg_db = get_config();

$mysqli = new mysqli($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
if(mysqli_connect_errno()) exit;

$mysqli->real_query("SET NAMES utf8");

$mysqli->real_query('delete from bf_plugin_history where (post_date >= DATE_ADD(NOW(), INTERVAL -30 MINUTE))');

$result = $mysqli->query('SELECT * FROM bf_plugins WHERE (status = \'-1\')');

while($row = $result->fetch_object()){
	$r = $mysqli->query('SELECT count(id) count FROM bf_plugin_history WHERE plugin_id = \''.$row->id.'\' AND b.post_date >= DATE_ADD(NOW(), INTERVAL -30 MINUTE)');
	$c = $r->fetch_object();

	if($c->count == '0'){
		$mysqli->real_query('delete from bf_plugins where (id >=  \''.$row->id.'\')');
	}
}

//$mysqli->real_query('OPTIMIZE TABLE bf_plugins, bf_plugin_history, bf_bots, bf_bots_ip, bf_process, bf_process_stats, bf_cabs, bf_filters, bf_filters_files, bf_filters_save, bf_filters_unnecessary');
*/

if($config['domains_start'] == 1){
    file_put_contents('/tmp/domain.sh', '#!/bin/sh' . "\n");
    file_put_contents('/tmp/domain.sh', 'cd ' . $dir . 'crons/scripts/' . "\n", FILE_APPEND);
    file_put_contents('/tmp/domain.sh', '/usr/bin/env php ' . $dir . 'crons/scripts/domains.php > /dev/null &', FILE_APPEND);
    chmod('/tmp/domain.sh', 0777);
    @system('/tmp/domain.sh');
    unlink('/tmp/domain.sh');
}

exit;

?>