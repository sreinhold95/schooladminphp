<?php
require( '../../include/config.inc.php' );
session_start();

//global $apikey;
if ($_SERVER['REQUEST_METHOD']=='GET'){
	if (isset($_GET['apikey'])||isset($_GET['Berufs_ID'])){
		if($apikey==$_GET['apikey']){
			header('HTTP/1.0 200 OK');
			header('Content-Type: application/json');
			$result = getberuf($_GET['Berufs_ID']);
			echo $result;
		}else{
			header('HTTP/1.0 200 OK');
			header('Content-Type: application/json');
			$data = array();
			$data['error']='no apikey or falsch';
			echo json_encode($data);
		}
	}
	else{
		header('HTTP/1.0 200 OK');
		header('Content-Type: application/json');
		$data = array();
		$data['error']='no apikey and Berufs_ID Set';
		echo json_encode($data);
	}
}
else{
	header('HTTP/1.0 200 OK');
		header('Content-Type: application/json');
		$data = array();
		$data['error']='not get';
		echo json_encode($data);
}

function getberuf($Beruf_ID){
	global $mysqli;
	//require $_SERVER['DOCUMENT_ROOT'].'/include/config.inc.php';
	$data = array();
    $student= $mysqli->query("select * from beruf where Berufs_ID ='".$Beruf_ID."';");
    $json=json_encode($student->fetch_assoc());
	return($json);
}