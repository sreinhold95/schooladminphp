<?php
	error_reporting(E_ALL);
	require('../include/config.inc.php');
	//session_destroy();
	//ini_set('session.gc_maxlifetime', 5*60); 
	//ini_set('session.cookie_lifetime', 5*60);
	session_start();
	/*$session_timeout = 5*60; // 360 Sek./60 Sek. = 6 Minuten
	if (!isset($_SESSION['last_visit'])) {
	$_SESSION['last_visit'] = time();
	// Aktion der Session wird ausgeführt
	}
	if((time() - $_SESSION['last_visit']) > $session_timeout) {*
	session_destroy();
	// Aktion der Session wird erneut ausgeführt
	}*/
	$_SESSION['last_visit'] = time();
	if(isset($_GET["site"])) {
		$site = $_GET['site'];
		if(strlen($site) != 0) {
			if($site == "homeclass") {
				$text = "site/login.php";
				include("../style/header.php");
				include("../style/menu.php");
				include("../style/content.php");
			}
			else if($site == "create") {
				$text = "site/create.php";
				include("../style/header.php");
				include("../style/menu.php");
				include("../style/content.php");
			}
			else if($site == "createtest") {
				$text = "site/createtest.php";
				include("../style/header.php");
				include("../style/menu.php");
				include("../style/content.php");
			}
		}
	}
?> 