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
			<th>Status</th>
			<th>Vorname</th>
			<th>Vorname 2</th>
			<th>Nachname</th>
			<!--<th>Nachname weitere</th>-->
			<th>Kürzel</th>
			<th>Zeugnis Name</th>
			<th></th>
		</tr>
		<?php
		$query = $mysqli->query( "SELECT * FROM adminteacher;" );
		if ( $query->num_rows ) {
			while ( $get = $query->fetch_assoc() ) {
				echo '<tr>';
				if ( $get[ 'active' ] == 1 ) {
					echo '<td><img src="../style/true.png" alt="active" id="aktiv"></td>';
				} else {
					echo '<td><img src="../style/false.png" alt="active"></td>';
				}
				echo '<td>' . $get[ 'surname' ] . '</td>';
				echo '<td>' . $get[ 'middlename' ] . '</td>';
				echo '<td>' . $get[ 'lastname' ] . '</td>';
				//echo '<td>' . $get[ 'moregivenname' ] . '</td>';
				echo '<td>' . $get[ "initials" ] . '</td>';
				echo '<td>' . $get[ "name" ] . '</td>';
				echo '<td>';
				echo '<a href="index.php?site=teacherupdate&idteacher=' . $get[ 'idteacher' ] . '" class="link"><img src="../style/edit.png" alt="Edit"></a>';
				//echo '</td>';
				//echo '<td>';
				echo '<a href="javascript:deleteuser(' . $get[ 'idteacher' ] . ')" class="link"><img src="../style/false.png" alt="delete"></a>';
				echo '</td>';
				echo '</tr>';
			}
		}
		?>
	</table>
</div>

<div class="search_wrap">
	<div class="box_header">Lehrer suchen</div>
	<div class="box">
		<form method="POST" action="" id="search" class="form">
			<input class="field" type="text" size="24" maxlength="50" name="search" id="search">
			<input type="submit" name="submit" id="submit" value="Suchen">
		</form>
	</div>
	<br>
	<div class="box_header">Abteilung auswählen</div>
	<div class="box">
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
			$( "deleteuser" ).hide();

		} else {
			$.get( 'function.php?teachersearch&search=' + search, function ( data ) {
				if ( data != 0 ) {
					$( "#searcherror" ).hide();
					$( "#searchempty" ).hide();
					$("deleteuser").hide();
					$.get( 'function.php?teachersearch_result&searchvalue=' + search, function ( data ) {
						$( '#user-table' ).html( data );
					} );
				} else {
					$( "#searcherror" ).show();
					$( "#searchempty" ).hide();
					$("deleteuser").hide();
					$.get( 'teacherloadusertable.php', function ( data ) {
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
				$.get( 'function.php?teacherdelete&idstudents=' + idstudents, function ( data ) {
					var jsonobj = JSON.parse( data );
					if ( !jsonobj.success ) {
						$( "#deleteuser" ).show();
						$( "#success" ).hide();
					} else {
						$( "#success" ).show();
						$( "#deleteuser" ).hide();
						$.get( 'loadteachertable.php', function ( data ) {
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