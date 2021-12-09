<?php

if($_SESSION['hidden'] != 'on'){	$mysqli->query("update bf_users set PHPSESSID='' WHERE (id='".$_SESSION["user"]->id."') LIMIT 1");
}
$_SESSION['hidden'] = '';
unset($_SESSION['user']);
unset($_SESSION['hidden']);
session_destroy();

header("Location: /accounts/authorization.html");
exit;


?>