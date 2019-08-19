<?php
	if(!isset($_SESSION[ 'userrole' ]))
		header('location: ../../index.php');
?>
<div class="error_wrap">
	<div id="searchempty">Oops! Nach was soll ich suchen?</div>
	<div id="searcherror">Leider finde ich keine Schüler mit ihren werten in der Datenbank.</div>
	<div id="emptyfield">Bitte füllen Sie alle rot hinterlegten Felder korrekt aus!</div>
	<div id="val">Ungültige Zeichen in Schulnummer - nur Zahlen!</div>
	<div id="error">Fehler: Benutzer konnte nicht angelegt werden!</div>
	<div id="success">Benutzer wurde erfolgreich angelegt!</div>
</div>
<div class="create_wrap">
	<div class="schüler_create_wrap">
		<div class="box_header">Neu</div>
		<br>
		<div class="box">
			<form method="POST" action="" id="useranlegen" class="form">
				<table class="table" id="anlegen-table">
					<tr>
						<th>Persönliche Daten Schüler</th>
						<th></th>
					</tr>
					<tr>
						<td>
							<label for="surname" class="label">Vorname:</label>
							<input class="field" type="text" size="24" maxlength="50" name="surname" id="surname">
							<label for="middlename" class="label">weitere Vornamen:</label>
							<input class="opt" type="text" size="24" maxlength="50" name="middlename" id="middlename">
							<label class="label">Nachname:</label>
							<input class="field" type="text" size="24" maxlength="50" name="givenname" id="givenname">
							<label for="moregivenname" class="label">weitere Nachnamen:</label>
							<input class="opt" type="text" size="24" maxlength="50" name="moregivenname" id="moregivenname">
							<label for="address" class="label">Adresse:</label>
							<input class="field" type="text" size="24" maxlength="50" name="address" id="street">
							<label for="PLZ" class="label">PLZ:</label>
							<!--<input class="field" type="text" size="24" maxlength="50" name="postalcode" id="postalcode">-->
							<select name= "postalcode" id="postalcode" class="field" size="1" onchange='settown("#postalcode option:selected","town","true")'>
							<?php
								//echo '<select name "classs"  id="classs" class="field"size="4">';
								echo '<option value="" selected disabled hidden>Bitte auswählen</option>';
								$check = $mysqli->query( "SELECT * FROM town;" );
								while ( $row = mysqli_fetch_array( $check ) ) {
									if ( $row[ 'plz' ] != "" ) {
										$plz=$row[ 'plz'];
										$ort = $row[ 'ort' ];
										$bundesland = $row[ 'bundesland' ];
										//if($plz=="64295")
										//	echo '<option selected="selected" value="' . $plz . '">'  . $plz ." " .$ort.   '</option>';
										//else
											echo '<option value="' . $plz . '">' . $plz ." " .$ort.  '</option>';
									} 
								}
							?>
							</select>
							<label for="surname" class="label">Ort:</label>
							<input class="field" type="text" size="24" maxlength="50" name="town" id="town">
							<label for="givenname" class="label">Bundesland:</label>
							<!--<input class="field" type="text" size="24" maxlength="50" name="province" id="province">-->
							<select name= "province" readonly id="province" class="field" size="1">
							<?php
								//echo '<select name "classs"  id="classs" class="field"size="4">';
								$check = $mysqli->query( "SELECT * FROM province;" );
								while ( $row = mysqli_fetch_array( $check ) ) {
									if ( $row[ '2st' ] != "" ) {
										$kzp=$row[ '2st'];
										$idprovince = $row[ 'idprovince' ];
										$province = $row[ 'province' ];
										if($kzp=="HE")
											echo '<option selected="selected" value="' . $idprovince . '">' . $province . '</option>';
										else
											echo '<option value="' . $idprovince . '">' . $province .'</option>';
									}
								}
							?>
							</select>
							<label for="birthdate" class="label">Geburtsdatum:</label>
							<input class="field" type="date" size="24" maxlength="50" name="birthdate" id="birthdate">
							<label for="birthcountry" class="label">Geburtsland:</label>
							<input class="field" type="text" size="24" maxlength="50" name="birthcountry" id="birthcountry">
							<label for="birthtown" class="label">Geburtsort:</label>
							<input class="field" type="text" size="24" maxlength="50" name="birthtown" id="birthtown">
						</td>
						<td>
							<label for="sex" class="label">Geschlecht:</label>
							<select name= "sex" id="sex" class="field" size="1">
							<option value="" selected disabled hidden>Bitte auswählen</option>
							<option value="m">männlich</option>
							<option value="w">weiblich</option>
							<option value="d">divers</option>
							</select>
							<label for="nationality" class="label">Staatsangehörigkeit:</label>
							<select name= "nationality" id="nationality" class="field" size="1">
							<?php
								//echo '<select name "classs"  id="classs" class="field"size="4">';
								$check = $mysqli->query( "SELECT 2st,Land FROM nationality;" );
								while ( $row = mysqli_fetch_array( $check ) ) {
									if ( $row[ '2st' ] != "" ) {
										$lkz = $row[ '2st' ];
										$Land = $row[ 'Land' ];
										if($lkz=="DE")
											echo '<option selected="selected" value="' . $lkz . '">' . $Land . '</option>';
										else
											echo '<option value="' . $lkz . '">' . $Land . '</option>';
									}
								}
							?>
							</select>
							<!--<input class="field" type="text" size="24" maxlength="50" name="nationality" id="nationality">-->
							<label for="birthdate" class="label">Muttersprache:</label>
							<input class="field" type="text" size="24" maxlength="50" name="family_speech" id="family_speech">
							<label for="religion" class="label">Religion:</label>
							<input class="field" type="text" size="24" maxlength="50" name="religion" id="religion">
							<label for="phone" class="label">Telefon:</label>
							<input class="opt" type="text" size="24" maxlength="50" name="phone" id="phone">
							<label for="mobilephone" class="label">Mobiltelefon:</label>
							<input class="opt" type="text" size="24" maxlength="50" name="mobilephone" id="mobilephone">
							<label for="email" class="label">Email:</label>
							<input class="opt" type="text" size="24" maxlength="50" name="email" id="email">
							<label for="classc" class="label">Klasse:</label>
							
							<?php
							echo '<input class="field" readonly type="text" size="24" maxlength="50" name="classc" id="classc" value="' .$_SESSION['classcode']. '">';
							?>
							<label for="email" class="label">in Deutschland seit:</label>
							<input class="opt" type="date" size="24" maxlength="50" name="email" id="indeutschlandseit">
							<label for="email" class="label">Sprachniveau Deutsch:</label>
							<select name= "sprachniveau" id="sprachniveau" class="field" size="1">
								<option value="" selected disabled hidden>Bitte auswählen</option>
								<option value="A0">00 - noch nicht bekannt</option>
								<option value="A0">A0 - kein deutsch</option>
								<option value="A1">A1 - Kann vertraute, alltägliche Ausdrücke und ganz einfache Sätze verstehen und verwenden, die auf die Befriedigung konkreter Bedürfnisse zielen. </option>
								<option value="A2">A2 - Kann Sätze und häufig gebrauchte Ausdrücke verstehen, die mit Bereichen von ganz un-mittelbarer Bedeutung zusammenhängen </option>
								<option value="B1">B1 - Kann die Hauptpunkte verstehen, wenn klare Standardsprache verwendet wird und wenn es um vertraute Dinge aus Arbeit, Schule, Freizeit usw. geht.</option>
								<option value="B2">B2 - Kann die Hauptinhalte komplexer Texte zu konkreten und abstrakten Themen verstehen; versteht im eigenen Spezialgebiet auch Fachdiskussionen. </option>
								<option value="C1">C1 - Kann praktisch fast allse, was er/sie liest oder hört, mühelos verstehen. </option>
								<option value="C2">C2 - Kann praktisch alles, was er/sie liest oder hört, mühelos verstehen. </option>

							</select>

						</td>
					</tr>
							
					<tr>
						<th>Informationen zur Ausbildung</th>
						<th></th>
					</tr>
					<tr>
						<td>
							<label class="label">Ausbildungsbeginn:</label>
							<input class="field" type="date" size="24" maxlength="50" name="ausbildungsbeginn" id="ausbildungsbeginn">
						</td>
						<td>
							<label for="religion" class="label">Ausbildungsberuf:</label>
							<select name= "Ausbildungsberuf" id="Ausbildungsberuf" class="field" size="1" onchange='tabellen_none("#Ausbildungsberuf option:selected","Ausbildungsbetrieb-table","<?php echo $apikey ?>")' >  
							<?php
								echo '<option value="" selected disabled hidden>Bitte auswählen</option>';
								$check = $mysqli->query( "SELECT * from beruf;" );
								while ( $row = mysqli_fetch_array( $check ) ) {
									if ( $row[ 'Berufs_ID' ] != "" ) {
										$berufsid=$row[ 'Berufs_ID'];
										$Berufbez = $row[ 'Berufbez' ];
										echo '<option value="' . $berufsid . '">'.$Berufbez.'</option>';
									} 
								}
							?>
							</select>
							<!--<input class="field" type="text" size="24" maxlength="50" name="Ausbildungsberuf" id="Ausbildungsberuf">-->
						</td>

					</tr>
				</table>
				<table class="table" id="Ausbildungsbetrieb-table" style="display:none" >
					<tr>
						<th>Informationen zum Ausbildungsbetrieb</th>
						<th></th>
					</tr>
					<tr>
						<td>
							<label for="birthdate" class="label">Name:</label>
							<input class="opt" type="text" size="24" maxlength="50" name="ausbildungsbetrieb_name" id="ausbildungsbetrieb_name">
						</td>
						<td>
							</td>
					</tr>
					<tr>
						<td>
							<label class="label">Straße:</label>
							<input class="opt" type="text" size="24" maxlength="50" name="ausbildungsbetrieb_name" id="ausbildungsbetrieb_strasse">
							<label class="label">Ort:</label>
							<input class="opt" readonly type="text" size="24" maxlength="50" name="religion" id="ausbildungsbetrieb_ort">
							<label class="label">Email:</label>
							<input class="opt" type="email" size="24" maxlength="50" name="religion" id="ausbildungsbetrieb_email">
						</td>
						<td>
						<label class="label">PLZ:</label>
						<select name= "ausbildungsbetrieb_plz" id="ausbildungsbetrieb_plz" class="opt" size="1" onchange='settown("#ausbildungsbetrieb_plz option:selected","ausbildungsbetrieb_ort","true")'>
							<?php
								//echo '<select name "classs"  id="classs" class="field"size="4">';
								echo '<option value="" selected disabled hidden>Bitte auswählen</option>';
								$check = $mysqli->query( "SELECT * FROM town;" );
								while ( $row = mysqli_fetch_array( $check ) ) {
									if ( $row[ 'plz' ] != "" ) {
										$plz=$row[ 'plz'];
										$ort = $row[ 'ort' ];
										$bundesland = $row[ 'bundesland' ];
										//if($plz=="62495")
											//echo '<option selected="selected" value="' . $plz . '">' . $plz . '</option>';
										//else
											echo '<option value="' . $plz . '">' . $plz ." " .$ort.  '</option>';
									} 
								}
							?>
							</select>
							<label class="label">Telefon:</label>
							<input class="opt" type="text" size="24" maxlength="50" name="ausbildungsbetrieb_telefon" id="ausbildungsbetrieb_telefon">
							<label class="label">Fax:</label>
							<input class="opt" type="text" size="24" maxlength="50" name="ausbildungsbetrieb_telefon" id="ausbildungsbetrieb_fax">
						</td>
					</tr>
					<tr>
						<td>
							<label class="label">Ausbilder Anrede:</label>
							<select name= "Ausbilder_anrede" id="ausbildungsbetrieb_ausbilder_anrede" class="opt" size="1">
							<option value="" selected disabled hidden>Bitte auswählen</option>
								<option selected="selected" value="Herr">Herr</option>
								<option value="Frau">Frau</option>
							</select>
							</td>
						<td>
							<label class="label">Ausbilder Name:</label>
							<input class="opt" type="text" size="24" maxlength="50" name="religion" id="ausbildungsbetrieb_ausbilder_name">
						</td>
					</tr>
				</table>
				<table class="table" id="Schulbildung-table">
					<tr>
							<th>abgehende Schule</th>
							<th></th>
					</tr>
					<tr>
						<td>
							<label class="label">Name</label>
							<input class="field" type="text" size="24" maxlength="50" name="lastschool" id="lastschool">
						</td>
						<td>
						</td>
					</tr>
					<tr>
						<td>
							<label class="label">Ort</label>
							<input class="field" type="text" size="24" maxlength="50" name="lastschool" id="lastschooltown">
						</td>
						<td>
							<label class="label">Bundesland </label>
							<select name= "province" readonly id="lastschoolprovince" class="field" size="1">
							<?php
								//echo '<select name "classs"  id="classs" class="field"size="4">';
								$check = $mysqli->query( "SELECT * FROM province;" );
								while ( $row = mysqli_fetch_array( $check ) ) {
									if ( $row[ '2st' ] != "" ) {
										$kzp=$row[ '2st'];
										$idprovince = $row[ 'idprovince' ];
										$province = $row[ 'province' ];
										if($kzp=="HE")
											echo '<option selected="selected" value="' . $province . '">' . $province . '</option>';
										else
											echo '<option value="' . $province . '">' . $province .'</option>';
									}
								}
							?>
							</select>
						</td>
					</tr>
					<tr id="Abgang" style="display:none">
						<td>
						<label class="label">Abgang:</label>
							<input class="field" type="date" size="24" maxlength="50" name="lastschooldate" id="lastschooldate">
					</tr>
					<tr id="Schulabschluss">
						<td>
						 <label for="graduation" class="label">Schulabschluss:</label>
							<select name="graduation" id="graduation" class="field" size="1">
								<?php
								echo '<option value="" selected disabled hidden>Bitte auswählen</option>';
								$check = $mysqli->query( "SELECT * FROM graduation;" );
								while ( $row = mysqli_fetch_array( $check ) ) {
									if ( $row[ 'graduation' ] != "" ) {
										$graduation = $row[ 'graduation' ];
										$idgraduation = $row[ 'idgraduation' ];
										if($graduation=="Teilzeit"){

										}
										else
											echo "<option value=" . $idgraduation . ">" . $graduation . "</option>";
									}
								}

								?>
							</select>
						</td>
					</tr>
				</table>

				<table class="table" id="anlegen-table" >	
					<tr>
						<th>zusätliche Informationen der Eltern</th>
						<th>bei Minderjährigen</th>
					</tr>
					<tr>
						<th>Mutter</th>
						<th>Vater</th>
					</tr>
					<tr>
						<td>
							<label for="surname" class="label">Vorname:</label>
							<input class="eltern" type="text" size="24" maxlength="50" name="surname" id="mother_surname">
							<label for="givenname" class="label">Nachname:</label>
							<input class="eltern" type="text" size="24" maxlength="50" name="givenname" id="mother_givenname">
							<label for="address" class="label">Adresse:</label>
							<input class="eltern" type="text" size="24" maxlength="50" name="address" id="mother_address">
							<label for="surname" class="label">PLZ:</label>
							<select name= "postalcode" id="mother_postalcode" class="eltern" size="1" onchange='settown("#mother_postalcode option:selected","mother_town","false")'>
							<?php
								//echo '<select name "classs"  id="classs" class="field"size="4">';
								echo '<option value="" selected disabled hidden>Bitte auswählen</option>';
								$check = $mysqli->query( "SELECT * FROM town;" );
								while ( $row = mysqli_fetch_array( $check ) ) {
									if ( $row[ 'plz' ] != "" ) {
										$plz=$row[ 'plz'];
										$ort = $row[ 'ort' ];
										$bundesland = $row[ 'bundesland' ];
										//if($plz=="62495")
										//	echo '<option selected="selected" value="' . $plz . '">' . $plz . '</option>';
										//else
											echo '<option value="' . $plz . '">' . $plz ." " .$ort.  '</option>';
									} 
								}
							?>
							</select>
							<label for="givenname" class="label">Ort:</label>
							<input class="eltern" type="text" size="24" maxlength="50" name="givenname" id="mother_town">
							<label for="address" class="label">Telefon:</label>
							<input class="eltern" type="text" size="24" maxlength="50" name="address" id="mother_phone">
							<label for="address" class="label">Mobiltelefon:</label>
							<input class="eltern" type="text" size="24" maxlength="50" name="address" id="mother_mobilephone">
						</td>
						<td>
							<label for="surname" class="label">Vorname:</label>
							<input class="eltern" type="text" size="24" maxlength="50" name="surname" id="father_surname">
							<label for="givenname" class="label">Nachname:</label>
							<input class="eltern" type="text" size="24" maxlength="50" name="givenname" id="father_givenname">
							<label for="address" class="label">Adresse:</label>
							<input class="eltern" type="text" size="24" maxlength="50" name="address" id="father_address">
							<label for="surname" class="label">PLZ:</label>
							<select name= "postalcode" id="father_postalcode" class="eltern" size="1" onchange='settown("#father_postalcode option:selected","father_town","false")'>
							<?php
								//echo '<select name "classs"  id="classs" class="field"size="4">';
								echo '<option value="" selected disabled hidden>Bitte auswählen</option>';
								$check = $mysqli->query( "SELECT * FROM town;" );
								while ( $row = mysqli_fetch_array( $check ) ) {
									if ( $row[ 'plz' ] != "" ) {
										$plz=$row[ 'plz'];
										$ort = $row[ 'ort' ];
										$bundesland = $row[ 'bundesland' ];
										//if($plz=="62495")
											//echo '<option selected="selected" value="' . $plz . '">' . $plz . '</option>';
										//else
											echo '<option value="' . $plz . '">' . $plz ." " .$ort.  '</option>';
									} 
								}
							?>
							</select>
							<label for="givenname" class="label">Ort:</label>
							<input class="eltern" type="text" size="24" maxlength="50" name="givenname" id="father_town">
							<label for="address" class="label">Telefon:</label>
							<input class="eltern" type="text" size="24" maxlength="50" name="address" id="father_phone">
							<label for="address" class="label">Mobiltelefon:</label>
							<input class="eltern" type="text" size="24" maxlength="50" name="address" id="father_mobilephone">
						</td>
					</tr>
				</table>
				<input type="submit" name="submit" id="submit" value="Speichern">
			</form>
		</div>
	</div>
</div>
<script>
$("#useranlegen").keypress(function(e) {
  //Enter key
  if (e.which == 13) {
    return false;
  }
});

	
$( "#useranlegen" ).submit( function ( event ) {
	event.preventDefault();
	error=[]

	/*var nodes = document.querySelectorAll("input[type=text]");
	for (var i=0; i<nodes.length; i++){
		if (nodes[i].value == "" ){
			nodes[i].style="background-color:red"
			error.push(nodes[i].name)
		}
			
	}*/
	var nodes = document.querySelectorAll(".field");
	for (var i=0; i<nodes.length; i++){
		if (nodes[i].value == "" ){
			nodes[i].style="background-color:red"
			error.push(nodes[i].name)
		}else{
			nodes[i].style="background-color:white"
		}
			
	}
	/*var nodes = document.querySelectorAll("select");
	for (var i=0; i<nodes.length; i++){
		if (nodes[i].value == "" ){
			nodes[i].style="background-color:red"
			error.push(nodes[i].name)
		}
			
	}*/
	if(new Date($( 'input#birthdate' ).val())-new Date(new Date().toISOString().slice(0,10))>0)
	{
		var nodes = document.querySelectorAll(".eltern");
	for (var i=0; i<nodes.length; i++){
		if (nodes[i].value == "" ){
			nodes[i].style="background-color:red"
			error.push(nodes[i].name)
		}
		else{
			nodes[i].style="background-color:white"
		}
			
	}
	}
	if(error.lenght>0){
	//if ( $( 'input#surname' ).val() == '' || $( 'input#givenname' ).val() == '' || $( 'input#birthdate' ).val() == 'TT.MM.JJJJ' ||  $( 'input#street' ).val()== '' ) {
		$( ".error_wrap" ).show();
		$( "#emptyfield" ).show();
		/*if ( isNaN( givennme ) ) {
			$( "#emptyfield" ).hide();
			$( "#val" ).show();
		}*/
	} 
	else {
		var student = {
			'surname':$( 'input#surname' ).val(),
			'middlename':$( 'input#middlename' ).val(),
			'givenname': $( 'input#givenname' ).val(),
			'moregivennname':$( 'input#moregivenname' ).val(),
			'street':$( 'input#street' ).val(),
			'postalcode':$( '#postalcode option:selected' ).val(),
			'province':$( '#province option:selected' ).val(),
			'birthdate':$( 'input#birthdate' ).val(),
			'birthtown':$( 'input#birthtown' ).val(),
			'birthcountry':$( 'input#birthcountry' ).val(),
			'nationality':$( '#nationality option:selected' ).val(),
			'sex':$( '#sex option:selected' ).val(),
			'classcode':$( "input#classc" ).val(),
			'family_speech':$( 'input#family_speech' ).val(),
			'phone':$( 'input#phone' ).val(),
			'mobilephone':$( 'input#mobilephone' ).val(),
			'email':$( 'input#email' ).val(),
			'religion':$( 'input#religion' ).val(),
			'idgraduation':$( '#graduation option:selected' ).val(),
			'idberuf':$( '#Ausbildungsberuf option:selected' ).val(),
			'lanisid':0,
			'lusdid':0,
			'active':1,
			'indeutschlandseit':$( 'input#indeutschlandseit' ).val(),
			'sprachniveau':$( '#sprachniveau option:selected' ).val(),
			'parents':{
				'mother_surname':$( 'input#mother_surname' ).val(),
				'mother_lastname':$( 'input#mother_givenname' ).val(),
				'mother_address':$( 'input#mother_address' ).val(),
				'mother_postalcode':$( '#mother_postalcode option:selected' ).val(),
				'mother_phone':$( 'input#mother_phone' ).val(),
				'mother_mobilephone':$( 'input#mother_mobilephone' ).val(),
				'father_surname':$( 'input#father_surname' ).val(),
				'father_lastname':$( 'input#father_givenname' ).val(),
				'father_address':$( 'input#father_address' ).val(),
				'father_postalcode':$( '#father_postalcode option:selected' ).val(),
				'father_phone':$( 'input#father_phone' ).val(),
				'father_mobilephone':$( 'input#father_mobilephone' ).val()
			},
			'apikey':"<?php echo $apikey ?>",
			'lastschool':$( 'input#lastschool' ).val(),
			'lastschooldate':$( 'input#lastschooldate' ).val(),
			'lastschooltown':$( 'input#lastschooltown' ).val(),
			'lastschoolprovince':$( '#lastschoolprovince option:selected' ).val(),
			'Ausbildungsbeginn':$( 'input#ausbildungsbeginn' ).val(),
			'Ausbildungsbetrieb':{
				'Name':$( 'input#ausbildungsbetrieb_name' ).val(),
				'Strasse':$( 'input#ausbildungsbetrieb_strasse' ).val(),
				'PLZ':$( '#ausbildungsbetrieb_plz option:selected' ).val(),
				'Telefon':$( 'input#ausbildungsbetrieb_telefon' ).val(),
				'Fax':$( 'input#ausbildungsbetrieb_fax' ).val(),
				'Email':$( 'input#ausbildungsbetrieb_email' ).val(),
				'Ausbilder':{
					'Anrede':$( '#ausbildungsbetrieb_ausbilder_anrede option:selected' ).val(),
					'Name':$( 'input#ausbildungsbetrieb_ausbilder_name' ).val(),
				}
			}
		};
		var url = "../../api/v1/students.php";
		var xhr = new XMLHttpRequest();
		xhr.open("POST", url, true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.onload = function () {
		var users = JSON.parse(xhr.responseText);
			if (xhr.readyState == 4 && xhr.status == "200") {
				console.table(users);
				/*if(users.success)
				{
					if ( confirm( "gespeichert." ) ){

					}
				}*/
			} else {
				console.error(users);
			}
		}
		var json = JSON.stringify(student)
		xhr.send("student=" +json);
		var nodes = document.querySelectorAll(".field");
		//if ( confirm( "gespeichert." ) ){

		//}
		/*
		for (var i=0; i<nodes.length; i++)
			{
				nodes[i].value == "" 
			}*/

	}
});
</script>
<style>
	.table_wrap {
		float:right;
		width: 600px;
		color: #303030;
		font-weight: 400;
		font-size: 12px;
		background: #fff;
		padding: 10px;
		-webkit-border-radius: 8px;
		-moz-border-radius: 8px;
		border-radius: 8px;
		border: 1px solid #D4D4D4;
		margin-bottom: 10px;
	}
	#anlegen-table td{
		width:230px;
	}
	#anlegen-table th{
		width:230px;
	}
	#Ausbildungsbetrieb-table th{
		width:230px;
	}
	#Ausbildungsbetrieb-table td{
		width:230px;
	}
	#Schulbildung-table th{
		width:230px;
	}
	#Schulbildung-table td{
		width:230px;
	}
	.add_wrap {
		float: left;
		width: 499px;
		color: #303030;
		font-weight: 400;
		font-size: 12px;
		background: #fff;
		padding: 10px;
		-webkit-border-radius: 8px;
		-moz-border-radius: 8px;
		border-radius: 8px;
		border: 1px solid #D4D4D4;
		margin-bottom: 10px;
	}
</style>