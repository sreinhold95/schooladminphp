<?php
$session_timeout = 600; // 1800 Sek./60 Sek. = 10 Minuten
session_start();
if((time() - $_SESSION['last_visit']) > $session_timeout) {
session_destroy();
session_unset();
header( 'location: ../index.php' );
// Aktion der Session wird erneut ausgeführt
}
$_SESSION['last_visit'] = time();
    if ( isset( $_SESSION[ 'loggedin' ] ) ) {
        $loggedin = $_SESSION[ 'loggedin' ];
    }else
        $loggedin = false;
    if ( $loggedin == true ) {
        if ( $_SESSION[ 'userrole' ] == 1 ) {
            
        }
    } else {
        header( 'location: ../index.php' );
    }

    $id = $_GET['id'];
	$classcode="";
	$activetoken="";
	$classteacher="";
	$idparents=0;
	$_SESSION['classcode']=$id;
	if(isset($_GET['idteacher'])){
		$idteacher=$_GET["idteacher"];
	}
	else if(isset($_GET['id'])){
		$id = $_GET['id'];
	}
	else 
	{
		$_SERVER['HTTP_REFERER'];
		header('Location:'.$_SERVER['HTTP_REFERER']);   
	}
?>

<div class="d-flex">
    <div class ="p-2">
        <div class="add_wrap">
                <div class="box_header">Klasse allgemein</div>
                <div class="box">
                    <table class="table" id="side-table">
                    <?php
                        $query = $mysqli->query("SELECT * FROM class WHERE classcode='".$id."'");
                        if($query->num_rows) {
                            while($get = $query->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>Klasse:</td>';
                                echo '<td>'.$get['classcode'].'</td>';
                                $classcode=$get['classcode'];
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td>Langname:</td>';
                                echo '<td>'.$get['longname'].'</td>';
                                echo '</tr>';
                            }
                        }
                    ?>
                    </table>
                </div>
        </div>
        <div class="add_wrap">
            <div class="box_header">Klassendaten</div>
                <div class="box">
                    <table class="table" id="side-table">
                        <?php
                        $query1 = $mysqli->query( "Update class set activetoken=0 where classcode='".$id."'and TIMESTAMPDIFF(MINUTE,tokenactivateat, NOW())>15;" );
                        if(isset($_GET["class"])){
                            $query=$mysqli->query("select * from classinformation where classcode='".$id."';");
                            $query1=$mysqli->query("select * from classinformation where classcode='".$id."';");
                            if($query->num_rows) {
                                while($get = $query1->fetch_assoc()) {
                                    $get2 = $query1->fetch_assoc();
                                    echo '<tr>';
                                    echo '<td>Klassenlehrer:</td>';
                                    if(isset($get2['teachername']))
                                        echo '<td>'.$get['teachername'].'<br>'.$get2['teachername'].'</td>';
                                    else
                                        echo '<td>'.$get['teachername'].'</td>';
                                    echo '</tr>';
                                    echo '<tr>';
                                    echo '<td>Schülertoken <br> (grüner Haken activ):</td>';
                                    echo '<td>'.$get['token'].'<br>';
                                    $activetoken=$get[ 'activetoken' ];
                                    if ( $get[ 'activetoken' ] == 1 ) {
                                        echo '<img src="../../style/true.png" alt="active" id="aktiv"></td>';
                                    } else {
                                        echo '<img src="../../style/false.png" alt="active"></td>';
                                    }
                                    echo '<tr>';
                                    echo '<tr>';
                                    echo '<td>Abteilung:</td>';
                                    echo '<td>'.$get['name'].'</td>';
                                    echo '</tr>';
                                    echo '<td>Abteilungsleiter:</td>';
                                    if(isset($get['hodname']))
                                        echo '<td>'.$get['hodname'].'</td>';
                                    else
                                        echo '<td></td>';
                                    echo '</tr>';
                                    $classteacher=$get["idteacher"];
                                }
                            }
                        }
                        ?>
                    </table>
                </div>
        </div>
        <div class="add_wrap">
            <div class="box_header">Klasseneinstellungen</div>
                <div class="box">
                    <table class="table" id="side-table">
                        <tr>
                        <td>
                            Stammbogen Klasse<br>drucken
                        </td>
                        <td>
                        </form>
                            <?php
                                echo '<a href="../api/v2/stammblatt.php?uuid=' . $_COOKIE["uuid"]. '&classcode=' . $classcode. '" target="_blank" class="link"><img src="../style/print1.jpg" alt="Edit"></a>';
                            ?>
                        </td>
                        </tr>
                            <form method="POST" action="" id="Klasseneinstellungen" class="form">
                            <?php
                                if($activetoken == "1") {
                                    echo '<tr>';
                                    echo '<td>Schüler:</td>';
                                    echo '<td><input type="radio" name="activate" id="activate" value="1" CHECKED>aktiver Token';
                                    echo '<br>';
                                    echo '<input type="radio" name="activate" id="activate" value="0">inaktiver Token</td>';
                                    echo '</tr>';
                                }
                                else {
                                    echo '<tr>';
                                    echo '<td>Token:</td>';
                                    echo '<td><input type="radio" name="activate" id="activate" value="1" >aktiver Token';
                                    echo '<br>';
                                    echo '<input type="radio" name="activate" id="activate" value="0" CHECKED>inaktiver Token</td>';
                                    echo '</tr>';
                                }
                                echo '<tr>';
                                echo '<td></td>';
                                echo '<td><input type="submit" name="submit" id="submit" value="Speichern"></td>';
                                echo '</tr>';
                            ?>
                        </form>
                    </table>
                </div>
        </div>
    </div>
    
    <div class="p-2">
        <div class="content_allg">
            <table class="table" id="students">
                <thead>
                    <!-- <tr>
                        <th colspan="9" id="classmate">Schüler</th>
                    </tr> -->
                    <tr>
                        <th>#</th>
                        <th>Status</th>
                        <th>Vorname</th>
                        <th>weitere VN</th>
                        <th>Nachname</th>
                        <th>weitere NN</th>
                        <th>drucken</th>
                        <th>bearbeiten</th>
                        <!-- <th>löschen</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                        /*$query = $mysqli->query( "SELECT * FROM all_students where classcode='".$id."' order by givenname;" );
                        if ( $query->num_rows ) {
                            $i=1;
                            while ( $get = $query->fetch_assoc() ) {
                                echo '<tr>';
                                echo '<td>'.$i.'</td>';
                                if ( $get[ 'active' ] == 1 ) {
                                    echo '<td><img src="../style/true.png" alt="active" id="aktiv"></td>';
                                } 
                                else 
                                {
                                    echo '<td><img src="../style/false.png" alt="active"></td>';
                                }
                                if(mb_detect_encoding ($get[ 'surname' ])=="ASCII")
                                    echo '<td>' . utf8_decode($get[ 'surname' ]) . '</td>';
                                else
                                    echo '<td>' . $get[ 'surname' ] . '</td>';
                                if(mb_detect_encoding ($get[ 'middlename' ])=="ASCII")
                                    echo '<td>' .utf8_decode( $get[ 'middlename' ]) . '</td>';
                                else
                                    echo '<td>' . $get[ 'middlename' ] . '</td>';
                                if(mb_detect_encoding ($get[ 'givenname' ])=="ASCII")
                                    echo '<td>' . utf8_decode ($get[ 'givenname' ]) . '</td>';
                                else
                                    echo '<td>' . $get[ 'givenname' ] . '</td>';
                                if(mb_detect_encoding ($get[ 'moregivenname' ])=="ASCII")
                                    echo '<td>' . utf8_decode($get[ 'moregivenname' ]) . '</td>';
                                else
                                    echo '<td>' . $get[ 'moregivenname' ] . '</td>';
                                echo '<td>';
                                echo '<a href="../../pdf/stammblattsus.php?idteacher=' . $classteacher. '&classcode=' . $classcode. '&idstudents=' . $get['idstudents']. '" class="link"><img src="../style/print.png" target="_blank" alt="Edit"></a>';
                                echo '</td>';
                                echo '<td>';
                                echo '<a href="index.php?site=update&idteacher=' . $classteacher . '&id=' . $get[ 'idstudents' ] . '" class="link"><img src="../style/edit.png" alt="Edit" target="_blank"></a>';
                                echo '</td>';
                                echo '<td>';
                                echo '<a href="javascript:deleteuser(' . $get[ 'idstudents' ] . ')" class="link"><img src="../style/false.png" alt="delete"></a>';
                                echo '</td>';
                                echo '</tr>';
                                $i++;
                            }
                        }*/
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<style>
.content_allg #students{
    width:900px !important;
    height:800px !important;
}
</style>