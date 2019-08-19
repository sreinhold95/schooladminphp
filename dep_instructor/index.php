<?php

require( '../include/config.inc.php' );
session_start();

if ( isset( $_GET[ "site" ] ) ) {
    $site = $_GET[ 'site' ];
    if ( strlen( $site ) != 0 ) {
        if ( $site == "update" ) {
            $text = "site/update.php";
        }
        else if ( $site == "create" ) {
            $text = "site/create.php";
        }
		else if ( $site == "students" ) {
            $text = "site/students.php";
        }
        else if ( $site == "class" ) {
            $text = "site/class.php";
        }
		else{
            $text = "site/home.php";
        }
    }
}
if ( isset( $_SESSION[ 'loggedin' ] ) ) {
    $loggedin = $_SESSION[ 'loggedin' ];
}else
    $loggedin = false;
if ( $loggedin == true ) {
    if ( $_SESSION[ 'userrole' ] == 2 ) {
        include( "../style/header.php" );
        include( "../style/menu.php" );
        include( "../style/content.php" );
    }
} else {
    header( 'location: ../index.php' );
}

?>