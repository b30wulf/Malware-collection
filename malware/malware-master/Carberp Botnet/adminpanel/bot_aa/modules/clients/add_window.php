<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

if(isset($_POST['submit'])){	array_walk($_POST, 'real_escape_string');

	if(empty($_POST['name'])){		$bad_form['name'] = 'Имя клиента не может быть пустым.';
		$FORM_BAD = 1;
	}

	if($FORM_BAD <> 1){
		if($mysqli->query("INSERT INTO bf_clients (name, post_id) VALUES ('".$_POST['name']."', '".$_SESSION['user']->id."')") == false){
			$errors .= '<div class="t"><div class="t4" align="center">Добавления клинента сейчас невозможно, попробуйте позже.</div></div>';
		}else{			$smarty->assign("registration_end", true);
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