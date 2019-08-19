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

<div class="error_wrap">
	<div id="searchempty">Oops! Nach was soll ich suchen?</div>
	<div id="searcherror">Leider finde ich keine Schüler mit ihren werten in der Datenbank.</div>
	<div id="emptyfield">Bitte füllen Sie alle Felder korrekt aus!</div>
	<div id="deleteuser">User konnte nicht gelöscht werden!</div>
	<div id="val">Ungültige Zeichen in Schulnummer - nur Zahlen!</div>
	<div id="error">Fehler: Benutzer konnte nicht angelegt werden!</div>
	<div id="success">Action erfolgreich ausgeführt</div>
</div>

<div class="table_wrap">
	<table class="table" id="user-table">
		<tr>
			<th>Klasse</th>
			<th>Langname</th>
			<th></th>
		</tr>
		<?php
		$query = $mysqli->query( "SELECT * FROM class;" );
		if ( $query->num_rows ) {
			while ( $get = $query->fetch_assoc() ) {
				echo '<tr>';
				/*if ( $get[ 'active' ] == 1 ) {
					echo '<td><img src="../style/true.png" alt="active" id="aktiv"></td>';
				} else {
					echo '<td><img src="../style/false.png" alt="active"></td>';
				}*/
				echo '<td>' . $get[ 'classcode' ] . '</td>';
				echo '<td>' . $get[ 'longname' ] . '</td>';				
				echo '<td>';
				echo '<a href="index.php?site=update&idteacher=0&class=1&id=' . $get[ 'classcode' ] . '" class="link"><img src="../style/edit.png" alt="Edit"></a>';
				//echo '</td>';
				//echo '<td>';
				echo '<a href="javascript:deleteuser(' . $get[ 'classcode' ] . ')" class="link"><img src="../style/false.png" alt="delete"></a>';
				echo '</td>';
				echo '</tr>';
			}
		}
		?>
	</table>
</div>
<div class="search_wrap">
	<div class="box_header">Klasse auswählen</div>
		<div class="box">
			<select name="classs" id=classs class="field" size="1">
				<?php
				$check = $mysqli->query( "SELECT * FROM class;" );
				while ( $row = mysqli_fetch_array( $check ) ) {
					if ( $row[ 'classcode' ] != "" ) {
						$classcode = $row[ 'classcode' ];
					} else
						$classcode = "noclass";
					echo "<option value=" . $classcode . ">" . $classcode . "</option>";
				}
				?>
			</select>
		</div>
	</div>
</div>
<script type="text/javascript">

$( "#classs" ).change( function ( event ) {
		var classcode = $( "#classs option:selected" ).val();
		event.preventDefault();
		if ( classcode == '' ) {
			$( "#searchempty" ).show();
			$( "#searcherror" ).hide();
			$("deleteuser").hide();

		} else {
			$.get( 'function.php?class&filter=' + classcode, function ( data ) {
				console.log(data);
				var jsondata = JSON.parse( data );
				if ( jsondata.success ) {
					$( "#searcherror" ).hide();
					$( "#searchempty" ).hide();
					$("deleteuser").hide();
					$.get( 'function.php?filter_class_result1&filter=' + classcode, function ( data ) {
						$( '#user-table' ).html( data );
					} );
				} else {
					$( "#searcherror" ).show();
					$( "#searchempty" ).hide();
					$("deleteuser").hide();
					$.get( 'loadusertable.php', function ( data ) {
						$( '#user-table' ).html( data );
					} );
				}
			} );
		}
	} );
</script>