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
	header('location: ../logout.php');
	// Aktion der Session wird erneut ausgeführt
}
$_SESSION['last_visit'] = time();

$id = $_GET['id'];
$classcode = "";
$activetoken = "";
$classteacher = "";
$idparents = 0;
if (isset($_GET['idteacher'])) {
	$idteacher = $_GET["idteacher"];
	$_SESSION['idteacher'] = 0;
} else if (isset($_GET['id'])) {
	$id = $_GET['id'];
} else {
	$_SERVER['HTTP_REFERER'];
	header('Location:' . $_SERVER['HTTP_REFERER']);
}
?>

<div class="error_wrap">
	<div id="emptyfield">Bitte füllen Sie diese Felder korrekt aus:</div>
	<div id="error_username">Ohne Benutzername ist ein zurücksetzen nicht möglich!</div>
	<div id="val">Ungültige Zeichen in Schulnummer - nur Zahlen!</div>
	<div id="error">Fehler: Benutzer konnte nicht aktualisiert werden!</div>
	<div id="success">Benutzerdaten erfolgreich aktualisiert.</div>
	<div id="password_reset_success">Das Passwort wurde auf das Erstpasswort zurück gesetzt.</div>
</div>
<div class="d-flex">
	<div class="p-2">
		<div class="add_wrap">
			<div class="box">
				<div class="box_header">Lehrkraft</div>
				<table class="table" id="side-table">
				<?php
					$query = $mysqli->query("select * from adminteacher where idteacher='" . $idteacher . "';");
					$query1 = $mysqli->query("select classcode from classinformation where idteacher='" . $idteacher . "';");
					if ($query->num_rows) {
						while ($get = $query->fetch_assoc()) {
							echo '<tr>';
							echo '<td>Vorname:</td>';
							echo '<td>' . $get['surname'] . '</td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td>Nachname:</td>';
							echo '<td>' . $get['lastname'] . '</td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td>Klassenlehrer in:</td>';
							echo '<td>';
							while ($get1 = $query1->fetch_assoc()) {
								
								echo $get1['classcode'].', ';
								
							}
							echo '</td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td>Schule:</td>';
							echo '<td>' . $get['school'] . '</td>';
							echo '</tr>';
							
						}
					}
					?>
				</table>
			</div>
			<div class="box">
				<div class="box_header">Abteilungsleiter</div>
				<table class="table" id="side-table">
				<?php
					$query = $mysqli->query("select name from headofdepartment where idteacher='" . $idteacher . "';");
					if ($query->num_rows) {
						while ($get = $query->fetch_assoc()) {
							echo '<tr>';
							echo '<td>Abteilungsname:</td>';
							echo '<td>' . $get['name'] . '</td>';
							echo '</tr>';							
						}
					}
					?>
				</table>
			</div>
		</div>
	</div>
	<div class="p-2">
	
		<div class="content_allg">
			<form method="POST" action="" id="adminuser">
				<?php
					$query = $mysqli->query("SELECT * FROM teacherinf WHERE idteacher='" . $idteacher . "'");
					if ($query->num_rows) {
						$i = 0;
						while ($get = $query->fetch_assoc()) {
							
				?>
				<div class="box_header">Lehrkraft</div>
				<div class="form-row">
					<div class="form-group col-sm-6">
						<label for="surname" class="label">Vorname:</label>
						<input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="surname" id="surname" value="<?php echo $get['Vorname']; ?>">
					</div>
					<div class="form-group col-sm-6">
						<label for="middlename" class="label">weitere Vornamen:</label>
						<input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="middlename" id="middlename" value="<?php echo $get['middlename']; ?>">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-sm-6">
						<label for="lastname" class="label">Nachname:</label>
						<input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="lastname" id="lastname" value="<?php echo $get['Nachname']; ?>">
					</div>
					<div class="form-group col-sm-6">
						<label for="moregivenname" class="label">weitere Nachnamen:</label>
						<input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="moregivenname" id="moregivenname" value="<?php echo $get['moregivenname']; ?>">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-sm-6">
						<label for="initials" class="label">Kürzel:</label>
						<input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="initials" id="initials" value="<?php echo $get['initials']; ?>">
					</div>
					<div class="form-group col-sm-6">
						<label for="school" class="label">Schule:</label>
							<select name="school" id="school" class="form-control form-control-sm" size="1">
								<?php
								$check = $mysqli->query("SELECT school,schoolname FROM school;");
								while ($row = mysqli_fetch_array($check)) {
									if ($row['school'] != "") {
										$schoolname = $row['schoolname'];
										$school = $row['school'];
										if ($school == $get['school'])
											echo '<option selected="selected" value="' . $school . '">' . $schoolname . '</option>';
										else
											echo '<option value="' . $school . '">' . $schoolname . '</option>';
									}
								}
								?>
							</select>
					</div>
				</div>
				<div class="box">
				<div class="box_header">Status</div>

				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="status" id="active" value="1" <?php if ($get['active'] == 1) {
																											echo "CHECKED";
																										} ?>>
					<label class="form-check-label" for="activate">
						aktive Lehrkraft
					</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="status" id="deactive" value="0" <?php if ($get['active'] == 0) {
																											echo "CHECKED";
																										} ?>>
					<label class="form-check-label" for="activate">
						ehemalige Lehrkraft
					</label>
				</div>
			</div>
				<?php
						}
					}
				?>
				<input type="submit" name="submit" id="submit" value="Speichern">
			</form>
		</div>
	</div>
</div>