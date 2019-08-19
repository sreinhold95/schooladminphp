<?php
require_once('../include/config.inc.php');
$id=$_GET['id'];
?>
<table class="table" id="user-table">
<?php
	if(isset($_GET["class"])){
			echo '<tr>';
			echo '<th colspan="8" id="classmate">Schüler</th>';
		echo '<tr>';
		echo '<th>lfd. Nr.</th>';
		echo '<th>Status</th>';
		echo '<th>Vorname</th>';
		echo '<th>weiter Vornamen</th>';
		echo '<th>Nachname</th>';
		echo '<th>weiter Nachnamen</th>';
		echo '<th>Schüler bearbeiten';
		echo '<th>inaktiv setzen</th>';
		echo '</tr>';
		$query = $mysqli->query( "SELECT * FROM all_students where classcode='".$id."';" );
		if ( $query->num_rows ) {
			$i=1;
			while ( $get = $query->fetch_assoc() ) {
				echo '<tr>';
				echo '<td>'.$i.'</td>';
				if ( $get[ 'active' ] == 1 ) {
					echo '<td><img src="../style/true.png" alt="active" id="aktiv"></td>';
				} else {
					echo '<td><img src="../style/false.png" alt="active"></td>';
				}
				echo '<td>' . $get[ 'surname' ] . '</td>';
				echo '<td>' . $get[ 'middlename' ] . '</td>';
				echo '<td>' . $get[ 'givenname' ] . '</td>';
				echo '<td>' . $get[ 'moregivenname' ] . '</td>';
				//echo '<td></td>';
				echo '<td>';
				echo '<a href="index.php?site=update&id=' . $get[ 'idstudents' ] . '" class="link"><img src="../style/edit.png" alt="Edit"></a>';
				echo '</td>';
				echo '<td>';
				echo '<a href="javascript:deleteuser(' . $get[ 'idstudents' ] . ')" class="link"><img src="../style/false.png" alt="delete"></a>';
				echo '</td>';
				echo '</tr>';
				$i++;
			}
		}
	}
?>
</table>