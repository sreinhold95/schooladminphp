<?php
session_start();
$session_timeout = 600; // 1800 Sek./60 Sek. = 10 Minuten
if (!isset($_SESSION['last_visit'])) {
$_SESSION['last_visit'] = time();
// Aktion der Session wird ausgeführt
}
if((time() - $_SESSION['last_visit']) > $session_timeout) {
session_destroy();
session_unset();
header( 'location: ../logout.php' );
// Aktion der Session wird erneut ausgeführt
}
$_SESSION['last_visit'] = time();

if ( isset( $_SESSION[ 'loggedin' ] ) ) {
    $loggedin = $_SESSION[ 'loggedin' ];
}else
    $loggedin = false;
if ( $loggedin == true ) {
    if ( $_SESSION[ 'userrole' ] == 1 ) {
        
    }
} else {
    session_destroy();
    session_unset();
    header( 'location: ../index.php' );
}
?>
<div class="d-flex">
    <div class="p-2 content_allg">
        <button class="btn btn-primary export" id="exportcsv" type="button">Download SuS</button>
        <div class="students" id="students"></div>
    </div>
    <div class="p-2 content_allg">
        <button class="btn btn-primary export" id="exportcsv1" type="button">Download LuL</button>
        <div class="students1" id="students1"></div>
    </div>
</div>