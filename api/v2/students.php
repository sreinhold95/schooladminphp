<?php
require $_SERVER['DOCUMENT_ROOT'] . '/include/config.inc.php';
session_start();
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: deny');
ini_set('error_reporting', E_ERROR);
error_reporting(E_ERROR);
$_SESSION["uuid"] = "";
$role=0;
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	$headers = apache_request_headers();
	$json = json_encode($headers);
	$uuid = $headers['uuid'];
	$tab = $headers['tab'];
	$auth = false;
	$username = "";
	$check = $mysqli->query("SELECT username from user where active=1 and uuid='" . $mysqli->real_escape_string($uuid) . "' and uuidlifetime>=DATE_SUB(NOW(),INTERVAL 24 HOUR)");
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
		if (isset($_GET['lastdays']))
			$result = getalllastdaystudent((int) $_GET['lastdays']);
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
		$check = $mysqli->query("SELECT classcode,school from class where uuid='" . $mysqli->real_escape_string($uuid) . "' and tokenactivateat>=DATE_SUB(NOW(),INTERVAL 45 MINUTE)");
		$school=$row["school"];
	} else
		$check = $mysqli->query("SELECT teacher,school from user where uuid='" . $mysqli->real_escape_string($uuid) . "' and uuidlifetime>=DATE_SUB(NOW(),INTERVAL 24 HOUR)");
	if ($check->num_rows) {
		while ($row = $check->fetch_assoc()) {
			$auth = true;
			$school=$row["school"];
		}
	}
	if ($auth) {
		header('HTTP/1.0 200 OK');
		header('Content-Type: application/json');
		$student = $_POST['student'];
		createstudent($student,$school);
	} else {
		header('HTTP/1.0 200 OK');
		header('Content-Type: application/json');
		$data = array();
		$data['error'] = 'uuid is too old please generate new one';
		$data['uuid']= $headers['uuid'];
		echo json_encode($data);
	}
} else if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
	$headers = apache_request_headers();
	$uuid = $headers['uuid'];
	$id = (int) $_GET['id'];
	$auth = false;
	$check = $mysqli->query("SELECT teacher,role from user where active=1 and uuid='" . $uuid . "' and uuidlifetime>=DATE_SUB(NOW(),INTERVAL 24 HOUR)");
	
	$teacherid=0;
	if ($check->num_rows) {
		while ($row = $check->fetch_assoc()) {
			$_SESSION['userrole']=$row["role"];
			$teacherid=$row["teacher"];
			$auth = true;
		}
	}
	if ($auth) {
		$data = array();
		header('HTTP/1.0 200 OK Patch');
		header('Content-Type: application/json');
		parse_str(file_get_contents('php://input'), $_PATCH);
		$data["setdone"] = $_PATCH['setdone'];
		$data["idstudent"] = $_PATCH['idstudent'];
		$data["idstudentfile"] = file_get_contents('php://input');
		if(isset($_PATCH['student'])){
			$student = $_PATCH['student'];
			updatestudent($student,$teacherid);
		}
		else if (isset($_PATCH['setdone'])){
			setdone((int) $_PATCH['setdone'],(int) $_PATCH['idstudent']);
		}
		//echo json_encode($data);
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
		$check = $mysqli->query("SELECT * FROM user WHERE active=1 and uuid= '" . $mysqli->real_escape_string($_SESSION["uuid"]) . "' ;");
		if ($check->num_rows) {
			$_SESSION['loggedin'] = true;
			while ($row = $check->fetch_assoc()) {
				$_SESSION['id'] = $row['iduser'];
				if (isset($row['role'])) {
					$_SESSION['userrole'] = $row['role'];
					if ($row['teacher'] != '')
						$_SESSION['idteacher'] = $row['teacher'];
				}
				$_SESSION['school']=$row['school'];
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
					$student = $mysqli->prepare("select * from all_students where school=? and active=? order by classcode ;");
					$student->bind_param('si', $_SESSION['school'],$_GET['active']);
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
				$student = $mysqli->prepare("select * from all_students where school=? order by classcode;");
				$student->bind_param('s', $_SESSION['school']);
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
			$student = $mysqli->prepare("select * from all_students where school=? order by classcode;");
			$student->bind_param('s', $_SESSION['school']);
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
function getalllastdaystudent($days)
{
	global $mysqli;
	global $tab;
	$data = array();
	if ($_SESSION['isactiv'] == 1) {
		if ($_SESSION['userrole'] == 1) {
			$student = $mysqli->prepare("SELECT surname,givenname,classcode,idstudents,changedby from all_students where admin_modified=0 and active=1 and (TIMESTAMPDIFF(DAY,modified, NOW())<".$days." or TIMESTAMPDIFF(DAY,created, NOW())<".$days.") order by classcode;");
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
					$student = $mysqli->prepare("SELECT surname,givenname,classcode,idstudents,changedby from all_students where administration_modified=0 and active=1 and school='".$_SESSION['school']."' and (TIMESTAMPDIFF(DAY,modified, NOW())<".$days." or TIMESTAMPDIFF(DAY,created, NOW())<".$days.") order by classcode;");
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
				$student = $mysqli->prepare("SELECT surname,givenname,classcode,idstudents,changedby from all_students where administration_modified=0 and school='".$_SESSION['school']."' and (TIMESTAMPDIFF(DAY,modified, NOW())<".$days." or TIMESTAMPDIFF(DAY,created, NOW())<".$days.") order by classcode;");
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
			$student = $mysqli->prepare("SELECT surname,givenname,classcode,idstudents,changedby from all_students_from_department where dep_modified=0 and headofdepartment='".$_SESSION["idteacher"]."' and (TIMESTAMPDIFF(DAY,modified, NOW())<".$days." or TIMESTAMPDIFF(DAY,created, NOW())<".$days.") order by classcode;");
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
			$student = $mysqli->prepare("select * from all_students_from_department where headofdepartment==? order by classcode;");
			$student->bind_param('i', $_SESSION['idteacher']);
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
				$student = $mysqli->prepare("SELECT * from all_students_from_department where school=? and (surname LIKE '%?%' or middlename LIKE '%?%' or givenname LIKE '%$?%' or moregivenname LIKE '%?%') and headofdepartment=?;");
				$student->bind_param('sssssi',$_SESSION['school'],$search,$search,$search,$search,$_SESSION['idteacher']);
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
				$student = $mysqli->prepare("SELECT * from students where surname LIKE '%$?%' or middlename LIKE '%$?%' or givenname LIKE '%$?%' or moregivenname LIKE '%$?%';");
				$student->bind_param('ssss',$search,$search,$search,$search);
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
				$student = $mysqli->prepare("SELECT * from students where school=? and (surname LIKE '%?%' or middlename LIKE '%?%' or givenname LIKE '%?%' or moregivenname LIKE '%?%');");
				$student->bind_param('sssss',$_SESSION['school'],$search,$search,$search,$search);
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
function createstudent($json,$school)
{
	global $mysqli;
	$error = array();
	$data = array();
	$student =array();
	$idparents=0;
	$json = str_replace("%26", "&", $_POST['student']);
	$jsonobj = json_decode($_POST['student']);
	/*$parentidstmt = $mysqli->prepare("select idparents from parents where mother_surname= ? and mother_lastname = ? and mother_address= ? and father_surname= ? and father_lastname= ? and father_address= ? limit 1;");
	$parentidstmt->bind_param('ssssss', $jsonobj->parents->mother_surname, $jsonobj->parents->mother_lastname, $jsonobj->parents->mother_address, $jsonobj->parents->father_surname, $jsonobj->parents->father_lastname, $jsonobj->parents->father_address);
	$parentidstmt->execute();
	$pid = $parentidstmt->get_result();
	while ($row = $pid->fetch_assoc()) {
		$idparents = $row['idparents'];
	}
	$errors["parentidstmt"] = $parentidstmt->error;
    $data["parentid"] = $idparents;
	$parentidstmt->close();*/
	if($idparents<=6){
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
		//$idparents=$parentstmt->insert_id;
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
	}
	$entrydate = date('Y-m-d');
	$studentexist=$mysqli->prepare("select idstudents from students where surname= ? and givenname= ? and birthdate= ? limit 1;");
	$sid=0;
	if($studentexist)
	{
		$studentexist->bind_param('sss',$jsonobj->surname,$jsonobj->givenname,$jsonobj->birthdate);
		$studentexist->execute();
		$sidstmt=$studentexist->get_result();
		while($row=$sidstmt->fetch_assoc()){
			$sid=$row['idstudents'];
		}
		$errors["studentexist"] = $studentexist->error;
		$studentexist->close();
		$data["sid"]=$sid;
	}
	else{
		$errors["studentexist"]="MySQL Syntax Error";
		$errors["studentexisterr"] = $mysqli->error;
		$data["json"]=$json;
		$data["sid"]="error";
	}
	if($sid==0)
	{
		$studentstmt = $mysqli->prepare("INSERT INTO students(surname,middlename,givenname,moregivenname,birthdate,birthtown,birthcountry,province,entryDate,classcode,address,religion,nationality,family_speech,phone,mobilephone,email,idparents,idgraduation,idberuf,active,town,plz,sex,lastschool,lastschooltown,lastschooldate,lastschoolprovince,Ausbildungsbeginn,Ausbildungsbetrieb,Ausbildungsbetrieb_strasse,Ausbildungsbetrieb_PLZ,Ausbildungsbetrieb_Telefon,Ausbildungsbetrieb_Fax,Ausbildungsbetrieb_Email,Ausbildungsbetrieb_Ausbilder_Anrede,Ausbildungsbetrieb_Ausbilder_Name,indeutschlandseit,sprachniveau,dsgvo,houserules,edvrules,school) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);");
		if ($studentstmt) {
			$studentstmt->bind_param(
			'sssssssssssssssssiiiissssssssssssssssssiiis',
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
			$jsonobj->edvrules,
			$school
		);
		$studentstmt->execute();
		$errors["studentstmt"] = $studentstmt->error;
		$studentstmt->close();
		} 
		else {
			$errors["studentstmt"] = "MySQL Syntax Error";
			$errors["json"]=$_POST['student'];
		}
	}
	else{
		//updatestudentid($json,"",$sid,$pid);
		$student=updatestudentid($json,"",$sid,$pid,$school);
		if ($student["success"]==false)
		{
			$errors["update"]=$student;
		}
	}
	if ($errors["parentstmt"] == "" && $errors["parentidstmt"] == "" && $errors["studentstmt"] == ""&& $errors["studentexist"] == ""&& $errors["update"] == "")
		$data['success'] = true;
	else
		$data['success'] = false;
	$data['errors'] = $errors;
	echo json_encode($data);
}

function updatestudent($json,$teacherid)
{
	global $mysqli;
	$error = array();
	$data = array();
	$json = str_replace("%26", "&", $json);
	$jsonobj = json_decode($json);
	$idparents=$jsonobj->parents->idparents;
	if($jsonobj->parents->idparents=="1")
	{ 
		if(($jsonobj->parents->mother_surname!="" && $jsonobj->parents->mother_lastname!="")||($jsonobj->parents->father_surname!="" && $jsonobj->parents->father_lastname!="")){
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
		}
		
	}else{
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
		$data["parentid"] = $idparents;
		$parentstmt->close();
	}
	//$exitDate="";
	if ($jsonobj->exitDate == "")
		$exitDate = null;
	else
		$exitDate = $jsonobj->exitDate;
	$studentstmt = $mysqli->prepare("update students set idparents=?,surname=?,middlename=?,givenname=?,moregivenname=?,birthdate=?,birthtown=?,birthcountry=?,province=?,entryDate=?,classcode=?,address=?,religion=?,nationality=?,family_speech=?,phone=?,mobilephone=?,email=?,idgraduation=?,idberuf=?,active=?,town=?,plz=?,sex=?,lastschool=?,lastschooltown=?,lastschooldate=?,lastschoolprovince=?,Ausbildungsbeginn=?,Ausbildungsbetrieb=?,Ausbildungsbetrieb_strasse=?,Ausbildungsbetrieb_PLZ=?,Ausbildungsbetrieb_Telefon=?,Ausbildungsbetrieb_Fax=?,Ausbildungsbetrieb_Email=?,Ausbildungsbetrieb_Ausbilder_Anrede=?,Ausbildungsbetrieb_Ausbilder_Name=?,indeutschlandseit=?,sprachniveau=?,exitDate=?,changedby=? where idstudents=?;");
	if ($studentstmt) {
		$studentstmt->bind_param(
			'isssssssssssssssssiiisssssssssssssssssssis',
			$idparents,
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
			$teacherid,
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
	$data['errors'] = $errors;
	echo json_encode($data);
}
function updatestudentid($json,$teacherid,$sid,$pid,$school)
{
	global $mysqli;
	$error = array();
	$data = array();
	$json = str_replace("%26", "&", $json);
	$jsonobj = json_decode($json);
	$idparents=$pid;
	if($pid=="1")
	{ 
		if(($jsonobj->parents->mother_surname!="" && $jsonobj->parents->mother_lastname!="")||($jsonobj->parents->father_surname!="" && $jsonobj->parents->father_lastname!="")){
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
		}
		
	}else{
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
			$idparents
		);
		$parentstmt->execute();
		$errors["parentstmt"] = $parentstmt->error;
		$data["parentid"] = $idparents;
		$parentstmt->close();
	}
	//$exitDate="";
	if ($jsonobj->exitDate == "")
		$exitDate = null;
	else
		$exitDate = $jsonobj->exitDate;
	$studentstmt = $mysqli->prepare("update students set idparents=?,surname=?,middlename=?,givenname=?,moregivenname=?,birthdate=?,birthtown=?,birthcountry=?,province=?,entryDate=?,classcode=?,address=?,religion=?,nationality=?,family_speech=?,phone=?,mobilephone=?,email=?,idgraduation=?,idberuf=?,active=?,town=?,plz=?,sex=?,lastschool=?,lastschooltown=?,lastschooldate=?,lastschoolprovince=?,Ausbildungsbeginn=?,Ausbildungsbetrieb=?,Ausbildungsbetrieb_strasse=?,Ausbildungsbetrieb_PLZ=?,Ausbildungsbetrieb_Telefon=?,Ausbildungsbetrieb_Fax=?,Ausbildungsbetrieb_Email=?,Ausbildungsbetrieb_Ausbilder_Anrede=?,Ausbildungsbetrieb_Ausbilder_Name=?,indeutschlandseit=?,sprachniveau=?,exitDate=?,changedby=?,school=? where idstudents=?;");
	if ($studentstmt) {
		$studentstmt->bind_param(
			'isssssssssssssssssiiisssssssssssssssssssiss',
			$idparents,
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
			$teacherid,
			$school,
			$sid
			
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
	$data['errors'] = $errors;
	//echo json_encode($data);
	return $data;
}
function setdone($setdone,$studentid){
	global $mysqli;
	$error = array();
	$data = array();
	if ($_SESSION['userrole']==1){
		$setdonestmt = $mysqli->prepare("update students set admin_modified=? where idstudents=? ");
	}else if ($_SESSION['userrole']==2){
		$setdonestmt = $mysqli->prepare("update students set dep_modified=? where idstudents=? ");
	}else if ($_SESSION['userrole']==4){
		$setdonestmt = $mysqli->prepare("update students set administration_modified=? where idstudents=? ");
	}
	$setdonestmt->bind_param(
		'ii',
		$setdone,
		$studentid	
	);
	$setdonestmt->execute();
	$errors["setdonestmt"] = $setdonestmt->error;
	$setdonestmt->close();
	if ($errors["setdonestmt"] == "")
		$data['success'] = true;
	else
		$data['success'] = false;
	$data['errors'] = $errors;
	echo json_encode($data);
}