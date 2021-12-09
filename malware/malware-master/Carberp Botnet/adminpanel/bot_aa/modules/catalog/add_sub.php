<?php

if(empty($Cur['id'])){	if(isset($_POST['submit']) && $_POST['submit'] == 'Добавить'){		array_walk($_POST, 'real_escape_string');

		if(empty($_POST['name'])){			$bad_form['name'] = 'Название не может быть пустым.';
			$FORM_BAD = 1;
		}

		if($FORM_BAD <> 1){			$sql = "INSERT INTO bf_filters (name, enable, parent_id) VALUES ('".$_POST['name']."', '1', '0')";

			if($mysqli->query($sql) == false){
				$errors .= '<div class="t"><div class="t4" align="center">Создание раздела сейчас невозможно, попробуйте позже.</div></div>';
			}else{
				$smarty->assign("save", true);
			}
		}else{			if(count($bad_form) > 0){
				rsort($bad_form);
				for($i = 0; $i < count($bad_form); $i++){
					if ( $i & 1 ) $value_count = "1"; else $value_count = "2";
					$errors .= '<div align="center">' . $bad_form[$i] . '</div>';
				}
			}
		}
		$smarty->assign("errors", $errors);
	}
}else{	$parent = $mysqli->query('SELECT * from bf_filters WHERE id = '.$Cur['id'].' LIMIT 1');

    if(!empty($parent->id) && empty($parent->host)){
	    if(isset($_POST['submit'])){
			array_walk($_POST, 'real_escape_string');

			if(empty($_POST['name'])){
				$bad_form['name'] = 'Название не может быть пустым.';
				$FORM_BAD = 1;
			}

			if($FORM_BAD <> 1){
				$sql = "INSERT INTO bf_filters (name, enable, parent_id) VALUES ('".$_POST['name']."', '1', '".($parent->parent_id == 0 ? $parent->id . '|' : $parent->parent_id . $parent->id . '|')."')";
				if($mysqli->query($sql) == false){
					$errors .= '<div class="t"><div class="t4" align="center">Создание раздела сейчас невозможно, попробуйте позже.</div></div>';
				}else{
					$smarty->assign("save", true);
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
			$smarty->assign("errors", $errors);
		}

	    $smarty->assign("parent", $parent);
		$dir['1'] = $parent->name;
		$dir['2'] = '<a href="/'.$Cur['to'].'/add_sub-'.$Cur['id'].'.html">'.$dirs['catalog']['add_sub'].'</a>';
	}else{		header('Location: /catalog/');
		exit;
	}
}

?>