<?php

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

if(empty($user->config['lang'])){
    $user->config['lang'] = $config['lang'];
}

if(isset($_POST['save'])){
	unset($_POST['save']);

	foreach($_POST['cp'] as $key => $item){
	    if(!preg_match('~^([0-9]+)$~is', $item) || empty($item)) $_POST['cp'][$key] = '100';
		if($item > 500)  $_POST['cp'][$key] = '100';
	}

	foreach($_POST as $key => $item){
	    switch($key){
			case 'systems':
				$_POST['systems'] = array_flip($_POST['systems']);
				array_walk($_POST['systems'], 'array_true');
				$user->config['systems'] = $_POST['systems'];
			break;
			/*
			case 'prefix':
				
				$_POST['prefix'] = array_flip($_POST['prefix']);
				array_walk($_POST['prefix'], 'array_true');
				$user->config['prefix'] = $_POST['prefix'];
			break;
			*/
			case 'userid':
				$user->config['userid'] = $_POST['userid'];
			break;
		    
			default:
				$user->config[$key] = $item;
			break;
		}
	}
	
	$mysqli->query('update bf_users set userid = \''.$user->config['userid'].'\', config = \''.json_encode($user->config).'\' WHERE (id = \''.$user->id.'\')');

	if(!empty($_POST['lang'])) $_SESSION['user']->config['lang'] = $_POST['lang'];
	
	header('Location: /accounts/');
	exit;
}

$systems = $mysqli->query('SELECT `nid`, `name` FROM bf_systems');
$prefix = $mysqli->query('SELECT  DISTINCT(prefix) prefix FROM bf_bots');

$smarty->assign('user', $user);
$smarty->assign("prefix", $prefix);
$smarty->assign('systems', $systems);

$cl = @json_decode(@file_get_contents('cache/clients_list.json'), true);
$smarty->assign('clist', $cl);

$dir['1'] = '<a href="/'.$Cur['to'].'/profile-'.$Cur['id'].'.html">'.ucfirst($user->login).'</a>';
$dir['2'] = '<a href="/'.$Cur['to'].'/'.$Cur['go'].'-'.$Cur['id'].'.html">'.$dirs[$Cur['to']][$Cur['go']].'</a>';

?>