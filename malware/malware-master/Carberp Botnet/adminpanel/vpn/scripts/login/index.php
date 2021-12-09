<?php

session_start();
$config = file_exists('cache/config.json') ? json_decode(file_get_contents('cache/config.json'), 1) : '';

if($config['autorize_key'] == true){	if(!empty($_GET['x'])){		$_SESSION['autorize_key'] = $_GET['x'];
		header('Location: /accounts/authorization.html');
	}else{		unset($_SESSION['autorize_key']);
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
		print(file_get_contents('404.html'));
		exit;
	}
}else{	header('Location: /accounts/authorization.html');
}

?>