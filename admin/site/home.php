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
	<h1>Adminbereich</h1>
	<h2>Hallo</h2>
</div>

<div class="search_wrap">
	<div class="box_header">Box 1</div>
	<div class="box">
		
	</div>
	<br>
	<div class="box_header">Box 2</div>
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
			$.get( 'function.php?search&search=' + search, function ( data ) {
				if ( data != 0 ) {
					$( "#searcherror" ).hide();
					$( "#searchempty" ).hide();
					$("deleteuser").hide();
					$.get( 'function.php?search_result&searchvalue=' + search, function ( data ) {
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