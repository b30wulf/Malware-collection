<?php

setlocale(LC_ALL,"ru_RU.UTF-8");
//error_reporting(E_ALL ^E_NOTICE);
error_reporting(0);

include_once('includes/config.php');
include_once('includes/functions.php');

require_once("classes/mysqli.class.php");
$mysqli = new mysqli_db();

$mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
unset($cfg_db);
if(count($mysqli->errors) > 0){
	echo 'Произошла ошибка...';
	exit;
}

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

if(file_exists('scripts/'.$Cur['to'].'/'.$Cur['go'].'.php')){	//$config = file_exists('includes/config.json') ? json_decode(file_get_contents('includes/config.json'), 1) : '';
	include_once('scripts/'.$Cur['to'].'/'.$Cur['go'].'.php');
	exit;
}
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
if($Cur['window'] == '1')$Cur['ajax'] = '1';

require_once("classes/smarty/Smarty.class.php");
$smarty = new Smarty;

$smarty->assign('site_data', 'empty.tpl');
$smarty->compile_check = true;
$smarty->caching = false;
$smarty->template_dir = 'templates/';
$smarty->compile_dir = 'cache/smarty/';
$smarty->cache_dir = 'cache/smarty/';
//$config = file_exists('includes/config.json') ? json_decode(file_get_contents('includes/config.json'), 1) : '';
//$smarty->assign('config', $config);
$smarty->assignByRef("Cur", $Cur);
$smarty->allow_php_tag = true;

//$smarty->allow_php_templates = true;

session_start();

if(isset($_SESSION['user']->PHPSESSID)){	if(empty($Cur['to'])) $Cur['to'] = 'admins';
	if(empty($Cur['go'])) $Cur['go'] = 'index';
}else{	if(empty($Cur['to'])) $Cur['to'] = 'accounts';
	if(empty($Cur['go'])) $Cur['go'] = 'authorization';
}

if(!isset($_SESSION['user']->PHPSESSID) || $_SESSION['user']->PHPSESSID != $_COOKIE['PHPSESSID']){
	//$_SESSION['user']->access['accounts']['registration'] = 'on';
	$_SESSION['user']->access['accounts']['authorization'] = 'on';
	$_SESSION['user']->access['accounts']['exit'] = 'on';
	$_SESSION['user']->access['accounts']['captcha'] = 'on';
	$_SESSION['user']->access['accounts']['confirm'] = 'on';
}

$dir = array();

?>