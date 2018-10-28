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
	<div class="header"><div id="logo">Verwaltung - FLS</div></div>
	<div class="content">
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
        <div class="login_wrap">
        <h2>Lehrer</h2>
		<form method="POST" action="login.php" class="normal" id="loginmanagement">
			<label>Kürzel:</label>
			<br>
			<input type="text" class="field" id="tusername" name="tusername" />
			<br>
			<label>Passwort:</label>
			<br>
			<input type="password" class="field" id="tpassword" name="tpassword" />
			<br> 
			<input type="submit" class="button" value="Login" name="teacher">
		</form>
	</div>
        <br>
	<div class="login_wrap">
		<h2>neue Schüler</h2>
		<form method="POST" action="login.php" class="normal" id="logintoken">
			<label>Token:</label>
			<br>
			<input type="password" class="field" id="token" name="token" />
			<br> 
			<input type="submit" class="button" value="Login" name="user">
		</form>
	</div>	
</div>
</body>

    <!-- Begin Cookie Consent plugin by Silktide - http://silktide.com/cookieconsent -->
<!--
<script type="text/javascript">
    window.cookieconsent_options = {"message":"FLS verwendet Cookies, um Ihnen den bestmöglichste Nutzungserfahrung zu gewährleisten. Durch die weitere Nutzung der Seite erklären Sie sich mit der Cookie-Nutzung einverstanden.","dismiss":"OK","learnMore":"","link":null,"theme":"dark-top"};
</script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/1.0.9/cookieconsent.min.js"></script>
-->
<!-- End Cookie Consent plugin -->

</html>