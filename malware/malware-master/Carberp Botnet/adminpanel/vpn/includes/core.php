<?php

setlocale(LC_ALL,"ru_RU.UTF-8");
error_reporting(0);
ini_set('error_reporting', 0);

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
@$Cur['go']=preg_match('~^([A-Za-z_]+)$~', $_GET['go'])<>1?null:$_GET['go'];

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

session_start();

$smarty->assignByRef("_SESSION", $_SESSION);

if(isset($_SESSION['user']->PHPSESSID)){
	if(empty($Cur['to'])) $Cur['to'] = 'main';
	if(empty($Cur['go'])) $Cur['go'] = 'info';
}else{
	if($Cur['to'] != 'accounts'){
		session_destroy();
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
		print(file_get_contents('404.html'));
		exit;
 	}
	//if(empty($Cur['to'])) $Cur['to'] = 'accounts';
	//if(empty($Cur['go'])) $Cur['go'] = 'authorization';
}

if(empty($config['lang'])) $config['lang'] = 'ru';

if(!isset($_SESSION['user']->PHPSESSID) || $_SESSION['user']->PHPSESSID != $_COOKIE['PHPSESSID']){
	language($config['lang']);
	$smarty->assignByRef('lang', $lang);

	$_SESSION['user']->access['accounts']['authorization'] = 'on';
	$_SESSION['user']->access['accounts']['exit'] = 'on';
	$_SESSION['user']->access['accounts']['captcha'] = 'on';
	$_SESSION['user']->access['accounts']['confirm'] = 'on';
}else{	if(!empty($_SESSION['user']->config['lang'])){		$config['lang'] = $_SESSION['user']->config['lang'];
		language($_SESSION['user']->config['lang']);
	}else{		language($config['lang']);
	}
}

$smarty->assignByRef('lang', $lang);

if(!empty($_SERVER["HTTP_X_REAL_IP"])) $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_X_REAL_IP"];

$dir = array();
?>