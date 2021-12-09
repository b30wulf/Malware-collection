<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

get_function('real_escape_string');

if(!empty($Cur['id'])){
    $item = $mysqli->query('SELECT * from bf_systems WHERE id = '.$Cur['id'].' LIMIT 1');
	if($item->id == $Cur['id']){
		$smarty->assign("item", $item);
		if(isset($_POST['submit'])){			$format = $_POST['format'];
			@array_walk($_POST, 'real_escape_string');
            $_POST['format'] = $format;

			if(empty($_POST['name'])){
				$bad_form['name'] = $lang['knbp'];
				$FORM_BAD = 1;
			}else{				if(strlen($_POST['name']) > 125){
					$bad_form['name'] = $lang['knbpaq'];
					$FORM_BAD = 1;
				}
			}

			if(empty($_POST['percent'])){
				$bad_form['percent'] = $lang['knbpa'];
				$FORM_BAD = 1;
			}else{				if(strlen($_POST['percent']) > 125){
					$bad_form['percent'] = $lang['knbpaa'];
					$FORM_BAD = 1;
				}else{
					if(!preg_match('~^([0-9]+)$~', $_POST['percent'])){
						$bad_form['percent'] = $lang['knbpaz'];
						$FORM_BAD = 1;
					}
				}
			}

			if(!empty($_POST['format'])){
				ob_start();
				error_reporting(-1);
				include_once('includes/functions.numformat.php');
				eval($_POST['format']);
				$eval = ob_get_contents();
				error_reporting(0);
				ob_end_clean();

				if(strpos($eval, 'syntax error') != false){					$bad_form['format'] = $lang['knbpafz'];
					$FORM_BAD = 1;
				}elseif(strpos($eval, 'error</b>') != false){					$bad_form['format'] = $lang['knbpafz'];
					$FORM_BAD = 1;
				}
			}

			if($FORM_BAD <> 1){
				if($mysqli->query('update bf_systems set name = \''.$_POST['name'].'\', percent = \''.$_POST['percent'].'\', format = \''.base64_encode($_POST['format']).'\' WHERE (id = \''.$item->id.'\')') == false){
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

		if(empty($_POST['name'])) $_POST['name'] = $item->name;
		if(empty($_POST['percent'])) $_POST['percent'] = $item->percent;
		if(empty($_POST['format'])) $_POST['format'] = base64_decode($item->format);

	}
}


?>