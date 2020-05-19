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
header( 'location: ../index.php' );
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
<div class="row">
    <form class="form-horizontal" action="function.php" method="post" name="vornachklassegebdat" enctype="multipart/form-data">
        <fieldset>
            <!-- Form Name -->
            <legend>Import SuS Vorname, Nachname, Geburtsdatum, Klasse</legend>
            <!-- File Button -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="filebutton">Suche Datei</label>
                <div class="col-md-4">
                    <input type="file" name="file" id="vornachklassegebdatfile" class="input-large" accept=".csv">
                </div>
            </div>
            <!-- Button -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="singlebutton">Import SuS</label>
                <div class="col-md-4">
                    <button type="submit" id="submit" name="importsus" class="btn btn-primary button-loading" data-loading-text="Loading...">Import</button>
                </div>
            </div>
        </fieldset>
    </form>
    <form class="form-horizontal" action="function.php" method="post" name="susmitbetrieb"  enctype="multipart/form-data">
        <fieldset>
            <!-- Form Name -->
            <legend>Import SuS Nachname, Vorname, Geburtsdatum, Klasse, Berufskürzel, entryDate, Betrieb, Betrieb_PLZ, Betrieb_Email</legend>
            <!-- File Button -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="filebutton">Suche Datei</label>
                <div class="col-md-4">
                    <input type="file" name="file" id="susbetriebfile" class="input-large" accept=".csv">
                </div>
            </div>
            <!-- Button -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="singlebutton">Import SuS</label>
                <div class="col-md-4">
                    <button type="submit" id="submit" name="susbetrieb" class="btn btn-primary button-loading" data-loading-text="Loading...">Import</button>
                </div>
            </div>
        </fieldset>
    </form>
</div>
    

<script>

$( "#vornachklassegebdat" ).submit(
    function ( event ) {
        event.preventDefault();
        var file = document.getElementById("vornachklassegebdatfile").files[0];
        var reader = new FileReader();
        content = reader.readAsText(file);
        console.log(content);
    }

)
    


</script>