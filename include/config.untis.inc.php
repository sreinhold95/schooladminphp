<?php
	define('MYSQL_HOST', 'bszn.schule');
	define('MYSQL_USER', 'untis');
	define('MYSQL_PASS', 'zgv@741456');
	define('MYSQL_DATABASE', 'untis');
	define('SchoolID','6283');
	define('SchoolYear','20182019');
	define ('VersionID','1');

	$untis = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);
	//$untis->set_charset("utf8mb4");
	//check connection
	if ($untis->connect_error) {
	trigger_error('Database connection failed: ' . $mysqli->connect_error, E_USER_ERROR);
	}
?>