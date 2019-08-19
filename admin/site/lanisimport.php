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
            <form class="form-horizontal" action="function.php" method="post" name="upload_excel" enctype="multipart/form-data">
                <fieldset>
                    <!-- Form Name -->
                    <legend>Aus LDAP_LANiS von "csv aus pftp erstellen" Userids setzen</legend>
                    <!-- File Button -->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="filebutton">Select File</label>
                        <div class="col-md-4">
                            <input type="file" name="file" id="file" class="input-large">
                        </div>
                    </div>
                    <!-- Button -->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="singlebutton">Import data</label>
                        <div class="col-md-4">
                            <button type="submit" id="submit" name="Importlanis" class="btn btn-primary button-loading" data-loading-text="Loading...">Import</button>
                        </div>
                    </div>
                </fieldset>
            </form>

        </div>
    </div>
</div>