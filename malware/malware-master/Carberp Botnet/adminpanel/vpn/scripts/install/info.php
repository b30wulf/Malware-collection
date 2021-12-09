<?php

if(file_exists('cache/install')){
	header('Location: /login/');
	exit;
}

phpinfo();
?>