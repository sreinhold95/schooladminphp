<!DOCTYPE html>
<html>
<head>
	<title>DynDNS Webaccess - Anmelden</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="style/admincp.css" type="text/css">
</head>

<body>
	<noscript>
		<div class="jserror">Bitte aktivieren Sie Javascript, sonst ist der Funktionsumfang eingeschränkt.</div>
	</noscript>
	<div id="wartung">Herzlich Willkommen in der Schulverwaltung der FLS</div>
	<div class="header">
		<div id="logo">FLS - User Management</div>
	</div>
	<div class="content">
		<?php
		session_start();
		require_once('include/config.inc.php');
        if(isset($_POST[ 'username' ])){
		  $username = $_POST[ 'username' ];
		  $password = $_POST[ 'password' ];
		  //$password = hash( 'sha256', $password );  
        }
		if (isset($username)&isset($password) ) {
			$check = $mysqli->query( "SELECT * FROM user WHERE username= '" . $mysqli->real_escape_string( $username ) . "' 
									AND password = '" . $mysqli->real_escape_string( $password )."';" );
			if ( $check->num_rows ) {
				$_SESSION[ 'loggedin' ] = true;
				while ( $row = $check->fetch_assoc() ) {
					$_SESSION[ 'id' ] = $row[ 'iduser' ];
                    if(isset($row[ 'role' ])){
                        $_SESSION[ 'userrole' ] = $row[ 'role' ];
                    }
					//if (isset($row[ 'isactiv' ])){
                        $_SESSION[ 'isactiv' ] = 1;
                    //}
				}
				if ( $_SESSION[ 'isactiv' ] == 1 ) {
					if ( $_SESSION[ 'userrole' ] == 1 ) {
						header( 'location: admin/index.php?site=home' );
					} else {
						header( 'location: user/index.php?site=home' );
					}
				} else {
					echo '<font color="#FF0000">Account ist abgelaufen!</font>';
				}
			} else {
				echo '<font color="#FF0000">Account nicht vorhanden!</font>';
			}
		}
		if ( isset( $_POST[ 'token' ] ) ) {
            $query="SELECT classcode,token FROM class WHERE token= '" . $mysqli->real_escape_string($_POST[ 'token' ])."';";
            echo $query;
			$check = $mysqli->query($query);
			if ( $check->num_rows ) {
				$_SESSION[ 'loggedin' ] = true;
				while ( $row = $check->fetch_assoc() ) {
					$_SESSION[ 'token' ] = $row[ 'token' ];
					$_SESSION[ 'classcode' ] = $row[ 'classcode' ];
                    $_SESSION["userrole"]=0;
				}
				if (isset($_SESSION['token'])&isset($_SESSION['classcode'])){
					header( 'location: user/index.php?site=home' );
				}
			} else {
				echo '<font color="#FF0000">der Token der Klasse ist abgelaufen!</font>';
			}
		}
        if ( isset( $_POST[ 'teacher' ] ) ) {
            $query="SELECT teacher.teacherid,teacher.teacher_name,user.role FROM teacher,username WHERE teacher.initials= user.username and user.password='" . $mysqli->real_escape_string($_POST[ 'tpassword' ])."' and teacher.initials='" . $mysqli->real_escape_string($_POST[ 'tusername' ])."';";
            echo $query;
			$check = $mysqli->query($query);
			if ( $check->num_rows ) {
				$_SESSION[ 'loggedin' ] = true;
				while ( $row = $check->fetch_assoc() ) {
					$_SESSION["userrole"]=$row['user.role'];
                    $_SESSION['teacherid']=$row['teacher.teacherid'];
				}
                $classs=$mysqli->query("select classcode from class left join ");
			} else {
				echo '<font color="#FF0000">der Token der Klasse ist abgelaufen!</font>';
			}
		}
		?>
	</div>
</body>
</html>