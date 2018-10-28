 <?php
	if($_SESSION['userrole'] == 1) {
		?>
		<div id="menu">
			<ul>
				<li><a href="index.php?site=home">Verwaltung</a></li>
<!--				<li><a href="index.php?site=doc">Dokumentation</a></li>-->
				<li><a href="logout.php">Ausloggen</a></li>
			</ul>
		</div>
	</div>
		<?php
	}
	else {
		?>
		<div id="menu">
			<ul>
				<li><a href="index.php?site=home" class="link">Home</a></li>
<!--			<li><a href="index.php?site=settings" class="link">Konfiguration</a></li>
				<li><a href="mailto:info@itservice-hessen.de" class="link">Kontakt</a></li>
				<li><a href="index.php?site=doc" class="link">Dokumentation</a></li>
-->
				<li><a href="logout.php" class="link">Logout</a></li>
			</ul>
		</div>
	</div>
		<?php
	}
?>