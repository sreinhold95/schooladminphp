<?php
	session_start();
	$session_timeout = 600; // 1800 Sek./60 Sek. = 10 Minuten
	if (!isset($_SESSION['last_visit'])) {
	$_SESSION['last_visit'] = time();
	// Aktion der Session wird ausgeführt
	}
	if((time() - $_SESSION['last_visit']) > $session_timeout) {
	session_destroy();
	session_unset();
	header( 'location: ../index.php' );
	// Aktion der Session wird erneut ausgeführt
	}
	$_SESSION['last_visit'] = time();
    ?>
<div class="d-flex flex-column content_allg">
<button class="btn btn-primary export" id="export" type="button">Download Excel</button>
<div class="students" id="students"></div>
</div>