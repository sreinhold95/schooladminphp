<?php
	require $_SERVER['DOCUMENT_ROOT'].'/include/config.inc.php';
	session_start();
	ini_set('error_reporting', E_ERROR);
	//$tab="no";
	if ($_SERVER['REQUEST_METHOD']=='GET'){
		$headers = apache_request_headers();
		$uuid = $headers['uuid'];
		//if (issest($headers['tab'])){
			$tab = $headers['tab'];
			//echo $tab;
		//}
			
		$auth=false;
		$check=$mysqli->query("select teacher from user where uuid='".$uuid."' and uuidlifetime>=DATE_SUB(NOW(),INTERVAL 24 HOUR)");
		if($check->num_rows){
			while($row=$check->fetch_assoc()){
				$idteacher=$row["teacher"];
				
				$auth=true;
			}
		}
		if($auth){
			if (isset($uuid)){
				login($uuid);
				
				if(isset($_GET["classcode"]))
					$result = getclass($_GET["classcode"]);
				else
					$result = getallclasses();
				header('HTTP/1.0 200 OK');
				header('Content-Type: application/json');
			echo $result;
			}else{
				header('HTTP/1.0 403 Forbitten');
				header('Content-Type: application/json');
				$data = array();
				$data['error']='no username and passwort set';
				echo json_encode($data);
			}
		}else{
			header('HTTP/1.0 403 Forbitten');
			header('Content-Type: application/json');
			$data = array();
			$data['error']='not authorized';
			echo json_encode($data);
		}
	}
	function login($uuid){
		global $mysqli;
		if (isset($uuid)) {
				$check =$mysqli->query( "SELECT * FROM user WHERE uuid= '" . $mysqli->real_escape_string( $uuid ) ."';" );
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
	function getallclasses(){
		global $mysqli;
		global $tab;
		$data=array();
		if($_SESSION[ 'userrole' ] == 1 ){
			$query = $mysqli->query( "SELECT * FROM class;" );
			if ( $query->num_rows ) {
				while ($get=$query->fetch_assoc() ){
					if($tab=="yes")
						$data[]=$get;
					else
						$data[$get["classcode"]]=$get;
				}
				return json_encode($data);
			}
		}else if($_SESSION[ 'userrole' ] == 2 ){
			$query = $mysqli->query( "SELECT * FROM classdepartment where headidteacher= '".$_SESSION['idteacher']."' ;" );
			if ( $query->num_rows ) {
				while ($get=$query->fetch_assoc() ){
					if($tab=="yes")
						$data[]=$get;
					else
						$data[$get["classcode"]]=$get;
				}
				return json_encode($data);
			}
		}else if($_SESSION[ 'userrole' ] == 3 ){
			$query = $mysqli->query( "SELECT * FROM classteacher where idteacher= '".$_SESSION['idteacher']."' ;" );
			if ( $query->num_rows ) {
				while ($get=$query->fetch_assoc() ){
					if($tab=="yes")
						$data[]=$get;
					else
						$data[$get["classcode"]]=$get;
				}
				return json_encode($data);
			}
		}
		else if($_SESSION[ 'userrole' ] == 4 ){
			$query = $mysqli->query( "SELECT * FROM class;" );
			if ( $query->num_rows ) {
				while ($get=$query->fetch_assoc() ){
					if($tab=="yes")
						$data[]=$get;
					else
						$data[$get["classcode"]]=$get;
				}
				return json_encode($data);
			}
		}else {
			$data["error"] = "no rights";
			return json_encode($data);
			header('HTTP/1.0 403 no rights');
			header('Content-Type: application/json');
		}
	}
	function getclass($classcode){
		global $mysqli;
		$data=array();
		if($_SESSION[ 'userrole' ] == 1 ){
			$query = $mysqli->query( "SELECT * FROM class where classcode='".$classcode."';" );
			if ( $query->num_rows ) {
				while ($get=$query->fetch_assoc() ){
					$data[$get["classcode"]]=$get;
				}
				return json_encode($data);
			}
		}else if($_SESSION[ 'userrole' ] == 2 ){
			$query = $mysqli->query( "SELECT * FROM classdepartment where classcode='".$classcode."' and headidteacher= '".$_SESSION['idteacher']."' ;" );
			if ( $query->num_rows ) {
				while ($get=$query->fetch_assoc() ){
					$data[$get["classcode"]]=$get;
				}
				return json_encode($data);
			}
		}
		
	}

?>