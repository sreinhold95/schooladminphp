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
                <label for="middlename" class="label">weitere Vornamen:</label>
                <input class="field" type="text" size="24" maxlength="50" name="middlename" id="middlename">
                <label for="givenname" class="label">Nachname:</label>
                <input class="field" type="text" size="24" maxlength="50" name="givenname" id="givenname">
                <label for="givenname" class="label">weitere Nachnamen:</label>
                <input class="field" type="text" size="24" maxlength="50" name="moregivenname" id="moregivenname">
                <label for="surname" class="label">Adresse:</label>
                <input class="field" type="text" size="24" maxlength="50" name="address" id="address">
                <label for="givenname" class="label">Bundesland:</label>
                <input class="field" type="text" size="24" maxlength="50" name="province" id="province">
                <label for="birthdate" class="label">Geburtsdatum:</label>
                <input class="field" type="date" size="24" maxlength="50" name="birthdate" id="birthdate">
                <label for="middlename" class="label">Geburtsland:</label>
                <input class="field" type="text" size="24" maxlength="50" name="birthcountry" id="birthcountry">
                <label for="givenname" class="label">Geburtsort:</label>
                <input class="field" type="text" size="24" maxlength="50" name="birthtown" id="birthtown">
                <label for="givenname" class="label">Nationalität:</label>
                <input class="field" type="text" size="24" maxlength="50" name="nationality" id="nationality">
                <label for="birthdate" class="label">Muttersprache:</label>
                <input class="field" type="text" size="24" maxlength="50" name="family_speech" id="family_speech">
				<label for="birthdate" class="label">Religion:</label>
                <input class="field" type="text" size="24" maxlength="50" name="religion" id="religion">
                <label for="birthdate" class="label">Telefon:</label>
                <input class="field" type="text" size="24" maxlength="50" name="phone" id="phone">
                <label for="surname" class="label">Mobiltelefon:</label>
                <input class="field" type="text" size="24" maxlength="50" name="mobilephone" id="mobilephone">
                <label for="middlename" class="label">E-Mail:</label>
                <input class="field" type="text" size="24" maxlength="50" name="email" id="email">
                <label for="givenname" class="label">Schulabschluss:</label>
                <select name "graduation"  id="graduation" class="field"size="1">
                    <?php
                    $check = $mysqli->query( "SELECT * FROM graduation;" );
                    while($row = mysqli_fetch_array($check)) {
                        if($row['graduation']!=""){
                            $graduation=$row['graduation'];
							$idgraduation=$row['idgraduation'];
                            echo "<option value=" . $idgraduation . ">" . $graduation . "</option>";
                        }
                    }
                        
                    ?>
                </select>
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
        var token = "";
		var address = $( 'input#address' ).val();
        var province = $( 'input#province' ).val();
        var birthdate = $( 'input#birthdate' ).val();
        var birthtown = $( 'input#birthtown' ).val();
        var birthcountry = $( 'input#birthcountry' ).val();
		var nationality = $( 'input#nationality' ).val();
        var family_speech = $( 'input#family_speech' ).val();
        var phone = $( 'input#phone' ).val();
        var mobilephone = $( 'input#mobilephone' ).val();
        var email = $( 'input#email' ).val();
		var idgraduation = $( '#graduation option:selected' ).val();
		var religion = $( 'input#religion' ).val();
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
            $.get( 'function.php?add_student&surname=' + surname + '&middlename=' + middlename + '&givenname=' + givenname + '&moregivenname=' + moregivenname + '&birthdate=' + birthdate +'&token=' + token +'&address=' + address + '&province=' + province + '&birthtown=' + birthtown + '&birthcountry=' + birthcountry +'&nationality=' + nationality + '&family_speech=' + family_speech + '&phone=' + phone + '&mobilephone=' + mobilephone + '&graduation=' + idgraduation + '&email=' + email+ '&religion=' + religion, function ( data ) {
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
