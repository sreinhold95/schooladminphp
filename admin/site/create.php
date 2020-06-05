<?php
session_start();
$session_timeout = 600; // 1800 Sek./60 Sek. = 10 Minuten
if (!isset($_SESSION['last_visit'])) {
    $_SESSION['last_visit'] = time();
    // Aktion der Session wird ausgeführt
}
if ((time() - $_SESSION['last_visit']) > $session_timeout) {
    session_destroy();
    session_unset();
    header('location: ../index.php');
    // Aktion der Session wird erneut ausgeführt
}
$_SESSION['last_visit'] = time();

if (isset($_SESSION['loggedin'])) {
    $loggedin = $_SESSION['loggedin'];
} else
    $loggedin = false;
if ($loggedin == true) {
    if ($_SESSION['userrole'] == 1) {
    }
} else {
    header('location: ../index.php');
}
?>
<div class="d-flex">
    <div class="p-2">
        <div class="content_allg">
            <form method="POST" action="" id="useranlegen">
                <!-- Anfang -->
                <div class="box">
                    <div class="box_header">Schülerinformationen</div>
                    <br>
                    <div class="form-row">
                        <div class="form-group col-sm-6">
                            <label for="surname" class="label">Vorname:</label>
                            <input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="surname" id="surname" value="">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="middlename" class="label">weitere Vornamen:</label>
                            <input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="middlename" id="middlename" value="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm-6">
                            <label class="label">Nachname:</label>
                            <input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="givenname" id="givenname" value="">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="moregivenname" class="label">weitere Nachnamen:</label>
                            <input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="moregivenname" id="moregivenname" value="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm-6">
                            <label for="address" class="label">Straße:</label>
                            <input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="address" id="street" value="">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="address" class="label">Hausnummer:</label>
                            <input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="address" id="hnb" value="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm-2">
                            <label for="postalcode" class="label">PLZ:</label>
                            <input class="form-control form-control-sm" type="number" size="24" maxlength="50" name="postalcode" id="postalcode" onchange='settown("input#postalcode","town","true")' value="">

                        </div>
                        <div class="form-group col-sm-5">
                            <label for="town" class="label">Ort:</label>
                            <input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="town" id="town" value="" readonly>
                        </div>
                        <div class="form-group col-sm-5">
                            <label for="province" class="label">Bundesland:</label>
                            <select name="province" id="province" class="form-control form-control-sm" size="1" readonly>
                                <?php
                                $check = $mysqli->query("SELECT * FROM province;");
                                while ($row = mysqli_fetch_array($check)) {
                                    if ($row['2st'] != "") {
                                        $kzp = $row['2st'];
                                        $idprovince = $row['idprovince'];
                                        $province = $row['province'];
                                        echo '<option value="' . $idprovince . '">' . $province . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm-4">
                            <label for="birthdate" class="label">Geburtsdatum:</label>
                            <input class="form-control form-control-sm" type="date" size="24" maxlength="50" name="birthdate" id="birthdate" value="">
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="birthcountry" class="label">Geburtsland:</label>
                            <input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="birthcountry" id="birthcountry" value="">
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="birthtown" class="label">Geburtsort:</label>
                            <input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="birthtown" id="birthtown" value="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm-4">
                            <label for="sex" class="label">Geschlecht:</label>
                            <select name="sex" id="sex" class="form-control form-control-sm" size="1">
                                <option selected="selected" value="">Bitte wählen</option>
                                <?php
                                echo '<option value="d">divers</option>';
                                echo '<option value="m">männlich</option>';
                                echo '<option value="w">weiblich</option>';
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="nationality" class="label">Staatsangehörigkeit:</label>
                            <select name="nationality" id="nationality" class="form-control form-control-sm" size="1">
                                <option selected="selected" value="">Bitte wählen</option>
                                <?php
                                $check = $mysqli->query("SELECT 2st,Land FROM nationality;");
                                while ($row = mysqli_fetch_array($check)) {
                                    if ($row['2st'] != "") {
                                        $lkz = $row['2st'];
                                        $Land = $row['Land'];
                                        echo '<option value="' . $lkz . '">' . $Land . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="family_speech" class="label">Muttersprache:</label>
                            <input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="family_speech" id="family_speech" value="">

                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm-6">
                            <label for="religion" class="label">Religion:</label>
                            <input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="religion" id="religion" value="">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="email" class="label">in Deutschland seit: (Wenn in DE Geboren Geburtsdatum)</label>
                            <input class="form-control form-control-sm" type="date" size="24" maxlength="50" name="email" id="indeutschlandseit" value="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm-6">
                            <label for="phone" class="label">Telefon:</label>
                            <input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="phone" id="phone" value="">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="mobilephone" class="label">Mobiltelefon:</label>
                            <input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="mobilephone" id="mobilephone" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="label">Email:</label>
                        <input class="form-control form-control-sm" type="text" size="24" maxlength="100" name="email" id="email" value="">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm-6">
                            <label for="sprachniveau" class="label">Sprachniveau:</label>
                            <select name="sprachniveau" id="sprachniveau" class="form-control form-control-sm" size="1">
                                <option selected="selected" value="">Bitte wählen</option>
                                <option value="00">00 - noch nicht bekannt</option>
                                <option value="A0">A0 - kein deutsch</option>
                                <option value="A1">A1 - Kann vertraute, alltägliche Ausdrücke und ganz einfache Sätze verstehen und verwenden, die auf die Befriedigung konkreter Bedürfnisse zielen. </option>
                                <option value="A2">A2 - Kann Sätze und häufig gebrauchte Ausdrücke verstehen, die mit Bereichen von ganz un-mittelbarer Bedeutung zusammenhängen </option>
                                <option value="B1">B1 - Kann die Hauptpunkte verstehen, wenn klare Standardsprache verwendet wird und wenn es um vertraute Dinge aus Arbeit, Schule, Freizeit usw. geht.</option>
                                <option value="B2">B2 - Kann die Hauptinhalte komplexer Texte zu konkreten und abstrakten Themen verstehen; versteht im eigenen Spezialgebiet auch Fachdiskussionen. </option>
                                <option value="C1">C1 - Kann praktisch fast alles, was er/sie liest oder hört, mühelos verstehen. </option>
                                <option value="C2">C2 - Kann praktisch alles, was er/sie liest oder hört, mühelos verstehen. </option>

                            </select>
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="classc" class="label">Klasse:</label>
									<?php
									echo '<select name "classc"  id="classc" class="form-control form-control-sm size="1">';
									$check = $mysqli->query("SELECT classcode FROM class;");
									while ($row = mysqli_fetch_array($check)) {
										if ($row['classcode'] != "") {
											$classcode = $row['classcode'];
											echo '<option value="' . $classcode . '">' . $classcode . '</option>';
										}
									}
									echo '</select>';
									?>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm-6">
                            <label class="label">Ausbildungsbeginn:</label>
                            <input class="form-control form-control-sm" type="date" size="24" maxlength="50" name="ausbildungsbeginn" id="ausbildungsbeginn" value="">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="religion" class="label">Ausbildungsberuf:</label>
                            <select name="Ausbildungsberuf" id="Ausbildungsberuf" class="form-control form-control-sm" size="1" onchange='tabellen_none("#Ausbildungsberuf option:selected","Ausbildungsbetrieb","<?php echo $apikey ?>")'>
                                <?php
                                echo '<option value="" selected disabled hidden>Bitte auswählen</option>';
                                $check = $mysqli->query("SELECT * from beruf;");
                                while ($row = mysqli_fetch_array($check)) {
                                    if ($row['Berufs_ID'] != "") {
                                        $berufsid = $row['Berufs_ID'];
                                        $Berufbez = $row['Berufbez'];
                                        if ($berufsid == $get['idberuf'])
                                            echo '<option selected=selected value="' . $berufsid . '">' . $Berufbez . '</option>';
                                        else
                                            echo '<option value="' . $berufsid . '">' . $Berufbez . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="box" id="Ausbildungsbetrieb" style="display:none">
                    <div class="box_header">Informationen zum Ausbildungsbetrieb</div>

                    <br>
                    <div class="form-group">
                        <label for="ausbildungsbetrieb_name" class="label">Name:</label>
                        <input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="ausbildungsbetrieb_name" id="ausbildungsbetrieb_name" value="">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm-6">
                            <label for="ausbildungsbetrieb_strasse" class="label">Straße:</label>
                            <input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="ausbildungsbetrieb_name" id="ausbildungsbetrieb_strasse" value="">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="ausbildungsbetrieb_strasse" class="label">Hausnummer:</label>
                            <input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="ausbildungsbetrieb_name" id="ausbildungsbetrieb_hnb" value="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm-6">
                            <label for="ausbildungsbetrieb_plz" class="label">PLZ:</label>
                            <input class="form-control form-control-sm opt" type="number" size="24" maxlength="50" name="ausbildungsbetrieb_plz" id="ausbildungsbetrieb_plz" onchange='settown("input#ausbildungsbetrieb_plz","ausbildungsbetrieb_ort","true")' value="">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="ausbildungsbetrieb_ort" class="label">Ort:</label>
                            <input class="form-control form-control-sm" readonly type="text" size="24" maxlength="50" name="ausbildungsbetrieb_ort" id="ausbildungsbetrieb_ort" value="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm-3">
                            <label for="ausbildungsbetrieb_ausbilder_anrede" class="label">Ausbilder Anrede:</label>
                            <select name="ausbildungsbetrieb_ausbilder_anrede" id="ausbildungsbetrieb_ausbilder_anrede" class="form-control form-control-sm" size="1">
                                <option selected="selected" value="">Bitte wählen</option>
                                <option value="Herr">Herr</option>
                                <option value="Frau">Frau</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-9">
                            <label class="label">Ausbilder Name:</label>
                            <input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="religion" id="ausbildungsbetrieb_ausbilder_name" value="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm-4">
                            <label for="ausbildungsbetrieb_email" class="label">Email:</label>
                            <input class="form-control form-control-sm" type="text" size="24" maxlength="100" name="ausbildungsbetrieb_email" id="ausbildungsbetrieb_email" value="">
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="ausbildungsbetrieb_telefon" class="label">Telefon:</label>
                            <input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="ausbildungsbetrieb_telefon" id="ausbildungsbetrieb_telefon" value="">

                        </div>
                        <div class="form-group col-sm-4">
                            <label for="ausbildungsbetrieb_fax" class="label">Fax:</label>
                            <input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="ausbildungsbetrieb_fax" id="ausbildungsbetrieb_fax" value="">
                        </div>
                    </div>
                </div>
                <!-- bis hier -->
                <div class="box" id="Schulbildung">
                    <div class="box_header">Abgehende Schule</div>
                    <div class="form-group">
                        <label for="lastschool" class="label">Name</label>
                        <input class="form-control form-control-sm" type="text" size="24" maxlength="50" name="lastschool" id="lastschool" value="">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm 3">
                            <label for="lastschoolplz" class="label">PLZ:</label>
                            <input class="form-control form-control-sm" type="number" size="24" maxlength="50" name="address" id="lastschoolplz" onchange='settownpr("input#lastschoolplz","lastschooltown","true","lastschoolprovince")' value="">
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="lastschooltown" class="label">Ort</label>
                            <input class="form-control form-control-sm" readonly type="text" size="24" maxlength="50" name="lastschooltown" id="lastschooltown" value="">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="lastschoolprovince" class="label">Bundesland </label>
                            <select name="lastschoolprovince" id="lastschoolprovince" readonly class="form-control form-control-sm" size="1">
                                <?php
                                $check = $mysqli->query("SELECT * FROM province;");
                                while ($row = mysqli_fetch_array($check)) {
                                    if ($row['2st'] != "") {
                                        $kzp = $row['2st'];
                                        $idprovince = $row['idprovince'];
                                        $province = $row['province'];
                                        echo '<option value="' . $province . '">' . $province . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm-6">
                            <label class="label">Abgang:</label>
                            <input class="form-control form-control-sm" type="date" size="24" maxlength="50" name="lastschooldate" id="lastschooldate" value="">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="graduation" class="label">Schulabschluss:</label>
                            <select name="graduation" id="graduation" class="form-control form-control-sm" size="1">
                                <?php
                                echo '<option value="" selected>Bitte auswählen</option>';
                                $check = $mysqli->query("SELECT * FROM graduation;");
                                while ($row = mysqli_fetch_array($check)) {
                                    if ($row['graduation'] != "") {
                                        $graduation = $row['graduation'];
                                        $idgraduation = $row['idgraduation'];
                                        echo "<option value=" . $idgraduation . ">" . $graduation . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="box" id="eltern-table">
                        <div class="box_header">zusätliche Informationen der Eltern bei Minderjährigen</div>
                        <div class="form-row">
                            <div class="form-group col-sm-6">
                                <h6>Mutter</h6>
                                <label for="mother_surname" class="label">Vorname:</label>
                                <input class="form-control form-control-sm eltern" type="text" size="24" maxlength="50" name="mother_surname" id="mother_surname" value="">
                            </div>
                            <div class="form-group col-sm-6">
                                <h6> Vater</h6>
                                <label for="father_surname" class="label">Vorname:</label>
                                <input class="form-control form-control-sm eltern" type="text" size="24" maxlength="50" name="father_surname" id="father_surname" value="">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-sm-6">
                                <label for="mother_givenname" class="label">Nachname:</label>
                                <input class="form-control form-control-sm eltern" type="text" size="24" maxlength="50" name="mother_givenname" id="mother_givenname" value="">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="father_givenname" class="label">Nachname:</label>
                                <input class="form-control form-control-sm eltern" type="text" size="24" maxlength="50" name="father_givenname" id="father_givenname" value="">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-sm-6">
                                <label for="mother_address" class="label">Adresse:</label>
                                <input class="form-control form-control-sm eltern" type="text" size="24" maxlength="50" name="mother_address" id="mother_address" value="">

                            </div>
                            <div class="form-group col-sm-6">
                                <label for="addrefather_addressss" class="label">Adresse:</label>
                                <input class="form-control form-control-sm eltern" type="text" size="24" maxlength="50" name="father_address" id="father_address" value="">

                            </div>

                        </div>
                        <div class="form-row">
                            <div class="form-group col-sm-6">
                                <label for="mother_plz" class="label">PLZ:</label>
                                <input class="form-control form-control-sm eltern" type="number" size="24" maxlength="50" name="mother_plz" id="mother_plz" onchange='settown("input#mother_plz","mother_town","false")' value="">

                            </div>
                            <div class="form-group col-sm-6">
                                <label for="father_plz" class="label">PLZ:</label>
                                <input class="form-control form-control-sm eltern" type="number" size="24" maxlength="50" name="father_plz" id="father_plz" onchange='settown("input#father_plz","father_town","false")' value="">
                            </div>

                        </div>
                        <div class="form-row">
                            <div class="form-group col-sm-6">
                                <label for="mother_town" class="label">Ort:</label>
                                <input class="form-control form-control-sm eltern" type="text" size="24" maxlength="50" name="mother_town" id="mother_town" value="">

                            </div>
                            <div class="form-group col-sm-6">
                                <label for="father_town" class="label">Ort:</label>
                                <input class="form-control form-control-sm eltern" type="text" size="24" maxlength="50" name="father_town" id="father_town" value="">

                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-sm-6">
                                <label for="mother_phone" class="label">Telefon:</label>
                                <input class="form-control form-control-sm eltern" type="text" size="24" maxlength="50" name="mother_phone" id="mother_phone" value="">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="father_phone" class="label">Telefon:</label>
                                <input class="form-control form-control-sm eltern" type="text" size="24" maxlength="50" name="father_phone" id="father_phone" value="">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-sm-6">
                                <label for="mother_mobilephone" class="label">Mobiltelefon:</label>
                                <input class="form-control form-control-sm eltern" type="text" size="24" maxlength="50" name="mother_mobilephone" id="mother_mobilephone" value="">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="father_mobilephone" class="label">Mobiltelefon:</label>
                                <input class="form-control form-control-sm eltern" type="text" size="24" maxlength="50" name="father_mobilephone" id="father_mobilephone" value="">
                            </div>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box_header">Datenschutzerklärung, Schulordnung (Hausordnung), EDV Nutzungsordnung</div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="status" id="dsgvo" value="1">
                            <label class="form-check-label" for="activate">
                                Datenschutzerklärung
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="status" id="hordnung" value="1">
                            <label class="form-check-label" for="activate">
                                Hausordnung
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="status" id="edvordnung" value="1">
                            <label class="form-check-label" for="activate">
                                EDV Nutzungsordnung
                            </label>
                        </div>
                    </div>
                    <input type="submit" name="submit" id="submit" value="Speichern">
            </form>
            <!-- Ende -->
        </div>
    </div>
</div>