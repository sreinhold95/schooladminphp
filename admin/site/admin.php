<div class="error_wrap">
    <div id="searchempty">Oops! Nach was soll ich suchen?</div>
    <div id="searcherror">Leider finde ich keine Werte in der Datenbank. Das tut mir leid.</div>
    <div id="emptyfield">Bitte füllen Sie alle Felder korrekt aus!</div>
    <div id="val">Ungültige Zeichen in Schulnummer - nur Zahlen!</div>
    <div id="error">Fehler: Benutzer konnte nicht angelegt werden!</div>
    <div id="success">Benutzer wurde erfolgreich angelegt!</div>
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
            <th></th>
        </tr>
        <?php
        $query = $mysqli->query( "SELECT * FROM students;" );
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
                echo '<td>' . $get[ 'givenname' ] . '</td>';
                echo '<td>' . $get[ 'moregivenname' ] . '</td>';
                echo '<td>' . $get[ "classcode" ] . '</td>';
                echo '<td>';
                echo '<a href="index.php?site=update&id=' . $get[ 'idstudents' ] . '" class="link"><img src="../style/edit.png" alt="Edit"></a>';
                echo '</td>';
                echo '</tr>';
            }
        }
        ?>
    </table>
</div>

<div class="left">
    <div class="add_wrap">
        <div class="box_header">Neuen Schüler anlegen</div>
        <div class="box">
            <form method="POST" action="" id="useranlegen" class="form">
                <label for="surname" class="label">Vorname:</label>
                <input class="field" type="text" size="24" maxlength="50" name="surname" id="surname">
                <label for="middlename" class="label">2ter Vorname:</label>
                <input class="field" type="text" size="24" maxlength="50" name="middlename" id="middlename">
                <label for="givenname" class="label">Nachname:</label>
                <input class="field" type="text" size="24" maxlength="50" name="givenname" id="givenname">
                <label for="givenname" class="label">weitere Nachnamen:</label>
                <input class="field" type="text" size="24" maxlength="50" name="moregivenname" id="moregivenname">
                <label for="birthdate" class="label">Geburtsdatum:</label>
                <input class="field" type="date" size="24" maxlength="50" name="birthdate" id="birthdate">
                <label for="classs" class="label">Klasse:</label>
                <select name "classs" class="field"size="1">
                    <?php
                    $check = $mysqli->query( "SELECT * FROM class;" );
                    while($row = mysqli_fetch_array($check)) {
                        if($row['classcode']!=""){
                            $classcode=$row['classcode'];
                        }else
                            $classcode="noclass";
                        echo "<option value=" . $classcode . ">" . $classcode . " , " . $row['longname'] . "</option>";
                    }
                    ?>
                </select>
				<input type="submit" name="submit" id="submit" value="Speichern">
            </form>
        </div>
    </div>
    <div class="search_wrap">
        <div class="box_header">Benutzer suchen</div>
        <div class="box">
            <form method="POST" action="" id="search" class="form">
                <input class="field" type="text" size="24" maxlength="50" name="serach_schoolnumber" id="search_schoolnumber">
                <input type="submit" name="submit" id="submit" value="Suchen">
            </form>
        </div>
    </div>
    <div class="search_wrap">
        <div class="box_header">Filterung</div>
        <div class="box">
            <form method="POST" action="" id="filter" class="form">
                <input type="radio" name="isschool" id="isschool" value="0"> Schule<br>
                <input type="radio" name="isschool" id="isschool" value="1"> Firma<br>
                <input type="radio" name="isschool" id="isschool" value="2"> Privat<br>
                <input type="submit" name="submit" id="submit" value="Filtern">
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( document ).on( 'click', '.adduser_button', function () {
        $( '.adduser' ).slideToggle( 250 );
    } );

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
                $( "#val" ).show();
            }
        } else {
            $.get( 'function.php?add_student&schoolnumber=' + schoolnumber + '&schoolname=' + schoolname + '&username=' + username + '&isschool=' + isschool + '&ablaufdatum=' + telefon, function ( data ) {
                console.log( data );

                if ( data == 'true' ) {
                    $( "#emptyfield" ).hide();
                    $( "#val" ).hide();
                    $( "#error" ).hide();
                    $( "#success" ).show();
                } else {
                    $( "#emptyfield" ).hide();
                    $( "#val" ).hide();
                    $( "#error" ).show();
                }

                $.get( 'loadusertable.php', function ( data ) {
                    $( '#user-table' ).html( data );
                } );
            } );
        }
    } );


    $( "#search" ).submit( function ( event ) {
        var sschoolnumber = $( 'input#search_schoolnumber' ).val();
        event.preventDefault();
        $( ".error_wrap" ).show();
        if ( sschoolnumber == '' ) {
            $( "#searchempty" ).show();
            $( "#searcherror" ).hide();

        } else {
            $.get( 'function.php?search&search=' + sschoolnumber, function ( data ) {
                console.log( data );
                if ( data != 0 ) {
                    $( "#searcherror" ).hide();
                    $( "#searchempty" ).hide();
                    $.get( 'function.php?search_result&searchvalue=' + sschoolnumber, function ( data ) {
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

    $( "#filter" ).submit( function ( event ) {
        var sschoolnumber = $( 'input#isschool:CHECKED' ).val();
        event.preventDefault();
        if ( sschoolnumber == '' ) {
            $( "#searchempty" ).show();
            $( "#searcherror" ).hide();

        } else {
            $.get( 'function.php?filtern&filter=' + sschoolnumber, function ( data ) {
                console.log( data );
                if ( data != 0 ) {
                    $( "#searcherror" ).hide();
                    $( "#searchempty" ).hide();
                    $.get( 'function.php?filter_result&filter=' + sschoolnumber, function ( data ) {
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