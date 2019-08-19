<?php
if ( isset( $_SESSION[ 'loggedin' ] ) ) {
    $loggedin = $_SESSION[ 'loggedin' ];
}else
    $loggedin = false;
if ( $loggedin == true ) {
    if ( $_SESSION[ 'userrole' ] == 1 ) {
        
    }
} else {
    header( 'location: ../index.php' );
}?>
<?php
require_once('../include/config.inc.php');
?>
<table class="table" id="user-table">
		<tr>
            <th>Status</th>
            <th>Vorname</th>
            <th>Vorname 2</th>
            <th>Nachname</th>
            <th>Nachname weitere</th>
            <th>Klasse</th>
            <th>bearbeiten</th>
			<th>löschen</th>
        </tr>
<?php
		$query = $mysqli->query("SELECT * FROM all_students order by givenname;");
        if ( $query->num_rows ) {
            while ( $get = $query->fetch_assoc() ) {
				$classteacher=[];
				$query1 = $mysqli->query("SELECT idteacher from classteacher where classcode='". $get[ "classcode" ]."';");
				if ( $query1->num_rows ) {
					$i=0;
					while ( $get1 = $query1->fetch_assoc() ) {
						//if($i==1){
							$classteacher[$i]=$get1[ 'idteacher' ];
						//}
						$i++;
					}
				}
                echo '<tr>';
                if ( $get[ 'active' ] == 1 ) {
                    echo '<td><img src="../style/true.png" alt="active" id="aktiv"></td>';
                } else {
                    echo '<td><img src="../style/false.png" alt="active"></td>';
                }
                echo '<td>' . $get[ 'surname' ] . '</td>';
                echo '<td>' . $get[ 'middlename' ] . '</td>';
                echo '<td>' . $get[ 'givenname' ] . '</td>';
				echo '<td>' . $get[ 'moregivenname' ] . '</td>';
				echo '<td>' . $get[ 'classcode' ] . '</td>';
				echo '<td>';
				echo '<a href="index.php?site=update&idteacher=' . $classteacher[0]. '&id=' . $get[ 'idstudents' ] . '" class="link"><img src="../style/edit.png" alt="Edit"></a>';
				echo '</td>';
				echo '<td>';
				echo '<a href="javascript:deleteuser(' . $get[ 'idstudents' ] . ')" class="link"><img src="../style/false.png" alt="delete"></a>';
				echo '</td>';
                echo '</tr>';
            }
        }?>
</table>