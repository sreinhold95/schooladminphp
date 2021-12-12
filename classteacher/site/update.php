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
<div class="d-flex">
	<div class="p-2">
		<div class="add_wrap">
			<?php
			if (isset($_GET["class"]))

				echo '<div class="box_header">Klasse allgemein</div>';
			else
				echo '<div class="box_header">SuS Allgemein</div>';
			?>
			<div class="box">
				<table class="table" id="side-table">
					<?php
					$query = $mysqli->query("SELECT * FROM all_students WHERE idstudents='" . $id . "'");
					if ($query->num_rows) {
						while ($get = $query->fetch_assoc()) {
							echo '<tr>';
							echo '<td>Vorname:</td>';
							echo '<td>' . $get['surname'] . '</td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td>weitere Vornamen:</td>';
							echo '<td>' . $get['middlename'] . '</td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td>Nachname:</td>';
							echo '<td>' . $get['givenname'] . '</td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td>weitere Nachnamen:</td>';
							echo '<td>' . $get['moregivenname'] . '</td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td>Geburtsdatum:</td>';
							echo '<td>' . $get['birthdate'] . '</td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td>Userrname:</td>';
							echo '<td>' . $get['username'] . '</td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td>Schulordnung:</td>';
							if ($get['houserules']==1)
								echo '<td> zugestimmt </td>';
							else if ($get['houserules']==2)
								echo '<td> Klassenordner </td>';
							else if ($get['houserules']==3)
								echo '<td> Klassenordner </td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td>EDV Nutzungsordnung:</td>';
							if ($get['edvrules']==1)
								echo '<td> zugestimmt </td>';
							else if ($get['edvrules']==2)
								echo '<td> Klassenordner </td>';
							else if ($get['edvrules']==3)
								echo '<td> Klassenordner </td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td>Datenschutzerklärung:</td>';
							if ($get['dsgvo']==1)
								echo '<td> zugestimmt </td>';
							else if ($get['dsgvo']==2)
								echo '<td> Klassenordner </td>';
							else if ($get['dsgvo']==3)
								echo '<td> Klassenordner </td>';
							echo '</tr>';	
							$classcode = $get['classcode'];
						}
					}
					?>
				</table>
			</div>
		</div>

		<div class="add_wrap">
			<div class="box_header">Klassendaten</div>
			<div class="box">
				<table class="table" id="side-table">
					<?php
					$query1 = $mysqli->query("Update class set activetoken=0 where classcode='" . $id . "'and TIMESTAMPDIFF(MINUTE,tokenactivateat, NOW())>15;");
					$query = $mysqli->query("select * from classinformationfromstudent where idstudents='" . $id . "';");
					$query1 = $mysqli->query("select * from classinformationfromstudent where idstudents='" . $id . "';");
					if ($query->num_rows) {
						while ($get = $query1->fetch_assoc()) {
							$get2 = $query1->fetch_assoc();
							echo '<tr>';
							echo '<td>Klasse:</td>';
							echo '<td>' . $get['classcode'] . '</td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td>Schulform:</td>';
							echo '<td>' . $get['schoolform'] . '</td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td>Klassenlehrer:</td>';
							if (isset($get2['teachername']))
								echo '<td>' . $get['teachername'] . ', ' . $get2['teachername'] . '</td>';
							else
								echo '<td>' . $get['teachername'] . '</td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td>Abteilung:</td>';
							echo '<td>' . $get['name'] . '</td>';
							echo '</tr>';
							echo '<td>Abteilungsleiter:</td>';
							if (isset($get['hodname']))
								echo '<td>' . $get['hodname'] . '</td>';
							else
								echo '<td></td>';
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
				<div class="box">
					<div class="box_header">Schülerinformationen</div>
					<br>
					<?php
					$query = $mysqli->query("SELECT * FROM all_students WHERE idstudents='" . $id . "'");
					if ($query->num_rows) {
						while ($get = $query->fetch_assoc()) {
					?>

							<div class="form-row">
								<div class="form-group col-sm-6">
									<label for="surname" class="label">Vorname:</label>
									<input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="surname" id="surname" value="<?php if (mb_detect_encoding($get['surname']) == "ASCII") echo utf8_decode($get['surname']);
																																						else echo $get['surname']; ?>">
								</div>
								<div class="form-group col-sm-6">
									<label for="middlename" class="label">weitere Vornamen:</label>
									<input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="middlename" id="middlename" value="<?php if (mb_detect_encoding($get['middlename']) == "ASCII") echo utf8_decode($get['middlename']);
																																								else echo $get['middlename']; ?>">
								</div>
							</div>
							<div class="form-row">
								<div class="form-group col-sm-6">
									<label class="label">Nachname:</label>
									<input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="givenname" id="givenname" value="<?php if (mb_detect_encoding($get['givenname']) == "ASCII") echo utf8_decode($get['givenname']);
																																							else echo $get['givenname']; ?>">
								</div>
								<div class="form-group col-sm-6">
									<label for="moregivenname" class="label">weitere Nachnamen:</label>
									<input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="moregivenname" id="moregivenname" value="<?php if (mb_detect_encoding($get['moregivenname']) == "ASCII") echo utf8_decode($get['moregivenname']);
																																									else echo $get['moregivenname']; ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="address" class="label">Adresse:</label>
								<input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="address" id="street" value="<?php if (mb_detect_encoding($get['address']) == "ASCII") echo utf8_decode($get['address']);
																																					else echo $get['address']; ?>">
							</div>
							<div class="form-row">
								<div class="form-group col-sm-2">
									<label for="postalcode" class="label">PLZ:</label>
									<input class="form-control form-control-sm" type="number" size="24" maxlength="50" name="postalcode" id="postalcode" onchange='settown("input#postalcode","town","true")' value="<?php echo $get['plz'] ?>">

								</div>
								<div class="form-group col-sm-5">
									<label for="town" class="label">Ort:</label>
									<input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="town" id="town" value="<?php if (mb_detect_encoding($get['ort']) == "ASCII") echo utf8_decode($get['ort']);
																																					else echo $get['ort']; ?>" readonly>
								</div>
								<div class="form-group col-sm-5">
									<label for="province" class="label">Bundesland:</label>
									<select name="province" id="province" class="form-control form-control-sm" size="1" readonly>
										<?php
										$check = $mysqli->query("SELECT * FROM province;");
										while ($row = mysqli_fetch_array($check)) {
											if ($row['2st'] != "") {
												$kzp = $row['2st'];
												$idprovince = $row['idprovince'];
												$province = $row['province'];
												if ($province == $get['province'])
													echo '<option selected="selected" value="' . $idprovince . '">' . $province . '</option>';
												else
													echo '<option value="' . $idprovince . '">' . $province . '</option>';
											}
										}
										?>
									</select>
								</div>
							</div>
							<div class="form-row">
								<div class="form-group col-sm-4">
									<label for="birthdate" class="label">Geburtsdatum:</label>
									<input class="form-control form-control-sm" type="date" size="24" maxlength="50" name="birthdate" id="birthdate" value="<?php echo utf8_decode($get['birthdate']); ?>">
								</div>
								<div class="form-group col-sm-4">
									<label for="birthcountry" class="label">Geburtsland:</label>
									<input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="birthcountry" id="birthcountry" value="<?php if (mb_detect_encoding($get['birthcountry']) == "ASCII") echo utf8_decode($get['birthcountry']);
																																									else echo $get['birthcountry']; ?>">
								</div>
								<div class="form-group col-sm-4">
									<label for="birthtown" class="label">Geburtsort:</label>
									<input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="birthtown" id="birthtown" value="<?php if (mb_detect_encoding($get['birthtown']) == "ASCII") echo utf8_decode($get['birthtown']);
																																							else echo $get['birthtown']; ?>">
								</div>
							</div>
							<div class="form-row">
								<div class="form-group col-sm-4">
									<label for="sex" class="label">Geschlecht:</label>
									<select name="sex" id="sex" class="form-control form-control-sm" size="1">
										<?php
										if ($get['geschlecht'] == "m") {
											echo '<option selected="selected" value="m">männlich</option>';
											echo '<option value="w">weiblich</option>';
											echo '<option value="d">divers</option>';
										} else if ($get['geschlecht'] == "w") {
											echo '<option selected="selected" value="w">weiblich</option>';
											echo '<option value="m">männlich</option>';
											echo '<option value="d">divers</option>';
										} else if ($get['geschlecht'] == "d") {
											echo '<option selected="selected" value="d">divers</option>';
											echo '<option value="m">männlich</option>';
											echo '<option value="w">weiblich</option>';
										} else {
											echo '<option value="d">divers</option>';
											echo '<option value="m">männlich</option>';
											echo '<option value="w">weiblich</option>';
										}
										?>
									</select>
								</div>
								<div class="form-group col-sm-4">
									<label for="nationality" class="label">Staatsangehörigkeit:</label>
									<select name="nationality" id="nationality" class="form-control form-control-sm" size="1">
										<?php
										$check = $mysqli->query("SELECT 2st,Land FROM nationality;");
										while ($row = mysqli_fetch_array($check)) {
											if ($row['2st'] != "") {
												$lkz = $row['2st'];
												$Land = $row['Land'];
												if ($Land == $get['nationality'])
													echo '<option selected="selected" value="' . $lkz . '">' . $Land . '</option>';
												else
													echo '<option value="' . $lkz . '">' . $Land . '</option>';
											}
										}
										?>
									</select>
								</div>
								<div class="form-group col-sm-4">
									<label for="family_speech" class="label">Muttersprache:</label>
									<input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="family_speech" id="family_speech" value="<?php if (mb_detect_encoding($get['family_speech']) == "ASCII") echo utf8_decode($get['family_speech']);
																																									else echo $get['family_speech']; ?>">

								</div>
							</div>
							<div class="form-row">
								<div class="form-group col-sm-6">
									<label for="religion" class="label">Religion:</label>
									<input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="religion" id="religion" value="<?php if (mb_detect_encoding($get['religion']) == "ASCII") echo utf8_decode($get['religion']);
																																							else echo $get['religion']; ?>">
								</div>
								<div class="form-group col-sm-6">
									<label for="email" class="label">in Deutschland seit:</label>
									<input class="form-control form-control-sm" type="date" size="24" maxlength="50" name="email" id="indeutschlandseit" value="<?php echo $get['indeutschlandseit']; ?>">
								</div>
							</div>
							<div class="form-row">
								<div class="form-group col-sm-6">
									<label for="phone" class="label">Telefon:</label>
									<input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="phone" id="phone" value="<?php echo $get['phone']; ?>">
								</div>
								<div class="form-group col-sm-6">
									<label for="mobilephone" class="label">Mobiltelefon:</label>
									<input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="mobilephone" id="mobilephone" value="<?php echo $get['mobilephone']; ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="email" class="label">Email:</label>
								<input class="form-control form-control-sm" type="text" size="24" maxlength="100" name="email" id="email" value="<?php echo $get['email']; ?>">
							</div>
							<div class="form-row">
								<div class="form-group col-sm-6">
									<label for="sprachniveau" class="label">Sprachniveau:</label>
									<select name="sprachniveau" id="sprachniveau" class="form-control form-control-sm" size="1">
										<option value="00" <?php if ($get['sprachniveau'] == "00") echo 'selected'; ?>>00 - noch nicht bekannt</option>
										<option value="A0" <?php if ($get['sprachniveau'] == "A0") echo 'selected'; ?>>A0 - kein deutsch</option>
										<option value="A1" <?php if ($get['sprachniveau'] == "A1") echo 'selected'; ?>>A1 - Kann vertraute, alltägliche Ausdrücke und ganz einfache Sätze verstehen und verwenden, die auf die Befriedigung konkreter Bedürfnisse zielen. </option>
										<option value="A2" <?php if ($get['sprachniveau'] == "A2") echo 'selected'; ?>>A2 - Kann Sätze und häufig gebrauchte Ausdrücke verstehen, die mit Bereichen von ganz un-mittelbarer Bedeutung zusammenhängen </option>
										<option value="B1" <?php if ($get['sprachniveau'] == "B1") echo 'selected'; ?>>B1 - Kann die Hauptpunkte verstehen, wenn klare Standardsprache verwendet wird und wenn es um vertraute Dinge aus Arbeit, Schule, Freizeit usw. geht.</option>
										<option value="B2" <?php if ($get['sprachniveau'] == "B2") echo 'selected'; ?>>B2 - Kann die Hauptinhalte komplexer Texte zu konkreten und abstrakten Themen verstehen; versteht im eigenen Spezialgebiet auch Fachdiskussionen. </option>
										<option value="C1" <?php if ($get['sprachniveau'] == "C1") echo 'selected'; ?>>C1 - Kann praktisch fast alles, was er/sie liest oder hört, mühelos verstehen. </option>
										<option value="C2" <?php if ($get['sprachniveau'] == "C2") echo 'selected'; ?>>C2 - Kann praktisch alles, was er/sie liest oder hört, mühelos verstehen. </option>

									</select>
								</div>

								<div class="form-group col-sm-6">
									<label for="classc" class="label">Klasse:</label>
									<input class="form-control form-control-sm" type="text"  readonly size="24" maxlength="100" name="classc" id="classc" value="<?php echo $get['classcode']; ?>">
									<?php
									/*echo '<select name "classs"  id="classs" class="form-control form-control-sm size="1">';
									$check = $mysqli->query("SELECT classcode FROM class;");
									while ($row = mysqli_fetch_array($check)) {
										if ($row['classcode'] != "") {
											$graduation = $row['classcode'];
											$idgraduation = $row['classcode'];
											if ($row['classcode'] == $get['classcode'])
												echo '<option value="' . $graduation . '" selected=selected>' . $graduation . '</option>';
											else
												echo '<option value="' . $graduation . '">' . $graduation . '</option>';
										}
									}
									echo '</select>';*/
									?>
								</div>
							</div>

							<div class="form-row">
								<div class="form-group col-sm-6">
									<label for="entryDate" class="label">Eintrittsdatum:</label>
									<input type="date" class="form-control form-control-sm" name=entryDate id="entryDate" value="<?php echo $get["entryDate"]; ?>">
								</div>
								<div class="form-group col-sm-6">
									<label for="exitDate" class="label">Austrittsdatum:</label>
									<input type="date" class="form-control form-control-sm" name="exitDate" id="exitDate" value="<?php echo $get["exitDate"]; ?>">
								</div>
							</div>
							<div class="form-row">
								<div class="form-group col-sm-6">
									<label class="label">Ausbildungsbeginn:</label>
									<input class="form-control form-control-sm" type="date" size="24" maxlength="50" name="ausbildungsbeginn" id="ausbildungsbeginn" value="<?php echo $get['Ausbildungsbeginn']; ?>">
								</div>
								<div class="form-group col-sm-6">
									<label for="religion" class="label">Ausbildungsberuf:</label>
									<select name="Ausbildungsberuf" id="Ausbildungsberuf" class="form-control form-control-sm" size="1" onchange='tabellen_none("#Ausbildungsberuf option:selected","Ausbildungsbetrieb-table","<?php echo $apikey ?>")'>
										<?php
										echo '<option value="" selected disabled hidden>Bitte auswählen</option>';
										$check = $mysqli->query("SELECT * from beruf;");
										while ($row = mysqli_fetch_array($check)) {
											if ($row['Berufs_ID'] != "") {
												$berufsid = $row['Berufs_ID'];
												$Berufbez = $row['Berufbez'];
												if ($berufsid == $get['idberuf'])
													echo '<option selected=selected value="' . $berufsid . '">' . $Berufbez . '</option>';
												else
													echo '<option value="' . $berufsid . '">' . $Berufbez . '</option>';
											}
										}
										?>
									</select>
								</div>
							</div>
				</div>


				<?php
							if ($get['Schulform'] == "Teilzeit") {
								echo '<div class="box" id="Ausbildungsbetrieb">
										<div class="box_header" >Informationen zum Ausbildungsbetrieb</div>';
							} else
								echo '<div class="box" id="Ausbildungsbetrieb" style="display:none">
										<div class="box_header">Informationen zum Ausbildungsbetrieb</div>';


				?>
				<br>
				<div class="form-group">
					<label for="ausbildungsbetrieb_name" class="label">Name:</label>
					<input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="ausbildungsbetrieb_name" id="ausbildungsbetrieb_name" value="<?php if (mb_detect_encoding($get['Ausbildungsbetrieb']) == "ASCII") echo utf8_decode($get['Ausbildungsbetrieb']);
																																										else echo $get['Ausbildungsbetrieb']; ?>">
				</div>
				<div class="form-group">
					<label for="ausbildungsbetrieb_strasse" class="label">Straße:</label>
					<input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="ausbildungsbetrieb_name" id="ausbildungsbetrieb_strasse" value="<?php if (mb_detect_encoding($get['Ausbildungsbetrieb_Strasse']) == "ASCII") echo utf8_decode($get['Ausbildungsbetrieb_Strasse']);
																																											else echo $get['Ausbildungsbetrieb_Strasse']; ?>">
				</div>
				<div class="form-row">
					<div class="form-group col-sm-6">
						<label for="ausbildungsbetrieb_plz" class="label">PLZ:</label>
						<input class="form-control form-control-sm opt" type="number" size="24" maxlength="50" name="ausbildungsbetrieb_plz" id="ausbildungsbetrieb_plz" onchange='settown("input#ausbildungsbetrieb_plz","ausbildungsbetrieb_ort","true")' value="<?php echo $get['Ausbildungsbetrieb_PLZ'] ?>">
					</div>
					<div class="form-group col-sm-6">
						<label for="ausbildungsbetrieb_ort" class="label">Ort:</label>
						<input class="form-control form-control-sm" readonly type="text" size="24" maxlength="50" name="ausbildungsbetrieb_ort" id="ausbildungsbetrieb_ort" value="<?php if (mb_detect_encoding($get['Ausbildungsbetrieb_Ort']) == "ASCII") echo utf8_decode($get['Ausbildungsbetrieb_Ort']);
																																													else echo $get['Ausbildungsbetrieb_Ort']; ?>">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-sm-3">
						<label for="ausbildungsbetrieb_ausbilder_anrede" class="label">Ausbilder Anrede:</label>
						<select name="ausbildungsbetrieb_ausbilder_anrede" id="ausbildungsbetrieb_ausbilder_anrede" class="form-control form-control-sm" size="1">
							<?php
							if ($get["Ausbildungsbetrieb_Ausbilder_Anrede"] == "Herr") {
								echo '<option selected="selected" value="Herr">Herr</option>';
								echo '<option value="Frau">Frau</option>';
							} else {
								echo '<option value="Herr">Herr</option>';
								echo '<option selected="selected" value="Frau">Frau</option>';
							}
							?>
						</select>
					</div>
					<div class="form-group col-sm-9">
						<label class="label">Ausbilder Name:</label>
						<input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="religion" id="ausbildungsbetrieb_ausbilder_name" value="<?php if (mb_detect_encoding($get['Ausbildungsbetrieb_Ausbilder_Name']) == "ASCII") echo utf8_decode($get['Ausbildungsbetrieb_Ausbilder_Name']);
																																										else echo $get['Ausbildungsbetrieb_Ausbilder_Name']; ?>">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-sm-4">
						<label for="ausbildungsbetrieb_email" class="label">Email:</label>
						<input class="form-control form-control-sm" type="text" size="24" maxlength="100" name="ausbildungsbetrieb_email" id="ausbildungsbetrieb_email" value="<?php echo $get['Ausbildungsbetrieb_Email']; ?>">
					</div>
					<div class="form-group col-sm-4">
						<label for="ausbildungsbetrieb_telefon" class="label">Telefon:</label>
						<input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="ausbildungsbetrieb_telefon" id="ausbildungsbetrieb_telefon" value="<?php echo $get['Ausbildungsbetrieb_Telefon']; ?>">

					</div>
					<div class="form-group col-sm-4">
						<label for="ausbildungsbetrieb_fax" class="label">Fax:</label>
						<input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="ausbildungsbetrieb_fax" id="ausbildungsbetrieb_fax" value="<?php echo $get['Ausbildungsbetrieb_Fax']; ?>">
					</div>
				</div>
		</div>
		<div class="box" id="Schulbildung">
			<div class="box_header">Abgehende Schule</div>

			<div class="form-group">
				<label for="lastschool" class="label">Name</label>
				<input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="lastschool" id="lastschool" value="<?php if (mb_detect_encoding($get['lastschool']) == "ASCII") echo utf8_decode($get['lastschool']);
																																			else echo $get['lastschool']; ?>">
			</div>
			<div class="form-row">
				<div class="form-group col-sm 3">
					<label for="lastschoolplz" class="label">PLZ:</label>
					<input class="form-control form-control-sm" disabled type="number" size="24" maxlength="50" name="address" id="lastschoolplz" onchange='settown("input#lastschoolplz","lastschooltown","false")' value="<?php /*echo $get['father_postalcode']*/ ?>">
				</div>
				<div class="form-group col-sm-3">
					<label for="lastschooltown" class="label">Ort</label>
					<input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="lastschooltown" id="lastschooltown" value="<?php if (mb_detect_encoding($get['lastschooltown']) == "ASCII") echo utf8_decode($get['lastschooltown']);
																																						else echo $get['lastschooltown']; ?>">
				</div>
				<div class="form-group col-sm-6">
					<label for="lastschoolprovince" class="label">Bundesland </label>
					<select name="lastschoolprovince" id="lastschoolprovince" class="form-control form-control-sm" size="1">
						<?php
							$check = $mysqli->query("SELECT * FROM province;");
							while ($row = mysqli_fetch_array($check)) {
								if ($row['2st'] != "") {
									$kzp = $row['2st'];
									$idprovince = $row['idprovince'];
									$province = $row['province'];
									if ($province == $get['lastschoolprovince'])
										echo '<option selected="selected" value="' . $province . '">' . $province . '</option>';
									else
										echo '<option value="' . $province . '">' . $province . '</option>';
								}
							}
						?>
					</select>
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col-sm-6">
					<label class="label">Abgang:</label>
					<input class="form-control form-control-sm" type="date" size="24" maxlength="50" name="lastschooldate" id="lastschooldate" value="<?php echo $get['lastschooldate']; ?>">
				</div>
				<div class="form-group col-sm-6">
					<label for="graduation" class="label">Schulabschluss:</label>
					<select name="graduation" id="graduation" class="form-control form-control-sm" size="1">
						<?php
							echo '<option value="" selected disabled hidden>Bitte auswählen</option>';
							$check = $mysqli->query("SELECT * FROM graduation;");
							while ($row = mysqli_fetch_array($check)) {
								if ($row['graduation'] != "") {
									$graduation = $row['graduation'];
									$idgraduation = $row['idgraduation'];
									if ($graduation == $get['graduation']) {
										echo '<option selected="selected" value=' . $idgraduation . '>' . $graduation . '</option>';
									} else
										echo "<option value=" . $idgraduation . ">" . $graduation . "</option>";
								}
							}
						?>
					</select>
				</div>
			</div>
			<div class="box" id="eltern-table" <?php if ($get['mother_postalcode'] == "00000" && $get['father_postalcode'] == "00000") {
													echo 'style="display:none"';
												} else {
												} ?>>
												<div class="box_header">zusätliche Informationen der Eltern bei Minderjährigen</div>
				<div class="form-row">
					<div class="form-group col-sm-6">
						<h6>Mutter</h6>
						<label for="mother_surname" class="label">Vorname:</label>
						<input class="form-control form-control-sm eltern" type="text" size="24" maxlength="50" name="mother_surname" id="mother_surname" value="<?php if (mb_detect_encoding($get['mother_surname']) == "ASCII") echo utf8_decode($get['mother_surname']);
																																									else echo $get['mother_surname']; ?>">
					</div>
					<div class="form-group col-sm-6">
						<h6> Vater</h6>
						<label for="father_surname" class="label">Vorname:</label>
						<input class="form-control form-control-sm eltern" type="text" size="24" maxlength="50" name="father_surname" id="father_surname" value="<?php if (mb_detect_encoding($get['father_surname']) == "ASCII") echo utf8_decode($get['father_surname']);
																																									else echo $get['father_surname']; ?>">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-sm-6">
						<label for="mother_givenname" class="label">Nachname:</label>
						<input class="form-control form-control-sm eltern" type="text" size="24" maxlength="50" name="mother_givenname" id="mother_givenname" value="<?php if (mb_detect_encoding($get['mother_lastname']) == "ASCII") echo utf8_decode($get['mother_lastname']);
																																										else echo $get['mother_lastname']; ?>">
					</div>
					<div class="form-group col-sm-6">
						<label for="father_givenname" class="label">Nachname:</label>
						<input class="form-control form-control-sm eltern" type="text" size="24" maxlength="50" name="father_givenname" id="father_givenname" value="<?php if (mb_detect_encoding($get['father_lastname']) == "ASCII") echo utf8_decode($get['father_lastname']);
																																										else echo $get['father_lastname']; ?>">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-sm-6">
						<label for="mother_address" class="label">Adresse:</label>
						<input class="form-control form-control-sm eltern" type="text" size="24" maxlength="50" name="mother_address" id="mother_address" value="<?php if (mb_detect_encoding($get['mother_address']) == "ASCII") echo utf8_decode($get['mother_address']);
																																									else echo $get['mother_address']; ?>">

					</div>
					<div class="form-group col-sm-6">
						<label for="addrefather_addressss" class="label">Adresse:</label>
						<input class="form-control form-control-sm eltern" type="text" size="24" maxlength="50" name="father_address" id="father_address" value="<?php if (mb_detect_encoding($get['father_address']) == "ASCII") echo utf8_decode($get['father_address']);
																																									else echo $get['father_address']; ?>">

					</div>

				</div>
				<div class="form-row">
					<div class="form-group col-sm-6">
						<label for="mother_plz" class="label">PLZ:</label>
						<input class="form-control form-control-sm eltern" type="number" size="24" maxlength="50" name="mother_plz" id="mother_plz" onchange='settown("input#mother_plz","mother_town","false")' value="<?php echo $get['mother_postalcode'] ?>">

					</div>
					<div class="form-group col-sm-6">
						<label for="father_plz" class="label">PLZ:</label>
						<input class="form-control form-control-sm eltern" type="number" size="24" maxlength="50" name="father_plz" id="father_plz" onchange='settown("input#father_plz","father_town","false")' value="<?php echo $get['father_postalcode'] ?>">
					</div>

				</div>
				<div class="form-row">
					<div class="form-group col-sm-6">
						<label for="mother_town" class="label">Ort:</label>
						<input class="form-control form-control-sm eltern" type="text" size="24" maxlength="50" name="mother_town" id="mother_town" value="<?php if (mb_detect_encoding($get['mother_town']) == "ASCII") echo utf8_decode($get['mother_town']);
																																							else echo $get['mother_town']; ?>">

					</div>
					<div class="form-group col-sm-6">
						<label for="father_town" class="label">Ort:</label>
						<input class="form-control form-control-sm eltern" type="text" size="24" maxlength="50" name="father_town" id="father_town" value="<?php if (mb_detect_encoding($get['father_town']) == "ASCII") echo utf8_decode($get['father_town']);
																																							else echo $get['father_town']; ?>">

					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-sm-6">
						<label for="mother_phone" class="label">Telefon:</label>
						<input class="form-control form-control-sm eltern" type="text" size="24" maxlength="50" name="mother_phone" id="mother_phone" value="<?php echo $get['mother_phone']; ?>">
					</div>
					<div class="form-group col-sm-6">
						<label for="father_phone" class="label">Telefon:</label>
						<input class="form-control form-control-sm eltern" type="text" size="24" maxlength="50" name="father_phone" id="father_phone" value="<?php echo $get['father_phone']; ?>">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-sm-6">
						<label for="mother_mobilephone" class="label">Mobiltelefon:</label>
						<input class="form-control form-control-sm eltern" type="text" size="24" maxlength="50" name="mother_mobilephone" id="mother_mobilephone" value="<?php echo $get['mother_mobilephone']; ?>">
					</div>
					<div class="form-group col-sm-6">
						<label for="father_mobilephone" class="label">Mobiltelefon:</label>
						<input class="form-control form-control-sm eltern" type="text" size="24" maxlength="50" name="father_mobilephone" id="father_mobilephone" value="<?php echo $get['father_mobilephone']; ?>">
					</div>
				</div>
			</div>
			<div class="box">
				<div class="box_header">Status</div>

				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="status" id="active" value="1" <?php if ($get['active'] == 1) {
																											echo "CHECKED";
																										} ?>>
					<label class="form-check-label" for="activate">
						aktiver Schüler
					</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="status" id="deactive" value="0" <?php if ($get['active'] == 0) {
																											echo "CHECKED";
																										} ?>>
					<label class="form-check-label" for="activate">
						ehemaliger Schüler
					</label>
				</div>
			</div>
			<input type="button" value="Zurück" onClick="javascript:history.back()">
			<input type="submit" name="submit" id="submit" value="Änderungen Übernehmen">
			</form>
	<?php
							$idparents = $get['idparents'];
						}
					} ?>
		</div>
	</div>