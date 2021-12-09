<?php

get_function('real_escape_string');

function uh_load($row){
	global $uh;
	$row->config = json_decode($row->config);
	if($row->config->sbbc == true){
		$uh[$row->id] = $row;
	}
}

if(isset($_POST['reg_submit'])){
	@array_walk($_POST, 'real_escape_string');
	$_POST['login'] = @strtolower($_POST['login']);

	if(empty($_POST['login'])){
		$bad_form['login'] = $lang['alnbp'];
		$FORM_BAD = 1;
	}else{
		$result = $mysqli->query("SELECT login FROM bf_users WHERE (login='".$_POST['login']."')");
		if($result->login == $_POST['login']){
			$bad_form['login'] = $lang['avlye'];
			$FORM_BAD = 1;
		}
	}

	if(empty($_POST['password'])){
		$bad_form['password'] = $lang['apnbp'];
		$FORM_BAD = 1;
	}

	if($_POST['password'] <> $_POST['pass_dbl']){
		$bad_form['password'] = $lang['apens'];
		$FORM_BAD = 1;
	}

	if($_POST['cfg']['prefix'] == '*') $_POST['cfg']['prefix'] = '';

	if($FORM_BAD <> 1){
		if($mysqli->query("INSERT INTO bf_users (enable, login, password, config, access) VALUES ('1', '".$_POST['login']."', '".md5($_POST['password'])."', '".json_encode($_POST['cfg'])."', '".json_encode($_POST['rights'])."')") == false){
			$errors .= '<div class="t"><div class="t4" align="center">'.$lang['asyzsnv'].'</div></div>';
		}else{
			$uh = array();
			$mysqli->query('SELECT id,login,config,enable FROM bf_users', null, 'uh_load', false);
			file_put_contents('cache/users_hunters.json', json_encode($uh));

			if(function_exists('save_history_log')){
				thl('Action: Add user');
				thl('Login: ' . $_POST['login']);
				save_history_log();
			}
			
			header('Location: /accounts/index.html');
			exit;
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

$prefix = scandir('cache/prefix/', false);
unset($prefix[0], $prefix[1]);
$smarty->assign("prefix", $prefix);

include_once("modules/accounts/rights_list.php");
$smarty->assign("rights", $right);

?>