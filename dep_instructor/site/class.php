<?php
$session_timeout = 600; // 1800 Sek./60 Sek. = 10 Minuten
session_start();
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
    header( 'location: ../index.php' );
}?>

<div class="d-flex">
	<div class="p-2">
		<div class="search_wrap">
			<div class="box_header">Klasse auswählen</div>
				<div class="box">
					<select name="classs" id=classs class="field" size="1">
						<option selected="selected" value="alle">Alle Klassen</option>
						<?php
						$check = $mysqli->query( "SELECT * FROM classdepartment where headidteacher='".$_SESSION['idteacher']."';" );
						while ( $row = mysqli_fetch_array( $check ) ) {
							if ( $row[ 'classcode' ] != "" ) {
								$classcode = $row[ 'classcode' ];
							} else
								$classcode = "noclass";
							echo "<option value=" . $classcode . ">" . $classcode . "</option>";
						}
						?>
					</select>
				</div>
		</div>
	</div>
	<div class="p-2">
		<div class="content_allg">
			<table class="table table-striped" id="dep_classes">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">öffnen</th>
						<th scope="col">Klasse</th>
						<th scope="col">Langname</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
