#!/usr/bin/env php
<?php

$dir = array();

$dir['sites'] = '/srv/www/vhosts/';
$dir['vhosts.d'] = '/etc/lighttpd/vhosts.d/';
$dir['vhosts.auto.d'] = '/etc/lighttpd/vhosts.auto.d/';

$hosts = file_get_contents('/etc/hosts');

$vhosts_auto_d = scandir($dir['vhosts.auto.d']);
unset($vhosts_auto_d[0], $vhosts_auto_d[0]);

if(count($vhosts_auto_d) > 0){
	foreach($vhosts_auto_d as $d){
		if(is_file($vhosts_auto_d . $d)){
			@unlink($vhosts_auto_d . $d);
		}
	}
}

$sites = scandir($dir['sites']);
//unset($sites[0], $sites[1]);

foreach($sites as $site){
	if($site != '.' && $site != '..' && is_dir($dir['sites'] . $site)){
		if(!file_exists($dir['vhosts.d'] . $site . '.conf')){
			$txt = "\n";

			$txt .= '$HTTP["host"] == "'.$site.'" {' . "\n";
			$txt .= "\t" . 'var.server_name = "'.$site.'"' . "\n";
			$txt .= "\t" . 'server.document-root = "' . $dir['sites'] . $site .'/"' . "\n";

            if(file_exists($dir['sites'] . $site . '/404.html')){
            	$txt .= "\t" . 'server.error-handler-404 = "' . $dir['sites'] . $site . '/404.html' . '"' . "\n";
            }

            if(file_exists($dir['sites'] . $site . '/lighttpd_rewrite.conf')){
            	$txt .= "\t" . 'url.access-deny = ( "lighttpd_rewrite.conf" )' . "\n";
            	$txt .= "\t" . 'include "' . $dir['sites'] . $site . '/lighttpd_rewrite.conf' . '"' . "\n";
            }
            $txt .= '}';
			file_put_contents($dir['vhosts.auto.d'] . $site . '.conf', $txt);

			if(strpos($hosts, $site) == false){
				file_put_contents('/etc/hosts', '127.0.0.1 ' . $site . "\n", FILE_APPEND);
			}
		}
	}
}

?>