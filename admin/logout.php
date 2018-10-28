<?php
	
	session_start();
	session_destroy();
	unlink ( SESSION_FILE_DIR . '/sess_' . session_id());
	
	header('location: ../index.php')

?>