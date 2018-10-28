<?php

require( '../include/config.inc.php' );
session_start();

if ( isset( $_GET[ "site" ] ) ) {
    $site = $_GET[ 'site' ];
    if ( strlen( $site ) != 0 ) {
        if ( $site == "home" ) {
            $text = "site/admin.php";
        }
        if ( $site == "update" ) {
            $text = "site/update.php";
        }
        if ( $site == "doc" ) {
            $text = "site/doc.php";
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
        include( "../style/content.php" );
    }
} else {
    header( 'location: ../index.php' );
}

?>