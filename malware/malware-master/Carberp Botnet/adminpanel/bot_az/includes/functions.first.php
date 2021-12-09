<?php

if(!function_exists('no_found')){
	function no_found(){
		global $dir;
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
		print(file_get_contents($dir . '404.html'));
		exit;
	}
}

if(!function_exists('is_ok')){
	function is_ok(){
		header("Status: 403 Forbidden");
		header("HTTP/1.1 403 Forbidden");
		exit;
	}
}

if(!function_exists('print_data')){
	function print_data($str, $exit = false, $dg = false){
		global $debag;
		if($str == '403') is_ok();
		if($dg != false) $debag = $dg;
		$debag = true;
		if($debag == true){
			print($str);
			if($exit == true) exit;
		}else{
			no_found();
		}
	}
}

if(!empty($_POST['remote_ip'])){
	$_SERVER['REMOTE_ADDR'] = $_POST['remote_ip'];
}elseif(!empty($_SERVER["HTTP_X_REAL_IP"])){
	$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_X_REAL_IP"];
}

?>