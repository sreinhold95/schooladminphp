<?php
require $_SERVER['DOCUMENT_ROOT'] . '/include/config.inc.php';
session_start();
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: deny');
ini_set('error_reporting', E_ERROR);
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	$headers = apache_request_headers();
	$uuid = $headers['uuid'];
	$tab = $headers['tab'];
	$auth = false;
	$check = $mysqli->query("select username from user where uuid='" . $mysqli->real_escape_string($uuid) . "' and uuidlifetime>=DATE_SUB(NOW(),INTERVAL 12 HOUR)");
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
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$headers = apache_request_headers();
	$uuid = $headers['uuid'];
	$tab = $headers['tab'];
	$auth = false;
	$check = $mysqli->query("select username from user where uuid='" . $mysqli->real_escape_string($uuid) . "' and uuidlifetime>=DATE_SUB(NOW(),INTERVAL 12 HOUR)");
	if ($check->num_rows) {
		while ($row = $check->fetch_assoc()) {
			$auth = true;
		}
	}
	if ($auth == true) {
		if (isset($_POST['status'])) {
			settoken((int)$_POST['status'], $_POST['classcode']);
			header('HTTP/1.0 200 OK activate');
			header('Content-Type: application/json');
			echo $result;
			session_unset();
			session_abort();
		}
	}
}else if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
	$headers = apache_request_headers();
	$uuid = $headers['uuid'];
	$auth = false;
	$check = $mysqli->query("SELECT teacher,role from user where active=1 and uuid='" . $mysqli->real_escape_string($uuid) . "' and uuidlifetime>=DATE_SUB(NOW(),INTERVAL 24 HOUR)");
	if ($check->num_rows) {
		while ($row = $check->fetch_assoc()) {
			$_SESSION['userrole']=$row["role"];
			$auth = true;
		}
	}
	if ($auth) {
		$data = array();
		header('HTTP/1.0 200 OK Patch');
		header('Content-Type: application/json');
		$patch=file_get_contents('php://input');
		//print_r($patch);
		if($patch["schoolyearchange"]){
			// echo "juhu";
			schoolyearchange();
			//echo $_PATCH["schoolyearchange"];
		}
	} else {
		header('HTTP/1.0 403 Forbitten');
		header('Content-Type: application/json');
		$data = array();
		$data["error"] = "uuid false or outdated";
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
					$student = $mysqli->query("SELECT students.active,students.idstudents, students.surname,students.middlename,students.givenname,students.classcode,cteacher.surname as tsurname,cteacher.lastname as tgivenname, students.email, students.idnumber as device FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher  as cteacher on teacher_class.idteacher=cteacher.idteacher inner join teacher as depteacher on depteacher.idteacher=department.headofdepartment where teacher_class.classteacher=1 and depteacher.idteacher='" . $mysqli->real_escape_string($_SESSION['idteacher']) . "' order by classcode;");
					while ($get = $student->fetch_assoc()) {
						if ($tab == "yes")
							$data[] = $get;
						else
							$data[$get["idstudents"]] = $get;
					}
					$json = json_encode($data);
					break;
				case 2:
					$student = $mysqli->query("SELECT students.active,students.idstudents, students.surname,students.middlename,students.givenname,students.classcode,cteacher.surname as tsurname,cteacher.lastname as tgivenname, students.email, students.idnumber as device FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher  as cteacher on teacher_class.idteacher=cteacher.idteacher inner join teacher as depteacher on depteacher.idteacher=department.headofdepartment where teacher_class.classteacher=1 and depteacher.idteacher='" . $mysqli->real_escape_string($_SESSION['idteacher']) . "' order by classcode;");
					while ($get = $student->fetch_assoc()) {
						if ($tab == "yes")
							$data[] = $get;
						else
							$data[$get["idstudents"]] = $get;
					}
					$json = json_encode($data);
					break;
				case 3:
					$student = $mysqli->query("SELECT students.active,students.idstudents, students.surname,students.middlename,students.givenname,students.classcode,cteacher.surname as tsurname,cteacher.lastname as tgivenname, students.email, students.idnumber as device FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher  as cteacher on teacher_class.idteacher=cteacher.idteacher inner join teacher as depteacher on depteacher.idteacher=department.headofdepartment where teacher_class.classteacher=1 and cteacher.idteacher='" . $mysqli->real_escape_string($_SESSION['idteacher']) . "' order by classcode;");
					while ($get = $student->fetch_assoc()) {
						if ($tab == "yes")
							$data[] = $get;
						else
							$data[$get["idstudents"]] = $get;
					}
					$json = json_encode($data);
					break;
				case 4:
					$student = $mysqli->query("SELECT students.active,students.idstudents, students.surname,students.middlename,students.givenname,students.classcode,cteacher.surname as tsurname,cteacher.lastname as tgivenname, students.email, students.idnumber as device FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher  as cteacher on teacher_class.idteacher=cteacher.idteacher inner join teacher as depteacher on depteacher.idteacher=department.headofdepartment where teacher_class.classteacher=1 and depteacher.idteacher='" . $mysqli->real_escape_string($_SESSION['idteacher']) . "' order by classcode;");
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
					$student = $mysqli->query("SELECT students.active,students.idstudents, students.surname,students.middlename,students.givenname,students.classcode,students.birthdate,cteacher.surname as tsurname,cteacher.lastname as tgivenname, students.email, students.idnumber as device FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher  as cteacher on teacher_class.idteacher=cteacher.idteacher inner join teacher as depteacher on depteacher.idteacher=department.headofdepartment where teacher_class.classteacher=1 and students.classcode='" . $mysqli->real_escape_string($classcode) . "' group by idstudents;");
					while ($get = $student->fetch_assoc()) {
						if ($tab == "yes")
							$data[] = $get;
						else
							$data[$get["idstudents"]] = $get;
					}
					$json = json_encode($data);
					break;
				case 2:
					$student = $mysqli->query("SELECT students.active,students.idstudents, students.surname,students.middlename,students.givenname,students.classcode,students.birthdate,cteacher.surname as tsurname,cteacher.lastname as tgivenname, students.email, students.idnumber as device FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher  as cteacher on teacher_class.idteacher=cteacher.idteacher inner join teacher as depteacher on depteacher.idteacher=department.headofdepartment where teacher_class.classteacher=1 and depteacher.idteacher='" . $mysqli->real_escape_string($_SESSION['idteacher']) . "' and students.classcode='" . $mysqli->real_escape_string($classcode) . "' group by idstudents;");
					while ($get = $student->fetch_assoc()) {
						if ($tab == "yes")
							$data[] = $get;
						else
							$data[$get["idstudents"]] = $get;
					}
					$json = json_encode($data);
					break;
				case 3:
					$student = $mysqli->query("SELECT students.active,students.idstudents, students.surname,students.middlename,students.givenname,students.classcode,students.birthdate,cteacher.surname as tsurname,cteacher.lastname as tgivenname, students.email,students.idnumber as device FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher  as cteacher on teacher_class.idteacher=cteacher.idteacher inner join teacher as depteacher on depteacher.idteacher=department.headofdepartment where teacher_class.classteacher=1 and cteacher.idteacher='" . $mysqli->real_escape_string($_SESSION['idteacher']) . "' and students.classcode='" . $mysqli->real_escape_string($classcode) . "' group by idstudents;");
					while ($get = $student->fetch_assoc()) {
						if ($tab == "yes")
							$data[] = $get;
						else
							$data[$get["idstudents"]] = $get;
					}
					$json = json_encode($data);
					break;
				case 4:
					$student = $mysqli->query("SELECT students.active,students.idstudents, students.surname,students.middlename,students.givenname,students.classcode,students.birthdate,cteacher.surname as tsurname,cteacher.lastname as tgivenname, students.email, students.idnumber as device FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher  as cteacher on teacher_class.idteacher=cteacher.idteacher inner join teacher as depteacher on depteacher.idteacher=department.headofdepartment where teacher_class.classteacher=1 and students.classcode='" . $mysqli->real_escape_string($classcode) . "' group by idstudents;");
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
				$student = $mysqli->query("SELECT * from classinformation where classcode='" . $mysqli->real_escape_string($classcode) . "' ;");
				while ($get = $student->fetch_assoc()) {
					$data[$get["classcode"]] = $get;
				}
				$json = json_encode($data);
			} else if ($_SESSION['userrole'] == 2) {
				$student = $mysqli->query("SELECT * from classinformation where hodidteacher='" . $mysqli->real_escape_string($_SESSION['idteacher']) . "' and classcode='" . $mysqli->real_escape_string($classcode) . "' ;");
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
				$student = $mysqli->query("SELECT students.active,students.idstudents, students.surname,students.middlename,students.givenname,students.classcode,cteacher.surname as tsurname,cteacher.lastname as tgivenname FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher  as cteacher on teacher_class.idteacher=cteacher.idteacher inner join teacher as depteacher on depteacher.idteacher=department.headofdepartment where teacher_class.classteacher=1 and depteacher.idteacher='" . $mysqli->real_escape_string($_SESSION['idteacher']) . "' order by students.classcode;");
				while ($get = $student->fetch_assoc()) {
					if ($tab == "yes")
						$data[] = $get;
					else
						$data[$get["idstudents"]] = $get;
				}
				$json = json_encode($data);
				break;
			case 3:
				$student = $mysqli->query("SELECT students.active,students.idstudents, students.surname,students.middlename,students.givenname,students.classcode,cteacher.surname as tsurname,cteacher.lastname as tgivenname FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher  as cteacher on teacher_class.idteacher=cteacher.idteacher inner join teacher as depteacher on depteacher.idteacher=department.headofdepartment where teacher_class.classteacher=1 and cteacher.idteacher='" . $mysqli->real_escape_string($_SESSION['idteacher']) . "' order by students.classcode;");
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

function settoken($status, $classcode)
{
	global $mysqli;
	$errors = array();
	$data = array();
	if (isset($classcode)) {
		$activatedate = date('Y-m-d H:i:s');
		if ($status == 1) {
			$classtokenstmt = $mysqli->prepare("update class set activetoken=?,tokenactivateat=? where classcode=?");
			$classtokenstmt->bind_param("iss", $status,$activatedate, $classcode);
		} else {
			$classtokenstmt = $mysqli->prepare("update class set activetoken=? where classcode=?");
			$classtokenstmt->bind_param("is", $status, $classcode);
		}
		$classtokenstmt->execute();
		$errors["classtokenstmt"] = $classtokenstmt->error;
		$classtokenstmt->close();
		if ($errors["classtokenstmt"] == "")
			$data['success'] = true;
		else
			$data['success'] = false;
		$data['errors'] = $errors;
		echo json_encode($data);
	}
}
function schoolyearchange()
{
	global $mysqli;
	global $tab;
	$errors = array();
	$data = array();
	$exitDate = "2021-07-16";
	$classes=$mysqli->query("SELECT classcode,new_classcode from class where sequence=1");
	while ($get=$classes->fetch_assoc())
	{
		$new_classcode=$mysqli->prepare("UPDATE students set classcode=? where classcode=? and idstudents>=0 and active=1 ");
		$new_classcode->bind_param("ss",$get["new_classcode"],$get["classcode"]);
		$new_classcode->execute();
		$errors["new_classodeseq1"]=$new_classcode->error;
	}
	if ($errors["new_classodeseq1"] == ""){
		$classes=$mysqli->query("SELECT classcode,new_classcode from class where sequence=2");
		while ($get=$classes->fetch_assoc())
		{
			$new_classcode=$mysqli->prepare("UPDATE students set classcode=? where classcode=? and idstudents>=0 and active=1 ");
			$new_classcode->bind_param("ss",$get["new_classcode"],$get["classcode"]);
			$new_classcode->execute();
			$errors["new_classodeseq2"]=$new_classcode->error;
		}
	}
	if ($errors["new_classodeseq2"] == ""){
		$classes=$mysqli->query("SELECT classcode,new_classcode from class where sequence=3");
		while ($get=$classes->fetch_assoc())
		{
			$new_classcode=$mysqli->prepare("UPDATE students set classcode=? where classcode=? and idstudents>=0 and active=1 ");
			$new_classcode->bind_param("ss",$get["new_classcode"],$get["classcode"]);
			$new_classcode->execute();
			$errors["new_classodeseq3"]=$new_classcode->error;
		}
	}
	if ($errors["new_classodeseq3"] == ""){
		$classcode="delete";
		$inactivetodelete=$mysqli->prepare("UPDATE students set classcode =? where active=0 and idstudents>=0;");
		$inactivetodelete->bind_param("s",$classcode);
		$inactivetodelete->execute();
		$errors["inactivtodelete"]=$inactivetodelete->error;
		$inactivetodelete=$mysqli->prepare("UPDATE students set active =0 where classcode=? and idstudents>=0;");
		$inactivetodelete->bind_param("s",$classcode);
		$inactivetodelete->execute();
		$errors["inactivtodelete"]=$inactivetodelete->error;
		$new_classcode=$mysqli->prepare("UPDATE students set exitDate=? where classcode=? and idstudents>=0 and exitDate is null");
		$new_classcode->bind_param("ss",$exitDate,$classcode);
		$new_classcode->execute();
		$errors["setExitDate"]=$new_classcode->error;
	}
	if ($errors["new_classodeseq1"] == ""&& $errors["new_classodeseq2"] == ""&& $errors["new_classodeseq3"] == ""&& $errors["inactivetodelete"] == ""&& $errors["setExitDate"] == "")
			$data['success'] = true;
		else
			$data['success'] = false;
		$data['errors'] = $errors;
		echo json_encode($data);
}
