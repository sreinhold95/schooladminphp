<!DOCTYPE html>
<html lang="de">
<?php
include($_SERVER['DOCUMENT_ROOT'] . "/style/header.php");
require($_SERVER['DOCUMENT_ROOT'] . '/include/config.inc.php');
$session_timeout = 600; // 1800 Sek./60 Sek. = 10 Minuten
session_start();
if ((time() - $_SESSION['last_visit']) > $session_timeout) {
    session_destroy();
    session_unset();
    header('location: ../index.php');
    // Aktion der Session wird erneut ausgeführt
}
$_SESSION['last_visit'] = time();
if (isset($_GET["site"])) {
    $site = $_GET['site'];
    if (strlen($site) != 0) {
        $text = "site/" . $site . ".php";
    }
}
if (isset($_SESSION['loggedin'])) {
    $loggedin = $_SESSION['loggedin'];
} else
    $loggedin = false;
if ($loggedin == true) {
    if ($_SESSION['userrole'] == 2) {
        include($_SERVER['DOCUMENT_ROOT'] . '/style/menu.php');
        if ($text != "") {
            //echo "<body>";
            include($_SERVER['DOCUMENT_ROOT'] . '/style/content.php');
        } else {
            //echo "<body>";
            include($text1);
        }
    }
} else {
    header('location: ../index.php');
}
include($_SERVER['DOCUMENT_ROOT'] . '/style/bootstrap.php');
include($_SERVER['DOCUMENT_ROOT'] . '/dep_instructor/scripts/' . $_GET['site'] . '.php');
echo "</body>";
include($_SERVER['DOCUMENT_ROOT'] . '/style/footer.php');
?>

</html>