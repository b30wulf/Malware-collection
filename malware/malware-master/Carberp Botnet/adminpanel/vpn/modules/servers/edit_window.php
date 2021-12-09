<?php

get_function('create_cfg');

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

if(!empty($Cur['id'])){	$item = $mysqli->query('SELECT * from bf_servers WHERE id = '.$Cur['id'].' LIMIT 1');

	if($item->id == $Cur['id']){
		$smarty->assign("item", $item);

		if(isset($_POST['submit'])){
			if(empty($_POST['name'])){
				$bad_form['name'] = '"' . $lang['name'] . '" ' . $lang['nbp'];
				$FORM_BAD = 1;
			}else{
				if(strlen($_POST['name']) > 120){
					$bad_form['name'] = $lang['nbpn2'];
					$FORM_BAD = 1;
				}
			}

			if(empty($_POST['ip'])){
				$bad_form['ip'] = '"' . $lang['ip'] . '" ' . $lang['nbp'];
				$FORM_BAD = 1;
			}else{
				if(strlen($_POST['ip']) > 20){
					$bad_form['ip'] = $lang['nbpi'];
					$FORM_BAD = 1;
				}elseif(!preg_match('~^([0-9.]+)$~', $_POST['ip'])){
					$bad_form['ip'] = $lang['nbpi'];
					$FORM_BAD = 1;
				}else{
					$ip = explode('.', $_POST['ip'], 4);

					$test = true;
					foreach($ip as $t){
						if($t > 255){
							$test = false;
							break;
						}
					}

					if($test != true){
		            	$bad_form['ip'] = $lang['nbpi'];
						$FORM_BAD = 1;
					}
				}
			}

			if(empty($_POST['port'])){
				$bad_form['port'] = '"' . $lang['port'] . '" ' . $lang['nbp'];
				$FORM_BAD = 1;
			}else{
				if(strlen($_POST['port']) > 5){
					$bad_form['port'] = $lang['nbpp2'];
					$FORM_BAD = 1;
				}elseif($_POST['port'] > 65536 && $_POST['port'] < 0){
					$bad_form['port'] = $lang['nbpp2'];
					$FORM_BAD = 1;
				}
			}

			if(empty($_POST['ca'])){
				$bad_form['ca'] = '"' . $lang['ca'] . '" ' . $lang['nbp'];
				$FORM_BAD = 1;
			}

			if(empty($_POST['crt'])){
				$bad_form['crt'] = '"' . $lang['crt'] . '" ' . $lang['nbp'];
				$FORM_BAD = 1;
			}

			if(empty($_POST['key'])){
				$bad_form['key'] = '"' . $lang['key'] . '" ' . $lang['nbp'];
				$FORM_BAD = 1;
			}
            /*
			if(empty($_POST['ta'])){
				$bad_form['ta'] = '"' . $lang['ta'] . '" ' . $lang['nbp'];
				$FORM_BAD = 1;
			}
            */
			if($FORM_BAD <> 1){
				if($mysqli->query('update bf_servers set `name` = \''.$_POST['name'].'\', `protocol` = \''.$_POST['protocol'].'\', `ip` = \''.$_POST['ip'].'\', `port` = \''.$_POST['port'].'\', `ca` = \''.$_POST['ca'].'\', `crt` = \''.$_POST['crt'].'\', `key` = \''.$_POST['key'].'\', `ta` = \''.$_POST['ta'].'\', `cfg` = \''.base64_encode($_POST['cfg']).'\', `enable` = \''.$_POST['enable'].'\' WHERE (id = \''.$item->id.'\')') == false){
					$errors .= '<div class="t"><div class="t4" align="center">'.$lang['dzsnp'].'</div></div>';
				}else{
					$_POST['id'] = $item->id;
					$_POST['cfg'] = base64_encode($_POST['cfg']);
            		create_cfg($_POST);
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

		foreach($item as $k => $i){			if(!isset($_POST[$k])){				if($k == 'cfg'){					$_POST[$k] = base64_decode($item->$k);
				}else{					$_POST[$k] = $item->$k;
				}
			}
		}
	}else{		exit;
	}

}

?>