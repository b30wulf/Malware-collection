
header( 'Content-Disposition: attachment; filename="' . $data_file . '"');
if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){	header( 'X-LIGHTTPD-send-file: ' . $dir . 'cache/' . $data_file);
}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){	header('X-Sendfile: ' . $dir . 'cache/' . $data_file);
}