<!DOCTYPE html>
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
	<div id="wartung">Herzlich Willkommen in der Schulverwaltung der FLS</div>
	<?php
	include( "style/menu.php" );
	?>
	</div>
	<div class="content">
	<div class="login_wrap">
        <h2>Schüleranmeldung</h2>
		<form method="POST" action="/class/index.php?site=login" class="normal" id="logintoken">
			<input type="submit" class="button" value="zum Token" name="management">
		</form>
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
			<input type="submit" value="Login" name="management">
		</form>
	</div>
		<br>
		<?php include("style/bootstrap.php");?>
</body>
<?php
include("style/footer.php")
?>
</html>