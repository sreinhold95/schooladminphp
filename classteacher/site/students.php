<?php
$session_timeout = 600; // 1800 Sek./60 Sek. = 10 Minuten
session_start();
if((time() - $_SESSION['last_visit']) > $session_timeout) {
session_destroy();
session_unset();
header( 'location: ../logout.php' );
// Aktion der Session wird erneut ausgeführt
}
$_SESSION['last_visit'] = time();
?>
<div class="error_wrap">
	<div id="searchempty">Oops! Nach was soll ich suchen?</div>
	<div id="searcherror">Leider finde ich keine Schüler mit ihren werten in der Datenbank.</div>
	<div id="emptyfield">Bitte füllen Sie alle Felder korrekt aus!</div>
	<div id="val">Ungültige Zeichen in Schulnummer - nur Zahlen!</div>
	<div id="error">Fehler: Benutzer konnte nicht angelegt werden!</div>
	<div id="success">Action erfolgreich ausgeführt</div>
</div>
<div class="d-flex">
	<div class="p-2">
		<div class="add_wrap">
			<div class="box_header">Klasse auswählen</div>
			<div class="box">
				<select name= "classs" id=classs class="field" size="1">
				<option selected="selected" value="alle">Alle Klassen</option>
					<?php
					$check = $mysqli->query( "SELECT * FROM classteacher where idteacher='".$_SESSION['idteacher']."';" );
					while ( $row = mysqli_fetch_array( $check ) ) {
						if ( $row[ 'classcode' ] != "" ) {
							$classcode = $row[ 'classcode' ];
						} else
							$classcode = "noclass";
						echo "<option value=" . $classcode . ">" . $classcode ."</option>";
					}
					?>
				</select>
			</div>
		</div>
		<div class="add_wrap">
            <div class="box_header">Download</div>
            <div class="box">
                <button id="download-xlsx" type="button" class="btn btn-primary btn-sm">Download Excel</button>
            </div>
        </div>
	</div>
	<div class=" p-2">
		<div class="content_allg">
			<table class="table" id="students">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th>Aktiv</th>
						<th>bearbeiten</th>
						<th>drucken</th>
						<th>Vorname</th>
						<th>Nachname</th>
						<th>Klasse</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>