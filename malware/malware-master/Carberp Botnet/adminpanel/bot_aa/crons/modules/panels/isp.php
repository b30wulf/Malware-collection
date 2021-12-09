<?php
// https://server11.hosting.reg.ru/manager/@@@u0791085:hTsoTLWe

set_time_limit(0);
ini_set('max_execution_time', 0);

$mypid = getmypid();

$dir['script'] = pathinfo(__FILE__, PATHINFO_DIRNAME);
$dir['script'] = realpath($dir['script'] . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
$dir['includes'] = realpath($dir['script'] . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR);
$dir['cache'] = realpath($dir['script'] . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR);
$dir['modules'] = realpath($dir['script'] . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR);
$dir['cur_logs'] = file_get_contents($dir['cache'] . DIRECTORY_SEPARATOR . 'cur_logs.txt');

file_put_contents($dir['script'] . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'imports' . DIRECTORY_SEPARATOR . $mypid, 1);

require($dir['includes'] . DIRECTORY_SEPARATOR . 'curl.class.php');

function createPassword($length) {
	$chars = "234567890abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$i = 0;
	$password = "";
	while ($i <= $length) {
		$password .= $chars{@mt_rand(0,strlen($chars))};
		$i++;
	}
	return $password;
}

$site = base64_decode($_SERVER['argv'][1]);

$site = explode("@@@", $site);
$site[1] = explode(":", $site[1]);

$cookie = $dir['cache'] . DIRECTORY_SEPARATOR . md5($site[0]) . '.txt';

$http = new get_http();
$http->clear();
$http->config['post'] = true;
$http->config['postFields'] = 'login_theme=cpanel&user='.$site[1][0].'&pass='.$site[1][1].'&goto_uri=%2F';
$http->config['cookieFileLocation'] = $cookie;
$http->config['followlocation'] = false;
$http->config['referer'] = $site[0] . '/login/';
$http->open($site[0] . '/login/');

?>