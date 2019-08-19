<!DOCTYPE html>
<html>
<head>
	<title>Schulverwaltung- Anmelden</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="style/admincp.css" type="text/css">

</head>
<body>
	<noscript>
 		 <div class="jserror">Bitte aktivieren Sie Javascript, sonst ist der Funktionsumfang eingeschränkt.</div>
	</noscript>
	<div id="wartung">Herzlich Willkommen in der Schulverwaltung der FLS</div>
	<div class="header"><div id="logo">Verwaltung - FLS</div>
	<?php 
	//include( "style/header.php" );
	include( "style/menu.php" );
	?>
	</div>
	<div class="content">
	<div class="login_wrap">
        <h2>Schüleranmeldung</h2>
		<form method="POST" action="/class/index.php?site=homeclass" class="normal" id="loginmanagement">
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
			<input type="submit" class="button" value="Login" name="management">
		</form>
	</div>
        <br>
</body>
</html>