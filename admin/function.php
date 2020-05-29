<?php
session_start();
$session_timeout = 600; // 1800 Sek./60 Sek. = 10 Minuten
if (!isset($_SESSION['last_visit'])) {
	$_SESSION['last_visit'] = time();
	// Aktion der Session wird ausgeführt
}
if ((time() - $_SESSION['last_visit']) > $session_timeout) {
	session_destroy();
	session_unset();
	header('location: ../logout.php');
	// Aktion der Session wird erneut ausgeführt
}
$_SESSION['last_visit'] = time();
require_once('../include/config.inc.php');

//wenn nix geht das löschen bis zum nächsten kommentar
if (isset($_SESSION['loggedin'])) {
	$loggedin = $_SESSION['loggedin'];
} else
	$loggedin = false;
if ($loggedin == true) {
	if ($_SESSION['userrole'] == 1) {
	}
} else {
	header('location: ../logout.php');
}
//bis hier hin
if (isset($_POST["Importlanis"])) {
	set_time_limit(600);
	$filename = $_FILES["file"]["tmp_name"];
	if ($_FILES["file"]["size"] > 0) {
		$errors[] = array();
		$file = fopen($filename, "r");
		$i = 0;
		$result = "";
		//$studentstmt =$mysqli->prepare("update students set lanisid=? where birthdate=? givenname=? and surname=?;");
		while (($getData = fgetcsv($file, 10000, ";")) !== FALSE) {
			if ($i > 0) {

				$year = substr($getData[4], -4);
				$month = substr($getData[4], -7, 2);
				$day = substr($getData[4], -10, 2);
				//$date = $getData[6];
				$newDate = $year . "-" . $month . "-" . $day;
				//$studentstmt->bind_param('isss',
				//$getData[10],
				//$newDate,
				//$getData[0],
				//$getData[2]);
				//$studentstmt->execute();
				$gname = preg_replace('/(\d+)/', '', $getData[0]);
				$sname = preg_replace('/(\d+)/', '', $getData[2]);
				$sql = "update students set lanisid=" . $getData[10] . " where birthdate='" . $newDate . "' and givenname='" . $gname . "' and surname='" . $sname . "';";
				$error = $mysqli->query($sql);
				//echo $studentstmt->error;
				//$result = $studentstmt->error;
				//$error=$mysqli->error();
				//echo $sql;
			}
			$i++;
		}
		//$studentstmt->close();
		if (!isset($result)) {
			echo "<script type=\"text/javascript\">
				alert(\"Invalid File:Please Upload CSV File.\");
				//window.location = \index.php?site=lanisimport\"
				</script>";
		} else {
			echo "<script type=\"text/javascript\">
				alert(\"CSV File has been successfully Imported.\");
				window.location = \"index.php?site=lanisimport\"
			</script>";
		}
		fclose($file);
	}
}

if (isset($_POST["importsus"])) {
	set_time_limit(600);
	$filename = $_FILES["file"]["tmp_name"];
	if ($_FILES["file"]["size"] > 0) {
		$errors[] = array();
		$file = fopen($filename, "r");
		$i = 0;
		$result = "";
		$entrydate = date('Y-m-d');
		$plz = '00000';
		$studentstmt = $mysqli->prepare("insert into students (surname,givenname,birthdate,entryDate,classcode,plz,idberuf) values(?,?,?,?,?,?,?)");
		while (($getData = fgetcsv($file, 10000, ";")) !== FALSE) {
			if ($i > 0) {
				switch ($getData[4]) {
					case "FISI":
						$beruf = 21;
					case "FIAE":
						$beruf = 18;
					case "ITSE":
						$beruf = 23;
					case "ITSK":
						$beruf = 24;
					case "IK":
						$beruf = 22;
					case "BFS":
						$beruf = 11;
					case "EV":
						$beruf = 2;
					case "EH":
						$beruf = 1;
					case "FL":
						$beruf = 15;
				}
				$year = substr($getData[2], -4);
				$month = substr($getData[2], -7, 2);
				$day = substr($getData[2], -10, 2);
				//$date = $getData[6];
				$birthdate = $year . "-" . $month . "-" . $day;
				$year = substr($getData[5], -4);
				$month = substr($getData[5], -7, 2);
				$day = substr($getData[5], -10, 2);
				//$date = $getData[6];
				$entrydate = $year . "-" . $month . "-" . $day;
				$studentstmt->bind_param(
					'ssssssi',
					$getData[0],
					$getData[1],
					$birthdate,
					$entrydate,
					$getData[3],
					$plz,
					$beruf
				);
				$studentstmt->execute();
				echo $studentstmt->error;
			}
			$i++;
		}
		$studentstmt->close();
		if (!isset($result)) {
			echo "<script type=\"text/javascript\">
				alert(\"Invalid File:Please Upload CSV File.\");
				//window.location = \index.php?site=lanisimport\"
				</script>";
		} else {
			echo "<script type=\"text/javascript\">
				alert(\"CSV File has been successfully Imported.\");
				//window.location = \"index.php?site=lanisimport\"
			</script>";
		}
		fclose($file);
	}
}
if (isset($_POST["susbetrieb"])) {
	set_time_limit(600);
	$filename = $_FILES["file"]["tmp_name"];
	if ($_FILES["file"]["size"] > 0) {
		$errors[] = array();
		$file = fopen($filename, "r");
		$i = 0;
		$result = "";
		$entrydate = date('Y-m-d');
		$plz = '00000';
		$ausbildungsbeginn = "2019-08-01";
		//Import SuS Nachname,Vorname,Geburtsdatum,Klasse,Beruf,entryDate,Betrieb,Betrieb_PLZ,Betrieb_Email
		$studentstmt = $mysqli->prepare("insert into students (givenname,surname,birthdate,classcode,idberuf,entryDate,Ausbildungsbetrieb,Ausbildungsbetrieb_strasse,Ausbildungsbetrieb_PLZ,Ausbildungsbetrieb_Email,Ausbildungsbeginn) values(?,?,?,?,?,?,?,?,?,?,?)");
		while (($getData = fgetcsv($file, 10000, ";")) !== FALSE) {
			if ($i > 0) {
				switch ($getData[4]) {
					case "FISI":
						$beruf = 21;
					case "FIAE":
						$beruf = 18;
					case "ITSE":
						$beruf = 23;
					case "ITSK":
						$beruf = 24;
					case "IK":
						$beruf = 22;
					case "BFS":
						$beruf = 11;
					case "EV":
						$beruf = 2;
					case "EH":
						$beruf = 1;
					case "FL":
						$beruf = 15;
				}
				$year = substr($getData[2], -4);
				$month = substr($getData[2], -7, 2);
				$day = substr($getData[2], -10, 2);
				//$date = $getData[6];
				$birthdate = $year . "-" . $month . "-" . $day;
				$studentstmt->bind_param(
					'ssssissssss',
					$getData[0],
					$getData[1],
					$birthdate,
					$getData[3],
					$beruf,
					$getData[5],
					$getData[6],
					$getData[7],
					$getData[8],
					$getData[9],
					$ausbildungsbeginn
				);
				$studentstmt->execute();
				echo $studentstmt->error;
			}
			$i++;
		}
		$studentstmt->close();
		if (!isset($result)) {
			echo "<script type=\"text/javascript\">
				alert(\"Invalid File:Please Upload CSV File.\");
				//window.location = \index.php?site=lanisimport\"
				</script>";
		} else {
			echo "<script type=\"text/javascript\">
				alert(\"CSV File has been successfully Imported.\");
				//window.location = \"index.php?site=lanisimport\"
			</script>";
		}
		fclose($file);
	}
}

if (isset($_POST["Importlusd"])) {
	set_time_limit(600);
	$filename = $_FILES["file"]["tmp_name"];
	if ($_FILES["file"]["size"] > 0) {
		$errors[] = array();
		$file = fopen($filename, "r");
		$i = 0;
		$result = "";
		//$updatestmt=$mysqli->prepare("update students set lanisid=? where lastname=? and surname=?;");
		while (($getData = fgetcsv($file, 10000, ";")) !== FALSE) {
			if ($i > 0) {
				//$updatestmt->bind_param('iss',$getData[7],$getData[0],$getData[1]);
				//$updatestmt->execute();
				$year = substr($getData[6], -4);
				$month = substr($getData[6], -6, 2);
				$day = substr($getData[6], -8, 2);
				//$date = $getData[6];
				$newDate = $year . "-" . $month . "-" . $day;
				$sql = "update students set lanisid=" . $getData[7] . " where birthdate='" . $newDate . "' and givenname='" . $getData[0] . "' and surname='" . $getData[1] . "';";

				$result = $mysqli->query($sql);
				//$error $mysqli->error();
				//echo $sql;
			}
			$i++;
		}
		//$errors["updatestmt"]=$updatestmt->error;
		//$updatestmt->close();
		if (!isset($result)) {
			//}
			//echo $errors;
			echo "<script type=\"text/javascript\">
				alert(\"Invalid File:Please Upload CSV File.\");
				window.location = \index.php?site=lanisimport\"
				</script>";
		} else {
			//echo $result;
			echo "<script type=\"text/javascript\">
				alert(\"CSV File has been successfully Imported.\");
				window.location = \"index.php?site=lanisimport\"
			</script>";
		}
		fclose($file);
	}
}
?>