<?php

if(@file_exists('scripts/'.$_GET['to'].'/'.$_GET['go'].'.php')){
	@include_once('scripts/'.$_GET['to'].'/'.$_GET['go'].'.php');
	exit;
}

$dir = str_replace('\\', '/', realpath('.')) . '/';

include_once("includes/core.php");

if(!empty($_SESSION["user"]->PHPSESSID) && $_SESSION['hidden'] != 'on'){	$result = $mysqli->query("SELECT * FROM bf_users WHERE (login='".$_SESSION["user"]->login."') AND (password='".$_SESSION["user"]->password."') AND (PHPSESSID='".$_SESSION["user"]->PHPSESSID."') AND (enable='1') LIMIT 1");
	if($result->PHPSESSID == $_SESSION["user"]->PHPSESSID){		unset($_SESSION['user']);

		$result->login = ucfirst($result->login);
		$result->access = json_decode($result->access, true);
        $result->config = json_decode($result->config, true);

		$_SESSION['user'] = $result;
		$_SESSION['user']->PHPSESSID = $_COOKIE['PHPSESSID'];
		$_SESSION['user']->access['accounts']['authorization'] = 'on';
		$_SESSION['user']->access['accounts']['exit'] = 'on';

		if($_SESSION['hidden'] != 'on'){
			$mysqli->query("UPDATE bf_users SET expiry_date=NOW() WHERE (id='".$_SESSION["user"]->id."') LIMIT 1");
		}
	}else{		unset($_SESSION["user"]);
	}
}

if(file_exists('modules/'.$Cur['to'].'/'.$Cur['go'].'.php')){	if($_SESSION['user']->access[$Cur['to']][$Cur['go']] != 'on'){		$smarty->assign("site_data", "modules/accounts/access_denied.tpl");
	}else{
		language($config['lang'], $Cur['to']);

		if(file_exists('modules/'.$Cur['to'].'/module_dirs.php')){
			include_once('modules/'.$Cur['to'].'/module_dirs.php');

			if(!isset($dir['0'])){				$dir['0'] = '<a href="/'.$Cur['to'].'/">'.$dirs[$Cur['to']]['index'].'</a>';
				$smarty->assign('title', $dirs[$Cur['to']]['index']);
			}

			if($Cur['go'] != 'index' && !isset($dir['1'])){				$dir['1'] = '<a href="/'.$Cur['to'].'/'.$Cur['go'].'.html">'.$dirs[$Cur['to']][$Cur['go']].'</a>';
			    $smarty->assign('title', $dirs[$Cur['to']]['index'].' - '.$dirs[$Cur['to']][$Cur['go']]);
			}

			ksort($dir);
			$smarty->assignByRef('dir', $dir);
		}

		if(file_exists('modules/'.$Cur['to'].'/'.$Cur['go'].(($Cur['window'] == '1')?'_window':'').'.php')) include_once('modules/'.$Cur['to'].'/'.$Cur['go'].(($Cur['window'] == '1')?'_window':'').'.php');
		if($smarty->tpl_vars['site_data'] == 'empty.tpl' && file_exists('templates/modules/'.$Cur['to'].'/'.$Cur['go'].(($Cur['window'] == '1')?'_window':'').'.tpl')) $smarty->assign('site_data', 'modules/'.$Cur['to'].'/'.$Cur['go'].(($Cur['window'] == '1')?'_window':'').'.tpl');
	}

    if($Cur['ajax'] == '1'){
    	$smarty->display('ajax.tpl', implode('', $Cur));
    }else{
    	$smarty->display('index.tpl', implode('', $Cur));
    }
}else{	header("Location: /");
	exit;
}
?>