"<?php echo $lang['cs']; ?>"
<br /><br />
<?php
if($_GET['go'] != 'index') exit;
?>
<?php echo $lang['vp']; ?>:
<?php
$ver = explode('.', phpversion());

if(version_compare(phpversion(), '5.3.3', '>=') == true){
	echo phpversion();
}else{
	$INSTALL = true;
	echo '<span style="color:red">'.phpversion().'</span>';
}

 ?>
<hr />
WebServer - <?php echo $lang['module']; ?> mod_xsendfile:
<?php

if(!is_writable('cache/')) @chmod('cache/', '777');

if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){	echo $lang['in'];
}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
	$am = apache_get_modules();
	if($am[array_search('mod_xsendfile', $am)] == 'mod_xsendfile'){
		echo $lang['in'];
	}else{		echo ' <span style="color:red">'.$lang['ni'].'</span>';
	}
}

$check_download = @file_get_contents('http://' . $_SERVER["HTTP_HOST"] . '/accounts/check_download.html');
if(strlen($check_download) != 32){
	$INSTALL = true;
	echo ' <span style="color:red">('.$lang['nw'].')</span>';
}else{
	echo ' ('.$lang['wo'].')';
}

?>
<hr />
PHP - <?php echo $lang['module']; ?> GeoIP Country:
<?php

if(extension_loaded ('geoip')){
	echo $lang['io'];
}else{
	if(file_exists('cache/geoip/') && file_exists('cache/geoip/geoip.inc') && file_exists('cache/geoip/GeoIP.dat')){
		echo $lang['ev'];
	}else{
		$INSTALL = true;
		echo '<span style="color:red">'.$lang['ni'].'</span>';
	}
}

?>
<hr />
PHP - <?php echo $lang['module']; ?> MySQLi:
<?php

if(extension_loaded ('mysqli')){
	echo $lang['in'];
}else{
	$INSTALL = true;
	echo '<span style="color:red">'.$lang['ni'].'</span>';
}

?>
<hr />
PHP - Модуль Zip:
<?php

if(extension_loaded ('zip')){
	echo $lang['in'];
}else{
	$INSTALL = true;
	echo '<span style="color:red">'.$lang['ni'].'</span>';
}

?>
<hr />
PHP - <?php echo $lang['module']; ?> Pcntl:
<?php
$ret = exec('/usr/bin/env php -m | grep pcntl');
if($ret == 'pcntl'){
	echo $lang['in'];
}else{
	$INSTALL = true;
	echo '<span style="color:red">'.$lang['ni'].'</span>';
}

?>
<hr />
<?php

$func = array();
$func[] = 'base64_encode';
$func[] = 'base64_decode';
$func[] = 'gzdeflate';
$func[] = 'gzinflate';
$func[] = 'pack';
$func[] = 'json_encode';
$func[] = 'json_decode';
$func[] = 'dirname';
$func[] = 'mysqli_init';
$func[] = 'openssl_encrypt';
$func[] = 'openssl_decrypt';
$func[] = 'mb_convert_encoding';
//$func[] = 'pcntl_fork';

if(count($func) > 0){
	foreach($func as $value){
		if(!function_exists($value)){
			$INSTALL = true;
			//if($_GET['func'] == 1)
			echo 'PHP - '.$lang['fu'].' '.$value.': <span style="color:red">'.$lang['ot'].'</span><hr />';
		}else{
			//if($_GET['func'] == 1) echo 'PHP - Функция '.$value.': есть<hr />';
		}
	}
}

?>
<br />
<?php
if($INSTALL != true){
?>
<input type="button" value="<?php echo $lang['next']; ?>" onclick="location = '/install/index.html?step=2';" />
<?php
}else{
?>
<?php echo $lang['pn']; ?><br /><br />"<span style="color:red"><?php echo $lang['nk']; ?></span>"!
<?php
}
?>