<?php

	require('../include/config.inc.php');
	session_start();

	if(isset($_GET["site"])) {
		$site = $_GET['site'];
		if(strlen($site) != 0) {
			if($site == "home") {
				$text = "site/home.php";
			}
			if($site == "settings") {
				$text = "site/settings.php";
			}
			if($site == "doc") {
				$text = "site/doc.php";
			}
		}
	}

	if($_SESSION['loggedin'] == true) {
		if(isset($_SESSION['token'])) {
			include("../style/header.php");
			include("../style/menu.php");
			include("../style/content.php");
		}
	} else {
		header('location: ../index.php');
	}

?>