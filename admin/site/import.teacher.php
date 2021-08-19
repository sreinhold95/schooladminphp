<?php
session_start();
setlocale(LC_ALL, 'de_DE.utf8');
header('Content-Type: text/html; charset=UTF-8');
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

<div id="table_wrap">
    <div class="container">
        <div class="row">
            <form class="form-horizontal" action="function.php" method="post" name="import_teacher" enctype="multipart/form-data">
                <fieldset>
                    <!-- Form Name -->
                    <legend>Import der Lehrerdaten</legend>
                    <!-- File Button -->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="filebutton">Select File</label>
                        <div class="col-md-4">
                            <input type="file" name="file" id="file" class="input-large">
                        </div>
                    </div>
                    <!-- Button -->
                    <div class="form-group">
                        <!-- <label class="col-md-4 control-label" for="singlebutton">Import data</label> -->
                        <div class="col-md-4">
                            <button type="submit" id="submit" name="import_teacher" class="btn btn-primary button-loading" data-loading-text="Loading...">Import</button>
                        </div>
                    </div>
                </fieldset>
            </form>

        </div>
    </div>
</div>