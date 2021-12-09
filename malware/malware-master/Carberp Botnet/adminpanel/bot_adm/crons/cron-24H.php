#!/usr/bin/env php
<?php

$dir = pathinfo(__FILE__, PATHINFO_DIRNAME) . '/';
$dir = realpath($dir . '/../') . '/';

function uncompress($srcName, $dstName) {
    if(file_exists($srcName)){
        if(file_exists($dstName)) @unlink($dstName);
        
        $sfp = gzopen($srcName, "rb");
        $fp = fopen($dstName, "w");
        
        while ($string = gzread($sfp, 4096)) {
            fwrite($fp, $string, strlen($string));
        }
        
        gzclose($sfp);
        fclose($fp);
    }
}

file_put_contents('/tmp/recfg.sh', '#!/bin/sh' . "\n");
file_put_contents('/tmp/recfg.sh', 'cd ' . $dir . 'crons/scripts/' . "\n", FILE_APPEND);
file_put_contents('/tmp/recfg.sh', '/usr/bin/env php ' . $dir . 'crons/scripts/recfg.php > /dev/null &', FILE_APPEND);
chmod('/tmp/recfg.sh', 0777);
@system('/tmp/recfg.sh');
unlink('/tmp/recfg.sh');

/*
$content = file_get_contents('http://geolite.maxmind.com/download/geoip/database/GeoLiteCountry/');

preg_match_all('~<a href="GeoIP.dat.gz">GeoIP.dat.gz</a>(.*)(([0-9]+)-([a-zA-Z]+)-([0-9]+) ([0-9]+):([0-9]+))~is', $content, $content, PREG_SET_ORDER);

$date = strtotime($content[0][2]);


$gdate = '';

if(extension_loaded('geoip') == True){
    $vgeoip_country = explode(' ', geoip_database_info(GEOIP_COUNTRY_EDITION));
    $gdate = $vgeoip_country[1] . ' 00:01';
    unset($vgeoip_country);
}

if(!empty($gdate)){
    $compare = $gdate - $date;
}else{
    $compare = time() - $date; 
}

if($compare >= 3024000){
    file_put_contents($dir . 'cache/geoip/GeoIP.dat.gz', file_get_contents('http://geolite.maxmind.com/download/geoip/database/GeoLiteCountry/GeoIP.dat.gz'));
    uncompress($dir . 'cache/geoip/GeoIP.dat.gz', $dir . 'cache/geoip/GeoIP.dat');
    
    @unlink($dir . 'cache/geoip/GeoIP.dat.gz');
    
    if(function_exists('geoip_db_filename') && strtoupper(substr(PHP_OS, 0, 3)) != 'WIN'){
        $gip_file = geoip_db_filename(GEOIP_COUNTRY_EDITION);
        
        if(!empty($gip_file)){
            @unlink($gip_file);
            copy($dir . 'cache/geoip/GeoIP.dat', $gip_file);
        }
    }
}

*/

?>