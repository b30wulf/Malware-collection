<?php

if(empty($_POST['prefix']) && empty($_POST['id']) && !empty($_POST['uid'])) $_POST['id'] = $_POST['uid'];
if(!empty($_POST['id'])){
	$matches = explode('0', $_POST['id'], 2);
	if(!empty($matches[0]) && !empty($matches[1])){
		$_POST['prefix'] = $matches[0];
		$_POST['uid'] = '0' . $matches[1];
	}else{
		$_POST['prefix'] = 'UNKNOWN';
		$_POST['uid'] = '0123456789';
	}
}

if(empty($_POST['prefix']) || empty($_POST['uid'])) no_found();

$_POST['prefix'] = strtoupper($_POST['prefix']);
$_POST['uid'] = strtoupper($_POST['uid']);

if(!preg_match('~^([a-zA-Z]+)$~', $_POST['prefix']) || !preg_match('~^([a-zA-Z0-9]+)$~', $_POST['uid'])) no_found();

@file_put_contents($dir . 'cache/prefix/' . strtoupper($_POST['prefix']), true);

?>