<?php

function time_check($key, $value){
	global $text_time;
	$return = '';
	switch(strlen($value)){
		case '1':
			$return = $text_time[$key][$value];
		break;

		case '2':
			if(isset($text_time[$key][$value])){
				$return = $text_time[$key][$value];
			}else{
				$return = time_check($key, substr($value, strlen($value)-1, strlen($value)));
			}
		break;

		default:
			$return = time_check($key, substr($value, strlen($value)-2, strlen($value)));
		break;
	}
	return $return;
}

function time_math($s){
	global $text_time;

	$text_time['day'] = array('0' => 'дней', '1' => 'день', '2' => 'дня', '3' => 'дня', '4' => 'дня', '5' => 'дней', '6' => 'дней', '7' => 'дней', '8' => 'дней', '9' => 'дней', '11' => 'дней', '12' => 'дней', '13' => 'дней', '14' => 'дней');
	$text_time['hour'] = array('0' => 'часов', '1' => 'час', '2' => 'часа', '3' => 'часа', '4' => 'часа', '5' => 'часов', '6' => 'часов', '7' => 'часов', '8' => 'часов', '9' => 'часов', '11' => 'часов', '12' => 'часов', '13' => 'часов', '14' => 'часов');
	$text_time['min'] = array('0' => 'минут', '1' => 'минута', '2' => 'минуты', '3' => 'минуты', '4' => 'минуты', '5' => 'минут', '6' => 'минут', '7' => 'минут', '8' => 'минут', '9' => 'минут', '11' => 'минут', '12' => 'минут', '13' => 'минут', '14' => 'минут');
	$text_time['sec'] = array('0' => 'секунд', '1' => 'секунда', '2' => 'секунды', '3' => 'секунды', '4' => 'секунды', '5' => 'секунд', '6' => 'секунд', '7' => 'секунд', '8' => 'секунд', '9' => 'секунд', '11' => 'секунд', '12' => 'секунд', '13' => 'секунд', '14' => 'секунд');

	$time['sec'] =  $s%60;
	$m = floor($s/60);
	$time['min'] = $m%60;
	$m = floor($m/60);
	$time['hour'] = $m%24;
	$time['day'] = floor($m/24);
    $time = array_reverse($time);

    $return = '';
	foreach($time as $key => $value){
		if($value != '0'){
			if(!empty($return)) $return .= ', ';
            $return .= $value . ' ' . time_check($key, $value);
		}
	}
    return $return;
}


$brainforce["server_uptime"] = time_math(strtok( exec( "cat /proc/uptime" ), "." ));

if(file_exists('/etc/redhat-release')){	$brainforce["os"] = file_get_contents('/etc/redhat-release');
}elseif(file_exists('/etc/release')){	$brainforce["os"] = file_get_contents('/etc/release');
}else{	$brainforce["os"] = php_uname('s') . ' ' . php_uname('r') . ' (' . php_uname('m') . ')';
}

$brainforce["webserver"] = $_SERVER["SERVER_SOFTWARE"];

if(eregi('^5', phpversion())){
	$brainforce["phpversion"] = phpversion();
}else{
	$brainforce["phpversion"] = '<font color="red">'.phpversion().'</font>';
}

if(extension_loaded('Zend Optimizer') == True){
	$brainforce["ZendOptimizer"] = zend_optimizer_version();
}else{
	$brainforce["ZendOptimizer"] = '<font color="red">Не установлен</font>';
}

if(extension_loaded('geoip') == True){
	$vgeoip_country = explode(' ', geoip_database_info(GEOIP_COUNTRY_EDITION));
	$vgeoip_city = explode(' ', geoip_database_info(GEOIP_CITY_EDITION_REV0));
	$brainforce["geoip_country"] = $vgeoip_country[0] . ' ' . $vgeoip_country[1];
	$brainforce["geoip_city"] =  $vgeoip_city[0] . ' ' . $vgeoip_city[1];
	unset($vgeoip_country);
	unset($vgeoip_city);
}elseif(file_exists('cache/geoip/')){
	require_once("geoip/geoip.inc");
	require_once("geoip/geoipcity.inc");
	require_once("geoip/geoipregionvars.php");

    $gi = geoip_open("cache/geoip/GeoIP.dat",GEOIP_STANDARD);
    $brainforce["geoip_country"] = 'External library (' . $gi->databaseType . ')';
    geoip_close($gi);

    $gi = geoip_open("cache/geoip/GeoLiteCity.dat",GEOIP_STANDARD);
    $brainforce["geoip_city"] = 'External library (' . $gi->databaseType . ')';
    geoip_close($gi);
}

if(empty($brainforce["geoip_country"])) $brainforce["geoip_country"] = '<font color="red">Не установлен</font>';
if(empty($brainforce["geoip_city"])) $brainforce["geoip_city"] = '<font color="red">Не установлен</font>';

$result = $mysqli->query("SHOW TABLE STATUS", null, null, false);

$brainforce['mysql_table_count'] = 0;

foreach($result as $table) {
	$brainforce['mysql_table_count'] += 1;
	$t = $table->Avg_row_length + $table->Data_length + $table->Index_length + $table->Data_free;
	$brainforce['mysql_all_size'] += $t;
    $tables_size[$table->Name] = size_format($t);
    unset($t);
}

$smarty->assign('tables_size', $tables_size);

$result = $mysqli->query("SHOW STATUS WHERE (Variable_name = 'Uptime')");
$brainforce['mysql_uptime'] = $result->Value;

$brainforce['mysql_version'] = $mysqli->server_info();
$brainforce['mysql_all_size'] = size_format($brainforce['mysql_all_size']);

if(function_exists('sys_getloadavg')){
	$brainforce['sys_loadavg'] = sys_getloadavg();
	$brainforce['sys_loadavg']['0'] *= 100;
	$brainforce['sys_loadavg']['1'] *= 100;
	$brainforce['sys_loadavg']['2'] *= 100;
}else{	$brainforce['sys_loadavg']['0'] = '-';
	$brainforce['sys_loadavg']['1'] = '-';
	$brainforce['sys_loadavg']['2'] = '-';
}

$brainforce['mysql_uptime'] = time_math($brainforce['mysql_uptime']);
$brainforce['count_users'] = $mysqli->query_name("SELECT COUNT(id) count FROM bf_users");

$result = $mysqli->query("SELECT * FROM bf_users WHERE (PHPSESSID<>'') AND (expiry_date >= DATE_ADD(NOW(), INTERVAL -5 MINUTE)) ORDER by expiry_date DESC", null, null, false);
if(count($result) > 0){
	foreach($result as &$user){		$user->info = json_decode($user->info);
	}
}

$smarty->assign("active_user", $result);
$smarty->assign('core', $brainforce);
?>