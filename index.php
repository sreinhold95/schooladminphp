﻿<!DOCTYPE html>
<html>
<?php
include("style/header.php");
//ini_set('session.gc_maxlifetime', 10*60); 
//ini_set('session.cookie_lifetime', 10*60);
session_start();

?>
<body>
	<noscript>
 		 <div class="jserror">Bitte aktivieren Sie Javascript, sonst ist der Funktionsumfang eingeschränkt.</div>
	</noscript>
	<div id="wartung">Herzlich Willkommen in der Schulverwaltung im BSZN</div>
	<?php
	include( "style/menu.php" );
	?>
	</div>
	<div class="content">
	<div class="login_wrap">
        <h2>Schüleranmeldung</h2>
		<form method="POST" action="/class/index.php?site=login" class="normal" id="logintoken">
			<input type="submit" class="button" value="zum Token" id="login_token" name="management">
		</form>
		<h8><a href="Schulordnung_ab August_2020.pdf" target="_blank">Schulordnung der FLS</a><h8><br>
		<h8><a href="Datenschutzerklärung.pdf" target="_blank">Datenschutzerklärung der FLS</a><h8><br>
		<h8><a href="Nutzungsordnung für PC_ab August_2020.pdf" target="_blank">EDV Nutzererklärung der FLS</a><h8><br>
		<h8><a href="https://app.edkimo.com/feedback/fuluwgi" target="_blank">Umfrage Digitales Arbeiten der FLS</a><h8>
		
	</div>
	<br>
	 <div class="login_wrap">
        <h2>Verwaltung</h2>
		<form method="POST" action="login.php" class="normal" id="loginmanagement">
			<label>Username:</label>
			<br>
			<input type="text" class="field" id="username" name="username" />
			<br>
			<label>Passwort:</label>
			<br>
			<input type="password" class="field" id="password" name="password" />
			<br> 
			<input type="submit" value="Login" id="login_user" name="management">
		</form>
	</div>
		<br>
		<?php include("style/bootstrap.php");?>
<?php
include("style/footer.php")
?>
</body>
</html>
<script>
	
	/*const loginForm = document.getElementById("loginmanagement");
	const loginButton = document.getElementById("login_user");
	loginButton.addEventListener("click", (e)=>{
		login(loginForm);
	});
	function login(form) {
		$.ajax({
			type: "POST",
			url: "api/v2/login.php",
			dataType: "json",
			data : {'username':form.username,'password':form.password},
			cache:false,
			
			success: function(data){
				console.log(data);
				if(data.success == "true" )
				{
					alert(' You are successfully log in .. ' );
				}
				},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log(xhr.status);
				console.log(thrownError);
				}
			});
	}*/

</script>