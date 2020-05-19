<?php
require $_SERVER['DOCUMENT_ROOT'] . '/include/config.inc.php';
session_start();
ini_set('error_reporting', E_ERROR);
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	$headers = apache_request_headers();
	$uuid = $headers['uuid'];
	$tab = $headers['tab'];
	$auth = false;
	$check = $mysqli->query("select username from user where uuid='" . $uuid . "' and uuidlifetime>=DATE_SUB(NOW(),INTERVAL 24 HOUR)");
	if ($check->num_rows) {
		while ($row = $check->fetch_assoc()) {
			$auth = true;
		}
	}
	if ($auth == true) {
		if (isset($uuid)) {
			login($uuid);
			if ($_GET['classcode'] != "all") {
				if ($_GET['classcode'] == "") {
					$data = array();
					$data['error'] = 'no class';
					$result = json_encode($data);
				} else {
					$result = getclass($_GET['classcode']);
				}
			} else if (isset($_GET['classinfo'])) {
				$result = getclassinfo($_GET['classcode']);
			} else if ($_GET['classcode'] == "all") {
				$result = getallclass();
			} else {
				$data = array();
				$data['error'] = 'no class';
				$result = json_encode($data);
			}
			header('HTTP/1.0 200 OK');
			header('Content-Type: application/json');
			echo $result;
			session_unset();
			session_abort();
			//session_destroy();
		} else {
			header('HTTP/1.0 200 OK');
			header('Content-Type: application/json');
			$data = array();
			$data['error'] = 'no username and passwort set';
			echo json_encode($data);
			session_unset();
			session_abort();
			//session_destroy();
		}
	} else {
		header('HTTP/1.0 403 forbitten');
		header('Content-Type: application/json');
		$data = array();
		$data['error'] = 'no valid uuid or lifetime';
		echo json_encode($data);
		session_unset();
		session_abort();
		//session_destroy();
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
				}
				//if (isset($row[ 'isactiv' ])){
				$_SESSION['isactiv'] = 1;
				//}
			}
			/*if ( $_SESSION[ 'isactiv' ] == 1 ) {
					if ( $_SESSION[ 'userrole' ] == 1 ) {
						return;
					} else if ($_SESSION[ 'userrole' ] == 2){
						return;
					} else if ($_SESSION[ 'userrole' ] == 3){
						return;
					}
				}*/
		}
	}
}

function getclass($classcode)
{
	global $mysqli;
	global $tab;
	$data = array();
	if ($_SESSION['isactiv'] == 1) {
		if ($classcode == '') {
			switch ($_SESSION['userrole']) {
				case 1:
					$student = $mysqli->query("SELECT students.active,students.idstudents, students.surname,students.middlename,students.givenname,students.classcode,cteacher.surname as tsurname,cteacher.lastname as tgivenname FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher  as cteacher on teacher_class.idteacher=cteacher.idteacher inner join teacher as depteacher on depteacher.idteacher=department.headofdepartment where teacher_class.classteacher=1 and depteacher.idteacher='" . $_SESSION['idteacher'] . "' order by classcode;");
					while ($get = $student->fetch_assoc()) {
						if ($tab == "yes")
							$data[] = $get;
						else
							$data[$get["idstudents"]] = $get;
					}
					$json = json_encode($data);
					break;
				case 2:
					$student = $mysqli->query("SELECT students.active,students.idstudents, students.surname,students.middlename,students.givenname,students.classcode,cteacher.surname as tsurname,cteacher.lastname as tgivenname FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher  as cteacher on teacher_class.idteacher=cteacher.idteacher inner join teacher as depteacher on depteacher.idteacher=department.headofdepartment where teacher_class.classteacher=1 and depteacher.idteacher='" . $_SESSION['idteacher'] . "' order by classcode;");
					while ($get = $student->fetch_assoc()) {
						if ($tab == "yes")
							$data[] = $get;
						else
							$data[$get["idstudents"]] = $get;
					}
					$json = json_encode($data);
					break;
				case 3:
					$student = $mysqli->query("SELECT students.active,students.idstudents, students.surname,students.middlename,students.givenname,students.classcode,cteacher.surname as tsurname,cteacher.lastname as tgivenname FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher  as cteacher on teacher_class.idteacher=cteacher.idteacher inner join teacher as depteacher on depteacher.idteacher=department.headofdepartment where teacher_class.classteacher=1 and cteacher.idteacher='" . $_SESSION['idteacher'] . "' order by classcode;");
					while ($get = $student->fetch_assoc()) {
						if ($tab == "yes")
							$data[] = $get;
						else
							$data[$get["idstudents"]] = $get;
					}
					$json = json_encode($data);
					break;
				case 4:
					$student = $mysqli->query("SELECT students.active,students.idstudents, students.surname,students.middlename,students.givenname,students.classcode,cteacher.surname as tsurname,cteacher.lastname as tgivenname FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher  as cteacher on teacher_class.idteacher=cteacher.idteacher inner join teacher as depteacher on depteacher.idteacher=department.headofdepartment where teacher_class.classteacher=1 and depteacher.idteacher='" . $_SESSION['idteacher'] . "' order by classcode;");
					while ($get = $student->fetch_assoc()) {
						if ($tab == "yes")
							$data[] = $get;
						else
							$data[$get["idstudents"]] = $get;
					}
					$json = json_encode($data);
					break;
				default:
					$data["error"] = "not loggedin";
					$json = json_encode($data);
					break;
			}
		} else {
			switch ($_SESSION['userrole']) {
				case 1:
					$student = $mysqli->query("SELECT students.active,students.idstudents, students.surname,students.middlename,students.givenname,students.classcode,students.birthdate,cteacher.surname as tsurname,cteacher.lastname as tgivenname FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher  as cteacher on teacher_class.idteacher=cteacher.idteacher inner join teacher as depteacher on depteacher.idteacher=department.headofdepartment where teacher_class.classteacher=1 and students.classcode='" . $classcode . "' group by idstudents;");
					while ($get = $student->fetch_assoc()) {
						if ($tab == "yes")
							$data[] = $get;
						else
							$data[$get["idstudents"]] = $get;
					}
					$json = json_encode($data);
					break;
				case 2:
					$student = $mysqli->query("SELECT students.active,students.idstudents, students.surname,students.middlename,students.givenname,students.classcode,students.birthdate,cteacher.surname as tsurname,cteacher.lastname as tgivenname FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher  as cteacher on teacher_class.idteacher=cteacher.idteacher inner join teacher as depteacher on depteacher.idteacher=department.headofdepartment where teacher_class.classteacher=1 and depteacher.idteacher='" . $_SESSION['idteacher'] . "' and students.classcode='" . $classcode . "' group by idstudents;");
					while ($get = $student->fetch_assoc()) {
						if ($tab == "yes")
							$data[] = $get;
						else
							$data[$get["idstudents"]] = $get;
					}
					$json = json_encode($data);
					break;
				case 3:
					$student = $mysqli->query("SELECT students.active,students.idstudents, students.surname,students.middlename,students.givenname,students.classcode,students.birthdate,cteacher.surname as tsurname,cteacher.lastname as tgivenname FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher  as cteacher on teacher_class.idteacher=cteacher.idteacher inner join teacher as depteacher on depteacher.idteacher=department.headofdepartment where teacher_class.classteacher=1 and cteacher.idteacher='" . $_SESSION['idteacher'] . "' and students.classcode='" . $classcode . "' group by idstudents;");
					while ($get = $student->fetch_assoc()) {
						if ($tab == "yes")
							$data[] = $get;
						else
							$data[$get["idstudents"]] = $get;
					}
					$json = json_encode($data);
					break;
				case 4:
					$student = $mysqli->query("SELECT students.active,students.idstudents, students.surname,students.middlename,students.givenname,students.classcode,students.birthdate,cteacher.surname as tsurname,cteacher.lastname as tgivenname FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher  as cteacher on teacher_class.idteacher=cteacher.idteacher inner join teacher as depteacher on depteacher.idteacher=department.headofdepartment where teacher_class.classteacher=1 and students.classcode='" . $classcode . "' group by idstudents;");
					while ($get = $student->fetch_assoc()) {
						if ($tab == "yes")
							$data[] = $get;
						else
							$data[$get["idstudents"]] = $get;
					}
					$json = json_encode($data);
					break;
				default:
					$data["error"] = "not loggedin";
					$json = json_encode($data);
					break;
			}
		}
	} else {
		$data["error"] = "not loggedin";
		$json = json_encode($data);
	}
	return ($json);
}

function getclassinfo($classcode)
{
	global $mysqli;
	$data = array();
	if ($_SESSION['isactiv'] == 1) {
		if ($classcode != '') {
			if ($_SESSION['userrole'] == 1) {
				$student = $mysqli->query("SELECT * from classinformation where classcode='" . $classcode . "' ;");
				while ($get = $student->fetch_assoc()) {
					$data[$get["classcode"]] = $get;
				}
				$json = json_encode($data);
			} else if ($_SESSION['userrole'] == 2) {
				$student = $mysqli->query("SELECT * from classinformation where hodidteacher='" . $_SESSION['idteacher'] . "' and classcode='" . $classcode . "' ;");
				while ($get = $student->fetch_object()) {
					$data[$get["classcode"]] = $get;
				}
				$json = json_encode($data);
			}
		} else {
			$data["error"] = "no classcode";
			$json = json_encode($data);
		}
	} else {
		$data["error"] = "not loggedin";
		$json = json_encode($data);
	}
	return ($json);
}

function getallclass()
{
	global $mysqli;
	global $tab;
	$data = array();
	if ($_SESSION['isactiv'] == 1) {
		switch ($_SESSION['userrole']) {
		case 1:
			$student = $mysqli->query("SELECT students.active,students.idstudents, students.surname,students.middlename,students.givenname,students.classcode,cteacher.surname as tsurname,cteacher.lastname as tgivenname FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher  as cteacher on teacher_class.idteacher=cteacher.idteacher inner join teacher as depteacher on depteacher.idteacher=department.headofdepartment where teacher_class.classteacher=1 order by students.classcode;");
			while ($get = $student->fetch_assoc()) {
				if ($tab == "yes")
					$data[] = $get;
				else
					$data[$get["idstudents"]] = $get;
			}
			$json = json_encode($data);
		break;
		case 2:
			$student = $mysqli->query("SELECT students.active,students.idstudents, students.surname,students.middlename,students.givenname,students.classcode,cteacher.surname as tsurname,cteacher.lastname as tgivenname FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher  as cteacher on teacher_class.idteacher=cteacher.idteacher inner join teacher as depteacher on depteacher.idteacher=department.headofdepartment where teacher_class.classteacher=1 and depteacher.idteacher='" . $_SESSION['idteacher'] . "' order by students.classcode;");
			while ($get = $student->fetch_assoc()) {
				if ($tab == "yes")
					$data[] = $get;
				else
					$data[$get["idstudents"]] = $get;
			}
			$json = json_encode($data);
		break;
		case 3:
			$student = $mysqli->query("SELECT students.active,students.idstudents, students.surname,students.middlename,students.givenname,students.classcode,cteacher.surname as tsurname,cteacher.lastname as tgivenname FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher  as cteacher on teacher_class.idteacher=cteacher.idteacher inner join teacher as depteacher on depteacher.idteacher=department.headofdepartment where teacher_class.classteacher=1 and cteacher.idteacher='" . $_SESSION['idteacher'] . "' order by students.classcode;");
			while ($get = $student->fetch_assoc()) {
				if ($tab == "yes")
					$data[] = $get;
				else
					$data[$get["idstudents"]] = $get;
			}
			$json = json_encode($data);
		break;
		case 4:
			$student = $mysqli->query("SELECT students.active,students.idstudents, students.surname,students.middlename,students.givenname,students.classcode,cteacher.surname as tsurname,cteacher.lastname as tgivenname FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher  as cteacher on teacher_class.idteacher=cteacher.idteacher inner join teacher as depteacher on depteacher.idteacher=department.headofdepartment where teacher_class.classteacher=1 order by students.classcode;");
			while ($get = $student->fetch_assoc()) {
				if ($tab == "yes")
					$data[] = $get;
				else
					$data[$get["idstudents"]] = $get;
			}
			$json = json_encode($data);
		break;
		default: 
			$data["error"] = "keine berechtigung";
			$json = json_encode($data);
		
	}
	} else {
		$data["error"] = "not loggedin";
		$json = json_encode($data);
	}
	return ($json);
}
