<?php
require $_SERVER['DOCUMENT_ROOT'] . '/include/config.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/tcpdf/tcpdf.php';
//session_start();
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: deny');
ini_set('error_reporting', E_ERROR);
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $headers = apache_request_headers();
    $json = json_encode($headers);
    $uuid = $headers['uuid'];
    $idstudent = $_GET["student"];
}

$pdfName = "Stammblatt.pdf";
$pdfAuthor = "FLS Darmstadt";
$datenow = date("d.m.Y H:i:s");
$auth = false;
$role = 0;
$idteacher = 0;
$susuname = "";
$data = array();
if (!isset($uuid))
    $uuid = $_GET["uuid"];
//uuid teacher-ID
$check = $mysqli->query("select teacher,role,school from user where uuid='" . $mysqli->real_escape_string($uuid) . "' and uuidlifetime>=DATE_SUB(NOW(),INTERVAL 24 HOUR)");
if ($check->num_rows) {
    while ($row = $check->fetch_assoc()) {
        if (isset($row["role"])) {
            $role = $row["role"];
            $school=$row["school"];
        }
        if (isset($row["teacher"])) {
            $idteacher = $row["teacher"];
        }
        $auth = true;
    }
}
if ($auth) {
    //////////////////////////// Inhalt des PDFs als HTML-Code \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    // Erstellung des HTML-Codes. Dieser HTML-Code definiert das Aussehen eures PDFs.
    // tcpdf unterstützt recht viele HTML-Befehle. Die Nutzung von CSS ist allerdings
    // stark eingeschränkt.
    if ($role == 3) {
        $query = $mysqli->query("SELECT * from all_studentspdf inner join teacher_class on all_studentspdf.classcode=teacher_class.classcode inner join teacher on teacher_class.idteacher=teacher.idteacher where all_studentspdf.active=1 and all_studentspdf.idstudents='" . $mysqli->real_escape_string($idstudent) . "' and teacher_class.idteacher='" . $mysqli->real_escape_string($idteacher) . "'and all_studentspdf.school='".$mysqli->real_escape_string($school)."';");
    } else if ($role == 2) {
        $query = $mysqli->query("SELECT * from all_studentspdf inner join teacher_class on all_studentspdf.classcode=teacher_class.classcode inner join teacher on teacher_class.idteacher=teacher.idteacher where all_studentspdf.active=1 and all_studentspdf.idstudents='" . $mysqli->real_escape_string($idstudent) . "' and headidteacher='" . $mysqli->real_escape_string($idteacher) . "' and all_studentspdf.school='".$mysqli->real_escape_string($school)."' limit 1;");
    } else if ($role == 1) {
        $query = $mysqli->query("SELECT * from all_studentspdf inner join teacher_class on all_studentspdf.classcode=teacher_class.classcode inner join teacher on teacher_class.idteacher=teacher.idteacher where all_studentspdf.active=1 and all_studentspdf.idstudents='" . $mysqli->real_escape_string($idstudent) . "' limit 1;");
    }else if ($role == 4) {
        $query = $mysqli->query("SELECT * from all_studentspdf inner join teacher_class on all_studentspdf.classcode=teacher_class.classcode inner join teacher on teacher_class.idteacher=teacher.idteacher where all_studentspdf.active=1 and all_studentspdf.idstudents='" . $mysqli->real_escape_string($idstudent) . "' and all_studentspdf.school='".$mysqli->real_escape_string($school)."' limit 1;");
    }
    if ($query->num_rows) {
        $html = array();
        $htmlsusanmeldung = array();
        $i = 0;
        while ($get = $query->fetch_assoc()) {
            $pdfName = $get['ssurname'] . '_' . $get['sgivenname'] . ".pdf";
            $susname = $get['ssurname'] . ' ' . $get['sgivenname'];
            if ($get["Schulform"] == "Teilzeit") {
                $html[$i] = '
                <span style="text-allign:justify;"><h1>Stammbogen der '.$get[ 'schoolname' ].' (' . $get['Schulform'] . ')</h3></span>
                <table>
                    <tbody>
                        <tr>
                            <td>
                                <b>Klasse: ' . $get['classcode'] . '</b>
                            </td>
                            <td>
                                <b>Lehrer: ' . $get['surname'] . ' ' . $get['lastname'] . '</b>
                            </td>
                        </tr>
                    <tbody>
                </table>
                <b>Ausbildungsdaten</b>
                <br>
                <table border="0">
                <tr>
                    <td>
                        <b>Ausbildungsbeginn:</b>
                    </td>
                    <td>' . $newDate = date("d.m.Y", strtotime($get['Ausbildungsbeginn'])) . '</td>
                </tr> 
                <tr>
                    <td>
                        <b>Ausbildungsberuf:</b>
                    </td> 
                    <td>' . $get['Beruf'] . '
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Ausbildungsbetrieb:</b> 
                    </td> 
                    <td>' . $get['Ausbildungsbetrieb'] . '
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Straße:</b> 
                    </td> 
                    <td>' . $get['Ausbildungsbetrieb_Strasse'] . '
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>PLZ,Ort: </b>
                    </td> 
                    <td>' . $get['Ausbildungsbetrieb_PLZ'] . ', ' . $get['Ausbildungsbetrieb_Ort'] . '
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Telefon/Fax</b> 
                    </td>
                    <td>' . $get['Ausbildungsbetrieb_Telefon'] . ' / ' . $get['Ausbildungsbetrieb_Fax'] . ' 
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>E-Mail:</b>  
                    </td> 
                    <td>' . $get['Ausbildungsbetrieb_Email'] . '
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Ausbilder/in/Ansprechpartner/in:</b> 
                    </td> 
                    <td>' . $get['Ausbildungsbetrieb_Ausbilder_Anrede'] . ' ' . $get['Ausbildungsbetrieb_Ausbilder_Name'] . '
                    </td>
                </tr>
                </table>
                    <h3>Persönliche Daten der/des Auszubildenden:</h3>
                    <table border="0" cellpadding="1" cellspacing="1" nobr="true">
                    <thead>
                        <tr>
                        <td><b>Nachname:</b></td>
                        <td>' . $get['sgivenname'] . ' ' . $get['smoregivenname'] . '</td>
                        <td><b>Vorname:</b></td>
                        <td width="160">' . $get['ssurname'] . ' ' . $get['smiddlename'] . '</td>
                        </tr>
                    </thead>
                    </table>
                    <h4>- Geburtsdaten -</h4>
                    <table border="0" cellpadding="1" cellspacing="1" nobr="true">
                    <tbody>
                        <tr>
                            <td><b>Gerburtsdatum:</b></td>
                            <td width="150">' . $newDate = date("d.m.Y", strtotime($get['birthdate'])) . '</td>
                            <td><b>Geschlecht:</b></td>
                            <td>' . $get['geschlecht'] . '</td>
                        </tr>
                        <tr>
                            <td><b>Geburtsort:</b></td>
                            <td>' . $get['birthtown'] . '</td>
                            <td><b>Geburtsland:</b></td>
                            <td>' . $get['birthcountry'] . '</td>
                        </tr>
                        </table>
                        <h4>- Sprachniveau/Nationalität/Religion -</h4>
                        <table border="0">
                        <tr>
                            <td width="150"><b>In Deutschland seit:</b></td>
                            <td width="150">' . $newDate = date("d.m.Y", strtotime($get['indeutschlandseit'])) . '</td>
                            <td><b>Sprachniveau:</b></td>
                            <td>' . $get['sprachniveau'] . '</td>
                        </tr>
                        <tr>
                            <td><b>Religion/Konfession</b></td>
                            <td>' . $get['religion'] . '</td>
                            <td><b>Staatsangehörigkeit:</b></td>
                            <td>' . $get['nationality'] . '</td>
                        </tr>
                        </table>
                        <h4>- Wohnhaft -</h4>
                        <table border="0">
                        <tr>
                            <td width="150"><b>Straße:</b></td>
                            <td width="469">' . $get['address'] . '</td>
                        </tr>
                        <tr>
                            <td><b>PLZ:</b></td>
                            <td width="150">' . $get['plz'] . '</td>
                            <td width="160"><b>Wohnort:</b></td>
                            <td width="159">' . $get['ort'] . '</td>
                        </tr>
                        </table>
                        <h4>- Kontaktdaten -</h4>
                        <table border="0">
                        <tr>
                            <td width="150"><b>Telefon Festnetz:</b></td>
                            <td width="150">' . $get['phone'] . '</td>
                            <td><b>Telefon Mobil:</b></td>
                            <td>' . $get['mobilephone'] . '</td>
                        </tr>
                        <tr>
                            <td><b>E-Mail:</b></td>
                            <td width="469">' . $get['email'] . '</td>
                            
                        </tr>
                        </table>
                        <br>
                        ';
                        if($get[ 'school' ]=="fls"){
                            $html[$i] .= '<h4>- Zustimmungen zu Datenschutz,Schul und EDV Nutzungsordnung -</h4><br>';
                            if ($get['dsgvo'] == 2) {
                                $html[$i] .= "Datenschutzverordnung Klassenordner schauen, ";
                            } else if ($get['dsgvo'] == 1)
                                $html[$i] .= "Datenschutzverordnung ja, ";
                            else
                                $html[$i] .= "Datenschutzverordnung nein, ";
                            if ($get['houserules'] == 2) {
                                $html[$i] .= "Schulordnung Klassenordner schauen, ";
                            } else if ($get['houserules'] == 1)
                                $html[$i] .= "Schulordnung ja, ";
                            else
                                $html[$i] .= "Schulordnung nein, ";
                            if ($get['edvrules'] == 2) {
                                $html[$i] .= "EDV NUtzungsordnung Klassenordner schauen, ";
                            } else if ($get['edvrules'] == 1)
                                $html[$i] .= "EDV Nutzungsordnung ja, ";
                            else
                                $html[$i] .= "EDV Nutzungsordnung nein, ";
                        }
                $html[$i] .= ' <h4>- Schulischer Werdegang: -</h4>
                <table cellpadding="1" cellspacing="1" border="0">
                    <tr>
                        <td width="200"><b>Zuletzt besuchte Schule:</b></td>
                        <td width="439">' . $get['lastschool'] . '</td>
                    </tr>
                    <tr>
                        <td width="200"><b>Ort,Bundesland:</b></td>
                        <td width="150">' . $get['lastschooltown'] . ', ' . $get['lastschoolprovince'] . '</td>
                        <td width="150"><b>Datum Abgang:</b></td>
                        <td width="169>">' . $newDate = date("d.m.Y", strtotime($get['lastschooldate'])) . '</td>
                    </tr>
                    
                    <tr>
                        <td width="200"><b>höchster erreichter Schulabschluss:</b></td>
                        <td width="439">' . $get['graduation'] . '</td>
                    </tr>
                </table>
                <h4>- Erziehungsberechtigte (unter 18 Jahre): -</h4>
                <table cellpadding="1" cellspacing="1" border="0" >
                    <tr>
                    <td width="325"><h4>Mutter</h4></td>
                    <td width="325"><h4>Vater</h4></td>
                    </tr>
                    <tr>
                        <td width="118"><b>Name,Vorname:</b></td>
                        <td width="206.5">' . $get['mother_lastname'] . ', ' . $get['mother_surname'] . '</td>
                        <td width="118"><b>Name,Vorname:</b></td>
                        <td width="206.5">' . $get['father_lastname'] . ', ' . $get['father_surname'] . '</td>
                    </tr>
                    <tr>
                        <td width="118"><b>Straße:</b></td>
                        <td width="206.5">' . $get['mother_address'] . '</td>
                        <td width="118"><b>Straße:</b></td>
                        <td width="206.5">' . $get['father_address'] . '</td>
                    </tr>
                    <tr>
                        <td width="118"><b>PLZ,Ort:</b></td>
                        <td width="206.5">' . $get['mother_postalcode'] . ', ' . $get['mother_town'] . '</td>
                        <td width="118"><b>PLZ,Ort:</b></td>
                        <td width="206.5">' . $get['father_postalcode'] . ', ' . $get['father_town'] . '</td>
                    </tr>
                    <tr>
                        <td width="118"><b>Telefon, Mobil:</b></td>
                        <td width="206.5">' . $get['mother_phone'] . ', ' . $get['mother_mobilephone'] . '</td>
                        <td width="118"><b>Telefon, Mobil:</b></td>
                        <td width="206.5">' . $get['father_phone'] . ', ' . $get['father_mobilephone'] . '</td>
                    </tr>
                </table>
                <br><br><br>
                ____________________________________________<br>
                Datum, Unterschrift Schüler/in
            ';
            } else if ($get["Schulform"] == "Vollzeit") {
                $html[$i] = '
                <span style="text-allign:justify;"><h1>Stammbogen der '.$get[ 'schoolname' ].' (' . $get['Schulform'] . ')</h3></span>
                <table border="0">
                    <tbody>
                        <tr>
                            <td>
                                <b>Klasse: ' . $get['classcode'] . '</b>
                            </td>
                            <td>
                                <b>Lehrer: ' . $get['surname'] . ' ' . $get['lastname'] . '</b>
                            </td>
                        </tr>
                    <tbody>
                </table>
                <h3>Persönliche Daten der Schülerin/des Schülers :</h3>
                    <table border="0" cellpadding="1" cellspacing="1" nobr="true">
                    <thead>
                        <tr>
                        <td><b>Nachname:</b></td>
                        <td>' . $get['sgivenname'] . ' ' . $get['smoregivenname'] . '</td>
                        <td><b>Vorname:</b></td>
                        <td width="160">' . $get['ssurname'] . ' ' . $get['smiddlename'] . '</td>
                        </tr>
                    </thead>
                    </table>
                    <h4>- Geburtsdaten -</h4>
                    <table border="0" cellpadding="1" cellspacing="1" nobr="true">
                    <tbody>
                        <tr>
                            <td><b>Gerburtsdatum:</b></td>
                            <td width="150">' . $newDate = date("d.m.Y", strtotime($get['birthdate'])) . '</td>
                            <td><b>Geschlecht:</b></td>
                            <td>' . $get['geschlecht'] . '</td>
                        </tr>
                        <tr>
                            <td><b>Geburtsort:</b></td>
                            <td>' . $get['birthtown'] . '</td>
                            <td><b>Geburtsland:</b></td>
                            <td>' . $get['birthcountry'] . '</td>
                        </tr>
                        </table>
                        <h4>- Sprachniveau/Nationalität/Religion -</h4>
                        <table border="0">
                        <tr>
                            <td width="150"><b>In Deutschland seit:</b></td>
                            <td width="150">' . $newDate = date("d.m.Y", strtotime($get['indeutschlandseit'])) . '</td>
                            <td><b>Sprachniveau:</b></td>
                            <td>' . $get['sprachniveau'] . '</td>
                        </tr>
                        <tr>
                            <td><b>Religion/Konfession</b></td>
                            <td>' . $get['religion'] . '</td>
                            <td><b>Staatsangehörigkeit:</b></td>
                            <td>' . $get['nationality'] . '</td>
                        </tr>
                        </table>
                        <h4>- Wohnhaft -</h4>
                        <table border="0">
                        <tr>
                            <td width="150"><b>Straße:</b></td>
                            <td width="469">' . $get['address'] . '</td>
                        </tr>
                        <tr>
                            <td><b>PLZ:</b></td>
                            <td width="150">' . $get['plz'] . '</td>
                            <td width="160"><b>Wohnort:</b></td>
                            <td width="159">' . $get['ort'] . '</td>
                        </tr>
                        </table>
                        <h4>- Kontaktdaten -</h4>
                        <table border="0">
                        <tr>
                            <td width="150"><b>Telefon Festnetz:</b></td>
                            <td width="150">' . $get['phone'] . '</td>
                            <td><b>Telefon Mobil:</b></td>
                            <td>' . $get['mobilephone'] . '</td>
                        </tr>
                        <tr>
                            <td><b>E-Mail:</b></td>
                            <td width="469">' . $get['email'] . '</td>
                            
                        </tr>
                        </table>
                        <br>
                ';
                if($get[ 'school' ]=="fls"){
                    $html[$i] .= '<h4>- Zustimmungen zu Datenschutz,Schul und EDV Nutzungsordnung -</h4><br>';
                    if ($get['dsgvo'] == 2) {
                        $html[$i] .= "Datenschutzverordnung Klassenordner schauen, ";
                    } else if ($get['dsgvo'] == 1)
                        $html[$i] .= "Datenschutzverordnung ja, ";
                    else
                        $html[$i] .= "Datenschutzverordnung nein, ";
                    if ($get['houserules'] == 2) {
                        $html[$i] .= "Schulordnung Klassenordner schauen, ";
                    } else if ($get['houserules'] == 1)
                        $html[$i] .= "Schulordnung ja, ";
                    else
                        $html[$i] .= "Schulordnung nein, ";
                    if ($get['edvrules'] == 2) {
                        $html[$i] .= "EDV NUtzungsordnung Klassenordner schauen, ";
                    } else if ($get['edvrules'] == 1)
                        $html[$i] .= "EDV Nutzungsordnung ja, ";
                    else
                        $html[$i] .= "EDV Nutzungsordnung nein, ";
                }

                $html[$i] .= '<br><h4>- Erziehungsberechtigte (unter 18 Jahre): -</h4>
                <table cellpadding="1" cellspacing="1" border="0" >
                    <tr>
                    <td width="325"><h4>Mutter</h4></td>
                    <td width="325"><h4>Vater</h4></td>
                    </tr>
                    <tr>
                        <td width="118"><b>Name,Vorname:</b></td>
                        <td width="206.5">' . $get['mother_lastname'] . ', ' . $get['mother_surname'] . '</td>
                        <td width="118"><b>Name,Vorname:</b></td>
                        <td width="206.5">' . $get['father_lastname'] . ', ' . $get['father_surname'] . '</td>
                    </tr>
                    <tr>
                        <td width="118"><b>Straße:</b></td>
                        <td width="206.5">' . $get['mother_address'] . '</td>
                        <td width="118"><b>Straße:</b></td>
                        <td width="206.5">' . $get['father_address'] . '</td>
                    </tr>
                    <tr>
                        <td width="118"><b>PLZ,Ort:</b></td>
                        <td width="206.5">' . $get['mother_postalcode'] . ', ' . $get['mother_town'] . '</td>
                        <td width="118"><b>PLZ,Ort:</b></td>
                        <td width="206.5">' . $get['father_postalcode'] . ', ' . $get['father_town'] . '</td>
                    </tr>
                    <tr>
                        <td width="118"><b>Telefon, Mobil:</b></td>
                        <td width="206.5">' . $get['mother_phone'] . ', ' . $get['mother_mobilephone'] . '</td>
                        <td width="118"><b>Telefon, Mobil:</b></td>
                        <td width="206.5">' . $get['father_phone'] . ', ' . $get['father_mobilephone'] . '</td>
                    </tr>
                </table>
                <h3>Schulischer Werdegang:</h3>
                <table cellpadding="1" cellspacing="1" border="0">
                    <tr>
                        <td width="200"><b>Zuletzt besuchte Schule:</b></td>
                        <td width="439">' . $get['lastschool'] . '</td>
                    </tr>
                    <tr>
                        <td width="200"><b>Ort,Bundesland:</b></td>
                        <td width="439">' . $get['lastschooltown'] . ', ' . $get['lastschoolprovince'] . '</td>
                    </tr>
                    <tr>
                        <td width="200"><b>höchster erreichter Schulabschluss:</b></td>
                        <td width="439">' . $get['graduation'] . '</td>
                    </tr>
                </table>
                <br><br><br><br><br><br><br><br><br><br>
                ____________________________________________<br>
                Datum, Unterschrift Schüler/in
            ';
            }
            if($get[ 'school' ]=="fls"){
                $htmlsusanmeldung[$i]='
                <span style="text-allign:justify;"><h1>Zugangsdaten und Informationen für den Unterricht in der '.$get[ 'schoolname' ].'</h3></span>
                <table style="border:1px solid #D4D4D4">
                    <tbody>
                        <tr>
                            <td style="border-top:1px solid #000000; border-bottom:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000">
                                Dienst/Programm
                            </td>
                            <td style="border-top:1px solid #000000; border-bottom:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000">
                                Benutzername/Identität
                            </td>
                            <td style="border-top:1px solid #000000; border-bottom:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000">
                                Passwort
                            </td>
                        </tr>
                        <tr>
                            <td  style="border-bottom:1px solid #D4D4D4;">
                                LANiS (PCs)
                                <br> 
                            </td>
                            <td style="border-bottom:1px solid #D4D4D4;border-left:1px solid #D4D4D4; border-right:1px solid #D4D4D4">
                                '.$get['ssurname'].'.'.$get['sgivenname'].'
                            </td>
                            <td style="border-bottom:1px solid #D4D4D4;">
                                '.$newDate = date("dmY", strtotime($get["birthdate"])).'
                            </td>
                        </tr>
                        <tr>
                            <td  style="border-bottom:1px solid #D4D4D4;">
                                WebUntis<br> 
                            </td> 
                            <td style="border-bottom:1px solid #D4D4D4;border-left:1px solid #D4D4D4; border-right:1px solid #D4D4D4">
                                '.$get['ssurname'].'.'.$get['sgivenname'].'
                            </td>
                            <td  style="border-bottom:1px solid #D4D4D4;">
                            '.$newDate = date("dmY", strtotime($get["birthdate"])).'
                            </td>
                        </tr>
                        <tr>
                            <td  style="border-bottom:1px solid #D4D4D4;">
                                Moodle<br>
                                https://moodle.fls-da.de
                            </td> 
                            <td style="border-bottom:1px solid #D4D4D4;border-left:1px solid #D4D4D4; border-right:1px solid #D4D4D4">
                                '.$get['ssurname'].'.'.$get['sgivenname'].'
                            </td>
                            <td  style="border-bottom:1px solid #D4D4D4;">
                            '.$newDate = date("dmY", strtotime($get["birthdate"])).'
                            </td>
                        </tr>
                        <tr>
                            <td  style="border-bottom:1px solid #D4D4D4;">
                                Schulportal <br>
                                portal.fls-ds.de
                            </td> 
                            <td style="border-bottom:1px solid #D4D4D4;border-left:1px solid #D4D4D4; border-right:1px solid #D4D4D4">
                                '.$get['ssurname'].'.'.$get['sgivenname'].'
                            </td>
                            <td  style="border-bottom:1px solid #D4D4D4;">
                            '.$newDate = date("dmY", strtotime($get["birthdate"])).'
                            </td>
                        </tr>
                        <tr>
                        <td  style="border-bottom:1px solid #D4D4D4;">
                        WLAN<br>
                        Hilpertstraße:<br>
                        Name: FLS<br>
                        BSZN:<br>
                        Name: BSZN
                        </td> 
                        <td style="border-bottom:1px solid #D4D4D4;border-left:1px solid #D4D4D4; border-right:1px solid #D4D4D4">
                            '.$get['ssurname'].'.'.$get['sgivenname'].'
                        </td>
                        <td  style="border-bottom:1px solid #D4D4D4;">
                        '.$newDate = date("dmY", strtotime($get["birthdate"])).'
                        </td>
                    </tr>
                    </tbody>
            </table><br><br>
            <br><b style="color:red">Das Passwort muss nach der ersten Anmeldung am PC im Schülermodul geändert werden.</b>
            <br><b>Ihre Anmeldedaten werden im Laufe von 2 Werktagen in allen elektronischen Systemen der Schule eingebunden.</b>
            ';
            }
            else if ($get[ 'school' ]=="mbs"){
                $htmlsusanmeldung[$i]='
                <span style="text-allign:justify;"><h1>Zugangsdaten und Informationen für den Unterricht in der '.$get[ 'schoolname' ].'</h3></span>
                <table style="border:1px solid #D4D4D4">
                    <tbody>
                        <tr>
                            <td style="border-top:1px solid #000000; border-bottom:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000">
                                Dienst/Programm
                            </td>
                            <td style="border-top:1px solid #000000; border-bottom:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000">
                                Benutzername/Identität
                            </td>
                            <td style="border-top:1px solid #000000; border-bottom:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000">
                                Passwort
                            </td>
                        </tr>
                        <tr>
                            <td  style="border-bottom:1px solid #D4D4D4;">
                                LANiS (PCs)
                                <br> 
                            </td>
                            <td style="border-bottom:1px solid #D4D4D4;border-left:1px solid #D4D4D4; border-right:1px solid #D4D4D4">
                                '.$get['ssurname'].'.'.$get['sgivenname'].'
                            </td>
                            <td style="border-bottom:1px solid #D4D4D4;">
                                '.$newDate = date("dmY", strtotime($get["birthdate"])).'
                            </td>
                        </tr>
                        <tr>
                            <td  style="border-bottom:1px solid #D4D4D4;">
                                Schulportal <br>
                                https://portal.lanis-system.de/6283
                            </td> 
                            <td style="border-bottom:1px solid #D4D4D4;border-left:1px solid #D4D4D4; border-right:1px solid #D4D4D4">
                                '.$get['ssurname'].'.'.$get['sgivenname'].'
                            </td>
                            <td  style="border-bottom:1px solid #D4D4D4;">
                            '.$newDate = date("dmY", strtotime($get["birthdate"])).'
                            </td>
                        </tr>
                        <tr>
                        <td  style="border-bottom:1px solid #D4D4D4;">
                            WLAN<br>
                            Hilpertstraße:<br>
                            Name: FLS<br>
                            BSZN:<br>
                            Name: BSZN
                        </td> 
                        <td style="border-bottom:1px solid #D4D4D4;border-left:1px solid #D4D4D4; border-right:1px solid #D4D4D4">
                            '.$get['ssurname'].'.'.$get['sgivenname'].'
                        </td>
                        <td  style="border-bottom:1px solid #D4D4D4;">
                        '.$newDate = date("dmY", strtotime($get["birthdate"])).'
                        </td>
                    </tr>
                    </tbody>
            </table><br><br>
            <br><b style="color:red">Das Passwort muss nach der ersten Anmeldung am PC im Schülermodul geändert werden.</b>
            <br><b>Ihre Anmeldedaten sind nach 2 Werktagen in allen elektronischen Systemen der Schule eingebunden.</b>
            ';
            }
            $i++;
        }
    }


    class MYPDF extends TCPDF
    {

        //Page header
        public function Header()
        {
            // Logo
            $image_file = K_PATH_IMAGES . 'logo.jpg';
            $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            // Set font
            $this->SetFont('helvetica', 'B', 20);
            // Title
            //$this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        }

        // Page footer
        public function Footer()
        {
            // Position at 15 mm from bottom
            $this->SetY(-15);
            // Set font
            $this->SetFont('helvetica', 'I', 8);
            // Page number
            $this->Cell(0, 10, 'Dieser Stammbogen wurde maschinell erstellt am ' . date("d.m.Y H:i:s") . ' und ist nur mit Unterschrift gültig', 0, false, 'L', 0, '', 0, false, '', '');
            $this->Cell(0, 10, 'Seite ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
        }
    }

    //$html[0]=file_get_contents('AnmeldeformularTeilzeitschüler.htm');
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', true);

    // *** Set PDF protection (encryption) *********************

    /*
    The permission array is composed of values taken from the following ones (specify the ones you want to block):
        - print : Print the document;
        - modify : Modify the contents of the document by operations other than those controlled by 'fill-forms', 'extract' and 'assemble';
        - copy : Copy or otherwise extract text and graphics from the document;
        - annot-forms : Add or modify text annotations, fill in interactive form fields, and, if 'modify' is also set, create or modify interactive form fields (including signature fields);
        - fill-forms : Fill in existing interactive form fields (including signature fields), even if 'annot-forms' is not specified;
        - extract : Extract text and graphics (in support of accessibility to users with disabilities or for other purposes);
        - assemble : Assemble the document (insert, rotate, or delete pages and create bookmarks or thumbnail images), even if 'modify' is not set;
        - print-high : Print the document to a representation from which a faithful digital copy of the PDF content could be generated. When this is not set, printing is limited to a low-level representation of the appearance, possibly of degraded quality.
        - owner : (inverted logic - only for public-key) when set permits change of encryption and enables all other permissions.

    If you don't set any password, the document will open as usual.
    If you set a user password, the PDF viewer will ask for it before displaying the document.
    The master (owner) password, if different from the user one, can be used to get full document access.

    Possible encryption modes are:
        0 = RSA 40 bit
        1 = RSA 128 bit
        2 = AES 128 bit
        3 = AES 256 bit

    NOTES:
    - To create self-signed signature: openssl req -x509 -nodes -days 365000 -newkey rsa:1024 -keyout tcpdf.crt -out tcpdf.crt
    - To export crt to p12: openssl pkcs12 -export -in tcpdf.crt -out tcpdf.p12
    - To convert pfx certificate to pem: openssl pkcs12 -in tcpdf.pfx -out tcpdf.crt -nodes

    */
    $pdf->SetProtection(array('modify', 'copy', 'annot-forms', 'extract', 'assemble', 'print-high'), '', null, 0, null);

    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor($pdfAuthor);
    $pdf->SetTitle('Stammblatt: ' . $susname);
    $pdf->SetSubject('Stammblatt ');
    // Header und Footer Informationen
    $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    // Auswahl des Font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    // Auswahl der MArgins
    $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    // Automatisches Autobreak der Seiten
    $pdf->SetAutoPageBreak(TRUE, 0);
    // Image Scale 
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    // Schriftart
    $pdf->SetFont('dejavusans', '', 10);
    //style QR Code
    $style = array(
        'border' => true,
        'vpadding' => 'auto',
        'hpadding' => 'auto',
        'fgcolor' => array(0, 0, 0),
        'bgcolor' => false, //array(255,255,255)
        'module_width' => 1, // width of a single module in points
        'module_height' => 1 // height of a single module in points
    );
    // Neue Seite
    $i = 0;
    foreach ($html as $page) {
        $pdf->AddPage();
        // Fügt den HTML Code in das PDF Dokument ein
        $pdf->writeHTML($page, true, false, true, false, '');
        $pdf->AddPage();
        $pdf->writeHTML($htmlsusanmeldung[$i], true, false, true, false, '');
        $pdf->Text(22, 145, 'Untis App iOS');
        $pdf->write2DBarcode('https://apps.apple.com/de/app/untis-mobile/id926186904', 'QRCODE,Q', 20, 150, 30, 30, $style, 'N');
        $pdf->Text(53, 145, 'Untis App Android');
        $pdf->write2DBarcode('https://play.google.com/store/apps/details?id=com.grupet.web.app&hl=de', 'QRCODE,Q', 55, 150, 30, 30, $style, 'N');
        $pdf->Text(93, 145, 'Schulportal');
        $pdf->write2DBarcode('https://start.schulportal.hessen.de/6283', 'QRCODE,Q', 90, 150, 30, 30, $style, 'N');
        $pdf->Text(133, 145, 'Moodle');
        $pdf->write2DBarcode('https://moodle.fls-da.de', 'QRCODE,Q', 125, 150, 30, 30, $style, 'N');
        $pdf->Text(20, 185, 'Bitte bewahren Sie diesen Zettel sorgfältig auf.');
        $i++;
    }
    $i = 0;
    $pdf->Output($pdfName, 'D');
    header('HTTP/1.0 200 OK');
    header('Content-Type: application/pdf');
    //session_destroy();
} else {
    header('HTTP/1.0 403 Forbitten');
    header('Content-Type: application/json');
    $data["error"] = "key is outdated or credentials are wrong";
    echo json_encode($data);
    //session_destroy();
}
