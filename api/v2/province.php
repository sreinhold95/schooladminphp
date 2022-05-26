<?php
require $_SERVER['DOCUMENT_ROOT'].'/include/config.inc.php';
session_start();
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: deny');
ini_set('error_reporting', E_ERROR);
if ($_SERVER['REQUEST_METHOD']=='GET'){
	$headers = apache_request_headers();
    $json=json_encode($headers);
	$uuid = $headers['uuid'];
	$auth=false;
	$check=$mysqli->query("select teacher from user where uuid='".$mysqli->real_escape_string($uuid)."' and uuidlifetime>=DATE_SUB(NOW(),INTERVAL 24 HOUR)");
	if($check->num_rows){
    	while($row=$check->fetch_assoc()){
        	$auth=true;
    	}
	}
	if($auth){
		if(isset($_GET["province"])){
			$result =getplz($_GET["province"]);
			header('HTTP/1.0 200 OK');
			header('Content-Type: application/json');
			echo $result;
		}
	}
	else{
		header('HTTP/1.0 403 Forbitten');
		header('Content-Type: application/json');
		$data = array();
		$data['error']='not authorized';
		echo json_encode($data);
	}
}

function getplz($plz){
	global $mysqli;
	$data=array();
	#$dataobj;
	if(!$plz==''){
		$query=$mysqli->query("select * from province where province='".$mysqli->real_escape_string($plz)."';");
		$json=json_encode($query->fetch_assoc());
		
	}
	else{
		$data["error"]="no data";
		$json=json_encode($data);
	}
	return($json);
}
?>