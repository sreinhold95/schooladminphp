<?php
require( '../include/config.inc.php' );
session_start();
$session_timeout = 1800; // 1800 Sek./60 Sek. = 30 Minuten
if (!isset($_SESSION['last_visit'])) {
  $_SESSION['last_visit'] = time();
  // Aktion der Session wird ausgeführt
}
if((time() - $_SESSION['last_visit']) > $session_timeout) {
  session_destroy();
  // Aktion der Session wird erneut ausgeführt
}
$_SESSION['last_visit'] = time();
if ( isset( $_GET[ "site" ] ) ) {
    $site = $_GET[ 'site' ];
    if ( strlen( $site ) != 0 ) {
        if ( $site == "students" ) {
            $text = "site/students.php";
        }
        else if ( $site == "update" ) {
            $text = "site/update.php";
        }
        else if ( $site == "create" ) {
            $text = "site/create.php";
        }
		else if ( $site == "teacher" ) {
            $text = "site/teacher.php";
        }
        else if ( $site == "teacherupdate" ) {
            $text = "site/teacherupdate.php";
        }
        else if ( $site == "class" ) {
            $text = "site/class.php";
        }
        else if ( $site == "lanisexport" ) {
            $text="";
            $text1="site/lanisexport.php";
        }
        else if ( $site == "lanisimport" ) {
            //$text="";
            $text="site/lanisimport.php";
        }
        else if ( $site == "lusdimport" ) {
            //$text="";
            $text="site/lusdimport.php";
        }
        else if ( $site == "susimport" ) {
            $text="site/susimportnormal.php";
        }
		else {
            $text = "site/home.php";
        }
    }
}
if ( isset( $_SESSION[ 'loggedin' ] ) ) {
    $loggedin = $_SESSION[ 'loggedin' ];
}else
    $loggedin = false;
if ( $loggedin == true ) {
    if ( $_SESSION[ 'userrole' ] == 1 ) {
        include( "../style/header.php" );
        include( "../style/menu.php" );
        if ($text!="")
            include( "../style/content.php" );
        else
            include($text1);
    }
} else {
    header( 'location: ../index.php' );
}
?>