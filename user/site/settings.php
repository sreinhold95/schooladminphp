<div class="left">
	<div class="user_wrap">
		<div class="box_header">Passwortänderung</div>
		<div class="box">
			<div id="emptyfield">Bitte füllen Sie alle Felder korrekt aus!</div>
			<div id="success">Das Passwort wurde erfolgreich geändert.</div>
			<form method="POST" action="" id="setpassword1" class="form">
				<input class="field" type="hidden" size="24" maxlength="50" name="kdnr" id="kdnr" value="<?php echo $_SESSION['id']; ?>">
				<label for="schoolname" class="label">Passwort:</label>
				<input class="field" type="password" size="24" maxlength="50" name="password" id="password">
				<input type="submit" name="submit" id="submit" value="Speichern">
			</form>
		</div>
	</div>
</div>

<div class="config_wrap">
	<div class="box_header">Account Informationen</div>
	<div class="box">
		<div id="emptyfield2">Bitte füllen Sie alle Felder korrekt aus!</div>
		<div id="success2">Die Daten wurden erfolgreich gespeichert.</div>
		<form method="POST" action="" id="changeuser" class="form">
			<?php
				$query = $mysqli->query("SELECT * FROM user WHERE id = '".$_SESSION['id']."' ");
				if($query->num_rows) {
					while($get = $query->fetch_assoc()) {
						echo '<div id="left_text_form">';
							echo 'Kundennummer:';
						echo '</div>';
						echo '<div id="right_text_form">';
							echo '<input class="field" type="text" size="24" maxlength="50" id="schoolnumber" value="'.$get['kdnr'].'" READONLY>';
						echo '</div>';
						echo '<div id="left_text_form">';
							echo 'Schulname:';
						echo '</div>';
						echo '<div id="right_text_form">';
							echo '<input class="field" type="text" size="24" maxlength="50" id="schoolname" value="'.$get['schulname'].'">';
						echo '</div>';
						echo '<div id="left_text_form">';
							echo 'Ansprechpartner:';
						echo '</div>';
						echo '<div id="right_text_form">';
							echo '<input class="field" type="text" size="24" maxlength="50" id="user" value="'.$get['user'].'">';
						echo '</div>';				
						echo '<div id="left_text_form">';
							echo 'Telefon:';
						echo '</div>';
						echo '<div id="right_text_form">';
							echo '<input class="field" type="text" size="24" maxlength="50" id="telefon" value="'.$get['telefon'].'">';
						echo '</div>';
						echo '<div id="left_text_form">';
							echo 'Update Methode:';
						echo '</div>';
						echo '<div id="right_text_form">';
							echo '<select name="router" id="router" class="select">';
								if($get['router'] == 1) {
									echo '<option value="0">Server</option>';
									echo '<option value="1" SELECTED>FritzBox</option>';
									echo '<option value="2">pfSense</option>';
								}
								elseif($get['router'] == 0) {
									echo '<option value="0" SELECTED>Server</option>';
									echo '<option value="1">FritzBox</option>';
									echo '<option value="2">pfSense</option>';
								}
								else {
									echo '<option value="0">Server</option>';
									echo '<option value="1">FritzBox</option>';
									echo '<option value="2" SELECTED>pfSense</option>';
								}
							echo '</select>';
						echo '</div>';
						echo '<div id="left_text_form">';
							//echo 'Telefon:';
						echo '</div>';
						echo '<div id="right_text_form">';
							echo '<input type="submit" name="submit" id="submit" value="Speichern">';
						echo '</div>';
					}
				}
			?>
		</form>
	</div>
</div>

<script type="text/javascript">

$(document).on('click','.adduser_button', function() {
    $('.adduser').slideToggle(250);
});

$("#setpassword1").submit(function(event) {
	var id = $('input#kdnr').val();
	var password = $('input#password').val();
	event.preventDefault();
	if(password == '') {
		$("#emptyfield").fadeIn(250);
	}
	else {
			$.get('function.php?password&password='+password+'&id='+id, function(data) {
			console.log(data);

			if(data == 'true') {
				$("#emptyfield").hide();
				$("#val").hide();
				$("#error").hide();
				$("#success").fadeIn(250).delay(2000).fadeOut(250);
				$("#setpassword1")[0].reset();
			}
			else {
				$("#emptyfield").hide();
				$("#val").hide();
				$("#error").show();
			}
		});
	}
});

$("#changeuser").submit(function(event) {
	var id = $('input#kdnr').val();
	var schoolnumber = $('input#schoolnumber').val();
	var schoolname = $('input#schoolname').val();
	var user = $('input#user').val();
	var telefon = $('input#telefon').val();
	var router = $('#router').children(":selected").val();
	event.preventDefault();
	if(router == '' || user == '' || telefon == '') {
		$("#emptyfield2").fadeIn(250);
	}
	else {
			$.get('function.php?info&schoolnumber='+schoolnumber+'&schoolname='+schoolname+'&user='+user+'&telefon='+telefon+'&router='+router, function(data) {
			console.log(data);

			if(data == 'true') {
				$("#emptyfield").hide();
				$("#val").hide();
				$("#error").hide();
				$("#success2").fadeIn(250).delay(2000).fadeOut(250);
				$("#setpassword1")[0].reset();
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
</body>
</html>