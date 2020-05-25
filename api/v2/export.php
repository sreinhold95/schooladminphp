<?php
require $_SERVER['DOCUMENT_ROOT'] . '/include/config.inc.php';
session_start();
error_reporting(E_ERROR);
$_SESSION["uuid"] = "";
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	$headers = apache_request_headers();
	$json = json_encode($headers);
	$uuid = $headers['uuid'];
	$tab = $headers['tab'];
	$auth = false;
	$username = "";
	$check = $mysqli->query("select username from user where uuid='" . $uuid . "' and uuidlifetime>=DATE_SUB(NOW(),INTERVAL 24 HOUR)");
	if ($check->num_rows) {
		while ($row = $check->fetch_assoc()) {
			$username = $row["username"];
			$auth = true;
		}
	}
	if ($auth) {
		$_SESSION["uuid"] = $uuid;
		login();
		if (isset($_GET['lanis']))
			$result = getallstudentlanis();
		if (isset($_GET['webuntis']))
			$result = getallstudentwebuntis();
		echo $result;
	} else {
		header('HTTP/1.0 403 Forbitten');
		header('Content-Type: application/json');
		$data = array();
		$data['error'] = 'uuid is too old please generate new one';
		echo json_encode($data);
	}
}
function login()
{
	global $mysqli;
	if (isset($_SESSION["uuid"])) {
		$check = $mysqli->query("SELECT * FROM user WHERE uuid= '" . $mysqli->real_escape_string($_SESSION["uuid"]) . "' ;");
		if ($check->num_rows) {
			$_SESSION['loggedin'] = true;
			while ($row = $check->fetch_assoc()) {
				$_SESSION['id'] = $row['iduser'];
				if (isset($row['role'])) {
					$_SESSION['userrole'] = $row['role'];
					if ($row['teacher'] != '')
						$_SESSION['idteacher'] = $row['teacher'];
				}
				$_SESSION['isactiv'] = 1;
			}
			if ($_SESSION['isactiv'] == 1) {
				if ($_SESSION['userrole'] == 1) {
					return;
				} else if ($_SESSION['userrole'] == 2) {
					return;
				} else if ($_SESSION['userrole'] == 3) {
					return;
				} else if ($_SESSION['userrole'] == 4) {
					return;
				}
			}
		}
	}
}

function getallstudentlanis()
{
	global $mysqli;
	global $tab;
	$data = array();
	if ($_SESSION['isactiv'] == 1) {
		if ($_SESSION['userrole'] == 1) {
			$student = $mysqli->prepare("select * from lanis_import_nachname_vorname;");
			$student->execute();
			if ($student) {
				$data = array();
				$stdt = $student->get_result();
				while ($row = $stdt->fetch_assoc()) {
					if ($tab == "yes")
						$data[] = $row;
					else
						$data[] = $row;
				}
				$json = json_encode($data);
			}
			if ($json == "null") {
				$data["error"] = "no Student";
				$json = json_encode($data);
				header('HTTP/1.0 900 no data');
				header('Content-Type: application/json');
			} else {
				header('HTTP/1.0 200 OK');
				header('Content-Type: application/json');
			}
		}else {
			$data["error"] = "no rights" . " " . $_SESSION['userrole'];
			$json = json_encode($data);
			header('HTTP/1.0 403 no rights');
			header('Content-Type: application/json');
		}
	} else {
		$data["error"] = "not loggedin";
		$json = json_encode($data);
		header('HTTP/1.0 403 not loggedin');
		header('Content-Type: application/json');
	}
	return ($json);
}
function getallstudentwebuntis()
{
	global $mysqli;
	global $tab;
	$data = array();
	if ($_SESSION['isactiv'] == 1) {
		if ($_SESSION['userrole'] == 1) {
			$student = $mysqli->prepare("select * from untis_klassenbuch;");
			$student->execute();
			if ($student) {
				$data = array();
				$stdt = $student->get_result();
				while ($row = $stdt->fetch_assoc()) {
					if ($tab == "yes")
						$data[] = $row;
					else
						$data[$row["extern"]] = $row;
				}
				$json = json_encode($data);
			}
			if ($json == "null") {
				$data["error"] = "no Student";
				$json = json_encode($data);
				header('HTTP/1.0 900 no data');
				header('Content-Type: application/json');
			} else {
				header('HTTP/1.0 200 OK');
				header('Content-Type: application/json');
			}
		}else {
			$data["error"] = "no rights" . " " . $_SESSION['userrole'];
			$json = json_encode($data);
			header('HTTP/1.0 403 no rights');
			header('Content-Type: application/json');
		}
	} else {
		$data["error"] = "not loggedin";
		$json = json_encode($data);
		header('HTTP/1.0 403 not loggedin');
		header('Content-Type: application/json');
	}
	return ($json);
}