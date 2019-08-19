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
	<!--<div id="deleteuser">User konnte nicht gelöscht werden!</div>-->
	<div id="val">Ungültige Zeichen in Schulnummer - nur Zahlen!</div>
	<div id="error">Fehler: Benutzer konnte nicht angelegt werden!</div>
	<div id="success">Action erfolgreich ausgeführt</div>
</div>
<div class="table_wrap">
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
		$query = $mysqli->query( "SELECT * FROM all_students order by givenname;" );
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
				echo '<td>' . $get[ "classcode" ] . '</td>';
				echo '<td>';
				echo '<a href="index.php?site=update&idteacher=' . $classteacher[0]. '&id=' . $get[ 'idstudents' ] . '" class="link"><img src="../style/edit.png" alt="Edit"></a>';
				//echo '<a href="index.php?site=update&id=' . $get[ 'idstudents' ] . '" class="link"><img src="../style/edit.png" alt="Edit"></a>';
				echo '</td>';
				echo '<td>';
				echo '<a href="javascript:deleteuser(' . $get[ 'idstudents' ] . ')" class="link"><img src="../style/false.png" alt="delete"></a>';
				echo '</td>';
				echo '</tr>';
				$i=0;
			}
		}
		?>
	</table>
</div>

<div class="search_wrap">
	<div class="box_header">Benutzer suchen</div>
	<div class="box">
		<form method="POST" action="" id="search" class="form">
			<input class="field" type="text" size="24" maxlength="50" name="search" id="search">
			<input type="submit" name="submit" id="submit" value="Suchen">
		</form>
	</div>
	<br>
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
				echo "<option value=" . $classcode . ">" . $classcode . " , " . $row[ 'longname' ] . "</option>";
			}
			?>
		</select>
	</div>
</div>
</div>

<script type="text/javascript">
	$( "#useranlegen" ).submit( function ( event ) {
		var surname = $( 'input#surname' ).val();
		var middlename = $( 'input#middlename' ).val();
		var givenname = $( 'input#givenname' ).val();
		var moregivenname = $( 'input#moregivenname' ).val();
		var birthdate = $( 'input#birthdate' ).val();
		var classs = $( 'input#classs' ).val();
		event.preventDefault();
		$( ".error_wrap" ).show();
		if ( surname == '' ||  middlename == '' || givenname == '' || moregivenname == '' || birthdate == '' || classs == '' ) {
			$( "#emptyfield" ).show();
			if ( isNaN( givennme ) ) {
				$( "#emptyfield" ).hide();
				$("deleteuser").hide();
				$( "#val" ).show();
			}
		} else {
			$.get( 'function.php?add_student&schoolnumber=' + schoolnumber + '&schoolname=' + schoolname + '&username=' + username + '&isschool=' + isschool + '&ablaufdatum=' + telefon, function ( data ) {
				console.log( data );

				if ( data == 'true' ) {
					$( "#emptyfield" ).hide();
					$( "#val" ).hide();
					$( "#error" ).hide();
					$("deleteuser").hide();
					$( "#success" ).show();
				} else {
					$( "#emptyfield" ).hide();
					$( "#val" ).hide();
					$("deleteuser").hide();
					$( "#error" ).show();
				}

				$.get( 'loadusertable.php', function ( data ) {
					$( '#user-table' ).html( data );
				} );
			} );
		}
	} );


	$( "#search" ).submit( function ( event ) {
		var search = $( 'input#search' ).val();
		event.preventDefault();
		$( ".error_wrap" ).show();
		if ( search == '' ) {
			$( "#searchempty" ).show();
			$( "#searcherror" ).hide();
			//$( "deleteuser" ).hide();

		} else {
			$.get( 'function.php?search&search=' + search, function ( data ) {
				if ( data != 0 ) {
					$( "#searcherror" ).hide();
					$( "#searchempty" ).hide();
					//$("deleteuser").hide();
					$.get( 'function.php?search_result&searchvalue=' + search, function ( data ) {
						$( '#user-table' ).html( data );
					} );
				} else {
					$( "#searcherror" ).show();
					$( "#searchempty" ).hide();
					//$("deleteuser").hide();
					$.get( 'loadusertable.php', function ( data ) {
						$( '#user-table' ).html( data );
					} );
				}
			} );
		}
	} );

	function deleteuser( idstudents ) {
		if ( confirm( "Möchten Sie wirklich löschen" ) )
			if ( idstudents == "" ) {
				$( "#deleteuser" ).show();
				$( "#searcherror" ).hide();
			} else {
				$.get( 'function.php?delete&idstudents=' + idstudents, function ( data ) {
					var jsonobj = JSON.parse( data );
					if ( !jsonobj.success ) {
						$( "#deleteuser" ).show();
						$( "#success" ).hide();
					} else {
						$( "#success" ).show();
						$( "#deleteuser" ).hide();
						$.get( 'loadusertable.php', function ( data ) {
							$( '#user-table' ).html( data );
						} );
					}
				} );
			}
	}

	$( "#classs" ).change( function ( event ) {
		var classcode = $( "#classs option:selected" ).val();
		event.preventDefault();
		if ( classcode == '' ) {
			$( "#searchempty" ).show();
			$( "#searcherror" ).hide();
			$("deleteuser").hide();

		} else {
			$.get( 'function.php?filtern&filter=' + classcode, function ( data ) {
				console.log(data);
				var jsondata = JSON.parse( data );
				if ( jsondata.success ) {
					$( "#searcherror" ).hide();
					$( "#searchempty" ).hide();
					$("deleteuser").hide();
					$.get( 'function.php?filter_result&filter=' + classcode, function ( data ) {
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
	$( "#filter" ).submit( function ( event ) {
		var classcode = $( "#classs option:selected" ).val();
		console.log(classcode);
		event.preventDefault();
		if ( classcode == '' ) {
			$( "#searchempty" ).show();
			$( "#searcherror" ).hide();

		} else {
			$.get( 'function.php?filtern&filter=' + classcode, function ( data ) {
				var jsondata = JSON.parse(data);
				if ( jsondata.success) {
					$( "#searcherror" ).hide();
					$( "#searchempty" ).hide();
					$.get( 'function.php?filter_result&filter=' + classcode, function ( data ) {
						$( '#user-table' ).html( data );
					} );
				} else {
					$( "#searcherror" ).show();
					$( "#searchempty" ).hide();
					$.get( 'loadusertable.php', function ( data ) {
						$( '#user-table' ).html( data );
					} );
				}
			} );
		}
	} );
</script>
</body>
</html>