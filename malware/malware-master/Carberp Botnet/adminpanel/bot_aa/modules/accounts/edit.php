<?php

if(empty($Cur['id'])) $Cur['id'] = $_SESSION['user']->id;

if($Cur['id'] != $_SESSION['user']->id){
	if($_SESSION['user']->access['accounts']['edits'] != 'on'){
		$smarty->assign("site_data", "modules/accounts/access_denied.tpl");
		$smarty->display('index.tpl');
		exit;
	}
}


if(isset($_POST['edit_submit'])){	$user = $mysqli->query('SELECT * FROM bf_users WHERE (id<>\'0\') AND (id=\''.$Cur['id'].'\') LIMIT 1');

	array_walk($_POST, 'real_escape_string');

	$_POST['login'] = strtolower($_POST['login']);

	if(empty($_POST['login'])){
		$bad_form['login'] = 'Логин не может быть пустым.';
		$FORM_BAD = 1;
	}else{
		if($user->login <> $_POST['login']){			$result = $mysqli->query("SELECT login FROM bf_users WHERE (login='".$_POST['login']."')");
			if($result->login == $_POST['login']){				$bad_form['login'] = 'Логин занят.';
				$FORM_BAD = 1;
			}
		}
	}

	if(!empty($_POST['password'])){
		if($_POST['password'] <> $_POST['pass_dbl']){			$bad_form['password'] = '"Пароль" и "Повтор пароля" не совподают.';
			$FORM_BAD = 1;
		}
	}

	if($FORM_BAD <> 1){
		$sql_add = '';
        /*
		if($user->login <> $_POST['login']){			$sql_add .= 'login = \''.$_POST['login'].'\'';
		}
        */
		if(!empty($_POST['password'])){			if($user->password <> md5($_POST['password'])){				if(!empty($sql_add)) $sql_add .= ',';
				$sql_add .= 'password = \''.md5($_POST['password']).'\'';
			}
		}

		if(!empty($sql_add)){			$sql = 'update bf_users set '.$sql_add.', update_date = CURRENT_TIMESTAMP WHERE (id = \''.$user->id.'\')';

			if($_SESSION['user']->id == $user->id){
				//if($user->login <> $_POST['login']) $_SESSION['user']->login = ucfirst($_POST['login']);
				if(!empty($_POST['password']) && $user->password <> md5($_POST['password'])) $_SESSION['user']->password = md5($_POST['password']);
            }

			if($mysqli->query($sql) == false){				$errors .= '<div>Изменение сейчас невозможно, попробуйте позже.</div>';
			}else{				$smarty->assign("save", true);
			}
		}else{			$errors .= '<div>Изменение не требуеться.</div>';
		}
	}else{
		if(count($bad_form) > 0){
			rsort($bad_form);
			for($i = 0; $i < count($bad_form); $i++){
				if ( $i & 1 ) $value_count = "1"; else $value_count = "2";
				$errors .= '<div align="center">' . $bad_form[$i] . '</div>';
			}
		}
	}
	$smarty->assign("account_errors", $errors);
}

$result = $mysqli->query('SELECT * FROM bf_users WHERE (id<>\'0\') AND (id=\''.$Cur['id'].'\') LIMIT 1');
$smarty->assign("user", $result);

$dir['1'] = '<a href="/'.$Cur['to'].'/profile-'.$Cur['id'].'.html">'.ucfirst($result->login).'</a>';
$dir['2'] = '<a href="/'.$Cur['to'].'/'.$Cur['go'].'-'.$Cur['id'].'.html">'.$dirs[$Cur['to']][$Cur['go']].'</a>';

?>