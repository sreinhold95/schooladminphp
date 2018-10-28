<?php

	$id = $_GET['id'];

?>

<div class="error_wrap">
	<div id="emptyfield">Bitte füllen Sie alle Felder korrekt aus!</div>
	<div id="error_username">Ohne Benutzername ist ein zurücksetzen nicht möglich!</div>
	<div id="val">Ungültige Zeichen in Schulnummer - nur Zahlen!</div>
	<div id="error">Fehler: Benutzer konnte nicht aktualisiert werden!</div>
	<div id="success">Benutzerdaten erfolgreich aktualisiert.</div>
	<div id="password_reset_success">Das Passwort wurde auf das Erstpasswort zurück gesetzt.</div>
</div>

<div class="left">
	<div class="add_wrap">
		<div class="box_header">SuS Allgemein</div>
			<div class="box">
				<table class="table" id="user-table">
				<?php
					$query = $mysqli->query("SELECT * FROM students WHERE idstudents='".$id."'");
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
						}
					}
				?>
			</table>
		</div>
	</div>

	<div class="add_wrap">
		<div class="box_header">Klassendaten</div>
			<div class="box">
				<table class="table" id="user-table">
					<form method="POST" action="" id="ablauf" class="form">
					<?php
					$query = $mysqli->query("SELECT students.classcode,schoolform.schoolform,department.name,teacher.surname as tsurname,teacher.givenname as tgivenname FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher on teacher_class.idteacher=teacher.idteacher where students.idstudents= '".$id."' and teacher_class.classteacher=1;");
					if($query->num_rows) {
						while($get = $query->fetch_assoc()) {
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
							echo '<td>'.$get['tsurname'].' '.$get['tgivenname'].'</td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td>Abteilung:</td>';
							echo '<td>'.$get['name'].'</td>';
						    echo '</tr>';
                            echo '<td>Abteilungsleiter:</td>';
                            if(isset($get['headofdepartment']))
				                echo '<td>'.$get['headofdepartment'].'</td>';
                            else
                                echo '<td></td>';
							echo '</tr>';
						}
					}
					?>
				</form>
			</table>
		</div>
	</div>
</div>



<div class="table_wrap">
	<table class="table" id="user-table">
		<tr>
			<th colspan="2">Benutzerinformationen</th>
		</tr>
		<form method="POST" action="" id="adminuser" class="form">
			<?php
				$query = $mysqli->query("SELECT user.kdnr as kdnr, user.schulname as schulname, user.user as user, user.isschool as isschool, user.isactiv as isactiv, user.telefon as telefon, user.email as email FROM user LEFT JOIN vertragspartner ON (user.vertragspartner=vertragspartner.idvertragspartner) WHERE id='".$id."'");
				if($query->num_rows) {
					while($get = $query->fetch_assoc()) {
						if($get['isschool'] == 0) {
							echo '<tr>';
							echo '<td>Schulnummer</td>';
							echo '<td><input type="text" id="schoolnumber" value="'.$get['kdnr'].'" READONLY></td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td>Schulname</td>';
							echo '<td><input type="text" id="schoolname" value="'.$get['schulname'].'"></td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td>Technischer Ansprechpartner</td>';
							echo '<td><input type="text" id="user" value="'.$get['user'].'"></td>';
							echo '</tr>';
						} elseif($get['isschool'] == 1) {
							echo '<tr>';
							echo '<td>Kundennummer</td>';
							echo '<td><input type="text" id="schoolnumber" value="'.$get['kdnr'].'" READONLY></td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td>Firmenname</td>';
							echo '<td><input type="text" id="schoolname" value="'.$get['schulname'].'"></td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td>Ansprechpartner</td>';
							echo '<td><input type="text" id="user" value="'.$get['user'].'"></td>';
							echo '</tr>';
						} else {
							echo '<tr>';
							echo '<td>Kundennummer</td>';
							echo '<td><input type="text" id="schoolnumber" value="'.$get['kdnr'].'" READONLY></td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td>Ansprechpartner</td>';
							echo '<td><input type="text" id="user" value="'.$get['user'].'"></td>';
							echo '</tr>';
						}
						echo '<tr>';
						echo '<td>Telefon</td>';
						echo '<td><input type="text" id="telefon" value="'.$get['telefon'].'"></td>';
						echo '</tr>';
						echo '<tr>';
						echo '<td>E-Mail</td>';
						echo '<td><input type="text" id="adresse" value="'.$get['email'].'"></td>';
						echo '</tr>';
						echo '<tr>';
						if($get['isactiv'] == 1) {
							echo '<td>Account:</td>';
							echo '<td><input type="radio" name="activate" id="activate" value="1" CHECKED>Aktiviert';
							echo '<br>';
							echo '<input type="radio" name="activate" id="activate" value="0">Deaktiviert</td>';
						}
						else {
							echo '<td>Account:</td>';
							echo '<td><input type="radio" name="activate" id="activate" value="1" >Aktiviert';
							echo '<br>';
							echo '<input type="radio" name="activate" id="activate" value="0" CHECKED>Deaktiviert</td>';
						}
						echo '</tr>';
						echo '<tr>';
						echo '<td></td>';
						echo '<td><input type="submit" name="submit" id="submit" value="Speichern"></td>';
						echo '</tr>';
					}
				}
			?>
		</form>
	</table>
</div>


<!--
<div class="table_wrap">
	<table class="table" id="user-table">
		<tr>
			<th colspan="2">Vertragspartner</th>
		</tr>
		<form method="POST" action="" id="adminuser" class="form">
			<?php
				/*$query = $mysqli->query("SELECT vertragspartner.name as vp, vertragspartner.ort as ort, vertragspartner.adresse as adresse, vertragspartner.email as email, vertragspartner.telefon as telefon FROM user LEFT JOIN vertragspartner ON (user.vertragspartner=vertragspartner.idvertragspartner) WHERE id='".$id."' ");
				if($query->num_rows) {
					while($get = $query->fetch_assoc()) {
							echo '<tr>';
							echo '<td>Vertragspartner</td>';
							echo '<td><input type="text" id="schoolnumber" value="'.$get['vp'].'"></td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td>E-Mail</td>';
							echo '<td><input type="text" id="user" value="'.$get['email'].'"></td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td>Telefon</td>';
							echo '<td><input type="text" id="user" value="'.$get['telefon'].'"></td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td>Ort</td>';
							echo '<td><input type="text" id="schoolname" value="'.$get['ort'].'"></td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td>Adresse</td>';
							echo '<td><input type="text" id="user" value="'.$get['adresse'].'"></td>';
							echo '</tr>';
					}
				}
*/
			?>
		</form>
	</table>
</div>
-->



<!--
<div class="table_wrap">
	<table class="table" id="user-table">
		<tr>
			<th>Aktivierungsschlüssel für das Serverprogramm</th>
		</tr>
		<?php
			/*$query = $mysqli->query("SELECT * FROM user WHERE id='".$id."'");
			if($query->num_rows) {
				while($get = $query->fetch_assoc()) {
					echo '<tr>';
					echo '<td colspan="2">'.$get['activation'].'</td>';
					echo '</tr>';
				}
			}*/
		?>
	</table>
</div>
-->

<div class="table_wrap">
	<table class="table" id="user-table">
		<tr>
			<th colspan="2">Passwort zurücksetzen</th>
		</tr>
		<form method="POST" action="" id="setpassword" class="form">
			<?php
				$query = $mysqli->query("SELECT * FROM user WHERE id='".$id."'");
				if($query->num_rows) {
					while($get = $query->fetch_assoc()) {
						echo '<tr>';
						echo '<td>Benutzername</td>';
						echo '<td><input type="text" id="username" value="'.$get['username'].'" READONLY></td>';
						echo '</tr>';
						echo '<tr>';
						echo '<td>Passwort</td>';
						echo '<td><input type="submit" name="submit" id="submit" value="Passwort zurücksetzen"></td>';
						echo '</tr>';
					}
				}
			?>
		</form>
	</table>
</div>

<script type="text/javascript">

$("#activation").submit(function(event) {
	event.preventDefault();
	$(".error_wrap").show();
	$.get('action.php?activate', function(data) {
		console.log('data ', data); 
	});
});

$("#adminuser").submit(function(event) {
	var schoolnumber = $('input#schoolnumber').val();
	var schoolname = $('input#schoolname').val();
	var user = $('input#user').val();
	var adresse = $('input#adresse').val();
	var telefon = $('input#telefon').val();
	var ort = $('input#ort').val();
	var active = $('input#activate:checked').val();
	event.preventDefault();
	$(".error_wrap").show();
	if(schoolnumber == '' || schoolname == '' || user == '' || adresse == '' || telefon == '') {
		$("#success").hide();
		$("#error").hide();
		$("#val").hide();
		$("#emptyfield").show();
		if(isNaN(schoolnumber)) {
			$("#success").hide();
			$("#error").hide();
			$("#emptyfield").hide();
			$("#val").show();
		} 
	}
	else {
			$.get('function.php?user_update&schoolnumber='+schoolnumber+'&schoolname='+schoolname+'&user='+user+'&telefon='+telefon+'&adresse='+adresse+'&ort='+ort+'&active='+active, function(data) {
			console.log(data);

			if(data == 'true') {
				$("#emptyfield").hide();
				$("#val").hide();
				$("#error").hide();
				$("#success").show();
			}
			else {
				$("#emptyfield").hide();
				$("#val").hide();
				$("#error").show();
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