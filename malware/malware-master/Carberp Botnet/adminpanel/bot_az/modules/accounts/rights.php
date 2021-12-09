<?php

if(empty($Cur['id'])) $Cur['id'] = $_SESSION['user']->id;

if($Cur['id'] != $_SESSION['user']->id){
	if($_SESSION['user']->access['accounts']['right'] != 'on'){
		$smarty->assign("site_data", "modules/accounts/access_denied.tpl");
		$smarty->display('index.tpl');
		exit;
	}
}

if(isset($_POST['save'])){	$mysqli->query("update bf_users set access='".json_encode($_POST['rights'])."' WHERE (id <> '0') AND (id='".$Cur['id']."') LIMIT 1");
	header("Location: /accounts/");
	exit;
}else{	$result = $mysqli->query("SELECT * FROM bf_users WHERE (id <> '0') AND (id='".$Cur['id']."') LIMIT 1");
	$result->access = json_decode($result->access, true);
	$result->config = json_decode($result->config, true);

	include_once("modules/accounts/rights_list.php");
	
	if($result->config['infoacc'] == 1){
		unset($result->access['accounts']);
		unset($result->access['settings']);
		//unset($result->access['drops']);
		unset($result->access['drops']['del']);
		unset($result->access['systems']['add']);
		unset($result->access['systems']['edit']);
		unset($result->access['systems']['del']);
		
		unset($right['accounts']);
		unset($right['settings']);
		//unset($right['drops']);
		unset($right['drops']['del']);
		unset($right['systems']['add']);
		unset($right['systems']['edit']);
		unset($right['systems']['del']);
	}
	
	$smarty->assign("rights", $right);
	$smarty->assign("user", $result);
	//$smarty->assign("site_data", "modules/accounts/rights.tpl");

	$dir['1'] = '<a href="/'.$Cur['to'].'/profile-'.$Cur['id'].'.html">'.ucfirst($result->login).'</a>';
	$dir['2'] = '<a href="/'.$Cur['to'].'/'.$Cur['go'].'-'.$Cur['id'].'.html">'.$dirs[$Cur['to']][$Cur['go']].'</a>';
}

?>