<?php
	require_once('../include/config.inc.php');
	session_start();
	$school=$_SESSION["school"];
	if($school=="")
		$school=$_COOKIE["school"];
	session_destroy();
	//unlink ( SESSION_FILE_DIR . '/sess_' . session_id());
	setcookie("userid", "", 0, "/", $domain);
	setcookie("username","" , 0, "/", $domain);
	setcookie("userrole","" , 0, "/", $domain);
	setcookie("uuid", "", 0, "/", $domain);
	setcookie("classcode", "", 0, "/", $domain);
	setcookie("classtoken", "", 0, "/", $domain);
	session_unset();
	if ($school=="fls"){
		header('location: https://app.edkimo.com/survey/digitales-arbeiten/fuluwgi');
	}
	else{
		header('location: ../index.php');
	}
	
?>