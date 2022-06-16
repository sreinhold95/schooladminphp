<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Schulverwaltung">
    <meta name="author" content="Sebastian Reinhold">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../style/admincp.css" type="text/css">
    <!--Tabulator einbindung-->
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables@4.7.2/dist/js/tabulator.min.js"></script>
    <!--<link href="https://unpkg.com/tabulator-tables@4.7.0/dist/css/bootstrap/tabulator_bootstrap4.min.css" rel="stylesheet">-->
    <link href="../js/tabulator/css/tabulator_bootstrap4.css" rel="stylesheet">
    <!--<script type="text/javascript" src="../js/tabulator/js/tabulator.js"></script>-->
    <script type="text/javascript" src="../js/sheetjs/xlsx.full.min.js"></script>
    <script src="../js/js.js"></script>
    <title>Schulverwaltung <?php if(isset($_GET[ 'site' ])) echo "- ".$_GET[ 'site' ]; ?></title>
    <?php
    header('X-Content-Type-Options: nosniff');
	ini_set('error_reporting', E_ERROR)
    ?>
</head>