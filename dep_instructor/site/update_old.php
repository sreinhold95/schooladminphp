<?php

	$id = $_GET['id'];

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
					$query = $mysqli->query("SELECT students.classcode,schoolform.schoolform,department.name,teacher.surname as tsurname,teacher.givenname as tgivenname, depteacher.surname as depsurname, depteacher.givenname as depgivenname FROM students inner join class on students.classcode=class.classcode inner join schoolform on class.schoolform=schoolform.idschoolform inner join department on class.department=department.iddepartment inner join teacher_class on class.classcode=teacher_class.classcode inner join teacher on teacher_class.idteacher=teacher.idteacher inner join teacher as depteacher on depteacher.idteacher=department.headofdepartment where students.idstudents='".$id."' and teacher_class.classteacher=1;");
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
                            if(isset($get['depgivenname']))
				                echo '<td>'.$get['depgivenname'].'</td>';
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
				$query = $mysqli->query("SELECT * FROM students WHERE idstudents='".$id."'");
				if($query->num_rows) {
					while($get = $query->fetch_assoc()) {
						echo '<tr>';
						echo '<td>Name</td>';
						echo '<td><input type="text" id="surname" value="'.$get['surname'].'"></td>';
						echo '</tr>';
						echo '<tr>';
						echo '<td>weitere Vornamen</td>';
						echo '<td><input type="text" id="middlename" value="'.$get['middlename'].'"></td>';
						echo '</tr>';
						echo '<tr>';
						echo '<td>Nachname</td>';
						echo '<td><input type="text" id="givenname" value="'.$get['givenname'].'"></td>';
						echo '</tr>';
						echo '<tr>';
						echo '<td>weitere Nachnamen</td>';
						echo '<td><input type="text" id="moregivenname" value="'.$get['moregivenname'].'"></td>';
						echo '</tr>';
						echo '<tr>';
						echo '<td>Geburtsdatum</td>';
						echo '<td><input type="date" id="birthdate" value="'.$get['birthdate'].'"></td>';
						echo '</tr>';
						echo '<tr>';
						echo '<td>Klasse</td>';
						echo '<td>';
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
						echo '</td>';
						echo '</tr>';
						echo '<td>Geburtsort</td>';
						echo '<td><input type="text" id="birthtown" value="'.$get['birthtown'].'"></td>';
						echo '</tr>';
						echo '<tr>';
						echo '<td>Geburtsland</td>';
						echo '<td><input type="text" id="birthcountry" value="'.$get['birthcountry'].'"></td>';
						echo '</tr>';
						echo '<tr>';
						echo '<td>Bundesland</td>';
						echo '<td><input type="text" id="province" value="'.$get['province'].'"></td>';
						echo '</tr>';
						echo '<tr>';
						echo '<td>Nationalität</td>';
						echo '<td><input type="text" id="nationality" value="'.$get['nationality'].'"></td>';
						echo '</tr>';
						echo '<tr>';
						echo '<td>Muttersprache</td>';
						echo '<td><input type="text" id="family_speech" value="'.$get['family_speech'].'"></td>';
						echo '</tr>';
						echo '<tr>';
						echo '<td>Telefon</td>';
						echo '<td><input type="text" id="phone" value="'.$get['phone'].'"></td>';
						echo '</tr>';
						echo '<tr>';
						echo '<td>Mobiltelefon</td>';
						echo '<td><input type="text" id="mobilephone" value="'.$get['mobilephone'].'"></td>';
						echo '</tr>';
						echo '<tr>';
						echo '<td>E-Mail</td>';
						echo '<td><input type="text" id="email" value="'.$get['email'].'"></td>';
						echo '</tr>';
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

$("#activation").submit(function(event) {
	event.preventDefault();
	$(".error_wrap").show();
	$.get('action.php?activate', function(data) {
		console.log('data ', data)
	});
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