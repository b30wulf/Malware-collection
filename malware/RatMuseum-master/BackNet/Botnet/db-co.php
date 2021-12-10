<?php
	/*
	* Sample to connect to a mysql database
	*/

	define('DB_SERVEUR', 'localhost');	// Your database server address
	define('DB_USER', 'root'); 			// Database username
	define('DB_PASSWORD', '');			// ... password
	define('DB_BASE', 'backnet');		// Botnet database name

	$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
	$p_base = new PDO('mysql:host=' . DB_SERVEUR . ';dbname=' . DB_BASE, DB_USER, DB_PASSWORD, $pdo_options);
	$p_base->exec("Set character set utf8");
?>
