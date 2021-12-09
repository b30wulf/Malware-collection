<?php

function datediff($a){	return ((time() - strtotime($a)) / 60) / 60;
}

if($Cur['x'] == 'check'){	$dir = realpath('.') . '/';

	file_put_contents('/tmp/check.sh', '#!/bin/sh' . "\n");
	file_put_contents('/tmp/check.sh', 'cd ' . $dir . 'crons/' . "\n", FILE_APPEND);
	file_put_contents('/tmp/check.sh', '/usr/bin/env php ' . $dir . 'crons/checks.php > /dev/null &', FILE_APPEND);
	chmod('/tmp/check.sh', 0777);
	@system('/tmp/check.sh');
	unlink('/tmp/check.sh');

	sleep(3);
	header('Location: /admins/');
}
if(file_exists('cache/pid_checks.txt')) $smarty->assign('pid_checks', true);

$page['count_page'] = 50;

$admins = $mysqli->query("SELECT * FROM bf_admins ORDER by name LIMIT " . ($Cur['page'] == 0 ? 0 : $Cur['page']*$page['count_page']).','.$page['count_page'], null, null, false);

$count_keys = $mysqli->query_name("SELECT COUNT(*) count FROM bf_admins");

$smarty->assign('count_keys', $count_keys);
$smarty->assign('pages', html_pages('/admins/?', $count_keys, $page['count_page']));
$smarty->assign('admins', $admins);
$smarty->assign('title', 'Админки');

?>