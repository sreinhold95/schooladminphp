<?php
require $_SERVER['DOCUMENT_ROOT'] . '/include/config.inc.php';
session_start();
error_reporting(E_ALL);
header('X-Content-Type-Options: nosniff');
ini_set('error_reporting', E_ERROR);
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	$headers = apache_request_headers();
	$uuid = $headers['uuid'];
	$tab = $headers['tab'];
	$auth = false;
	$check = $mysqli->query("select teacher from user where uuid='" . $mysqli->real_escape_string($uuid) . "' and uuidlifetime>=DATE_SUB(NOW(),INTERVAL 24 HOUR)");
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
}else if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$headers = apache_request_headers();
	$uuid = $headers['uuid'];
	$auth = false;
	$check = $mysqli->query("select teacher from user where uuid='" . $mysqli->real_escape_string($uuid) . "' and uuidlifetime>=DATE_SUB(NOW(),INTERVAL 24 HOUR)");
	if ($check->num_rows) {
		while ($row = $check->fetch_assoc()) {
			$idteacher = $row["teacher"];
			$auth = true;
		}
	}
	if ($auth) {
		if (isset($uuid)) {
			login($uuid);
			if ($headers["import"]=true){
				$teacher=file_get_contents("php://input");
				$data = array();
				// $data["post"]="'".$teacher."'";
				$data["success"]=true;
				echo json_encode($data);
				// importteacher($teacher);
			}
			else{
				$teacher = $_POST['teacher'];
				createteacher($teacher);
			}
			header('HTTP/1.0 200 OK');
			header('Content-Type: application/json');
		} else {
			header('HTTP/1.0 403 Forbitten');
			header('Content-Type: application/json');
			$data = array();
			$data['error'] = 'no username and passwort set';
			echo json_encode($data);
		}
	} 
	else {
		header('HTTP/1.0 403 Forbitten');
		header('Content-Type: application/json');
		$data = array();
		$data['error'] = 'not authorized';
		echo json_encode($data);
	}
}else if ($_SERVER['REQUEST_METHOD'] == 'PATCH'){
	$headers = apache_request_headers();
	$uuid = $headers['uuid'];
	$auth = false;
	$check = $mysqli->query("select teacher from user where uuid='" . $mysqli->real_escape_string($uuid) . "' and uuidlifetime>=DATE_SUB(NOW(),INTERVAL 24 HOUR)");
	if ($check->num_rows) {
		while ($row = $check->fetch_assoc()) {
			$idteacher = $row["teacher"];
			$auth = true;
		}
	}
	if ($auth) {
		if (isset($uuid)) {
			login($uuid);
			if ($headers["import"]=true){
				$teacher=file_get_contents("php://input");
				$data = array();
				// $data["post"]="'".$teacher."'";
				$data["success"]=true;
				echo json_encode($data);
				// importteacher($teacher);
			}
			else{
				$teacher = $_PATCH['teacher'];
				updateteacher($teacher);
			}
			header('HTTP/1.0 200 OK');
			header('Content-Type: application/json');
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
		$query = $mysqli->query("SELECT * FROM adminteacher where school='".$mysqli->real_escape_string($_SESSION['school'])."';");
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
		$query = $mysqli->query("SELECT * FROM adminteacher where idteacher='" . $mysqli->real_escape_string($idteacher) . "';");
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
function createteacher($teacher){
	global $mysqli;
	$errors = array();
	$data = array();
	$json = str_replace("%26", "&", $teacher);
	$jsonobj = json_decode($teacher);
	$teacherstmt = $mysqli->prepare("INSERT INTO teacher (initials,surname,middlename,lastname,moregivenname,name,school,email)values(?,?,?,?,?,?,?,?)");
	if ($teacherstmt) {
		$teacherstmt->bind_param(
			'ssssssss',
			$jsonobj->initials,
			$jsonobj->surname,
			$jsonobj->middlename,
			$jsonobj->lastname,
			$jsonobj->moregivenname,
			$jsonobj->surname+" "+$jsonobj->middlename+" "+$jsonobj->lastname+" "+$jsonobj->moregivenname,
			$jsonobj->school,
			$jsonobj->email
		);
		$teacherstmt->execute();
		$errors["teacherstmt"] = $teacherstmt->error;
		$teacherstmt->close();
	}
	else {
		$errors["teacherstmt"] = "MySQL Syntax Error";
		$errors["json"]=$_POST['teacher'];
	}
	if ($errors["teacherstmt"]==""){
		$userstmt = $mysqli->prepare("INSERT INTO user (active,username,password,role,teacher,school)values(?,?,?,?,?,?)");
		if ($userstmt) {
			$userstmt->bind_param(
				'ississ',
				1,
				$jsonobj->surname.substr(0,1)+$jsonobj->lastname,
				$jsonobj->password,
				$jsonobj->role,
				$jsonobj->idteacher,
				$jsonobj->school,
				$jsonobj->email
			);
			$userstmt->execute();
			$errors["userstmt"] = $userstmt->error;
			$userstmt->close();
		}
		else {
			$errors["userstmt"] = "MySQL Syntax Error";
			$errors["json"]=$_POST['teacher'];
		}
	}
	if ($errors["teacherstmt"] == "" && $errors["userstmt"] == "" )
		$data['success'] = true;
	else
		$data['success'] = false;
	$data['errors'] = $errors;
	echo json_encode($data);
}
function importteacher($teacher){
	global $mysqli;
	$errors = array();
	$data = array();
	$json = str_replace("%26", "&", $teacher);
	$jsonobj = json_decode($teacher);
	$teacherstmt = $mysqli->prepare("INSERT INTO teacher (initials,surname,middlename,lastname,moregivenname,name,school,email)values(?,?,?,?,?,?,?,?)");
	if ($teacherstmt) {
		foreach ($jsonobj as $item) {
			$teacherstmt->bind_param(
				'ssssssss',
				$item->initials,
				$item->surname,
				$item->middlename,
				$item->lastname,
				$item->moregivenname,
				$item->surname+" "+$item->middlename+" "+$item->lastname+" "+$item->moregivenname,
				$item->school,
				$item->email
			);
			$teacherstmt->execute();
			$errors["teacherstmt"] = $teacherstmt->error;
		}
		$teacherstmt->close();
	}
	else {
		$errors["teacherstmt"] = "MySQL Syntax Error";
		$errors["json"]=$_POST['teacher'];
	}
	if ($errors["teacherstmt"]==""){
		$userstmt = $mysqli->prepare("INSERT INTO user (active,username,password,role,teacher,school)values(?,?,?,?,?,?)");
		if ($userstmt) {
			foreach ($jsonobj as $item) {
				$userstmt->bind_param(
					'ississ',
					1,
					$item->surname.substr(0,1)+$item->lastname,
					$item->password,
					$item->role,
					$item->idteacher,
					$item->school,
					$item->email
				);
				$userstmt->execute();
				$errors["userstmt"] = $userstmt->error;
			}
			$userstmt->close();
		}
		else {
			$errors["userstmt"] = "MySQL Syntax Error";
			$errors["json"]=$_POST['teacher'];
		}
	}
	if ($errors["teacherstmt"] == "" && $errors["userstmt"] == "" )
		$data['success'] = true;
	else
		$data['success'] = false;
	$data['errors'] = $errors;
	echo json_encode($data);
}
function updateteacher($teacher){
	global $mysqli;
	$errors = array();
	$data = array();
	$json = str_replace("%26", "&", $teacher);
	$jsonobj = json_decode($teacher);
	$teacherstmt = $mysqli->prepare("UPDATE teacher set initials=:initials,surname=:surname,middlename=:middlename,lastname=:lastname,moregivenname=:moregivenname,name=:name,school=:school,email=:email where idteacher=:idteacher");
	if ($teacherstmt) {
		$teacherstmt->bind_Param(':initials',$jsonobj->initials);
		$teacherstmt->bind_Param(':surname',$jsonobj->surname);
		$teacherstmt->bind_Param(':middlename',$jsonobj->miidlename);
		$teacherstmt->bind_Param(':lastname',$jsonobj->lastanme);
		$teacherstmt->bind_Param(':moregivenname',$jsonobj->moregivenname);
		$teacherstmt->bind_Param(':name',$jsonobj->surname+" "+$jsonobj->middlename+" "+$jsonobj->lastname+" "+$jsonobj->moregivenname);
		$teacherstmt->bind_Param(':school',$jsonobj->school);
		$teacherstmt->bind_Param(':email',$jsonobj->email);
		$teacherstmt->execute();
		$errors["teacherstmt"] = $teacherstmt->error;
		$teacherstmt->close();
	}
	else {
		$errors["teacherstmt"] = "MySQL Syntax Error";
	}
	if ($errors["teacherstmt"]==""){
		$userstmt = $mysqli->prepare("UPDATE user set active=?,username=?,password=?,role=?,teacher=?,school=? where idteacher=?");
		if ($userstmt) {
			$userstmt->bind_param(
				'ississi',
				1,
				$jsonobj->surname.substr(0,1)+$jsonobj->lastname,
				$jsonobj->password,
				$jsonobj->role,
				$jsonobj->idteacher,
				$jsonobj->parents->school,
				$jsonobj->parents->email,
				$jsonobj->idteacher
			);
			$userstmt->execute();
			$errors["userstmt"] = $userstmt->error;
			$userstmt->close();
		}
		else {
			$errors["userstmt"] = "MySQL Syntax Error";
		}
	}
	if ($errors["teacherstmt"] == "" && $errors["userstmt"] == "" )
		$data['success'] = true;
	else
		$data['success'] = false;
	$data['errors'] = $errors;
	echo json_encode($data);
}