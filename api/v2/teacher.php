<?php
require $_SERVER['DOCUMENT_ROOT'] . '/include/config.inc.php';
session_start();
error_reporting(E_ERROR);
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	$headers = apache_request_headers();
	$uuid = $headers['uuid'];
	$tab = $headers['tab'];
	$auth = false;
	$check = $mysqli->query("select teacher from user where uuid='" . $uuid . "' and uuidlifetime>=DATE_SUB(NOW(),INTERVAL 24 HOUR)");
	if ($check->num_rows) {
		while ($row = $check->fetch_assoc()) {
			$idteacher = $row["teacher"];
			$auth = true;
		}
	}
	if ($auth) {
		if (isset($uuid)) {
			login($uuid);
			if (isset($_GET["idteacher"]))
				$result = getteacher($_GET["idteacher"]);
			else
				$result = getallteacher();
			header('HTTP/1.0 200 OK');
			header('Content-Type: application/json');
			echo $result;
		} else {
			header('HTTP/1.0 403 Forbitten');
			header('Content-Type: application/json');
			$data = array();
			$data['error'] = 'no username and passwort set';
			echo json_encode($data);
		}
	} else {
		header('HTTP/1.0 403 Forbitten');
		header('Content-Type: application/json');
		$data = array();
		$data['error'] = 'not authorized';
		echo json_encode($data);
	}
}
function login($uuid)
{
	global $mysqli;
	if (isset($uuid)) {
		$check = $mysqli->query("SELECT * FROM user WHERE uuid= '" . $mysqli->real_escape_string($uuid) . "';");
		if ($check->num_rows) {
			$_SESSION['loggedin'] = true;
			while ($row = $check->fetch_assoc()) {
				$_SESSION['id'] = $row['iduser'];
				if (isset($row['role'])) {
					$_SESSION['userrole'] = $row['role'];
					if ($row['teacher'] != '')
						$_SESSION['idteacher'] = $row['teacher'];
					if ($row['school'] != '')
						$_SESSION['school'] = $row['school'];
				}
				//if (isset($row[ 'isactiv' ])){
				$_SESSION['isactiv'] = 1;
				//}
			}
			if ($_SESSION['isactiv'] == 1) {
				if ($_SESSION['userrole'] == 1) {
					return;
				} else if ($_SESSION['userrole'] == 2) {
					return;
				} else if ($_SESSION['userrole'] == 3) {
					return;
				}
			}
		}
	}
}
function getallteacher()
{
	global $mysqli;
	global $tab;
	$data = array();
	if ($_SESSION['userrole'] == 1) {
		$query = $mysqli->query("SELECT * FROM adminteacher;");
		if ($query->num_rows) {
			while ($get = $query->fetch_assoc()) {
				if ($tab == "yes")
					$data[] = $get;
				else
					$data[$get["initials"]] = $get;
			}
			return json_encode($data);
		}
	} else if ($_SESSION['userrole'] == 4) {
		$query = $mysqli->query("SELECT * FROM adminteacher where school='".$_SESSION['school']."';");
		if ($query->num_rows) {
			while ($get = $query->fetch_assoc()) {
				if ($tab == "yes")
					$data[] = $get;
				else
					$data[$get["initials"]] = $get;
			}
			return json_encode($data);
		}
	} else {
		$data1 = array();
		$data1["error"] = "your not an admin";
		return json_encode($data);
	}
}
function getteacher($idteacher)
{
	global $mysqli;
	global $tab;
	$data = array();
	if ($_SESSION['userrole'] == 1) {
		$query = $mysqli->query("SELECT * FROM adminteacher where idteacher='" . $idteacher . "';");
		if ($query->num_rows) {
			while ($get = $query->fetch_assoc()) {
				if ($tab == "yes")
					$data[] = $get;
				else
					$data[$get["initials"]] = $get;
			}
			return json_encode($data);
		}
	} else {
		$data1 = array();
		$data1["error"] = "your not an admin";
		return json_encode($data);
	}
}
