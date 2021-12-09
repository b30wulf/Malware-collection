
function str2db($str){
	global $config, $rc;
	if($config['scramb'] == 1){
		return rc_encode($str);
	}else{
		return $str;
	}
}
