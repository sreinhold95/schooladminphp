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
?>
<div class="error_wrap">
	<div id="searchempty">Oops! Nach was soll ich suchen?</div>
	<div id="searcherror">Leider finde ich keine Schüler mit ihren werten in der Datenbank.</div>
	<div id="emptyfield">Bitte füllen Sie alle Felder korrekt aus!</div>
	<div id="deleteuser">User konnte nicht gelöscht werden!</div>
	<div id="val">Ungültige Zeichen in Schulnummer - nur Zahlen!</div>
	<div id="error">Fehler: Benutzer konnte nicht angelegt werden!</div>
	<div id="success">Action erfolgreich ausgeführt</div>
</div>
<div class="content_allg">
	<h1>Herzlich wilkommen</h1>
	Dieses Programm dient dazu die Stammdaten (Vollzeit/Teilzeit) der SuS digital zu erfassen.<br>
	Die Trennung der Schulform erfolgt automatisch aufgrund der Berufsbezeichnung.
</div>
<div class="search_wrap" style="display:none">>
	<div class="box_header">box 1</div>
	<div class="box">
	</div>
	<br>
	<div class="box_header">Box2</div>
	<div class="box">
	</div>
</div>