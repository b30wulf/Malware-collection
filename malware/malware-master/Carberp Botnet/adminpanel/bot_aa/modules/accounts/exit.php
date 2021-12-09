<?php

$mysqli->query("update bf_users set PHPSESSID='' WHERE (id='".$_SESSION["user"]->id."') LIMIT 1");

unset($_SESSION['user']);
session_destroy();

header("Location: /");
exit;


?>