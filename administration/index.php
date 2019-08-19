<?php

require( '../include/config.inc.php' );
session_start();

if ( isset( $_GET[ "site" ] ) ) {
    $site = $_GET[ 'site' ];
    if ( strlen( $site ) != 0 ) {
        if ( $site == "home" ) {
            $text = "site/home.php";
        }
        if ( $site == "update" ) {
            $text = "site/update.php";
        }
        if ( $site == "create" ) {
            $text = "site/create.php";
        }
		if ($site=="students"){
			$text="site/students.php";
		}
    }
}
if ( isset( $_SESSION[ 'loggedin' ] ) ) {
    $loggedin = $_SESSION[ 'loggedin' ];
}else
    $loggedin = false;
if ( $loggedin == true ) {
    if ( $_SESSION[ 'userrole' ] == 4 ) {
        include( "../style/header.php" );
        include( "../style/menu.php" );
        include( "../style/content.php" );
    }
} else {
    header( 'location: ../index.php' );
}

?>