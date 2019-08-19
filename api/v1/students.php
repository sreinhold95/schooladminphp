<?php
require( '../../include/config.inc.php' );
session_start();
//error_reporting(0);
if ($_SERVER['REQUEST_METHOD']=='GET'){
	if (isset($_GET['username'])||isset($_GET['password'])){
		login($_GET['username'],$_GET['password']);
		$result = getstudent((int)$_GET['id']);
		header('HTTP/1.0 200 OK');
		header('Content-Type: application/json');
	echo $result;
	}else{
		header('HTTP/1.0 200 OK');
		header('Content-Type: application/json');
		$data = array();
		$data['error']='no username and passwort set';
		echo json_encode($data);
	}
}else if($_SERVER['REQUEST_METHOD']=='POST'){
	header('HTTP/1.0 200 OK');
	header('Content-Type: application/json');
	$student =$_POST['student'];
	createstudent($student);
}
else if($_SERVER['REQUEST_METHOD']=='PATCH'){
	header('HTTP/1.0 200 OK');
	header('Content-Type: application/json');
	parse_str(file_get_contents('php://input'), $_PATCH);
	$student =$_PATCH['student'];
	updatestudent($student);
}

function login($username,$password){
	global $mysqli;
	if (isset($username)&isset($password) ) {
			$check =$mysqli->query( "SELECT * FROM user WHERE username= '" . $mysqli->real_escape_string( $username ) . "' 
									AND password = '" . $mysqli->real_escape_string( $password )."';" );
			if ( $check->num_rows ) {
				$_SESSION[ 'loggedin' ] = true;
				while ( $row = $check->fetch_assoc() ) {
					$_SESSION[ 'id' ] = $row[ 'iduser' ];
                    if(isset($row[ 'role' ])){
                        $_SESSION[ 'userrole' ] = $row[ 'role' ];
						if($row['teacher']!='')
							$_SESSION['idteacher'] = $row['teacher'];
                    }
					//if (isset($row[ 'isactiv' ])){
                        $_SESSION[ 'isactiv' ] = 1;
                    //}
				}
				if ( $_SESSION[ 'isactiv' ] == 1 ) {
					if ( $_SESSION[ 'userrole' ] == 1 ) {
						return;
					} else if ($_SESSION[ 'userrole' ] == 2){
						return;
					} else if ($_SESSION[ 'userrole' ] == 3){
						return;
					}
				}
		}
	}
}

function getstudent($id){
	global $mysqli;
	$data = array();
	if($_SESSION['isactiv']==1){
		if($id!=''){
			$student= $mysqli->query("select * from all_students where idstudents ='".$id."';");
			$json=json_encode($student->fetch_assoc());
		}else{
			$data["error"]="no Student ID";
			$json=json_encode($data);
		}
	}else{
		$data["error"]="not loggedin";
		$json=json_encode($data);
		
	}
	
	return($json);
}

function createstudent($json){
	global $mysqli;
	global $apikey;
	$error=array();
	$data = array();
	$json=str_replace("%26","&",$_POST['student']);
	$jsonobj=json_decode($_POST['student']);
	if($jsonobj->apikey==$apikey){
		$parentstmt=$mysqli->prepare("INSERT INTO parents (mother_surname,mother_lastname,mother_address,mother_postalcode,mother_phone,mother_mobilephone,father_surname,father_lastname,father_address,father_postalcode,father_phone,father_mobilephone)values(?,?,?,?,?,?,?,?,?,?,?,?)");
		$parentstmt->bind_param('ssssssssssss',
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
		$jsonobj->parents->father_mobilephone);
		$parentstmt->execute();
		$errors["parentstmt"]=$parentstmt->error;
		$parentstmt->close();
		$parentidstmt=$mysqli->prepare("select idparents from parents where mother_surname= ? and mother_lastname = ? and mother_address= ? and father_surname= ? and father_lastname= ? and father_address= ?");
		$parentidstmt->bind_param('ssssss',$jsonobj->parents->mother_surname,$jsonobj->parents->mother_lastname,$jsonobj->parents->mother_address,$jsonobj->parents->father_surname,$jsonobj->parents->father_lastname,$jsonobj->parents->father_address);
		$parentidstmt->execute();
		$pid=$parentidstmt->get_result();
		while($row = $pid->fetch_assoc()) {
			$idparents = $row['idparents'];
		}
		$errors["parentidstmt"]=$parentidstmt->error;
		$data["parentid"]=$idparents;
		$parentidstmt->close();
		$entrydate=date('Y-m-d');
		$studentstmt=$mysqli->prepare("INSERT INTO students(surname,middlename,givenname,moregivenname,birthdate,birthtown,birthcountry,province,entryDate,classcode,address,religion,nationality,family_speech,phone,mobilephone,email,idparents,idgraduation,idberuf,active,town,plz,sex,lastschool,lastschooltown,lastschooldate,lastschoolprovince,Ausbildungsbeginn,Ausbildungsbetrieb,Ausbildungsbetrieb_strasse,Ausbildungsbetrieb_PLZ,Ausbildungsbetrieb_Telefon,Ausbildungsbetrieb_Fax,Ausbildungsbetrieb_Email,Ausbildungsbetrieb_Ausbilder_Anrede,Ausbildungsbetrieb_Ausbilder_Name,indeutschlandseit,sprachniveau) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);"); 
		if($studentstmt){
			$studentstmt->bind_param('sssssssssssssssssiiiissssssssssssssssss',
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
			$jsonobj->sprachniveau);
			$studentstmt->execute();
			$errors["studentstmt"]=$studentstmt->error;
			$studentstmt->close();
		}else{
			$errors["studentstmt"]="MySQL Syntax Error";
		}
		if($errors["parentstmt"]==""&&$errors["parentidstmt"]==""&&$errors["studentstmt"]=="")
			$data['success'] = true;
		else
			$data['success'] = false;
		$data['errors'] = $errors;
		echo json_encode($data);
	}
	else{
		echo json_decode();
	}
}

function updatestudent($json){
	global $mysqli;
	global $apikey;
	$error=array();
	$data = array();
	$json=str_replace("%26","&",$json);
	$jsonobj=json_decode($json);
	if($jsonobj->apikey==$apikey){
		$parentstmt=$mysqli->prepare("update parents set mother_surname=?,mother_lastname=?,mother_address=?,mother_postalcode=?,mother_phone=?,mother_mobilephone=?,father_surname=?,father_lastname=?,father_address=?,father_postalcode=?,father_phone=?,father_mobilephone=? where idparents=?");
		$parentstmt->bind_param('ssssssssssssi',
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
		$jsonobj->parents->idparents);
		$parentstmt->execute();
		$errors["parentstmt"]=$parentstmt->error;
		$parentstmt->close();
		$parentidstmt=$mysqli->prepare("select idparents from parents where mother_surname= ? and mother_lastname = ? and mother_address= ? and father_surname= ? and father_lastname= ? and father_address= ?");
		$parentidstmt->bind_param('ssssss',$jsonobj->parents->mother_surname,$jsonobj->parents->mother_lastname,$jsonobj->parents->mother_address,$jsonobj->parents->father_surname,$jsonobj->parents->father_lastname,$jsonobj->parents->father_address);
		$parentidstmt->execute();
		$pid=$parentidstmt->get_result();
		while($row = $pid->fetch_assoc()) {
			$idparents = $row['idparents'];
		}
		$errors["parentidstmt"]=$parentidstmt->error;
		$data["parentid"]=$idparents;
		$parentidstmt->close();
		$entrydate=date('Y-m-d');
		$studentstmt=$mysqli->prepare("update students set surname=?,middlename=?,givenname=?,moregivenname=?,birthdate=?,birthtown=?,birthcountry=?,province=?,entryDate=?,classcode=?,address=?,religion=?,nationality=?,family_speech=?,phone=?,mobilephone=?,email=?,idgraduation=?,idberuf=?,active=?,town=?,plz=?,sex=?,lastschool=?,lastschooltown=?,lastschooldate=?,lastschoolprovince=?,Ausbildungsbeginn=?,Ausbildungsbetrieb=?,Ausbildungsbetrieb_strasse=?,Ausbildungsbetrieb_PLZ=?,Ausbildungsbetrieb_Telefon=?,Ausbildungsbetrieb_Fax=?,Ausbildungsbetrieb_Email=?,Ausbildungsbetrieb_Ausbilder_Anrede=?,Ausbildungsbetrieb_Ausbilder_Name=?,indeutschlandseit=?,sprachniveau=? where idstudents=?;"); 
		if($studentstmt){
			$studentstmt->bind_param('sssssssssssssssssiiisssssssssssssssssss',
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
			$jsonobj->idstudent);
			$studentstmt->execute();
			$errors["studentstmt"]=$studentstmt->error;
			$studentstmt->close();
		}else{
			$errors["studentstmt"]="MySQL Syntax Error";
		}
		if($errors["studentstmt"]=="")
		if($errors["parentstmt"]==""&&$errors["studentstmt"]=="")
			$data['success'] = true;
		else
			$data['success'] = false;
		$data['errors'] = $errors;
		echo json_encode($data);
	}
	else{
		echo json_decode();
	}
	//echo json_decode(iconv('ASCII', 'UTF-8//IGNORE', $_POST['student']));
	//echo json_encode($jsonobj);
}
?>