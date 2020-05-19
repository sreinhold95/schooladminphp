<?php
function auth($uuid){
    $auth=false;
    $check=$mysqli->query("select teacher from user where uuid='".$uuid."' and uuidlifetime>=DATE_SUB(NOW(),INTERVAL 24 HOUR)");
	if($check->num_rows){
    	while($row=$check->fetch_assoc()){
        	$auth=true;
    	}
	}
}
?>