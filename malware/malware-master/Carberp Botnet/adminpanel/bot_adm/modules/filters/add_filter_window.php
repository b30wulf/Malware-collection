<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));
$smarty->assign('value_name', 'p' . $smarty->tpl_vars['rand_name']->value);

//print_rm($_POST);

if(empty($Cur['id'])){
	if(isset($_POST['submit']) && $_POST['submit'] == $lang['add']){
        $_POST['name'] = real_escape_string($_POST['name']);
        $_POST['host'] = real_escape_string($_POST['host']);
        $_POST['savelog'] = real_escape_string($_POST['savelog']);

		if($_POST['savelog'] == 'on'){
			$_POST['savelog'] = '1';
		}else{
			$_POST['savelog'] = '0';
		}

		if(empty($_POST['name'])){
			$bad_form['name'] = $lang['fnmbp'];
			$FORM_BAD = 1;
		}

		if(empty($_POST['host'])){
			$bad_form['host'] = $lang['snmbp'];
			$FORM_BAD = 1;
		}else{
			if($mysqli->query_name('SELECT host from bf_filters WHERE host = \''.$_POST['host'].'\' LIMIT 1', null, 'host') == $_POST['host']){
				$bad_form['host'] = $lang['dsyes'];
				$FORM_BAD = 1;
			}
		}

		if(preg_match('~^([a-zA-Z0-9.,-]+)$~', $_POST['host']) != true){
			$bad_form['host_words'] = $lang['smststzrd'];
			$FORM_BAD = 1;
		}

		if($FORM_BAD <> 1){
            $insert_id = $mysqli->query("INSERT INTO bf_filters (name, host, parent_id) VALUES ('".$_POST['name']."', '".$_POST['host']."', '0')");

            if($insert_id == false){
				$errors .= '<div class="t"><div class="t4" align="center">'.$lang['sfsnp'].'</div></div>';
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
}else{
	$parent = $mysqli->query('SELECT * from bf_filters WHERE id = '.$Cur['id'].' LIMIT 1');

	if($parent->id != $Cur['id'] || !empty($parent->host)){
		exit;
	}

	if(isset($_POST['submit']) && $_POST['submit'] == $lang['add']){
        $_POST['name'] = real_escape_string($_POST['name']);
        $_POST['host'] = real_escape_string($_POST['host']);
        $_POST['savelog'] = real_escape_string($_POST['savelog']);

		if($_POST['savelog'] == 'on'){			$_POST['savelog'] = '1';
		}else{			$_POST['savelog'] = '0';
		}

		if(empty($_POST['name'])){
			$bad_form['name'] = $lang['fnmbp'];
			$FORM_BAD = 1;
		}

		if(empty($_POST['host'])){
			$bad_form['host'] = $lang['snmbp'];
			$FORM_BAD = 1;
		}else{
			if($mysqli->query_name('SELECT host from bf_filters WHERE host = \''.$_POST['host'].'\' LIMIT 1', null, 'host') == $_POST['host']){
				$bad_form['host'] = $lang['dsyes'];
				$FORM_BAD = 1;
			}
		}

		if(preg_match('~^([a-zA-Z0-9.,-]+)$~', $_POST['host']) != true){
			$bad_form['host_words'] = $lang['smststzrd'];
			$FORM_BAD = 1;
		}

		if($FORM_BAD <> 1){
			$insert_id = $mysqli->query("INSERT INTO bf_filters (name, host, parent_id) VALUES ('".$_POST['name']."', '".$_POST['host']."', '". (empty($parent->parent_id)? $parent->id . '|' : $parent->parent_id . $parent->id . '|') ."')");

            if($insert_id == false){
				$errors .= '<div class="t"><div class="t4" align="center">'.$lang['sfsnp'].'</div></div>';
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

	$dir['1'] = $parent->name;
	$dir['2'] = '<a href="/'.$Cur['to'].'/add_filter-'.$Cur['id'].'.html">'.$dirs['catalog']['add_filter'].'</a>';
	$smarty->assign('parent', $parent);
}

?>