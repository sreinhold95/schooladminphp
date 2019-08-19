<?php

if ( isset( $_SESSION[ 'loggedin' ] ) ) {
    $loggedin = $_SESSION[ 'loggedin' ];
}else
    $loggedin = false;
if ( $loggedin == true ) {
    if ( $_SESSION[ 'userrole' ] == 1 ) {
        if(isset($_GET['idteacher']))
            $idteacher=$_GET["idteacher"];
        else 
        {
            $_SERVER['HTTP_REFERER'];
            header('Location:'.$_SERVER['HTTP_REFERER']);   
        }
    }
} else {
    header( 'location: ../../index.php' );
}
?>

<div class="error_wrap">
	<div id="emptyfield">Bitte füllen Sie diese Felder korrekt aus:</div>
	<div id="error_username">Ohne Benutzername ist ein zurücksetzen nicht möglich!</div>
	<div id="val">Ungültige Zeichen in Schulnummer - nur Zahlen!</div>
	<div id="error">Fehler: Benutzer konnte nicht aktualisiert werden!</div>
	<div id="success">Benutzerdaten erfolgreich aktualisiert.</div>
	<div id="password_reset_success">Das Passwort wurde auf das Erstpasswort zurück gesetzt.</div>
</div>



<div class="table_wrap">
<?php
    $query = $mysqli->query("SELECT * FROM teacherinf WHERE idteacher='".$idteacher."'");
				if($query->num_rows) {
                    $i=0;
					while($get = $query->fetch_assoc()) {
                        //if ($i=0){
                        ?>
                        <table class="table" id="anlegen-table">
                            <tr>
                                <th>Lehrer</th>
                            </tr>
                            <tr>
                                <td>
                                    <label for="surname" class="label">Vorname:</label>
                                    <input class="field" type="text" size="24" maxlength="50" name="surname" id="surname" value="<?php echo $get['Vorname']; ?>">
                                    <label for="middlename" class="label">weitere Vornamen:</label>
                                    <input class="field" type="text" size="24" maxlength="50" name="middlename" id="middlename" value="<?php echo $get['middlename']; ?>">
                                    <label class="label">Nachname:</label>
                                    <input class="field" type="text" size="24" maxlength="50" name="givenname" id="givenname" value="<?php echo $get['Nachname']; ?>">
                                    <label for="moregivenname" class="label">weitere Nachnamen:</label>
                                    <input class="field" type="text" size="24" maxlength="50" name="moregivenname" id="moregivenname" value="<?php echo $get['moregivenname']; ?>">
                                </td>
                             </tr>
                        </table>
                        <?php
                        //}
                        //$i++;
                    }
                }
?>
</div>


<style>
	.left{
		width:360px;
	}
	.table_wrap {
		float:left;
		color: #303030;
		font-weight: 400;
		font-size: 12px;
		background: #fff;
		padding: 10px;
		-webkit-border-radius: 8px;
		-moz-border-radius: 8px;
		border-radius: 8px;
		border: 1px solid #D4D4D4;
		margin-bottom: 10px;
	}
	#anlegen-table td{
		width:230px;
	}
	#anlegen-table th{
		width:230px;
	}
	#Ausbildungsbetrieb-table th{
		width:230px;
	}
	#Ausbildungsbetrieb-table td{
		width:230px;
	}
	#Schulbildung-table th{
		width:230px;
	}
	#Schulbildung-table td{
		width:230px;
	}
	.tabl1e_wrap {
		float: left;
		width: 499px;
		color: #303030;
		font-weight: 400;
		font-size: 12px;
		background: #fff;
		padding: 10px;
		-webkit-border-radius: 8px;
		-moz-border-radius: 8px;
		border-radius: 8px;
		border: 1px solid #D4D4D4;
		margin-bottom: 10px;
	}
</style>