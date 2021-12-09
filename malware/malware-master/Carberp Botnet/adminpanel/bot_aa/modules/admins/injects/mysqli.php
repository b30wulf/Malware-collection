
include_once($dir . 'includes/functions.get_config.php');

if(function_exists('get_config')){
	$cfg_db = get_config();
}else{
	include_once($dir . 'includes/config.php');
}

$mysqli = new mysqli($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);

if (mysqli_connect_errno()){
	exit;
}

