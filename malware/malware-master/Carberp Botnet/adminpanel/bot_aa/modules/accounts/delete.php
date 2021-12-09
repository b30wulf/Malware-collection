<?php

$user = $mysqli->query('SELECT * FROM bf_users WHERE (id<>\'0\') AND (id=\''.$Cur['id'].'\') LIMIT 1');

if($user->id == $Cur['id']){	$mysqli->query('delete from bf_users where (id = \''.$user->id.'\')');
	$mysqli->query('delete from bf_keys where (post_id = \''.$user->id.'\')');
	$mysqli->query('delete from bf_pays where (post_id = \''.$user->id.'\')');
	header('Location: /accounts/');
	exit;
}else{
	header('Location: /accounts/');	exit;
}

?>