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
// create empty variable to be filled with export data
$csv_export = '';

$query=$mysqli->query("select * from lanis_import_nachname_vorname;");
$csv_filename='lanis_datensync'.date('d-m-Y_MM:HH').'.csv';
$field = $query->field_count;
// create line with field names
for($i = 0; $i < $field; $i++) {
    $csv_export.= mysqli_fetch_field_direct($query, $i)->name.';';
}
// newline (seems to work both on Linux & Windows servers)
$csv_export.= '
';
// loop through database query and fill export variable
while($row = mysqli_fetch_array($query)) {
    // create line with field values
    for($i = 0; $i < $field; $i++) {
        $csv_export.= '"'.$row[mysqli_fetch_field_direct($query, $i)->name].'";';
    }
    $csv_export.= '
';
}
// Export the data and prompt a csv file for download
// Export the data and prompt a csv file for download
header('Content-Encoding: UTF-8');
header('Content-type: text/csv; charset=UTF-8');
header("Content-Disposition: attachment; filename=".$csv_filename."");
//echo "\xEF\xBB\xBF"; // UTF-8 BOM
echo($csv_export);
?>