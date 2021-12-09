<?php

if(isset($_POST['reg_submit'])){	@array_walk($_POST, 'real_escape_string');
	$_POST['login'] = @strtolower($_POST['login']);

	if(empty($_POST['login'])){
		$bad_form['login'] = 'Логин не может быть пустым.';
		$FORM_BAD = 1;
	}else{
        $result = $mysqli->query("SELECT login FROM bf_users WHERE (login='".$_POST['login']."')");
        if($result->login == $_POST['login']){        	$bad_form['login'] = 'Введенный логин уже есть в системе.';
        	$FORM_BAD = 1;
        }
	}

	if(empty($_POST['password'])){
		$bad_form['password'] = 'Пароль не может быть пустым.';
		$FORM_BAD = 1;
	}

	if($_POST['password'] <> $_POST['pass_dbl']){
		$bad_form['password'] = '"Пароль" и "Повтор пароля" не совподают.';
		$FORM_BAD = 1;
	}

	if(empty($_POST['email'])){
		$bad_form['email'] = 'Емаил не может быть пустым.';
		$FORM_BAD = 1;
	}elseif(check_email($_POST['email']) != $_POST['email']){
		$bad_form['email'] = 'Емаил не верный.';
		$FORM_BAD = 1;
	}else{
		$result = $mysqli->query("SELECT email FROM bf_users WHERE (email='".$_POST['email']."')");
		if($result->email == $_POST['email']){
			$bad_form['email'] = 'Емаил занят.';
			$FORM_BAD = 1;
		}
	}

	if($FORM_BAD <> 1){
		if($mysqli->query("INSERT INTO bf_users (enable, login, password, email, config, access) VALUES ('1', '".$_POST['login']."', '".md5($_POST['password'])."', '".$_POST['email']."', '".json_encode($_POST['cfg'])."', '".json_encode($_POST['rights'])."')") == false){
			$errors .= '<div class="t"><div class="t4" align="center">Создание учетной записи сейчас невозможно, попробуйте позже.</div></div>';
		}else{
			header('Location: /accounts/index.html');
			exit;
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
	$smarty->assign("errors", $errors);
}

include_once("modules/accounts/rights_list.php");
$smarty->assign("rights", $right);

?>