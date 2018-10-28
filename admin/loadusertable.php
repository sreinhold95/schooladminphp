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
<!--            <th>Klasse</th>-->
            <th></th>
        </tr>
        <?php
        $query = $mysqli->query( "SELECT students.surname,students.middlename,students.givenname,students.moregivenname,students.active FROM students,class where students.class=class.classcode and class.token='" . $_SESSION['token'] . "';" );
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