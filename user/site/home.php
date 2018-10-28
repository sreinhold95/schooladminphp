<div class="table_wrap">
    <table class="table" id="user-table">
        <tr>
            <th>Status</th>
            <th>Vorname</th>
            <th>Vorname 2</th>
            <th>Nachname</th>
            <th>Nachname weitere</th>
<!--            <th>Klasse</th>-->
            <th></th>
        </tr>
        <?php
        $query = $mysqli->query( "SELECT students.surname,students.middlename,students.givenname,students.moregivenname,students.active FROM students,class where students.classcode=class.classcode and class.token='" . $_SESSION['token'] . "';" );
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
                //echo '<td>' . $get[ "class" ] . '</td>';
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
				<input type="submit" name="submit" id="submit" value="Speichern">
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
        var token = "";
        <?php
        echo " token = \"".$_SESSION['token']."\";";
        ?>
        event.preventDefault();
        $( ".error_wrap" ).show();
        if ( surname == '' ||  middlename == '' || givenname == '' || moregivenname == '' || birthdate == '' || token == '' ) {
            $( "#emptyfield" ).show();
            if ( isNaN( givenname ) ) {
                $( "#emptyfield" ).hide();
                $( "#val" ).show();
            }
        } else {
            $.get( 'function.php?add_student&surname=' + surname + '&middlename=' + middlename + '&givenname=' + givenname + '&moregivenname=' + moregivenname + '&birthdate=' + birthdate +'&token=' + token, function ( data ) {
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
                document.getElementById('surname').value ="";
                document.getElementById('middlename').value ="";
                document.getElementById('givenname').value ="";
                document.getElementById('moregivenname').value ="";
                document.getElementById('birthdate').value ="";

            } );
        }
    } );
</script>
</body>
