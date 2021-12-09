<?php

get_function('size_format');

function ioncube_version () {
	if(function_exists('ioncube_loader_iversion') ) {
		$ioncube_loader_iversion = ioncube_loader_iversion();
		return (int)substr($ioncube_loader_iversion,0,1) . '.' . (int)substr($ioncube_loader_iversion,1,2)  . '.' . (int)substr($ioncube_loader_iversion,3,2);
	} else {
		return ioncube_loader_version();
	}
}

function time_check($key, $value){
	global $lang;
	$return = '';
	switch(strlen($value)){
		case '1':
			$return = $lang[$key][$value];
		break;

		case '2':
			if(isset($lang[$key][$value])){
				$return = $lang[$key][$value];
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
	global $lang;

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

if(count($license['ip']) > 0){	$brainforce["ip"] = implode('<br />', array_keys($license['ip']));
}else{
	$brainforce["ip"] = $lang['no1'];
}

if(PHP_OS != 'WINNT'){	$uptime = @file_get_contents( "/proc/uptime" );
	if(!empty($uptime)){		$brainforce["server_uptime"] = time_math(strtok($uptime, "."));
	}elseif(@exec('cat /proc/uptime') != ''){		$brainforce["server_uptime"] = time_math(strtok(@exec('cat /proc/uptime'), "." ));
	}else{		$brainforce["server_uptime"] = 'N/A';
	}

	ob_start();
	system('cat /proc/meminfo');
	$fmeminfo = ob_get_contents();
	ob_end_clean();

	if(empty($fmeminfo)){		$fmeminfo = @file_get_contents("/proc/meminfo");
	}

	if(!empty($fmeminfo)){		$data = explode("\n", $fmeminfo);
		$meminfo = array();
		foreach ($data as $line) {			list($key, $val) = explode(":", $line);
			$meminfo[$key] = trim(str_replace('kB', '', $val));
		}

    	$meminfo['MemTotal'] *= 1024;
    	$meminfo['MemFree'] *= 1024;
    	$meminfo['Buffers'] *= 1024;
    	$meminfo['Cached'] *= 1024;
    	$meminfo['MemFreeAll'] = $meminfo['MemFree'] + $meminfo['Buffers'] + $meminfo['Cached'];
    	$meminfo['Used'] = $meminfo['MemTotal'] - $meminfo['MemFree'];
    	$meminfo['UsedAll'] = $meminfo['MemTotal'] - $meminfo['MemFree'] - $meminfo['Buffers'] - $meminfo['Cached'];
    	$meminfo['SwapTotal'] *= 1024;
    	$meminfo['SwapFree'] *= 1024;
    	$meminfo['SwapCached'] *= 1024;
    	$meminfo['SwapFreeAll'] = $meminfo['SwapFree'] + $meminfo['SwapCached'];
		$brainforce["server_meminfo"] = $meminfo;
	}else{		$brainforce["server_meminfo"] = false;
	}

	$brainforce["sys"] = json_decode(exec('sar | fgrep Average | awk \'{print "{\"user\":\""$3"\",\"system\":\""$5"\",\"iowait\":\""$6"\",\"idle\":\""$8"\"}"}\''), true);
}

if(@file_exists('/etc/redhat-release')){	$brainforce["os"] = @file_get_contents('/etc/redhat-release');
}elseif(@file_exists('/etc/debian_version')){
	$brainforce["os"] = 'Debian ' . @file_get_contents('/etc/debian_version') . ' (' . php_uname('m') . ')';
}elseif(@file_exists('/etc/release')){
	$brainforce["os"] = @file_get_contents('/etc/release');
}else{	$brainforce["os"] = php_uname('s') . ' ' . php_uname('r') . ' (' . php_uname('m') . ')';
}

$brainforce["webserver"] = $_SERVER["SERVER_SOFTWARE"];

if(version_compare(phpversion(), '5.3.3', '>=') == true){
	$brainforce["phpversion"] = phpversion();
}else{
	$brainforce["phpversion"] = '<font color="red">'.phpversion().'</font>';
}

if(extension_loaded('Zend Optimizer') == True){	$brainforce["ZendOptimizer"] = zend_optimizer_version();
}elseif(extension_loaded('Zend Guard Loader') == True){
	$brainforce["ZendOptimizer"] = 'Zend Guard Loader';
}else{
	$brainforce["ZendOptimizer"] = $lang['ni'];
}

if(extension_loaded('ionCube Loader') == True){
	$brainforce["ionCubeLoader"] = ioncube_version();
}else{
	$brainforce["ionCubeLoader"] = '<font color="red">'.$lang['ni'].'</font>';
}

if(extension_loaded('geoip') == True){
	$vgeoip_country = explode(' ', geoip_database_info(GEOIP_COUNTRY_EDITION));
	$brainforce["geoip_country"] = $vgeoip_country[0] . ' ' . $vgeoip_country[1];
	unset($vgeoip_country);
}elseif(file_exists('cache/geoip/')){
	//require_once('cache/geoip/geoip.inc');
    //$gi = geoip_open('cache/geoip/GeoIP.dat',GEOIP_STANDARD);
    //$brainforce["geoip_country"] = 'External library<br />dbType (' . $gi->databaseType . ')<br />dbSegments (' . $gi->databaseSegments . ')';
    //geoip_close($gi);
    $brainforce["geoip_country"] = 'External library (cache/geoip/GeoIP.dat)';
}

if(empty($brainforce["geoip_country"])) $brainforce["geoip_country"] = '<font color="red">'.$lang['ni'].'</font>';

$result = $mysqli->query("SHOW TABLE STATUS");

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
}else{	$brainforce['sys_loadavg']['0'] = '-';
	$brainforce['sys_loadavg']['1'] = '-';
	$brainforce['sys_loadavg']['2'] = '-';
}

$brainforce['mysql_uptime'] = time_math($brainforce['mysql_uptime']);

$result = $mysqli->query("SELECT * FROM bf_users WHERE (PHPSESSID<>'') AND (expiry_date >= DATE_ADD(NOW(), INTERVAL -5 MINUTE)) ORDER by expiry_date DESC", null, null, false);
if(count($result) > 0){
	foreach($result as &$user){		$user->info = json_decode($user->info);
	}
}

$modules = scandir('modules/', false);
unset($modules[0], $modules[1]);
$mod = array();
foreach($modules as $value){	if(is_dir('modules/' . $value)){		if(file_exists('modules/' . $value . '/module.php')){			include('modules/' . $value . '/module.php');
			$mod[] = $module;
			unset($modules);
		}else{			$mod[] = array('name' => $value, 'version' => 'N/A', 'autor' => 'unknow', 'email' => 'unknow');
		}
	}
}

if(function_exists('eaccelerator_info')){
	$eaccelerator_info = eaccelerator_info();
	$smarty->assign("eaccelerator_info", $eaccelerator_info);
}

if(function_exists('disk_free_space')){
	$brainforce['dfs'] = disk_free_space(realpath('./'));
}

if(function_exists('disk_total_space')){
	$brainforce['dts'] = disk_total_space(realpath('./'));
}

if(file_exists('cache/current_speed.txt')){
	$s = json_decode(file_get_contents('cache/current_speed.txt'), true);
	$ms = array();
	$ms['rx'] = array();
	$ms['tx'] = array();

	$cs['rx'] = count($s['rx'])-1;
	foreach($s['rx'] as $k => $c){
		if($cs['rx'] > $k){
			$ms['rx'][] = $s['rx'][$k+1] - $s['rx'][$k];
		}
	}
	$msc['rx'] = ceil(array_sum($ms['rx']) / $cs['rx']);
	$s['rx'] = size_format($msc['rx']);
	$s['rxb'] = size_format($msc['rx'], 2, true);
	$cs['tx'] = count($s['tx'])-1;
	
	foreach($s['tx'] as $k => $c){
		if($cs['tx'] > $k){
			$ms['tx'][] = $s['tx'][$k+1] - $s['tx'][$k];
		}
	}
	$msc['tx'] = ceil(array_sum($ms['tx']) / $cs['tx']);
	$s['tx'] = size_format($msc['tx']);
	$s['txb'] = size_format($msc['tx'], 2, true);

	$s['ft'] = date('d.m.Y H:i:s', $s['time']);
}

$smarty->assign('speed', $s);

$smarty->assign("active_user", $result);
$smarty->assign('core', $brainforce);
$smarty->assign('mods', $mod);
$smarty->assign('PHP_OS', PHP_OS);
?>