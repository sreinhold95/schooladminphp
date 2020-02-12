<?php
	define('MYSQL_HOST', 	'192.168.3.23');
	define('MYSQL_USER', 	'zeugnis');
	define('MYSQL_PASS', 	'zeugnis1984#');
	define('MYSQL_DATABASE', 'bsznsql2');
	$mysqli = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);
	$mysqli->set_charset("utf8mb4");
	$apikey="assjsjnncnceuecnccnnc21wq2";
	//check connection
	if ($mysqli->connect_error) {
	trigger_error('Database connection failed: ' . $mysqli->connect_error, E_USER_ERROR);
	}
?>