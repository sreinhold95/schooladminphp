<?php
$session_timeout = 600; // 1800 Sek./60 Sek. = 10 Minuten
session_start();
if((time() - $_SESSION['last_visit']) > $session_timeout) {
session_destroy();
session_unset();
setcookie("userid", "", 0, "/", $domain);
setcookie("username","" , 0, "/", $domain);
setcookie("userrole","" , 0, "/", $domain);
setcookie("uuid", "", 0, "/", $domain);
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
    header( 'location: ../index.php' );
}
?>
<div class="d-flex">
	<div class="p-2 search_wrap">
		<div class="box_header">Klasse auswählen</div>
			<div class="box">
				<select name="classs" id=classs class="field" size="1">
				<option selected="selected" value="alle">Alle Klassen</option>
					<?php
					$check = $mysqli->query( "SELECT * FROM class;" );
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
	<div class="p-2 content_allg">
		<table class="table table-striped" id="classes">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">Klasse</th>
					<th scope="col">Langname</th>
					<th scope="col">öffnen</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>