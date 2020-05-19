<?php
require $_SERVER['DOCUMENT_ROOT'].'/include/config.inc.php';
require $_SERVER['DOCUMENT_ROOT'].'/tcpdf/tcpdf.php';
session_start();
if ($_SERVER['REQUEST_METHOD']=='GET'){
    $headers = apache_request_headers();
    $json=json_encode($headers);
    $uuid = $headers['uuid'];
    $classcode=$_GET["classcode"];
    $username=$_GET["username"];
}

$pdfName = "Stammblatt.pdf";
$pdfAuthor="FLS Darmstadt";
$datenow = date("d.m.Y H:i:s");
$auth=false;
//uuid teacher-ID
$check=$mysqli->query("select teacher from user where uuid='".$uuid."' and username='".$username."'");
if($check->num_rows){
    while($row=$check->fetch_assoc()){
        $idteacher=$row["teacher"];
        $auth=true;
    }
}
if($auth){
 echo $idteacher;
}

?>