<?php
session_start();
$session_timeout = 600; // 1800 Sek./60 Sek. = 10 Minuten
if (!isset($_SESSION['last_visit'])) {
	$_SESSION['last_visit'] = time();
	// Aktion der Session wird ausgeführt
}
if ((time() - $_SESSION['last_visit']) > $session_timeout) {
	session_destroy();
	session_unset();
	header('location: ../admin/logout.php');
	// Aktion der Session wird erneut ausgeführt
}
$_SESSION['last_visit'] = time();

if (isset($_SESSION['loggedin'])) {
	$loggedin = $_SESSION['loggedin'];
} else
	$loggedin = false;
if ($loggedin == true) {
	if ($_SESSION['userrole'] == 1) {
	}
} else {
	session_destroy();
	session_unset();
	header('location: ../admin/logout.php');
} ?>
<div class="error_wrap">
	<div id="searchempty">Oops! Nach was soll ich suchen?</div>
	<div id="searcherror">Leider finde ich keine Schüler mit ihren werten in der Datenbank.</div>
	<div id="emptyfield">Bitte füllen Sie alle Felder korrekt aus!</div>
	<!--<div id="deleteuser">User konnte nicht gelöscht werden!</div>-->
	<div id="val">Ungültige Zeichen in Schulnummer - nur Zahlen!</div>
	<div id="error">Fehler: Benutzer konnte nicht angelegt werden!</div>
	<div id="success">Action erfolgreich ausgeführt</div>
</div>
<div class="d-flex">
	<div class="p-2 search_wrap">
		<div class="box_header">SuS suchen</div>
		<div class="box">
			<form method="POST" action="" id="search_student" class="form">
				<input class="field" type="text" size="24" maxlength="50" name="search" id="search">
				<input type="submit" name="submit" id="submit" value="Suchen">
			</form>
		</div>
		<br>
		<div class="box_header">Klasse auswählen</div>
		<div class="box">
			<select name="classs" id=classs class="field" size="1">
				<option selected="selected" value="alle">Alle Klassen</option>
				<?php
				$check = $mysqli->query("SELECT * FROM class;");
				while ($row = mysqli_fetch_array($check)) {
					if ($row['classcode'] != "") {
						$classcode = $row['classcode'];
					} else
						$classcode = "noclass";
					echo "<option value=" . $classcode . ">" . $classcode . "</option>";
				}
				?>
			</select>
		</div>
	</div>
	<div class="p-2 content_allg">
		<table class="table table-striped" id="students">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">Aktiv</th>
					<th scope="col">Vorname</th>
					<!-- <th scope="col">Vorname 2</th> -->
					<th scope="col">Nachname</th>
					<!-- <th scope="col">Nachname weitere</th> -->
					<th scope="col">Klasse</th>
					<th scope="col">bearbeiten</th>
					<th scope="col">drucken</th>
					<th scope="col">deaktivieren</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>