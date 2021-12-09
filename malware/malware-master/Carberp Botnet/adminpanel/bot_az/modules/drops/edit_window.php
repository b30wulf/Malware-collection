<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

function cuto($t){
	return str_replace("'", '', $t);
}

if(!empty($Cur['id'])){
	if(!empty($_SESSION['user']->config['userid'])){
		$item = $mysqli->query('SELECT * from bf_drops WHERE (userid = \''.$_SESSION['user']->config['userid'].'\') AND (id = '.$Cur['id'].') LIMIT 1');
	}else{
		$item = $mysqli->query('SELECT * from bf_drops WHERE id = '.$Cur['id'].' LIMIT 1');
	}

	if($item->id == $Cur['id']){
		$smarty->assign("item", $item);

		if(strstr($_POST['system'], '|') != false){
			$_POST['system'] = explode('|', $_POST['system']);
		}

		if(isset($_POST['submit'])){
			$_POST['system'] = implode('|', $_POST['system']);
			@array_walk($_POST, 'cuto');

			if(empty($_POST['name'])){
				$bad_form['name'] = $lang['knbp'];
				$FORM_BAD = 1;
			}else{
				if(strlen($_POST['name']) > 250){
					$bad_form['name'] = $lang['knbpaq'];
					$FORM_BAD = 1;
				}
			}

			if(empty($_POST['receiver'])){
				$bad_form['receiver'] = $lang['rnbp'];
				$FORM_BAD = 1;
			}else{
				if(strlen($_POST['receiver']) > 1024){
					$bad_form['receiver'] = $lang['rnbpaq'];
					$FORM_BAD = 1;
				}
			}

			if(empty($_POST['destination'])){
				$bad_form['destination'] = $lang['dnbp'];
				$FORM_BAD = 1;
			}else{
				if(strlen($_POST['destination']) > 250){
					$bad_form['destination'] = $lang['dnbpaq'];
					$FORM_BAD = 1;
				}
			}

			if(empty($_POST['acc'])){
				$bad_form['acc'] = $lang['anbp'];
				$FORM_BAD = 1;
			}else{
				if(strlen($_POST['acc']) > 32){
					$bad_form['acc'] = $lang['anbpaq'];
					$FORM_BAD = 1;
				}elseif(!preg_match('~^([0-9]+)$~', $_POST['acc'])){
					$bad_form['acc'] = '"' . $lang['acc'] . '" ' . $lang['mbtc'];
					$FORM_BAD = 1;
				}
			}

			if(empty($_POST['vat']) && $_POST['vat'] !== '0'){
				$bad_form['vat'] = $lang['vnbp'];
				$FORM_BAD = 1;
			}else{
				if(strlen($_POST['vat']) > 2){
					$bad_form['vat'] = $lang['vnbpaq'];
					$FORM_BAD = 1;
				}
			}

			if(empty($_POST['from'])){
				$bad_form['from'] = $lang['fnbp'];
				$FORM_BAD = 1;
			}else{
				if(strlen($_POST['from']) > 16){
					$bad_form['from'] = $lang['fnbpaq'];
					$FORM_BAD = 1;
				}elseif(!preg_match('~^([0-9]+)$~', $_POST['from'])){
					$bad_form['from'] = '"' . $lang['from'] . '" ' . $lang['maxis'];
					$FORM_BAD = 1;
				}
			}

			if(empty($_POST['to'])){
				$bad_form['to'] = $lang['tnbp'];
				$FORM_BAD = 1;
			}else{
				if(strlen($_POST['to']) > 16){
					$bad_form['to'] = $lang['tnbpaq'];
					$FORM_BAD = 1;
				}elseif(!preg_match('~^([0-9]+)$~', $_POST['to'])){
					$bad_form['to'] = '"' . $lang['to'] . '" ' . $lang['maxis'];
					$FORM_BAD = 1;
				}
			}
			
			if(!empty($_POST['max'])){
				if(!preg_match('~^([0-9]+)$~', $_POST['max'])){
					$bad_form['max'] = '"' . $lang['max'] . '" ' . $lang['maxis'];
					$FORM_BAD = 1;
				}elseif($_POST['max'] <= $_POST['to']){
					$bad_form['max'] = $lang['maxiz'];
					$FORM_BAD = 1;
				}
			}

			if(!empty($_POST['other']['kppb']) && !preg_match('~^([0-9]+)$~', $_POST['other']['kppb'])){
				$bad_form['otherkppb'] = '"' . $lang['dkppb'] . '" ' . $lang['mbtc'];
				$FORM_BAD = 1;
			}

			if(!empty($_POST['other']['bik']) && !preg_match('~^([0-9]+)$~', $_POST['other']['bik'])){
				$bad_form['otherbik'] = '"' . $lang['dbik'] . '" ' . $lang['mbtc'];
				$FORM_BAD = 1;
			}else{
				if(strlen($_POST['from']) > 9){
					$bad_form['from'] = $lang['fnbpaq'];
					$FORM_BAD = 1;
				}
			}

			if(!empty($_POST['other']['BnkKOrrAcnt']) && !preg_match('~^([0-9]+)$~', $_POST['other']['BnkKOrrAcnt'])){
				$bad_form['otherBnkKOrrAcnt'] = '"' . $lang['dsbp'] . '" ' . $lang['mbtc'];
				$FORM_BAD = 1;
			}

			if(!empty($_POST['other']['inn']) && !preg_match('~^([0-9]+)$~', $_POST['other']['inn'])){
				$bad_form['otherinn'] = '"' . $lang['inn'] . '" ' . $lang['mbtc'];
				$FORM_BAD = 1;
			}

			if(!empty($_POST['other']['kppp']) && !preg_match('~^([0-9]+)$~', $_POST['other']['kppp'])){
				$bad_form['otherkppp'] = '"' . $lang['dkppp'] . '" ' . $lang['mbtc'];
				$FORM_BAD = 1;
			}
			
			if(!empty($_POST['citybank'])){
				$_POST['citybank'] = mb_strtolower($_POST['citybank'], 'UTF8');
				$_POST['citybank'] = preg_replace('~^ã.~', '', $_POST['citybank'], 1);
			}
			
			if(!empty($_POST['check_city']) && $_POST['check_city'] == 1){
				if(empty($_POST['citybank'])){
					$bad_form['check_city'] = 'CityBank Empty!';
					$FORM_BAD = 1;
				}
			}
			
			$_POST['other']['check_note'] = $_POST['check_note'];
			$_POST['other']['check_city'] = $_POST['check_city'];

			if($FORM_BAD <> 1){
				//if($mysqli->query("INSERT INTO bf_drops (`name`, `receiver`, `destination`, `acc`, `from`, `to`, `vat`, `other`, `system`, `post_id`) VALUES ('".$_POST['name']."', '".$_POST['receiver']."', '".$_POST['destination']."', '".$_POST['acc']."', '".$_POST['from']."', '".$_POST['to']."', '".$_POST['vat']."', '".json_encode($_POST['other'])."', '".$_POST['system']."|', '".$_SESSION['user']->id."')") == false){
				$other = array_map('base64_encode', $_POST['other']);
				if($mysqli->query('update bf_drops set `name` = \''.$_POST['name'].'\', `receiver` = \''.$_POST['receiver'].'\', `destination` = \''.$_POST['destination'].'\', `acc` = \''.$_POST['acc'].'\', `from` = \''.$_POST['from'].'\', `to` = \''.$_POST['to'].'\', `citybank` = \''.$_POST['citybank'].'\', `max` = \''.$_POST['max'].'\', `vat` = \''.$_POST['vat'].'\', `other` = \''.json_encode($other).'\', `check_note` = \''.$_POST['check_note'].'\', `check_city` = \''.$_POST['check_city'].'\', `system` = \''.$_POST['system'].'|\' WHERE (id = \''.$item->id.'\')') == false){
					$errors .= '<div class="t"><div class="t4" align="center">'.$lang['dzsnp'].'</div></div>';
				}else{
					$smarty->assign("save", true);
				}
			}else{
				$base64enc = true;
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
		
		//$item->other = json_decode($item->other, true);
		
		if(empty($_POST['name'])) $_POST['name'] = $item->name;
		if(empty($_POST['receiver'])) $_POST['receiver'] = $item->receiver;
		if(empty($_POST['destination'])) $_POST['destination'] = $item->destination;
		if(empty($_POST['acc'])) $_POST['acc'] = $item->acc;
		if(empty($_POST['from'])) $_POST['from'] = $item->from;
		if(empty($_POST['to'])) $_POST['to'] = $item->to;
		if(empty($_POST['max'])) $_POST['max'] = $item->max;
		if(empty($_POST['vat'])) $_POST['vat'] = $item->vat;
		if(empty($_POST['name'])) $_POST['name'] = $item->name;
		if(empty($_POST['citybank'])) $_POST['citybank'] = $item->citybank;
		if(empty($_POST['check_note'])) $_POST['check_note'] = $item->check_note;
		if(empty($_POST['check_city'])) $_POST['check_city'] = $item->check_city;
		$item->system = array_flip(explode('|', $item->system));

		if(!is_array($_POST['other'])){
			$_POST['other'] = array();
			$item->other = json_decode($item->other, true);
			foreach($item->other as $k => $i){
				if(empty($_POST['other'][$k])) $_POST['other'][$k] = base64_decode($i);
			}
		}

		if($_SESSION['user']->config['infoacc'] == '1'){
			foreach($_SESSION['user']->config['systems'] as $key => $item){
				$sql .= ' OR (nid = \''.$key.'\')';
			}
			
			$sql = preg_replace('~^ OR ~', '', $sql);
			
			if(!empty($sql)){
				$systems = $mysqli->query('SELECT * from bf_systems WHERE '.$sql, null, null, false);
			}
		}else{
			$systems = $mysqli->query('SELECT * from bf_systems', null, null, false);
		}
		
		$smarty->assign('systems', $systems);
	}
}

?>