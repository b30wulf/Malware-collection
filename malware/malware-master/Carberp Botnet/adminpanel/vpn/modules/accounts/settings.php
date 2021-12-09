<?php

function uh_load($row){	global $uh;
	$row->config = json_decode($row->config);
	if($row->config->sbbc == true){		$uh[$row->id] = $row;
	}
}

function array_true(&$item, $key){
    $item = true;
}


if(empty($Cur['id'])) $Cur['id'] = $_SESSION['user']->id;

if($Cur['id'] != $_SESSION['user']->id){
	if($_SESSION['user']->access['accounts']['setting'] != 'on'){
		$smarty->assign("site_data", "modules/accounts/access_denied.tpl");
		$smarty->display('index.tpl');
		exit;
	}
}

$user = $mysqli->query('SELECT * FROM bf_users WHERE (id<>\'0\') AND (id=\''.$Cur['id'].'\') LIMIT 1');

$user->access = json_decode($user->access, true);
$user->config = json_decode($user->config, true);

if(empty($user->config['lang'])){	$user->config['lang'] = $config['lang'];
}

if(isset($_POST['save'])){
	unset($_POST['save']);

	foreach($_POST as $key => $item){
		switch($key){
			case 'servers':
				$_POST['servers'] = array_flip($_POST['servers']);
				array_walk($_POST['servers'], 'array_true');
				$user->config['servers'] = $_POST['servers'];
			break;
		
			case 'clients':
				$_POST['clients'] = array_flip($_POST['clients']);
				array_walk($_POST['clients'], 'array_true');
				$user->config['clients'] = $_POST['clients'];
			break;
		
			default:
				$user->config[$key] = $item;
			break;
		}
	}
	
	$mysqli->query('update bf_users set config = \''.json_encode($user->config).'\' WHERE (id = \''.$user->id.'\')');

	if(!empty($_POST['lang'])) $_SESSION['user']->config['lang'] = $_POST['lang'];

	header('Location: /accounts/');	exit;
}

$servers = $mysqli->query('SELECT `id`, `name`, `ip` FROM bf_servers');
$clients = $mysqli->query('SELECT `id`, `name`, `desc` FROM bf_clients');

$smarty->assign('user', $user);
$smarty->assign('servers', $servers);
$smarty->assign('clients', $clients);

$dir['1'] = '<a href="/'.$Cur['to'].'/profile-'.$Cur['id'].'.html">'.ucfirst($user->login).'</a>';
$dir['2'] = '<a href="/'.$Cur['to'].'/'.$Cur['go'].'-'.$Cur['id'].'.html">'.$dirs[$Cur['to']][$Cur['go']].'</a>';

?>