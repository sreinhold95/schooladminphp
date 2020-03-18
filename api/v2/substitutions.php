<?php
require $_SERVER['DOCUMENT_ROOT'].'/include/config.untis.inc.php';
require $_SERVER['DOCUMENT_ROOT'].'/include/config.inc.php';
session_start();

if ($_SERVER['REQUEST_METHOD']=='GET'){
	if (isset($_GET['username'])||isset($_GET['password'])){
		login($_GET['username'],$_GET['password']);
		$result = getclass($_GET['classcode']);
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
function substitutionsclass ($class,$school_id,$schoolyear_id,$versionid){
	global $untis;
	$data=array();
	$dataobj;
	if($_SESSION['isactiv']==1){
		if($classcode==''){
			$data["error"]="no class";
		}else{
			
		}
	}else
	{
		$data["error"]="not loggedin";
		$json=json_encode($data);
	}
	return $json;
}
