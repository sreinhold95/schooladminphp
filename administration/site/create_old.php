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

if (isset($_SESSION['loggedin'])) {
	$loggedin = $_SESSION['loggedin'];
} else
	$loggedin = false;
if ($loggedin == true) {
	if ($_SESSION['userrole'] == 1) {
	}
} else {
	header('location: ../index.php');
} ?>
<div class="error_wrap">
	<div id="searchempty">Oops! Nach was soll ich suchen?</div>
	<div id="searcherror">Leider finde ich keine Schüler mit ihren werten in der Datenbank.</div>
	<div id="emptyfield">Bitte füllen Sie alle Felder korrekt aus!</div>
	<div id="val">Ungültige Zeichen in Schulnummer - nur Zahlen!</div>
	<div id="error">Fehler: Benutzer konnte nicht angelegt werden!</div>
	<div id="success">Benutzer wurde erfolgreich angelegt!</div>
</div>
<div class="table_wrap">
	<table class="table" id="user-table">
		<tr>
			<th>Status</th>
			<th>Vorname</th>
			<th>Vorname 2</th>
			<th>Nachname</th>
			<th>Nachname weitere</th>
			<th>Klasse</th>
			<th></th>
		</tr>
	</table>
</div>
<div class="left">
	<div class="add_wrap">
		<div class="box_header">Neuen Schüler anlegen</div>
		<div class="box">
			<form method="POST" action="" id="useranlegen" class="form">
				<label for="surname" class="label">Vorname:</label>
				<input class="field" type="text" size="24" maxlength="50" name="surname" id="surname">
				<label for="middlename" class="label">weitere Vornamen:</label>
				<input class="field" type="text" size="24" maxlength="50" name="middlename" id="middlename">
				<label for="givenname" class="label">Nachname:</label>
				<input class="field" type="text" size="24" maxlength="50" name="givenname" id="givenname">
				<label for="givenname" class="label">weitere Nachnamen:</label>
				<input class="field" type="text" size="24" maxlength="50" name="moregivenname" id="moregivenname">
				<label for="surname" class="label">Adresse:</label>
				<input class="field" type="text" size="24" maxlength="50" name="address" id="address">
				<label for="surname" class="label">PLZ:</label>
				<input class="field" type="text" size="24" maxlength="50" name="postalcode" id="postalcode">
				<label for="surname" class="label">Ort:</label>
				<input class="field" type="text" size="24" maxlength="50" name="town" id="town">
				<label for="givenname" class="label">Bundesland:</label>
				<input class="field" type="text" size="24" maxlength="50" name="province" id="province">
				<label for="birthdate" class="label">Geburtsdatum:</label>
				<input class="field" type="date" size="24" maxlength="50" name="birthdate" id="birthdate">
				<label for="middlename" class="label">Geburtsland:</label>
				<input class="field" type="text" size="24" maxlength="50" name="birthcountry" id="birthcountry">
				<label for="givenname" class="label">Geburtsort:</label>
				<input class="field" type="text" size="24" maxlength="50" name="birthtown" id="birthtown">
				<label for="givenname" class="label">Nationalität:</label>
				<input class="field" type="text" size="24" maxlength="50" name="nationality" id="nationality">
				<label for="birthdate" class="label">Muttersprache:</label>
				<input class="field" type="text" size="24" maxlength="50" name="family_speech" id="family_speech">
				<label for="birthdate" class="label">Religion:</label>
				<input class="field" type="text" size="24" maxlength="50" name="religion" id="religion">
				<label for="birthdate" class="label">Telefon:</label>
				<input class="field" type="text" size="24" maxlength="50" name="phone" id="phone">
				<label for="surname" class="label">Mobiltelefon:</label>
				<input class="field" type="text" size="24" maxlength="50" name="mobilephone" id="mobilephone">
				<label for="middlename" class="label">E-Mail:</label>
				<input class="field" type="text" size="24" maxlength="50" name="email" id="email">
				<label for="givenname" class="label">Schulabschluss:</label>
				<select name="graduation" id="graduation" class="field" size="1">
					<?php
					$check = $mysqli->query("SELECT * FROM graduation;");
					while ($row = mysqli_fetch_array($check)) {
						if ($row['graduation'] != "") {
							$graduation = $row['graduation'];
							$idgraduation = $row['idgraduation'];
							echo "<option value=" . $idgraduation . ">" . $graduation . "</option>";
						}
					}

					?>
				</select>
				<label for="classs" class="label">Klasse:</label>
				<?php
				echo '<select name "classs"  id="classs" class="field"size="4">';
				$check = $mysqli->query("SELECT classcode FROM class;");
				while ($row = mysqli_fetch_array($check)) {
					if ($row['classcode'] != "") {
						$graduation = $row['classcode'];
						$idgraduation = $row['classcode'];
						echo '<option value="' . $idgraduation . '">' . $graduation . '</option>';
					}
				}
				echo '</select>';
				?>
				<input type="submit" name="submit" id="submit" value="Speichern">
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	$("#useranlegen").submit(function(event) {
		var surname = $('input#surname').val();
		var middlename = $('input#middlename').val();
		var givenname = $('input#givenname').val();
		var moregivenname = $('input#moregivenname').val();
		var street = $('input#street').val();
		var town = $('input#town').val();
		var postalcode = $('input#postalcode').val();
		var address = street + ';' + postalcode + ';' + town;
		var province = $('input#province').val();
		var birthdate = $('input#birthdate').val();
		var birthtown = $('input#birthtown').val();
		var birthcountry = $('input#birthcountry').val();
		var nationality = $('input#nationality').val();
		var family_speech = $('input#family_speech').val();
		var phone = $('input#phone').val();
		var mobilephone = $('input#mobilephone').val();
		var email = $('input#email').val();
		var idgraduation = $('#graduation option:selected').val();
		var religion = $('input#religion').val();
		var classcode = $("#classs option:selected").val();
		event.preventDefault();
		$(".error_wrap").show();
		if (surname == '' || givenname == '' || birthdate == '' || classcode == '') {
			$("#emptyfield").show();
			if (isNaN(givennme)) {
				$("#emptyfield").hide();
				$("#val").show();
			}
		} else {
			$.get('function.php?add_student&surname=' + surname + '&middlename=' + middlename + '&givenname=' + givenname + '&moregivenname=' + moregivenname + '&birthdate=' + birthdate + '&token=' + token + '&address=' + address + '&province=' + province + '&birthtown=' + birthtown + '&birthcountry=' + birthcountry + '&nationality=' + nationality + '&family_speech=' + family_speech + '&phone=' + phone + '&mobilephone=' + mobilephone + '&graduation=' + idgraduation + '&email=' + email + '&religion=' + religion, function(data) {

				var jsondata = JSON.parse(data);
				if (jsondata.success) {
					$("#emptyfield").hide();
					$("#val").hide();
					$("#error").hide();
					$("#success").show();
					document.getElementById('surname').value = "";
					document.getElementById('middlename').value = "";
					document.getElementById('givenname').value = "";
					document.getElementById('moregivenname').value = "";
					document.getElementById('birthdate').value = "";
					document.getElementById('street').value = "";
					document.getElementById('postalcode').value = "";
					document.getElementById('town').value = "";
					document.getElementById('province').value = "";
					document.getElementById('birthdate').eval = "";
					document.getElementById('birthtown').value = "";
					document.getElementById('birthcountry').value = "";
					document.getElementById('nationality').value = "";
					document.getElementById('family_speech').value = "";
					document.getElementById('phone').value = "";
					document.getElementById('mobilephone').value = "";
					document.getElementById('email').value = "";
					document.getElementById8('religion').value = "";
					$.get('function.php?filter_class&filter=' + classcode, function(data) {
						console.log(data)
						var jsondata = JSON.parse(data)
						if (jsondata.success) {
							$("#searcherror").hide();
							$("#searchempty").hide();
							$.get('function.php?filter_class_result&filter=' + classcode, function(data) {
								$('#user-table').html(data);
							});
						} else {
							$("#searcherror").show();
							$("#searchempty").hide();
						}
					});
				} else {
					$("#emptyfield").hide();
					$("#val").hide();
					$("#error").show();
				}
			})
		}
	})
	$("#classs").change(function(event) {
		var classcode = $("#classs option:selected").val();
		event.preventDefault();
		if (classcode == '') {
			$("#searchempty").show();
			$("#searcherror").hide();

		} else {
			$.get('function.php?filter_class&filter=' + classcode, function(data) {
				console.log(data)
				var jsondata = JSON.parse(data)
				if (jsondata.success) {
					$("#searcherror").hide();
					$("#searchempty").hide();
					$.get('function.php?filter_class_result&filter=' + classcode, function(data) {
						$('#user-table').html(data);
					});
				} else {
					$("#searcherror").show();
					$("#searchempty").hide();
				}
			})
		}
	})
</script>