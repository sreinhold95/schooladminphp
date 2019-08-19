<?php
if ( isset( $_SESSION[ 'loggedin' ] ) ) {
    $loggedin = $_SESSION[ 'loggedin' ];
}else
    $loggedin = false;
if ( $loggedin == true ) {
    if ( $_SESSION[ 'userrole' ] == 1 ) {
        
    }
} else {
    header( 'location: ../index.php' );
}


?>

<div id="table_wrap">
    <div class="container">
        <div class="row">
            <form class="form-horizontal" action="function.php" method="post" name="vornachklassegebdat" enctype="multipart/form-data">
                <fieldset>
                    <!-- Form Name -->
                    <legend>Import SuS Vorname,Nachname,Geburtsdatum,Klasse</legend>
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
            <form class="form-horizontal" action="" method="post" name="susmitbetrieb">
                <fieldset>
                    <!-- Form Name -->
                    <legend>Import SuS Nachname,Vorname,Klasse,Geburtsdatum, Betrieb</legend>
                    <!-- File Button -->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="filebutton">Suche Datei</label>
                        <div class="col-md-4">
                            <input type="file" name="file" id="submit" class="input-large" accept=".csv">
                        </div>
                    </div>
                    <!-- Button -->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="singlebutton">Import SuS</label>
                        <div class="col-md-4">
                            <button type="submit" id="submit" name="submit" class="btn btn-primary button-loading" data-loading-text="Loading...">Import</button>
                        </div>
                    </div>
                </fieldset>
            </form>
            <form class="form-horizontal" action="function.php" method="post" name="upload_excel" enctype="multipart/form-data">
                <fieldset>
                    <!-- Form Name -->
                    <legend>Import der LUSD Daten </legend>
                    <!-- File Button -->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="filebutton">Suche Datei</label>
                        <div class="col-md-4">
                            <input type="file" name="file" id="file" class="input-large"  accept=".csv">
                        </div>
                    </div>
                    <!-- Button -->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="singlebutton">Import SuS</label>
                        <div class="col-md-4">
                            <button type="submit" id="submit" name="submit" class="btn btn-primary button-loading" data-loading-text="Loading...">Import</button>
                        </div>
                    </div>
                </fieldset>
            </form> 
        </div>
    </div>
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