<?php
	require_once('../include/config.inc.php');
	session_start();
	session_destroy();
	//unlink ( SESSION_FILE_DIR . '/sess_' . session_id());
	setcookie("userid", "", 0, "/", $domain);
	setcookie("username","" , 0, "/", $domain);
	setcookie("userrole","" , 0, "/", $domain);
	setcookie("uuid", "", 0, "/", $domain);
	session_unset();
	header('location: ../index.php')

?>