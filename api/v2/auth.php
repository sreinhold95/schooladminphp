<?php
$userid=0;
$uuid="";
    require $_SERVER['DOCUMENT_ROOT'].'/include/config.inc.php';
    $data = array();
    $user=array();
    if ($_SERVER['REQUEST_METHOD']=='GET'){
        $username=$_GET["username"];
        $password=$_GET["password"];
        global $mysqli;
        if (isset($username)&isset($password) ) {
            $data["username"]=$username;
            $check =$mysqli->query( "SELECT * FROM user WHERE username= '" . $mysqli->real_escape_string( $username ) . "' 
                                    AND password = '" . $mysqli->real_escape_string( $password )."';" );
            if ( $check->num_rows ) {
                while ( $row = $check->fetch_assoc() ) {
                    $userid=$row['iduser'];
                    $data["userid"]=$userid;
                }
                if($mysqli->query("update user set uuid=uuid(), uuidlifetime=now() where iduser='".$userid."';")===TRUE){
                    $data["debug"]="uuid set";
                    $check1=$mysqli->query("SELECT uuid FROM user WHERE iduser=".$userid.";");
                    if($check1->num_rows){
                        while ( $row = $check1->fetch_assoc() ) {
                            $data["success"]=true;
                            $uuid=$row['uuid'];
                            $data["uuid"]=$uuid;
                            $data["username"]=$username;
                        }
                    }
                  
                }
            }
		}else{
            $data["error"]="username or password not set";
        }
        $data["get"]=true;
    }
    else{
        $data["success"]=false;
        $data["error"]="no get response";
    }
    header('HTTP/1.0 200 OK');
	header('Content-Type: application/json');
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: deny');
    echo json_encode($data);
?>