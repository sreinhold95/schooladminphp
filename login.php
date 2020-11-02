<?php
ini_set('session.gc_maxlifetime', 60 * 60);

//ini_set('session.cookie_lifetime', 10*60);
header('HTTP/1.0 200 OK');
http_response_code(200);
$session_timeout = 10 * 60; // 360 Sek./60 Sek. = 6 Minuten
session_start();
if (!isset($_SESSION['last_visit'])) {
	$_SESSION['last_visit'] = time();
	// Aktion der Session wird ausgeführt
}
if ((time() - $_SESSION['last_visit']) > $session_timeout) {
	session_destroy();
	// Aktion der Session wird erneut ausgeführt
}
$_SESSION['last_visit'] = time();
require_once('include/config.inc.php');
global $domain;
if (isset($_POST['username'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];
	//$password = hash( 'sha256', $password );  
}
if (isset($username) & isset($password)) {
	$stmt = $mysqli->prepare("SELECT * FROM user WHERE username= ? AND password = ? limit 1;");
	$stmt->bind_param("ss", $username, $password);
	if ($stmt->execute()) {
		$pid = $stmt->get_result();
		while ($row = $pid->fetch_assoc()) {
			$_SESSION['loggedin'] = true;
			setcookie("userid", $row['iduser'],  0, "/", $domain, true);
			setcookie("username", $row['username'], 0, "/", $domain, true);
			if ($row['teacher'] != '')
				setcookie("idteacher", $row['teacher'], 0, "/", $domain, true);
			$_SESSION['id'] = $row['iduser'];
			$_SESSION["username"] = $row['username'];
			$_SESSION["school"] = $row['school'];
			if (isset($row['role'])) {
				$_SESSION['userrole'] = $row['role'];
				if ($row['teacher'] != '')
					$_SESSION['idteacher'] = $row['teacher'];
			}
			$_SESSION['isactiv'] = 1;
			$uuid = uuid($username, $password);
			setcookie("uuid", $uuid, 0, "/", $domain, true);
		}
		if ($_SESSION['isactiv'] == 1) {
			if ($_SESSION['userrole'] == 1) {
				header('location: admin/index.php?site=home');
			} else if ($_SESSION['userrole'] == 2) {
				header('location: dep_instructor/index.php?site=home');
			} else if ($_SESSION['userrole'] == 3) {
				header('location: classteacher/index.php?site=home');
			} else if ($_SESSION['userrole'] == 4) {
				header('location: administration/index.php?site=home');
			}
		} else {
			echo '<font color="#FF0000">Account ist abgelaufen!</font>';
		}
	} else {
		echo '<font color="#FF0000">Account nicht vorhanden!</font>';
	}
}
if (isset($_POST['token'])) {
	$stmt = $mysqli->prepare("SELECT classcode,token,activetoken,uuid,school FROM class WHERE activetoken=1 and TIMESTAMPDIFF(MINUTE,tokenactivateat, NOW())<45 and token=? limit 1;");
	$stmt->bind_param("s", $mysqli->real_escape_string($_POST['token']));
	if ($stmt->execute()) {
		$pid = $stmt->get_result();
		while ($row = $pid->fetch_assoc()) {
			$_SESSION['loggedin'] = true;
			ob_start();
			setcookie("classtoken", $row['token'],0, "/", $domain);
			setcookie("classcode", $row['classcode'],0, "/", $domain);
			setcookie("userrole", "0",0, "/", $domain);
			setcookie("uuid", $row['uuid'], 0, "/", $domain);
			setcookie("school", $row['school'],0, "/", $domain);
			ob_end_flush();
			$_SESSION['token'] = $row['token'];
			$_SESSION['classcode'] = $row['classcode'];
			$_SESSION["userrole"] = 0;
			$_SESSION["school"] = $row['school'];
			
		}
		if (isset($_SESSION['token']) & isset($_SESSION['classcode'])) {
			header('location: class/index.php?site=create');
		}
	} else {
		echo '<font color="#FF0000">der Token der Klasse ist abgelaufen!</font>';
	}
}
function uuid($username, $password)
{
	global $mysqli;
	$uuid = $mysqli->prepare("update user set uuid=uuid(), uuidlifetime=now() WHERE username= ? AND password = ? and (uuidlifetime<=DATE_SUB(NOW(),INTERVAL 12 HOUR) or uuidlifetime is null) limit 1;");
	$uuid->bind_param("ss", $username, $password);
	$uuid->execute();
	$uid = "";
	$getuuid = $mysqli->prepare("SELECT uuid FROM user WHERE username= ? AND password = ? limit 1;");
	$getuuid->bind_param("ss", $username, $password);
	$getuuid->execute();
	$erg = $getuuid->get_result();
	while ($row = $erg->fetch_assoc()) {
		$uid = $row['uuid'];
	}
	return $uid;
}