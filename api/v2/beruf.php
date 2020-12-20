<?php
require $_SERVER['DOCUMENT_ROOT'].'/include/config.inc.php';
session_start();

//global $apikey;
if ($_SERVER['REQUEST_METHOD']=='GET'){
	$headers = apache_request_headers();
	$uuid = $headers['uuid'];
	$auth=false;
	$check=$mysqli->query("select teacher from user where uuid='".$mysqli->real_escape_string($uuid)."' and uuidlifetime>=DATE_SUB(NOW(),INTERVAL 24 HOUR)");
	if($check->num_rows){
    	while($row=$check->fetch_assoc()){
			//$idteacher=$row["teacher"];
			
        	$auth=true;
    	}
	}
	if (isset($_GET['Berufs_ID'])){
		if($auth){
			header('HTTP/1.0 200 OK');
			header('Content-Type: application/json');
			$result = getberuf($_GET['Berufs_ID']);
			echo $result;
		}else{
			header('HTTP/1.0 403 Forbitten');
			header('Content-Type: application/json');
			$data = array();
			$data['error']='not authorited';
			echo json_encode($data);
		}
	}
	else{
		header('HTTP/1.0 200 OK');
		header('Content-Type: application/json');
		$data = array();
		$data['error']='no Berufs_ID Set';
		echo json_encode($data);
	}
}
else{
	header('HTTP/1.0 400 Bad Protocol');
		header('Content-Type: application/json');
		$data = array();
		$data['error']='not get';
		echo json_encode($data);
}

function getberuf($Beruf_ID){
	global $mysqli;
	//require $_SERVER['DOCUMENT_ROOT'].'/include/config.inc.php';
	$data = array();
    $beruf= $mysqli->query("select * from beruf where Berufs_ID ='".$mysqli->real_escape_string($Beruf_ID)."';");
    $json=json_encode($beruf->fetch_assoc());
	return($json);
}