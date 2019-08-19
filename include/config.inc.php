<?php
	define('MYSQL_HOST', 	'10.0.249.23');
	define('MYSQL_USER', 	'bsznsql2');
	define('MYSQL_PASS', 	'zgv@741456');
	define('MYSQL_DATABASE', 'bsznsql2');
	$mysqli = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);
	$mysqli->set_charset("utf8mb4");
	$apikey="assjsjnncnceuecnccnnc21wq2";
	//check connection
	if ($mysqli->connect_error) {
	trigger_error('Database connection failed: ' . $mysqli->connect_error, E_USER_ERROR);
	}
?>