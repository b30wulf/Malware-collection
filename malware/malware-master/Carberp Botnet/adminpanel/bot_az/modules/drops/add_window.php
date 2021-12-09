<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

if(strstr($_POST['system'], '|') != false){	$_POST['system'] = explode('|', $_POST['system']);
}elseif(!empty($_POST['system']) && !is_array($_POST['system'])){	$system = $_POST['system'];
	$_POST['system'] = array();
	$_POST['system'][] = $system;
}

function cuto($t){
	if(!is_array($t)){		return str_replace("'", '', $t);
	}else{		return $t;
	}
}

if(is_array($_POST['system']) && count($_POST['system']) > 0){
	$_POST['system'] = implode('|', $_POST['system']);
	@array_walk($_POST, 'cuto');

	if(isset($_POST['submit'])){
		if($_SESSION['user']->config['infoacc'] == '1'){
			$system = explode('|', $_POST['system']);
			foreach($system as $item){
				if($_SESSION['user']->config['systems'][$item] != true){
					$bad_form['system'] = 'System Error!';
					$FORM_BAD = 1;
				}
			}
		}
		
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
			$other = array_map('base64_encode', $_POST['other']);
			if($mysqli->query("INSERT INTO bf_drops (`name`, `receiver`, `destination`, `acc`, `from`, `to`, `max`, `citybank`, `vat`, `other`, `check_city`, `check_note`, `system`, `post_id`, `userid`) VALUES ('".$_POST['name']."', '".$_POST['receiver']."', '".$_POST['destination']."', '".$_POST['acc']."', '".$_POST['from']."', '".$_POST['to']."', '".$_POST['max']."', '".$_POST['citybank']."', '".$_POST['vat']."', '".json_encode($other)."', '".$_POST['check_city']."', '".$_POST['check_note']."', '".$_POST['system']."|', '".$_SESSION['user']->id."', '".$_SESSION['user']->config['userid']."')") == false){
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
}else{	if($_SESSION['user']->config['infoacc'] == '1'){
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

?>