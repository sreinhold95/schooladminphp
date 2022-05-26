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
    header( 'location: ../logout.php' );
}
?>

<div class="row">
    <form class="form-horizontal" action="function.php" method="post" name="importsusuvngk" enctype="multipart/form-data">
        <fieldset">
            <!-- Form Name -->
            <legend>Vorname,Nachname,Klasse,Geburtsdatum</legend>
            <!-- File Button -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="filebutton">Datei wählen</label>
                <div class="col-md-4">
                    <input type="file" name="file" id="file" class="input-large" accept=".csv">
                </div>
            </div>
            <!-- Button -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="singlebutton">Import data</label>
                <div class="col-md-4">
                    <button type="submit" id="submit" name="importsusuvngk" class="btn btn-primary button-loading" data-loading-text="Loading...">Import</button>
                </div>
            </div>
        </fieldset>
    </form>
</div>