<?php
require $_SERVER['DOCUMENT_ROOT'].'/include/config.inc.php';
session_start();

if ($_SERVER['REQUEST_METHOD']=='GET'){
	$headers = apache_request_headers();
	$uuid = $headers['uuid'];
	$auth=false;
	$check=$mysqli->query("select teacher from user where uuid='".$uuid."' and uuidlifetime>=DATE_SUB(NOW(),INTERVAL 24 HOUR)");
	if($check->num_rows){
    	while($row=$check->fetch_assoc()){
        	$auth=true;
    	}
	}
	if ($auth){
		login($uuid);
		$result = getdepartment($_GET['department']);
		header('HTTP/1.0 200 OK');
		header('Content-Type: application/json');
	echo $result;
	}else{
		header('HTTP/1.0 200 OK');
		header('Content-Type: application/json');
		$data = array();
		$data['error']='no username and passwort set';
		echo json_encode($data);
		session_destroy();
	}
}
function login($uuid){
	global $mysqli;
	if (isset($_SESSION["uuid"]) ) {
			$check =$mysqli->query( "SELECT * FROM user WHERE uuid= '" . $mysqli->real_escape_string( $uuid) . "' ;" );
			if ( $check->num_rows ) {
				$_SESSION[ 'loggedin' ] = true;
				while ( $row = $check->fetch_assoc() ) {
					$_SESSION[ 'id' ] = $row[ 'iduser' ];
                    if(isset($row[ 'role' ])){
                        $_SESSION[ 'userrole' ] = $row[ 'role' ];
						if($row['teacher']!='')
							$_SESSION['idteacher'] = $row['teacher'];
                    }
                        $_SESSION[ 'isactiv' ] = 1;
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
function getdepartment($json){
	global $mysqli;
	$data=array();
	$dataobj;
	if($_SESSION['isactiv']==1){
		if($classcode==''){
			if($_SESSION['userrole']=1){
				$student= $mysqli->query("SELECT students.active,students.idstudents, students.surname,students.middlename,students.givenname,students.classcode,cteacher.surname as tsurname,cteacher.givenname as tgivenname FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher  as cteacher on teacher_class.idteacher=cteacher.idteacher inner join teacher as depteacher on depteacher.idteacher=department.headofdepartment where teacher_class.classteacher=1 and depteacher.idteacher='".$_SESSION['idteacher']."';");
					while ($get=$student->fetch_assoc() ){
						$data[$get["idstudents"]]=$get;
					}
				$json=json_encode($data);
			}
			
		}else{
			if($_SESSION['userrole']=2){
				$student= $mysqli->query("SELECT students.active,students.idstudents, students.surname,students.middlename,students.givenname,students.classcode,cteacher.surname as tsurname,cteacher.givenname as tgivenname FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher  as cteacher on teacher_class.idteacher=cteacher.idteacher inner join teacher as depteacher on depteacher.idteacher=department.headofdepartment where teacher_class.classteacher=1 and depteacher.idteacher='".$_SESSION['idteacher']."' and class.classcode='".$classcode."';");
				while ($get=$student->fetch_object() ){
						$data[$get["idstudents"]]=$get;
					}
				$json=json_encode($data);
			}
			
		}
	}else{
		$data["error"]="not loggedin";
		$json=json_encode($data);
		
	}
	
	return($json);
}
?>