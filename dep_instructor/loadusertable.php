<?php
require_once('../include/config.inc.php');
?>
<table class="table" id="user-table">
<tr>
			<th></th>
			<th>Vorname</th>
			<th>Nachname</th>
			<th>Klasse</th>
			<th></th>
		</tr>
		<?php
		$query = $mysqli->query( "SELECT * from all_students_from_department where headofdepartment='".$_SESSION["idteacher"]."'");
		if ( $query->num_rows ) {
			while ( $get = $query->fetch_assoc() ) {
				echo '<tr>';
				if ( $get[ 'active' ] == 1 ) {
					echo '<td><img src="../style/true.png" alt="active" id="aktiv"></td>';
				} else {
					echo '<td><img src="../style/false.png" alt="active"></td>';
				}
				echo '<td>' . $get[ 'surname' ] . '</td>';
				echo '<td>' . $get[ 'givenname' ] . '</td>';
				echo '<td>' . $get[ 'classcode' ] . '</td>';
				echo '<td>';
				echo '<a href="index.php?site=update&id=' . $get[ 'idstudents' ] . '" class="link"><img src="../style/edit.png" alt="Edit"></a>';
				echo '<a href="javascript:deleteuser(' . $get[ 'idstudents' ] . ')" class="link"><img src="../style/false.png" alt="delete"></a>';
				echo '</td>';
				echo '</tr>';
			}
		}
        ?>
</table>