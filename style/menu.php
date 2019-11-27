	<div id="menu">
		<ul>
			<?php
			if(isset($_SESSION[ 'userrole' ])){
				if ( $_SESSION[ 'userrole' ] == 1 ) {
					?>
						<li><a href="index.php?site=home" class="link">Home</a></li>
						<li><a href="index.php?site=students" class="link">Schüler</a></li>
						<li><a href="index.php?site=teacher" class="link">Lehrer</a></li>
						<li><a href="index.php?site=class" class="link">Klassen</a></li>
						<!--<li><a href="index.php?site=lanisexport" class="link">LANiS Export</a></li>-->
						<li><a href="index.php?site=lanisimport" class="link">LANiS Import</a></li>
						<li><a href="index.php?site=susimport" class="link">SuS Import</a></li>
						<!--<li><a href="index.php?site=lusdimport" class="link">LUSD Import</a></li>-->
						<li><a href="logout.php" class="link">Logout</a></li>
					<?php
				} else if ( $_SESSION[ 'userrole' ] == 3 ) {
					?>
						<li><a href="index.php?site=home" class="link">Home</a></li>
						<li><a href="index.php?site=students" class="link">Schüler</a></li>
						<li><a href="index.php?site=class" class="link">Klassen</a></li>
						<li><a href="logout.php" class="link">Logout</a></li>
					<?php
				} else if ( $_SESSION[ 'userrole' ] == 4 ) {
					?>
						<li><a href="index.php?site=home" class="link">Home</a></li>
						<li><a href="index.php?site=students" class="link">Schüler</a></li>
						<li><a href="index.php?site=teacher" class="link">Lehrer</a></li>
						<li><a href="logout.php" class="link">Logout</a></li>
					
					<?php
				} else if ( $_SESSION[ 'userrole' ] == 2 ) {
					?>
				
						<li><a href="index.php?site=home" class="link">Home</a></li>
						<li><a href="index.php?site=students" class="link">Schüler</a></li>
						<li><a href="index.php?site=class" class="link">Klassen</a></li>
						<li><a href="logout.php" class="link">Logout</a></li>
					
					<?php
				}
				else{
					?>
					<li><a href="logout.php" class="link">Logout</a></li>
					<?php
				}
			}
			else if(isset($_GET["site"])){
				if($_GET["site"]=="homeclass")
				{
					?>
							<li><a href="../index.php" class="link">Startseite</a></li> 
					<?php
				}
				else {
					?>
						<li><a href="class/index.php?site=home" class="link">Registrieren</a></li> 
					<?php
				}
			}
			else {
				?>
					<li><a href="class/index.php?site=homeclass" class="link">Registrieren</a></li>
				<?php
			}
			?>
		</ul>
	</div>
</div>
