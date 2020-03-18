<!DOCTYPE html>
<html>
<head>
	<title>DSchulverwaltung - Anmelden</title>
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
		<div id="menu">
			<ul>
				<li><a href="../index.php" class="link">Startseite</a></li> 
			</ul>
		</div>	
	</div>
	
	<div class="content">
		<?php
		ini_set('session.gc_maxlifetime', 10*60); 
		ini_set('session.cookie_lifetime', 10*60);
		session_start();
		$session_timeout = 5*60; // 360 Sek./60 Sek. = 6 Minuten
		if (!isset($_SESSION['last_visit'])) {
		$_SESSION['last_visit'] = time();
		// Aktion der Session wird ausgeführt
		}
		if((time() - $_SESSION['last_visit']) > $session_timeout) {
		session_destroy();
		// Aktion der Session wird erneut ausgeführt
		}
		$_SESSION['last_visit'] = time();
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
					$_SESSION["username"]=$row['username'];
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
						header( 'location: admin/index.php?site=home' );
					} else if ($_SESSION[ 'userrole' ] == 2){
						header( 'location: dep_instructor/index.php?site=home' );
					} else if ($_SESSION[ 'userrole' ] == 3){
						header( 'location: classteacher/index.php?site=home' );
					} else if ($_SESSION['userrole']==4	){
						header('location: administration/index.php?site=home');
					}
				} else {
					echo '<font color="#FF0000">Account ist abgelaufen!</font>';
				}
			} else {
				echo '<font color="#FF0000">Account nicht vorhanden!</font>';
			}
		}
		if ( isset( $_POST[ 'studenttoken' ] ) ) {
            $query="SELECT idstudents,token FROM students WHERE token= '" . $mysqli->real_escape_string($_POST[ 'studenttoken' ])."';";
            //echo $query;
			$check = $mysqli->query($query);
			if ( $check->num_rows ) {
				$_SESSION[ 'loggedin' ] = true;
				while ( $row = $check->fetch_assoc() ) {
					$_SESSION[ 'token' ] = $row[ 'token' ];
					$_SESSION[ 'idstudents' ] = $row[ 'idstudents' ];
                    $_SESSION["userrole"]=0;
				}
				if (isset($_SESSION['token'])&isset($_SESSION['idstudents'])){
					header( 'location: user/index.php?site=home' );
				}
			} else {
				echo '<font color="#FF0000">der Token der Klasse ist abgelaufen!</font>';
			}
		}if ( isset( $_POST[ 'token' ] ) ) {
            $query="SELECT classcode,token,activetoken FROM class WHERE activetoken=1 and TIMESTAMPDIFF(MINUTE,tokenactivateat, NOW())<15 and token= '" . $mysqli->real_escape_string($_POST[ 'token' ])."';";
			$check = $mysqli->query($query);
			if ( $check->num_rows ) {
				$_SESSION[ 'loggedin' ] = true;
				while ( $row = $check->fetch_assoc() ) {
					if($row['activetoken']=="1")
					{
						$_SESSION[ 'token' ] = $row[ 'token' ];
						$_SESSION[ 'classcode' ] = $row[ 'classcode' ];
						$_SESSION["userrole"]=0;
					}
				}
				if (isset($_SESSION['token'])&isset($_SESSION['classcode'])){
					header( 'location: class/index.php?site=create' );
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