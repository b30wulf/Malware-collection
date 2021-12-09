<?php

if(empty($Cur['id'])) $Cur['id'] = $_SESSION['user']->id;

if($Cur['id'] != $_SESSION['user']->id){
	if($_SESSION['user']->access['accounts']['profiles'] != 'on'){
		$smarty->assign("site_data", "modules/accounts/access_denied.tpl");
		$smarty->display('index.tpl');
		exit;
	}
}else{	$_SESSION['user']->access['accounts']['enable_disable'] = false;
}

if($_SESSION['user']->access['accounts']['enable_disable'] == 'on'){	if($Cur['type'] === '1'){		$mysqli->query("UPDATE bf_users SET enable='1' WHERE (id='".$Cur['id']."') LIMIT 1");
	}elseif($Cur['type'] === '0'){		$mysqli->query("UPDATE bf_users SET enable='0' WHERE (id='".$Cur['id']."') LIMIT 1");
	}
}
//print_rm($_SESSION['user']->config);
$result = $mysqli->query('SELECT * FROM bf_users WHERE (id<>\'0\') AND (id=\''.$Cur['id'].'\') LIMIT 1');

//print_rm($sum);
$smarty->assign("summ", $summ);

$result->info = json_decode($result->info);
$result->config = json_decode($result->config, true);
$smarty->assign("user", $result);

$dir['1'] = '<a href="/'.$Cur['to'].'/profile-'.$Cur['id'].'.html">'.ucfirst($result->login).'</a>';
//$dir['2'] = '<a href="/'.$Cur['to'].'/'.$Cur['go'].'-'.$Cur['id'].'.html">'.$dirs[$Cur['to']][$Cur['go']].'</a>';
?>