<div class="error_wrap">
	<div id="searchempty">Oops! Nach was soll ich suchen?</div>
	<div id="searcherror">Leider finde ich keine Schüler mit ihren werten in der Datenbank.</div>
	<div id="emptyfield">Bitte füllen Sie alle Felder korrekt aus!</div>
	<div id="deleteuser">User konnte nicht gelöscht werden!</div>
	<div id="val">Ungültige Zeichen in Schulnummer - nur Zahlen!</div>
	<div id="error">Fehler: Benutzer konnte nicht angelegt werden!</div>
	<div id="success">Action erfolgreich ausgeführt</div>
</div>
<div class="d-flex flex-column content_allg">
<h1 class="p-2">Guten Tag im Abteilungsleiter Bereich</h1>
	<br>
	<h2 class="p-2">
	<?php
	$session_timeout = 600; // 1800 Sek./60 Sek. = 10 Minuten
	
	if((time() - $_SESSION['last_visit']) > $session_timeout) {
	session_destroy();
	session_unset();
	header( 'location: ../dep_instructor/logout.php' );
	// Aktion der Session wird erneut ausgeführt
	}
	$_SESSION['last_visit'] = time();
	 echo 'Angemeldet als: ';
	 echo $_SESSION["username"];
	?>
	</h2>
</div>
<br>
<div class="d-flex flex-column content_allg">
	<div class="p-2 row">
		<div class="col-sm">
			<h3>geänderte SuS in den letzen 30 Tagen</h3>
		</div>
	</div>
	<div class="p-2 row">
		<div class="col-sm">
		<table class="table table-striped" id="students">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">Vorname</th>
					<th scope="col">Nachname</th>
					<th scope="col">Klasse</th>
					<th scope="col">bearbeiten</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
		</div>
	</div>
</div>