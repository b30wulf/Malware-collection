
if(file_exists($file_name)){
	header( "Content-Disposition: attachment; filename=\"" . basename($file_name) . '"' );
	header( "Content-Length: " . filesize($file_name));

	if(preg_match('~lighttpd~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
		header( 'X-LIGHTTPD-send-file: ' . $file_name);
	}elseif(preg_match('~apache~', strtolower($_SERVER['SERVER_SOFTWARE'])) == true){
		header('X-Sendfile: ' . $file_name);
	}
}else{
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
}

exit;
