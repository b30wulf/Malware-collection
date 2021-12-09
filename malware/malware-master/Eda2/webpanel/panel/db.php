<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);
$connection = new PDO('mysql:host=localhost;port=3306;dbname=dbnamex;charset=utf8', 'username', 'oassword');
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//table name = dummy
$q = $connection->query('SELECT * FROM dummy');
?>
