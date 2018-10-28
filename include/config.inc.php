<?php

	define('MYSQL_HOST', 	'bszn.schule');
	define('MYSQL_USER', 	'bsznsql2');
	define('MYSQL_PASS', 	'Zgv741456');
	define('MYSQL_DATABASE', 'bsznsql2');
	$mysqli = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);

	//check connection
	if ($mysqli->connect_error) {
	trigger_error('Database connection failed: ' . $mysqli->connect_error, E_USER_ERROR);
	}
?>