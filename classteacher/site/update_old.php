<?php
	$id = $_GET['id'];
	$classcode="";
	$activetoken="";
	if(isset($_GET['idteacher'])){
		$idteacher=$_GET["idteacher"];
	}
	else if(isset($_GET['id'])){
		$id = $_GET['id'];
	}
	else 
	{
		$_SERVER['HTTP_REFERER'];
		header('Location:'.$_SERVER['HTTP_REFERER']);   
	}
	//$idteacher=$_GET["idteacher"];
	//require("../../js/js.js");
?>

<div class="error_wrap">
	<div id="emptyfield">Bitte füllen Sie diese Felder korrekt aus:</div>
	<div id="error_username">Ohne Benutzername ist ein zurücksetzen nicht möglich!</div>
	<div id="val">Ungültige Zeichen in Schulnummer - nur Zahlen!</div>
	<div id="error">Fehler: Benutzer konnte nicht aktualisiert werden!</div>
	<div id="success">Benutzerdaten erfolgreich aktualisiert.</div>
	<div id="password_reset_success">Das Passwort wurde auf das Erstpasswort zurück gesetzt.</div>
</div>

<div class="left">
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
					if (isset($_GET["class"])){
						$query = $mysqli->query("SELECT * FROM class WHERE classcode='".$id."'");
						if($query->num_rows) {
							while($get = $query->fetch_assoc()) {
								echo '<tr>';
								echo '<td>Klasse:</td>';
								echo '<td>'.$get['classcode'].'</td>';
								$classcode=$get['classcode'];
								echo '</tr>';
								echo '<tr>';
								echo '<td>Langname:</td>';
								echo '<td>'.$get['longname'].'</td>';
								echo '</tr>';
							}
						}
					}else{
						$query = $mysqli->query("SELECT * FROM all_students WHERE idstudents='".$id."'");
						if($query->num_rows) {
							while($get = $query->fetch_assoc()) {
								echo '<tr>';
								echo '<td>Vorname:</td>';
								echo '<td>'.$get['surname'].'</td>';
								echo '</tr>';
								echo '<tr>';
								echo '<td>weitere Vornamen:</td>';
								echo '<td>'.$get['middlename'].'</td>';
								echo '</tr>';
								echo '<tr>';
								echo '<td>Nachname:</td>';
								echo '<td>'.$get['givenname'].'</td>';
								echo '</tr>';
								echo '<tr>';
								echo '<td>weitere Nachnamen:</td>';
								echo '<td>'.$get['moregivenname'].'</td>';
								echo '</tr>';
								echo '<td>Geburtsdatum:</td>';
								echo '<td>'.$get['birthdate'].'</td>';
								echo '</tr>';
								$classcode=$get['classcode'];
							}
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
					$query1 = $mysqli->query( "Update class set activetoken=0 where classcode='".$id."'and TIMESTAMPDIFF(MINUTE,tokenactivateat, NOW())>15;" );
					if(isset($_GET["class"])){
						$query=$mysqli->query("select * from classinformation where classcode='".$id."';");
						$query1=$mysqli->query("select * from classinformation where classcode='".$id."';");
						//$query = $mysqli->query("SELECT students.classcode,schoolform.schoolform,department.name,teacher.surname as tsurname,teacher.givenname as tgivenname, depteacher.surname as depsurname, depteacher.givenname as depgivenname FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher on teacher_class.idteacher=teacher.idteacher inner join teacher as depteacher on depteacher.idteacher=department.headofdepartment where students.idstudents='".$id."' and teacher_class.classteacher=1;");
						//$query = $mysqli->query("SELECT students.classcode,schoolform.schoolform,department.name,teacher.surname as tsurname,teacher.givenname as tgivenname FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher on teacher_class.idteacher=teacher.idteacher where students.idstudents= '".$id."' and teacher_class.classteacher=1;");
						if($query->num_rows) {
							while($get = $query1->fetch_assoc()) {
								$get2 = $query1->fetch_assoc();
								echo '<tr>';
								echo '<td>Klassenlehrer:</td>';
								if(isset($get2['teachername']))
									echo '<td>'.$get['teachername'].'<br>'.$get2['teachername'].'</td>';
								else
									echo '<td>'.$get['teachername'].'</td>';
								echo '</tr>';
								echo '<tr>';
								echo '<td>Schülertoken <br> (grüner Haken activ):</td>';
								echo '<td>'.$get['token'].'<br>';
								$activetoken=$get[ 'activetoken' ];
								if ( $get[ 'activetoken' ] == 1 ) {
									echo '<img src="../../style/true.png" alt="active" id="aktiv"></td>';
								} else {
									echo '<img src="../../style/false.png" alt="active"></td>';
								}
								echo '<tr>';
								echo '<tr>';
								echo '<td>Abteilung:</td>';
								echo '<td>'.$get['name'].'</td>';
								echo '</tr>';
								echo '<td>Abteilungsleiter:</td>';
								if(isset($get['hodname']))
									echo '<td>'.$get['hodname'].'</td>';
								else
									echo '<td></td>';
								echo '</tr>';
							}
						}
					}else
					{
						$query=$mysqli->query("select * from classinformationfromstudent where idstudents='".$id."';");
						$query1=$mysqli->query("select * from classinformationfromstudent where idstudents='".$id."';");
						//$query = $mysqli->query("SELECT students.classcode,schoolform.schoolform,department.name,teacher.surname as tsurname,teacher.givenname as tgivenname, depteacher.surname as depsurname, depteacher.givenname as depgivenname FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher on teacher_class.idteacher=teacher.idteacher inner join teacher as depteacher on depteacher.idteacher=department.headofdepartment where students.idstudents='".$id."' and teacher_class.classteacher=1;");
						//$query = $mysqli->query("SELECT students.classcode,schoolform.schoolform,department.name,teacher.surname as tsurname,teacher.givenname as tgivenname FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher on teacher_class.idteacher=teacher.idteacher where students.idstudents= '".$id."' and teacher_class.classteacher=1;");
						if($query->num_rows) {
							while($get = $query1->fetch_assoc()) {
								$get2 = $query1->fetch_assoc();
								echo '<tr>';
								echo '<td>Klasse:</td>';
								echo '<td>'.$get['classcode'].'</td>';
								echo '</tr>';
								echo '<tr>';
								echo '<td>Schulform:</td>';
								echo '<td>'.$get['schoolform'].'</td>';
								echo '</tr>';
								echo '<tr>';
								echo '<td>Klassenlehrer:</td>';
								if(isset($get2['teachername']))
									echo '<td>'.$get['teachername'].', '.$get2['teachername'].'</td>';
								else
									echo '<td>'.$get['teachername'].'</td>';
								echo '</tr>';
								echo '<tr>';
								echo '<td>Abteilung:</td>';
								echo '<td>'.$get['name'].'</td>';
								echo '</tr>';
								echo '<td>Abteilungsleiter:</td>';
								if(isset($get['hodname']))
									echo '<td>'.$get['hodname'].'</td>';
								else
									echo '<td></td>';
								echo '</tr>';
							}
						}
					}
					?>
			</table>
		</div>
	</div>
	<?php
	if(isset($_GET["class"]))
		echo '<div class="add_wrap" id="Klasseneinstellungen_wrap">';
	else
		echo '<div class="add_wrap" id="Klasseneinstellungen_wrap" style="display:none">';
	?>
	
		<div class="box_header">Klasseneinstellungen</div>
			<div class="box">
				
				
				<table class="table" id="side-table">
				<tr>
				<td>
					Stammbogen Klasse<br>drucken
				</td>
				<td>
				</form>
				<!--<button onclick='print(<?php echo $classode; ?>,<?php echo $idteacher; ?>)'>Drucken</button>-->
				<?php
						//echo '<button onclick="print(,)">Drucken</button>';
						echo '<a href="../../pdf/stammblatt.php?idteacher=' . $idteacher. '&classcode=' . $classcode. '" class="link"><img src="../style/print1.jpg" target="_blank" alt="Edit"></a>';
					?>
				</td>
				</tr>
					<form method="POST" action="" id="Klasseneinstellungen" class="form">
					<?php
						if($activetoken == "1") {
							echo '<tr>';
							echo '<td>Schüler:</td>';
							echo '<td><input type="radio" name="activate" id="activate" value="1" CHECKED>aktiver Token';
							echo '<br>';
							echo '<input type="radio" name="activate" id="activate" value="0">inaktiver Token</td>';
							echo '</tr>';
						}
						else {
							echo '<tr>';
							echo '<td>Token:</td>';
							echo '<td><input type="radio" name="activate" id="activate" value="1" >aktiver Token';
							echo '<br>';
							echo '<input type="radio" name="activate" id="activate" value="0" CHECKED>inaktiver Token</td>';
							echo '</tr>';
						}
						echo '<tr>';
						echo '<td></td>';
						echo '<td><input type="submit" name="submit" id="submit" value="Speichern"></td>';
						echo '</tr>';
					?>
					
					</form>
				</table>
			</div>	
		</div>
	</div>	
</div>
	

<div class="table_wrap">
	
			<?php
		if(isset($_GET["class"])){
			echo '<table class="table" id="user-table">';
			echo '<tr>';
			echo '<th colspan="9" id="classmate">Schüler</th>';
			echo '</tr>';
			echo '<tr>';
			echo '<th>lfd. Nr.</th>';
			echo '<th>Status</th>';
			echo '<th>Vorname</th>';
			echo '<th>weiter Vornamen</th>';
			echo '<th>Nachname</th>';
			echo '<th>weiter Nachnamen</th>';
			echo '<th>Schüler Stammblatt drucken';
			echo '<th>Schüler bearbeiten';
			echo '<th>inaktiv setzen</th>';
			echo '</tr>';
			
			$query = $mysqli->query( "SELECT * FROM all_students where classcode='".$id."';" );
			if ( $query->num_rows ) {
				$i=1;
				while ( $get = $query->fetch_assoc() ) {
					echo '<tr>';
					echo '<td>'.$i.'</td>';
					if ( $get[ 'active' ] == 1 ) {
						echo '<td><img src="../style/true.png" alt="active" id="aktiv"></td>';
					} 
					else 
					{
						echo '<td><img src="../style/false.png" alt="active"></td>';
					}
					echo '<td>' . $get[ 'surname' ] . '</td>';
					echo '<td>' . $get[ 'middlename' ] . '</td>';
					echo '<td>' . $get[ 'givenname' ] . '</td>';
					echo '<td>' . $get[ 'moregivenname' ] . '</td>';
					echo '<td>';
					echo '<a href="../../pdf/stammblattsus.php?idteacher=' . $idteacher. '&classcode=' . $classcode. '&idstudents=' . $get['idstudents']. '" class="link"><img src="../style/print.png" target="_blank" alt="Edit"></a>';
					echo '</td>';
					echo '<td>';
					echo '<a href="index.php?site=update&idteacher=' . $get[ 'idstudents' ] . '&id=' . $get[ 'idstudents' ] . '" class="link"><img src="../style/edit.png" alt="Edit" target="_blank"></a>';
					echo '</td>';
					echo '<td>';
					echo '<a href="javascript:deleteuser(' . $get[ 'idstudents' ] . ')" class="link"><img src="../style/false.png" alt="delete"></a>';
					echo '</td>';
					echo '</tr>';
					$i++;
				}
			}
			echo '</table>';
		}
		else
		{
			
			echo '<div class="box">';

			$query = $mysqli->query("SELECT * FROM all_students WHERE idstudents='".$id."'");
				if($query->num_rows) {
					while($get = $query->fetch_assoc()) {
			?>
			<form method="POST" action="" id="adminuser" class="form">
				<table class="table" id="anlegen-table">
					<tr>
						<th>Persönliche Daten Schüler</th>
						<th></th>
					</tr>
					<tr>
						<td>
							<label for="surname" class="label">Vorname:</label>
							<input class="field" type="text" size="24" maxlength="50" name="surname" id="surname" value="<?php echo $get['surname']; ?>">
							<label for="middlename" class="label">weitere Vornamen:</label>
							<input class="field" type="text" size="24" maxlength="50" name="middlename" id="middlename" value="<?php echo $get['middlename']; ?>">
							<label class="label">Nachname:</label>
							<input class="field" type="text" size="24" maxlength="50" name="givenname" id="givenname" value="<?php echo $get['givenname']; ?>">
							<label for="moregivenname" class="label">weitere Nachnamen:</label>
							<input class="field" type="text" size="24" maxlength="50" name="moregivenname" id="moregivenname" value="<?php echo $get['moregivenname']; ?>">
							<label for="address" class="label">Adresse:</label>
							<input class="field" type="text" size="24" maxlength="50" name="address" id="street" value="<?php echo $get['address']; ?>">
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
										if($plz==$get['plz'])
											echo '<option selected="selected" value="' . $plz . '">'  . $plz ." " .$ort.   '</option>';
										else
											echo '<option value="' . $plz . '">' . $plz ." " .$ort.  '</option>';
									} 
								}
							?>
							</select>
							<label for="surname" class="label">Ort:</label>
							<input class="field" type="text" size="24" maxlength="50" name="town" id="town"  value="<?php echo $get['ort']; ?>">
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
										if($province==$get['province'])
											echo '<option selected="selected" value="' . $idprovince . '">' . $province . '</option>';
										else
											echo '<option value="' . $idprovince . '">' . $province .'</option>';
									}
								}
							?>
							</select>
							<label for="birthdate" class="label">Geburtsdatum:</label>
							<input class="field" type="date" size="24" maxlength="50" name="birthdate" id="birthdate" value="<?php echo $get['birthdate']; ?>">
							<label for="birthcountry" class="label">Geburtsland:</label>
							<input class="field" type="text" size="24" maxlength="50" name="birthcountry" id="birthcountry" value="<?php echo $get['birthcountry']; ?>">
							<label for="birthtown" class="label">Geburtsort:</label>
							<input class="field" type="text" size="24" maxlength="50" name="birthtown" id="birthtown" value="<?php echo $get['birthtown']; ?>">
						</td>
						<td>
							<label for="sex" class="label">Geschlecht:</label>
							<select name= "sex" id="sex" class="field" size="1">
							<?php
							if ($get['sex']="m"){
								echo '<option selected="selected" value="m">männlich</option>';
							    echo '<option value="w">weiblich</option>';
								echo '<option value="d">divers</option>';
							}
							else if ($get['sex']="w"){
								echo '<option selected="selected" value="w">weiblich</option>';
								echo '<option value="m">männlich</option>';
								echo '<option value="d">divers</option>';
							}
							else if ($get['sex']="d"){
								echo '<option selected="selected" value="d">divers</option>';
								echo '<option value="m">männlich</option>';
								echo '<option value="w">weiblich</option>';
							}
							?>
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
										if($Land==$get['nationality'])
											echo '<option selected="selected" value="' . $lkz . '">' . $Land . '</option>';
										else
											echo '<option value="' . $lkz . '">' . $Land . '</option>';
									}
								}
							?>
							</select>
							<!--<input class="field" type="text" size="24" maxlength="50" name="nationality" id="nationality">-->
							<label for="birthdate" class="label">Muttersprache:</label>
							<input class="field" type="text" size="24" maxlength="50" name="family_speech" id="family_speech" value="<?php echo $get['family_speech']; ?>">
							<label for="religion" class="label">Religion:</label>
							<input class="field" type="text" size="24" maxlength="50" name="religion" id="religion" value="<?php echo $get['religion']; ?>">
							<label for="phone" class="label">Telefon:</label>
							<input class="field" type="text" size="24" maxlength="50" name="phone" id="phone" value="<?php echo $get['phone']; ?>">
							<label for="mobilephone" class="label">Mobiltelefon:</label>
							<input class="field" type="text" size="24" maxlength="50" name="mobilephone" id="mobilephone" value="<?php echo $get['mobilephone']; ?>">
							<label for="email" class="label">Email:</label>
							<input class="field" type="text" size="24" maxlength="50" name="email" id="email" value="<?php echo $get['email']; ?>">
							<label for="classc" class="label">Klasse:</label>
							
							<?php
							echo'<select name "classs"  id="classs" class="field"size="1">';
							$check = $mysqli->query( "SELECT classcode FROM class;" );
							while($row = mysqli_fetch_array($check)) {
								if($row['classcode']!=""){
									$graduation=$row['classcode'];
									$idgraduation=$row['classcode'];
									if($row['classcode']==$get['classcode'])
										echo '<option value="'.$graduation.'" selected=selected>'.$graduation.'</option>';
									else
										echo '<option value="'.$graduation.'">'.$graduation.'</option>';
								}
							}
							echo '</select>';
							?>
						</td>
					</tr>
							
					<tr>
						<th>Informationen zur Ausbildung</th>
						<th></th>
					</tr>
					<tr>
						<td>
							<label class="label">Ausbildungsbeginn:</label>
							<input class="field" type="date" size="24" maxlength="50" name="ausbildungsbeginn" id="ausbildungsbeginn" value="<?php echo $get['Ausbildungsbeginn']; ?>">
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
										if($berufsid==$get['idberuf'])
											echo '<option selected=selected value="' . $berufsid . '">'.$Berufbez.'</option>';
										else
										echo '<option value="' . $berufsid . '">'.$Berufbez.'</option>';
									} 
								}
							?>
							</select>
							<!--<input class="field" type="text" size="24" maxlength="50" name="Ausbildungsberuf" id="Ausbildungsberuf">-->
						</td>

					</tr>
				</table>
				<?php
				if($get['Schulform']=="Teilzeit"){
					echo '<table class="table" id="Ausbildungsbetrieb-table">';
				}
				else
					echo'<table class="table" id="Ausbildungsbetrieb-table" style="display:none" >';
				

				?>
					<tr>
						<th>Informationen zum Ausbildungsbetrieb</th>
						<th></th>
					</tr>
					<tr>
						<td>
							<label for="birthdate" class="label">Name:</label>
							<input class="field" type="text" size="24" maxlength="50" name="ausbildungsbetrieb_name" id="ausbildungsbetrieb_name"  value="<?php echo $get['Ausbildungsbetrieb']; ?>">
						</td>
						<td>
							</td>
					</tr>
					<tr>
						<td>
							<label for="religion" class="label">Straße:</label>
							<input class="field" type="text" size="24" maxlength="50" name="ausbildungsbetrieb_name" id="ausbildungsbetrieb_strasse"  value="<?php echo $get['Ausbildungsbetrieb_Strasse']; ?>">
							<label for="religion"  class="label">Ort:</label>
							<input class="field" readonly type="text" size="24" maxlength="50" name="religion" id="ausbildungsbetrieb_ort"  value="<?php echo $get['Ausbildungsbetrieb_Ort']; ?>">
							<label for="religion" class="label">Email:</label>
							<input class="field" type="text" size="24" maxlength="50" name="religion" id="ausbildungsbetrieb_email"  value="<?php echo $get['Ausbildungsbetrieb_Email']; ?>">
						</td>
						<td>
						<label for="religion" class="label">PLZ:</label>
						<select name= "ausbildungsbetrieb_plz" id="ausbildungsbetrieb_plz" class="field" size="1" onchange='settown("#ausbildungsbetrieb_plz option:selected","ausbildungsbetrieb_ort","true")'>
							<?php
								//echo '<select name "classs"  id="classs" class="field"size="4">';
								$check = $mysqli->query( "SELECT * FROM town;" );
								while ( $row = mysqli_fetch_array( $check ) ) {
									if ( $row[ 'plz' ] != "" ) {
										$plz=$row[ 'plz'];
										$ort = $row[ 'ort' ];
										$bundesland = $row[ 'bundesland' ];
										if($plz==$get['Ausbildungsbetrieb_PLZ'])
											echo '<option selected="selected" value="' . $plz . '">' . $plz ." " .$ort.   '</option>';
										else
											echo '<option value="' . $plz . '">' . $plz ." " .$ort.  '</option>';
									} 
								}
							?>
							</select>
							<label class="label">Telefon:</label>
							<input class="field" type="text" size="24" maxlength="50" name="ausbildungsbetrieb_telefon" id="ausbildungsbetrieb_telefon"  value="<?php echo $get['Ausbildungsbetrieb_Telefon']; ?>">
							<label class="label">Fax:</label>
							<input class="field" type="text" size="24" maxlength="50" name="ausbildungsbetrieb_telefon" id="ausbildungsbetrieb_fax" value="<?php echo $get['Ausbildungsbetrieb_Fax']; ?>">
						</td>
					</tr>
					<tr>
						<td>
							<label class="label">Ausbilder Anrede:</label>
							<select name= "Ausbilder_anrede" id="ausbildungsbetrieb_ausbilder_anrede" class="field" size="1">
								<?php
								if($get["Ausbildungsbetrieb_Ausbilder_Anrede"]=="Herr") {
								echo '<option selected="selected" value="Herr">Herr</option>';
								echo '<option value="Frau">Frau</option>';
								}
								else{
								echo '<option value="Herr">Herr</option>';
								echo '<option selected="selected" value="Frau">Frau</option>';
								}
								?>
								
							</select>
							</td>
						<td>
							<label class="label">Ausbilder Name:</label>
							<input class="field" type="text" size="24" maxlength="50" name="religion" id="ausbildungsbetrieb_ausbilder_name" value="<?php echo $get['Ausbildungsbetrieb_Ausbilder_Name']; ?>">
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
							<input class="field" type="text" size="24" maxlength="50" name="lastschool" id="lastschool" value="<?php echo $get['lastschool']; ?>">
						</td>
						<td>
						</td>
					</tr>
					<tr>
						<td>
							<label class="label">Ort</label>
							<input class="field" type="text" size="24" maxlength="50" name="lastschool" id="lastschooltown" value="<?php echo $get['lastschooltown']; ?>">
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
										if($province==$get['lastschoolprovince'])
											echo '<option selected="selected" value="' . $province . '">' . $province . '</option>';
										else
											echo '<option value="' . $province . '">' . $province .'</option>';
									}
								}
							?>
							</select>
						</td>
					</tr>
					<?php
					if($get['Schulform']=="Teilzeit"){
						echo '<tr id="Abgang" >';
					}
					else
						echo '<tr id="Abgang" style="display:none">';
					?>
					
						<td>
						<label class="label">Abgang:</label>
							<input class="field" type="date" size="24" maxlength="50" name="family_speech" id="lastschooldate" value="<?php echo $get['lastschooldate']; ?>">
					</tr>
					<tr id="Schulabschluss" style="display:none" >
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
											if($graduation==$get['graduation'])
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
							<input class="field" type="text" size="24" maxlength="50" name="surname" id="mother_surname" value="<?php echo $get['mother_surname']; ?>">
							<label for="givenname" class="label">Nachname:</label>
							<input class="field" type="text" size="24" maxlength="50" name="givenname" id="mother_givenname" value="<?php echo $get['mother_lastname']; ?>">
							<label for="address" class="label">Adresse:</label>
							<input class="field" type="text" size="24" maxlength="50" name="address" id="mother_address" value="<?php echo $get['mother_address']; ?>">
							<label for="surname" class="label">PLZ:</label>
							<select name= "postalcode" id="mother_postalcode" class="field" size="1" onchange='settown("#mother_postalcode option:selected","mother_town","false")'>
							<?php
								//echo '<select name "classs"  id="classs" class="field"size="4">';
								$check = $mysqli->query( "SELECT * FROM town;" );
								while ( $row = mysqli_fetch_array( $check ) ) {
									if ( $row[ 'plz' ] != "" ) {
										$plz=$row[ 'plz'];
										$ort = $row[ 'ort' ];
										$bundesland = $row[ 'bundesland' ];
										if($plz==$get['mother_postalcode'])
											echo '<option selected="selected" value="' . $plz . '">' . $plz ." " .$ort.  '</option>';
										else
											echo '<option value="' . $plz . '">' . $plz ." " .$ort.  '</option>';
									} 
								}
							?>
							</select>
							<label for="givenname" class="label">Ort:</label>
							<input class="field" type="text" size="24" maxlength="50" name="givenname" id="mother_town" value="<?php echo $get['mother_town']; ?>">
							<label for="address" class="label">Telefon:</label>
							<input class="field" type="text" size="24" maxlength="50" name="address" id="mother_phone" value="<?php echo $get['mother_phone']; ?>">
							<label for="address" class="label">Mobiltelefon:</label>
							<input class="field" type="text" size="24" maxlength="50" name="address" id="mother_mobilephone" value="<?php echo $get['mother_mobilephone']; ?>">
						</td>
						<td>
							<label for="surname" class="label">Vorname:</label>
							<input class="field" type="text" size="24" maxlength="50" name="surname" id="father_surname" value="<?php echo $get['father_surname']; ?>">
							<label for="givenname" class="label">Nachname:</label>
							<input class="field" type="text" size="24" maxlength="50" name="givenname" id="father_givenname" value="<?php echo $get['father_lastname']; ?>">
							<label for="address" class="label">Adresse:</label>
							<input class="field" type="text" size="24" maxlength="50" name="address" id="father_address" value="<?php echo $get['father_address']; ?>">
							<label for="surname" class="label">PLZ:</label>
							<select name= "postalcode" id="father_postalcode" class="field" size="1" onchange='settown("#father_postalcode option:selected","father_town","false")'>
							<?php
								//echo '<select name "classs"  id="classs" class="field"size="4">';
								$check = $mysqli->query( "SELECT * FROM town;" );
								while ( $row = mysqli_fetch_array( $check ) ) {
									if ( $row[ 'plz' ] != "" ) {
										$plz=$row[ 'plz'];
										$ort = $row[ 'ort' ];
										$bundesland = $row[ 'bundesland' ];
										if($plz==$get['father_postalcode'])
											echo '<option selected="selected" value="' . $plz . '">' . $plz ." " .$ort.  '</option>';
										else
											echo '<option value="' . $plz . '">' . $plz ." " .$ort.  '</option>';
									} 
								}
							?>
							</select>
							<label for="givenname" class="label">Ort:</label>
							<input class="field" type="text" size="24" maxlength="50" name="givenname" id="father_town" value="<?php echo $get['father_town']; ?>">
							<label for="address" class="label">Telefon:</label>
							<input class="field" type="text" size="24" maxlength="50" name="address" id="father_phone" value="<?php echo $get['father_phone']; ?>">
							<label for="address" class="label">Mobiltelefon:</label>
							<input class="field" type="text" size="24" maxlength="50" name="address" id="father_mobilephone" value="<?php echo $get['father_mobilephone']; ?>">
						</td>
					</tr>
					<?php
					echo '<tr>';
						if($get['active'] == 1) {
							echo '<td>Schüler:</td>';
							echo '<td><input type="radio" name="activate" id="activate" value="1" CHECKED>aktiver Schüler';
							echo '<br>';
							echo '<input type="radio" name="activate" id="activate" value="0">inaktiver Schüler</td>';
						}
						else {
							echo '<td>Status:</td>';
							echo '<td><input type="radio" name="activate" id="activate" value="1" >aktiver Schüler';
							echo '<br>';
							echo '<input type="radio" name="activate" id="activate" value="0" CHECKED>inaktiver Schüler</td>';
						}
						echo '</tr>';
						?>
				</table>
				<input type="submit" name="submit" id="submit" value="Speichern">
			</form>
			<?php
				}
			}			
		echo '</div>';
		
		}
		?>
	</table>
</div>
<style>
	.left{
		width:360px;
	}
	.table_wrap {
		float:left;
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
	.tabl1e_wrap {
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
<script type="text/javascript">
/*window.onload = function () {
    //var fiveMinutes = 60 * 5,
	//	display = document.querySelector('#time');
		setInterval(function() {
			$.get( 'loadusertable.php?class&id=<?php echo $id; ?>', function ( data ) {
							$( '#user-table' ).html( data );
						} );
		}, 60 * 1000);
	//startTimer(fiveMinutes, display);
};*/

function deleteuser( idstudents ) {
		var idteacher="<?php echo $_SESSION['idteacher'] ?>";
		if ( confirm( "Möchten Sie wirklich deaktivieren" ) )
			if ( idstudents == "" ) {
				$( "#deleteuser" ).show();
				$( "#searcherror" ).hide();
			} else {
				$.get( 'function.php?delete&idstudents=' + idstudents, function ( data ) {
					var jsonobj = JSON.parse( data );
					if ( !jsonobj.success ) {
						$( "#deleteuser" ).show();
						$( "#success" ).hide();
					} else {
						$( "#success" ).show();
						$( "#deleteuser" ).hide();
						$.get( 'loadusertable.php?idteacher='+idteacher, function ( data ) {
							$( '#user-table' ).html( data );
						} );
					}
				} );
			}
	}
$(document).ready(function(){
	var datefield=document.createElement("input")
	datefield.setAttribute("type", "date")
	if (datefield.type!="date"){ //if browser doesn't support input type="date", initialize date picker widget:
		jQuery(function($){ //on document.ready
			$('#birthdate').datepicker({
				dateFormat: "yy-dd-mm"
			});
		})
}
});

$("#Klasseneinstellungen").submit(function(event) {
	event.preventDefault();
	//$(".error_wrap").show();

	var activetoken = $( 'input#activate:checked').val()
	if (activetoken==1){
		if ( confirm( "Der Token gilt ab jetzt 15 Minutren." ) ){
		var activetoken = $( 'input#activate:checked').val()
		var classcode = "<?php echo $classcode ?>"
		$.get('function.php?token&activetoken='+activetoken+'&classcode='+classcode, function(data) {
			//console.log('data ', data)
			data=JSON.parse(data);
			if (data.success)
				location.reload(); 
		});
		}
		else {
		}
	}
	else{
		if ( confirm( "Der Token wird jetzt deaktiviert." ) ){
		var activetoken = $( 'input#activate:checked').val()
		var classcode = "<?php echo $classcode ?>"
		$.get('function.php?token&activetoken='+activetoken+'&classcode='+classcode, function(data) {
			//console.log('data ', data)
			data=JSON.parse(data);
			if (data.success)
				location.reload(); 
		});
		//location.reload(); 
		}
		else {
		}
	}
	//location.reload(); 
});

$("#adminuser").submit(function(event) {
		var surname = $( 'input#surname' ).val();
		var middlename = $( 'input#middlename' ).val();
		var givenname = $( 'input#givenname' ).val();
		var moregivenname = $( 'input#moregivenname' ).val();
		var address = $( 'input#address' ).val();
        var province = $( 'input#province' ).val();
        var birthdate = $( 'input#birthdate' ).val();
        var birthtown = $( 'input#birthtown' ).val();
        var birthcountry = $( 'input#birthcountry' ).val();
		var nationality = $( 'input#nationality' ).val();
        var family_speech = $( 'input#family_speech' ).val();
        var phone = $( 'input#phone' ).val();
        var mobilephone = $( 'input#mobilephone' ).val();
        var email = $( 'input#email' ).val();
		var activate = $( 'input#activate:checked').val()
		var religion = $( 'input#religion' ).val();
		var idstudents="";
		var classs = $( '#classs option:selected' ).val();
		<?php
        	echo " idstudents = \"".$id."\";";
        ?>
	event.preventDefault();
	$(".error_wrap").show();
	if ( surname==''||givenname==''||birthdate=='') {
            $( "#emptyfield" ).show();
	}
	else {
			$.get( 'function.php?user_update&birthdate=' + birthdate + '&surname=' + surname +'&middlename=' + middlename +'&givenname=' + givenname +'&moregivenname=' + moregivenname +'&address=' + address + '&province=' + province + '&birthtown=' + birthtown + '&birthcountry=' + birthcountry +'&nationality=' + nationality + '&family_speech=' + family_speech + '&phone=' + phone + '&mobilephone=' + mobilephone + '&email=' + email+ '&religion=' + religion + '&idstudents='+ idstudents + '&active='+activate+ '&class='+classs + '&birthdate='+birthdate, function ( data ) {
                console.log( data );
				var obj=jQuery.parseJSON(data); 
				if(obj.success){
                    $( "#emptyfield" ).hide();
                    $( "#val" ).hide();
                    $( "#error" ).hide();
                    $( "#success" ).show();
                } else {
					if(obj.emptyfield){
					 	$( "#emptyfield" ).show();
                    	$( "#val" ).hide();
                    	$( "#error" ).show();
						var div = document.getElementById("emptyfield");
						for (var x in obj.errors)
								div.textContent+=" "+x;
    					var text = div.textContent;
					}else
						$( "#error" ).show();
                   
                }
                
		});
	}
});

$("#setpassword").submit(function(event) {
	var username = $('input#username').val();
	event.preventDefault();
	$(".error_wrap").show();
	if(username == '') {
		$("#error_username").show();
	}
	else {
			$.get('function.php?reset&username='+username, function(data) {
			console.log(data);

			if(data == 'true') {
				$("#emptyfield").hide();
				$("#val").hide();
				$("#error").hide();
				$("#password_reset_success").show();
			}
			else {
				$("#emptyfield").hide();
				$("#val").hide();
				$("#error").show();
			}
		});
	}
});

$("#ablauf").submit(function(event) {
	var ablauf = $('input#ablauf').val();
	var id = $('input#id').val();
	event.preventDefault();
	$(".error_wrap").show();
	if(username == '') {
		$("#error_username").show();
	}
	else {
			$.get('function.php?abgelaufen&datum='+ablauf+'&id='+id, function(data) {
			console.log(data);

			if(data == 'true') {
				$("#emptyfield").hide();
				$("#val").hide();
				$("#error").hide();
				$("#password_reset_success").show();
			}
			else {
				$("#emptyfield").hide();
				$("#val").hide();
				$("#error").show();
			}
		});
	}
});

</script>