<nav class="navbar navbar-expand-lg navbar-light bg-primary">
	<span class="navbar-brand mb-0 h1">Schulverwaltung</span>
	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">
			<?php
			if(isset($_SESSION[ 'userrole' ])){
				if ( $_SESSION[ 'userrole' ] == 1 ) {
					?>
						<li class="nav-item <?php if($_GET[ 'site' ]=="home") echo "active";?>">
							<a class="nav-link " href="index.php?site=home" >Home</a>
						</li>
						<li class="nav-item <?php if($_GET[ 'site' ]=="students"||$_GET[ 'site' ]=="update") echo "active";?>">
							<a class="nav-link" href="index.php?site=students">Schüler</a>
						</li>
						<li class="nav-item <?php if($_GET[ 'site' ]=="teacher") echo "active";?>">
							<a class="nav-link" href="index.php?site=teacher" >Lehrer</a>
						</li>
						<li class="nav-item <?php if($_GET[ 'site' ]=="class"||$_GET[ 'site' ]=="update.class") echo "active";?>">
							<a class="nav-link" href="index.php?site=class">Klassen</a>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Import</a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="index.php?site=lanisimport">LANiS Import</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.php?site=susimport">SuS Import</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.php?site=import.teacher">Import Lehrer</a>
							</div>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Export</a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="index.php?site=export.lanis">LANiS  Sync Export</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.php?site=export.webuntis">Webuntis Export</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.php?site=export.webuntis.newyear">Webuntis Export Angänger</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.php?site=export.ReNo">Export ReNo Nach,Vor,Mail</a>
							</div>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">sonstiges</a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="index.php?site=schoolyearchange.class">Jahresumzug</a>
								<!-- <div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.php?site=export.webuntis">Webuntis Export</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.php?site=export.webuntis.newyear">Webuntis Export Angänger</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.php?site=export.ReNo">Export ReNo Nach,Vor,Mail</a> -->
							</div>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="logout.php">Logout</a>
							</li>
					<?php
				} else if ( $_SESSION[ 'userrole' ] == 3 ) {
					?>
						<li class="nav-item <?php if($_GET[ 'site' ]=="home") echo "active";?>">
							<a class="nav-link " href="index.php?site=home" >Home</a>
						</li>
						<li class="nav-item <?php if($_GET[ 'site' ]=="students"||$_GET[ 'site' ]=="update") echo "active";?>">
							<a class="nav-link" href="index.php?site=students">Schüler</a>
						</li>
						</li>
						<li class="nav-item <?php if($_GET[ 'site' ]=="update.class"||$_GET[ 'site' ]=="class") echo "active";?>">
							<a class="nav-link" href="index.php?site=class">Klassen</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="logout.php">Logout</a>
							</li>
					<?php
				} else if ( $_SESSION[ 'userrole' ] == 4 ) {
					?>
						<li class="nav-item <?php if($_GET[ 'site' ]=="home") echo "active";?>">
							<a class="nav-link " href="index.php?site=home" >Home</a>
						</li>
						<li class="nav-item <?php if($_GET[ 'site' ]=="students"||$_GET[ 'site' ]=="update") echo "active";?>">
							<a class="nav-link" href="index.php?site=students">Schüler</a>
						</li>
						<li class="nav-item <?php if($_GET[ 'site' ]=="teacher") echo "active";?>">
							<a class="nav-link" href="index.php?site=teacher" >Lehrer</a>
						</li>
						<li class="nav-item <?php if($_GET[ 'site' ]=="update.class"||$_GET[ 'site' ]=="class") echo "active";?>">
							<a class="nav-link" href="index.php?site=class">Klassen</a>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Export</a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="index.php?site=export.all">SuS Export-Alle</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.php?site=export.new">SuS Export-nur neue Seit letzer Anmeldung</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.php?site=export.webuntis">Webuntis Export</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.php?site=export.webuntis.newyear">Webuntis Export Angänger</a>
							</div>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="logout.php">Logout</a>
							</li>
					<?php
				} else if ( $_SESSION[ 'userrole' ] == 2 ) {
					?>
					<li class="nav-item <?php if($_GET[ 'site' ]=="home") echo "active";?>">
						<a class="nav-link " href="index.php?site=home" >Home</a>
					</li>
					<li class="nav-item <?php if($_GET[ 'site' ]=="students"||$_GET[ 'site' ]=="update") echo "active";?>">
						<a class="nav-link" href="index.php?site=students">Schüler</a>
					</li>
					<li class="nav-item <?php if($_GET[ 'site' ]=="class"||$_GET[ 'site' ]=="update.class") echo "active";?>">
						<a class="nav-link" href="index.php?site=class">Klassen</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="logout.php">Logout</a>
					</li>
					<?php
				}
				else{
					?>
					<li class="nav-item "><a href="logout.php">Logout</a></li>
					<?php
				}
			}
			else if(isset($_GET["site"])){
				if($_GET["site"]=="homeclass")
				{
					?>
							<li class="nav-item "><a href="../index.php">Startseite</a></li> 
					<?php
				}
				else {
					?>
						<li class="nav-item active"><a href="class/index.php?site=home">Registrieren</a></li> 
					<?php
				}
			}
			else {
				?>
					<li class="nav-item active"><a href="class/index.php?site=homeclass">Registrieren</a></li>
				<?php
			}
			?>
		</ul>
	</div>
</nav>