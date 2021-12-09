<?php
//error_reporting(-1);
$dir = str_replace('\\', '/', pathinfo(__FILE__, PATHINFO_DIRNAME));
$dir = str_replace('/scripts/logs', '/', $dir);
$dir = $dir . 'cache/';

$file_name = $dir . 'filter_ftps_get_all_' . date('d.m.Y_G.i.s', time()) . '.txt';
//$mysqli->db[0]->real_query('SELECT concat(\'ftp://\', v2, \':\', v3, \'@\', v1, \'/\') INTO OUTFILE \''.$file_name.'\' FIELDS TERMINATED BY \'\' LINES TERMINATED BY \'\r\n\' FROM bf_filter_ftps');
//echo 'SELECT concat(\'ftp://\', v2, \':\', v3, \'@\', v1, \'/\') INTO OUTFILE \''.$file_name.'\' FIELDS TERMINATED BY \'\' LINES TERMINATED BY \'\r\n\' FROM bf_filter_ftps';
if(file_exists($file_name)){	header( "Content-Disposition: attachment; filename=\"" . basename($file_name) . '"' );
	if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){		header( 'X-LIGHTTPD-send-file: ' . $file_name);
	}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){		header('X-Sendfile: ' . $file_name);
	}
}

exit;

?>