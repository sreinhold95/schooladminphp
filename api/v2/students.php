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
		if (isset($_GET['id']))
			$result = getstudent((int) $_GET['id']);
		if (isset($_GET['all']))
			$result = getallstudent();
		if (isset($_GET['search']))
			$result = searchstudent((string) $_GET['search']);
		if (isset($_GET['department']))
			$result = getdepstudent();
		echo $result;
	} else {
		header('HTTP/1.0 403 Forbitten');
		header('Content-Type: application/json');
		$data = array();
		$data['error'] = 'uuid is too old please generate new one';
		echo json_encode($data);
	}
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$headers = apache_request_headers();
	$uuid = $headers['uuid'];
	$auth = false;
	if (isset($headers['classtoken'])) {
		$classcode = $headers['classtoken'];
		$check = $mysqli->query("select classcode from class where uuid='" . $uuid . "' and tokenactivateat>=DATE_SUB(NOW(),INTERVAL 15 MINUTE)");
	} else
		$check = $mysqli->query("select teacher from user where uuid='" . $uuid . "' and uuidlifetime>=DATE_SUB(NOW(),INTERVAL 24 HOUR)");
	if ($check->num_rows) {
		while ($row = $check->fetch_assoc()) {
			//$idteacher=$row["teacher"];
			$auth = true;
		}
	}
	if ($auth) {
		header('HTTP/1.0 200 OK');
		header('Content-Type: application/json');
		$student = $_POST['student'];
		createstudent($student);
	} else {
		header('HTTP/1.0 200 OK');
		header('Content-Type: application/json');
		$data = array();
		$data['error'] = 'uuid is too old please generate new one';
		echo json_encode($data);
	}
} else if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
	$headers = apache_request_headers();
	$uuid = $headers['uuid'];
	$id = (int) $_GET['id'];
	$auth = false;
	$check = $mysqli->query("select teacher from user where uuid='" . $uuid . "' and uuidlifetime>=DATE_SUB(NOW(),INTERVAL 24 HOUR)");
	if ($check->num_rows) {
		while ($row = $check->fetch_assoc()) {
			//$idteacher=$row["teacher"];
			$auth = true;
		}
	}
	if ($auth) {
		header('HTTP/1.0 200 OK');
		header('Content-Type: application/json');
		parse_str(file_get_contents('php://input'), $_PATCH);
		$student = $_PATCH['student'];
		updatestudent($student);
	} else {
		header('HTTP/1.0 403 Forbitten');
		header('Content-Type: application/json');
		$data = array();
		$data["error"] = "uuid false or outdated";
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

function getallstudent()
{
	global $mysqli;
	global $tab;
	$data = array();
	if ($_SESSION['isactiv'] == 1) {
		if ($_SESSION['userrole'] == 1) {
			$student = $mysqli->prepare("select * from all_students order by classcode;");
			$student->execute();
			if ($student) {
				$data = array();
				$stdt = $student->get_result();
				while ($row = $stdt->fetch_assoc()) {
					if ($tab == "yes")
						$data[] = $row;
					else
						$data[$row["idstudents"]] = $row;
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
		} else if ($_SESSION['userrole'] == 4) {
			if (isset($_GET['active'])) {
				if ($_GET['active'] == 1) {
					$student = $mysqli->prepare("select * from all_students where active=1 order by classcode ;");
					$student->execute();
					if ($student) {
						$data = array();
						$stdt = $student->get_result();
						while ($row = $stdt->fetch_assoc()) {
							if ($tab == "yes")
								$data[] = $row;
							else
								$data[$row["idstudents"]] = $row;
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
				}
			} else {
				$student = $mysqli->prepare("select * from all_students order by classcode;");
				$student->execute();
				if ($student) {
					$data = array();
					$stdt = $student->get_result();
					while ($row = $stdt->fetch_assoc()) {
						if ($tab == "yes")
							$data[] = $row;
						else
							$data[$row["idstudents"]] = $row;
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
			}
		} else if ($_SESSION['userrole'] == 2) {
			$student = $mysqli->prepare("select * from all_students order by classcode;");
			$student->execute();
			if ($student) {
				$data = array();
				$stdt = $student->get_result();
				while ($row = $stdt->fetch_assoc()) {
					if ($tab == "yes")
						$data[] = $row;
					else
						$data[$row["idstudents"]] = $row;
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
		} else {
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

function getdepstudent()
{
	global $mysqli;
	global $tab;
	$data = array();
	if ($_SESSION['isactiv'] == 1) {
		if ($_SESSION["userrole" == 2]) {
			$student = $mysqli->prepare("select * from all_students_from_department where headofdepartment='" . $mysqli->real_escape_string($_SESSION['idteacher']) . "' order by classcode;");
			$student->execute();
			if ($student) {
				$data = array();
				$stdt = $student->get_result();
				while ($row = $stdt->fetch_assoc()) {
					if ($tab == "yes")
						$data[] = $row;
					else
						$data[$row["idstudents"]] = $row;
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
		} else {
			$data["error"] = "no rights";
			$json = json_encode($data);
			header('HTTP/1.0 403 Forbitten');
			header('Content-Type: application/json');
		}
	} else {
		$data["error"] = "not loggedin";
		$json = json_encode($data);
		header('HTTP/1.0 403 Forbitten');
		header('Content-Type: application/json');
	}
	return ($json);
}

function getstudent($id)
{
	global $mysqli;
	global $tab;
	$data = array();
	if ($_SESSION['isactiv'] == 1) {
		if ($_SESSION["userrole" == 1]) {
			if ($id != '') {
				$student = $mysqli->prepare("select * from all_students where idstudents =? limit 1;");
				$student->bind_param('s', $id);
				$student->execute();
				if ($student) {
					$json = json_encode($student->get_result()->fetch_assoc());
				}
				if ($json == "null") {
					$data["error"] = "no Student";
					$json = json_encode($data);
				}
				header('HTTP/1.0 900 no data');
				header('Content-Type: application/json');
			} else {
				$data["error"] = "no Student ID";
				$data["studentid"] = $id;
				$json = json_encode($data);
				header('HTTP/1.0 901 data failed');
				header('Content-Type: application/json');
			}
		} else {
			$data["error"] = "no rights";
			$json = json_encode($data);
			header('HTTP/1.0 403 Forbitten');
			header('Content-Type: application/json');
		}
	} else {
		$data["error"] = "not loggedin";
		$json = json_encode($data);
		header('HTTP/1.0 403 Forbitten');
		header('Content-Type: application/json');
	}
	return ($json);
}
function searchstudent($search)
{
	global $tab;
	global $mysqli;
	$data = array();
	if ($_SESSION['isactiv'] == 1) {
		if ($search != '') {
			if ($_SESSION['userrole'] == 2) {
				$student = $mysqli->prepare("select * from all_students_from_department where (surname LIKE '%$search%' or middlename LIKE '%$search%' or givenname LIKE '%$search%' or moregivenname LIKE '%$search%') and headofdepartment='" . $mysqli->real_escape_string($_SESSION['idteacher']) . "';");
				//$student->bind_param('ssss',$search);
				$student->execute();
				if ($student) {
					$stmt = $student->get_result();
					while ($row = $stmt->fetch_assoc()) {
						if ($tab == "yes")
							$data[] = $row;
						else
							$data[$row["idstudents"]] = $row;
					}
					$json = json_encode($data);
				}
			} else if ($_SESSION['userrole'] == 1) {
				$student = $mysqli->prepare("select * from students where surname LIKE '%$search%' or middlename LIKE '%$search%' or givenname LIKE '%$search%' or moregivenname LIKE '%$search%';");
				//$student->bind_param('ssss',$search);
				$student->execute();
				if ($student) {
					$stmt = $student->get_result();
					while ($row = $stmt->fetch_assoc()) {
						if ($tab == "yes")
							$data[] = $row;
						else
							$data[$row["idstudents"]] = $row;
					}
					$json = json_encode($data);
				}
			} else if ($_SESSION['userrole'] == 4) {
				$student = $mysqli->prepare("select * from students where surname LIKE '%$search%' or middlename LIKE '%$search%' or givenname LIKE '%$search%' or moregivenname LIKE '%$search%';");
				//$student->bind_param('ssss',$search);
				$student->execute();
				if ($student) {
					$stmt = $student->get_result();
					while ($row = $stmt->fetch_assoc()) {
						if ($tab == "yes")
							$data[] = $row;
						else
							$data[$row["idstudents"]] = $row;
					}
					$json = json_encode($data);
				}
			}

			if ($json == "null") {
				$data["error"] = "no Student found";
				$json = json_encode($data);
			}
			header('HTTP/1.0 200 OK');
			header('Content-Type: application/json');
		} else {
			$data["error"] = "no Student ID";
			//$data["studentid"]=$id;			
			$json = json_encode($data);
			header('HTTP/1.0 901 data failed');
			header('Content-Type: application/json');
		}
	} else {
		$data["error"] = "not loggedin";
		$json = json_encode($data);
		header('HTTP/1.0 403 Forbitten');
		header('Content-Type: application/json');
	}
	return ($json);
}
function createstudent($json)
{
	global $mysqli;
	$error = array();
	$data = array();
	$json = str_replace("%26", "&", $_POST['student']);
	$jsonobj = json_decode($_POST['student']);
	$parentstmt = $mysqli->prepare("INSERT INTO parents (mother_surname,mother_lastname,mother_address,mother_postalcode,mother_phone,mother_mobilephone,father_surname,father_lastname,father_address,father_postalcode,father_phone,father_mobilephone)values(?,?,?,?,?,?,?,?,?,?,?,?)");
	$parentstmt->bind_param(
		'ssssssssssss',
		$jsonobj->parents->mother_surname,
		$jsonobj->parents->mother_lastname,
		$jsonobj->parents->mother_address,
		$jsonobj->parents->mother_postalcode,
		$jsonobj->parents->mother_phone,
		$jsonobj->parents->mother_mobilephone,
		$jsonobj->parents->father_surname,
		$jsonobj->parents->father_lastname,
		$jsonobj->parents->father_address,
		$jsonobj->parents->father_postalcode,
		$jsonobj->parents->father_phone,
		$jsonobj->parents->father_mobilephone
	);
	$parentstmt->execute();
	$errors["parentstmt"] = $parentstmt->error;
	$parentstmt->close();
	$parentidstmt = $mysqli->prepare("select idparents from parents where mother_surname= ? and mother_lastname = ? and mother_address= ? and father_surname= ? and father_lastname= ? and father_address= ? limit 1;");
	$parentidstmt->bind_param('ssssss', $jsonobj->parents->mother_surname, $jsonobj->parents->mother_lastname, $jsonobj->parents->mother_address, $jsonobj->parents->father_surname, $jsonobj->parents->father_lastname, $jsonobj->parents->father_address);
	$parentidstmt->execute();
	$pid = $parentidstmt->get_result();
	while ($row = $pid->fetch_assoc()) {
		$idparents = $row['idparents'];
	}
	$errors["parentidstmt"] = $parentidstmt->error;
	$data["parentid"] = $idparents;
	$parentidstmt->close();
	$entrydate = date('Y-m-d');
	$studentstmt = $mysqli->prepare("INSERT INTO students(surname,middlename,givenname,moregivenname,birthdate,birthtown,birthcountry,province,entryDate,classcode,address,religion,nationality,family_speech,phone,mobilephone,email,idparents,idgraduation,idberuf,active,town,plz,sex,lastschool,lastschooltown,lastschooldate,lastschoolprovince,Ausbildungsbeginn,Ausbildungsbetrieb,Ausbildungsbetrieb_strasse,Ausbildungsbetrieb_PLZ,Ausbildungsbetrieb_Telefon,Ausbildungsbetrieb_Fax,Ausbildungsbetrieb_Email,Ausbildungsbetrieb_Ausbilder_Anrede,Ausbildungsbetrieb_Ausbilder_Name,indeutschlandseit,sprachniveau,dsgvo,houserules,edvrules) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);");
	if ($studentstmt) {
		$studentstmt->bind_param(
			'sssssssssssssssssiiiissssssssssssssssssiii',
			$jsonobj->surname,
			$jsonobj->middlename,
			$jsonobj->givenname,
			$jsonobj->moregivenname,
			$jsonobj->birthdate,
			$jsonobj->birthtown,
			$jsonobj->birthcountry,
			$jsonobj->province,
			$entrydate,
			$jsonobj->classcode,
			$jsonobj->street,
			$jsonobj->religion,
			$jsonobj->nationality,
			$jsonobj->family_speech,
			$jsonobj->phone,
			$jsonobj->mobilephone,
			$jsonobj->email,
			$idparents,
			$jsonobj->idgraduation,
			$jsonobj->idberuf,
			$jsonobj->active,
			$jsonobj->town,
			$jsonobj->postalcode,
			$jsonobj->sex,
			$jsonobj->lastschool,
			$jsonobj->lastschooltown,
			$jsonobj->lastschooldate,
			$jsonobj->lastschoolprovince,
			$jsonobj->Ausbildungsbeginn,
			$jsonobj->Ausbildungsbetrieb->Name,
			$jsonobj->Ausbildungsbetrieb->Strasse,
			$jsonobj->Ausbildungsbetrieb->PLZ,
			$jsonobj->Ausbildungsbetrieb->Telefon,
			$jsonobj->Ausbildungsbetrieb->Fax,
			$jsonobj->Ausbildungsbetrieb->Email,
			$jsonobj->Ausbildungsbetrieb->Ausbilder->Anrede,
			$jsonobj->Ausbildungsbetrieb->Ausbilder->Name,
			$jsonobj->indeutschlandseit,
			$jsonobj->sprachniveau,
			$jsonobj->dsgvo,
			$jsonobj->houserules,
			$jsonobj->edvrules
		);
		$studentstmt->execute();
		$errors["studentstmt"] = $studentstmt->error;
		$studentstmt->close();
	} else {
		$errors["studentstmt"] = "MySQL Syntax Error";
		$errors["json"]=$_POST['student'];
	}
	if ($errors["parentstmt"] == "" && $errors["parentidstmt"] == "" && $errors["studentstmt"] == "")
		$data['success'] = true;
	else
		$data['success'] = false;
	$data['errors'] = $errors;
	echo json_encode($data);
}

function updatestudent($json)
{
	global $mysqli;
	$error = array();
	$data = array();
	$json = str_replace("%26", "&", $json);
	$jsonobj = json_decode($json);
	$parentstmt = $mysqli->prepare("update parents set mother_surname=?,mother_lastname=?,mother_address=?,mother_postalcode=?,mother_phone=?,mother_mobilephone=?,father_surname=?,father_lastname=?,father_address=?,father_postalcode=?,father_phone=?,father_mobilephone=? where idparents=?");
	$parentstmt->bind_param(
		'ssssssssssssi',
		$jsonobj->parents->mother_surname,
		$jsonobj->parents->mother_lastname,
		$jsonobj->parents->mother_address,
		$jsonobj->parents->mother_postalcode,
		$jsonobj->parents->mother_phone,
		$jsonobj->parents->mother_mobilephone,
		$jsonobj->parents->father_surname,
		$jsonobj->parents->father_lastname,
		$jsonobj->parents->father_address,
		$jsonobj->parents->father_postalcode,
		$jsonobj->parents->father_phone,
		$jsonobj->parents->father_mobilephone,
		$jsonobj->parents->idparents
	);
	$parentstmt->execute();
	$errors["parentstmt"] = $parentstmt->error;
	$parentstmt->close();
	$parentidstmt = $mysqli->prepare("select idparents from parents where mother_surname= ? and mother_lastname = ? and mother_address= ? and father_surname= ? and father_lastname= ? and father_address= ?");
	$parentidstmt->bind_param('ssssss', $jsonobj->parents->mother_surname, $jsonobj->parents->mother_lastname, $jsonobj->parents->mother_address, $jsonobj->parents->father_surname, $jsonobj->parents->father_lastname, $jsonobj->parents->father_address);
	$parentidstmt->execute();
	$pid = $parentidstmt->get_result();
	while ($row = $pid->fetch_assoc()) {
		$idparents = $row['idparents'];
	}
	$errors["parentidstmt"] = $parentidstmt->error;
	$data["parentid"] = $idparents;
	$parentidstmt->close();
	//$exitDate="";
	if ($jsonobj->exitDate == "")
		$exitDate = null;
	else
		$exitDate = $jsonobj->exitDate;
	$studentstmt = $mysqli->prepare("update students set surname=?,middlename=?,givenname=?,moregivenname=?,birthdate=?,birthtown=?,birthcountry=?,province=?,entryDate=?,classcode=?,address=?,religion=?,nationality=?,family_speech=?,phone=?,mobilephone=?,email=?,idgraduation=?,idberuf=?,active=?,town=?,plz=?,sex=?,lastschool=?,lastschooltown=?,lastschooldate=?,lastschoolprovince=?,Ausbildungsbeginn=?,Ausbildungsbetrieb=?,Ausbildungsbetrieb_strasse=?,Ausbildungsbetrieb_PLZ=?,Ausbildungsbetrieb_Telefon=?,Ausbildungsbetrieb_Fax=?,Ausbildungsbetrieb_Email=?,Ausbildungsbetrieb_Ausbilder_Anrede=?,Ausbildungsbetrieb_Ausbilder_Name=?,indeutschlandseit=?,sprachniveau=?,exitDate=? where idstudents=?;");
	if ($studentstmt) {
		$studentstmt->bind_param(
			'sssssssssssssssssiiissssssssssssssssssss',
			$jsonobj->surname,
			$jsonobj->middlename,
			$jsonobj->givenname,
			$jsonobj->moregivenname,
			$jsonobj->birthdate,
			$jsonobj->birthtown,
			$jsonobj->birthcountry,
			$jsonobj->province,
			$jsonobj->entryDate,
			$jsonobj->classcode,
			$jsonobj->street,
			$jsonobj->religion,
			$jsonobj->nationality,
			$jsonobj->family_speech,
			$jsonobj->phone,
			$jsonobj->mobilephone,
			$jsonobj->email,
			$jsonobj->idgraduation,
			$jsonobj->idberuf,
			$jsonobj->active,
			$jsonobj->town,
			$jsonobj->postalcode,
			$jsonobj->sex,
			$jsonobj->lastschool,
			$jsonobj->lastschooltown,
			$jsonobj->lastschooldate,
			$jsonobj->lastschoolprovince,
			$jsonobj->Ausbildungsbeginn,
			$jsonobj->Ausbildungsbetrieb->Name,
			$jsonobj->Ausbildungsbetrieb->Strasse,
			$jsonobj->Ausbildungsbetrieb->PLZ,
			$jsonobj->Ausbildungsbetrieb->Telefon,
			$jsonobj->Ausbildungsbetrieb->Fax,
			$jsonobj->Ausbildungsbetrieb->Email,
			$jsonobj->Ausbildungsbetrieb->Ausbilder->Anrede,
			$jsonobj->Ausbildungsbetrieb->Ausbilder->Name,
			$jsonobj->indeutschlandseit,
			$jsonobj->sprachniveau,
			//$jsonobj->exitDate,
			$exitDate,
			$jsonobj->idstudent
		);
		$studentstmt->execute();
		$errors["studentstmt"] = $studentstmt->error;
		$studentstmt->close();
	} else {
		$errors["studentstmt"] = "MySQL Syntax Error";
	}
	if ($errors["parentstmt"] == "" && $errors["studentstmt"] == "")
		$data['success'] = true;
	else
		$data['success'] = false;
	$errors["exitDate"] = $jsonobj->exitDate;
	$data['errors'] = $errors;
	echo json_encode($data);
}
